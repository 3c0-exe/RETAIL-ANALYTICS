<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    // Export Sales Analytics to CSV
    public function salesCsv(Request $request)
    {
        $user = auth()->user();
        $branchId = $user->isAdmin() ? $request->branch_id : $user->branch_id;

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();

        // FIXED: Changed transactions.total to transactions.total_amount
        $salesByBranch = Transaction::select(
                'branches.name as branch',
                DB::raw('COUNT(transactions.id) as transactions'),
                DB::raw('SUM(transactions.total_amount) as total_sales'),
                DB::raw('AVG(transactions.total_amount) as avg_transaction')
            )
            ->join('branches', 'transactions.branch_id', '=', 'branches.id')
            ->when($branchId, fn($q) => $q->where('transactions.branch_id', $branchId))
            ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
            ->groupBy('branches.id', 'branches.name')
            ->orderByDesc('total_sales')
            ->get();

        $filename = 'sales_report_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($salesByBranch) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, ['Branch', 'Transactions', 'Total Sales', 'Avg Transaction']);

            // Data
            foreach ($salesByBranch as $row) {
                fputcsv($file, [
                    $row->branch,
                    $row->transactions,
                    number_format($row->total_sales, 2),
                    number_format($row->avg_transaction, 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Export Sales Analytics to Excel
    public function salesExcel(Request $request)
    {
        $user = auth()->user();
        $branchId = $user->isAdmin() ? $request->branch_id : $user->branch_id;

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();

        // FIXED: Changed transactions.total to transactions.total_amount
        $salesByBranch = Transaction::select(
                'branches.name as branch',
                DB::raw('COUNT(transactions.id) as transactions'),
                DB::raw('SUM(transactions.total_amount) as total_sales'),
                DB::raw('AVG(transactions.total_amount) as avg_transaction')
            )
            ->join('branches', 'transactions.branch_id', '=', 'branches.id')
            ->when($branchId, fn($q) => $q->where('transactions.branch_id', $branchId))
            ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
            ->groupBy('branches.id', 'branches.name')
            ->orderByDesc('total_sales')
            ->get();

        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Title
        $sheet->setCellValue('A1', 'Sales Analytics Report');
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

        // Date range
        $sheet->setCellValue('A2', 'Period: ' . $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y'));
        $sheet->mergeCells('A2:D2');

        // Headers
        $sheet->setCellValue('A4', 'Branch');
        $sheet->setCellValue('B4', 'Transactions');
        $sheet->setCellValue('C4', 'Total Sales');
        $sheet->setCellValue('D4', 'Avg Transaction');
        $sheet->getStyle('A4:D4')->getFont()->setBold(true);

        // Data
        $row = 5;
        foreach ($salesByBranch as $data) {
            $sheet->setCellValue('A' . $row, $data->branch);
            $sheet->setCellValue('B' . $row, $data->transactions);
            $sheet->setCellValue('C' . $row, '₱' . number_format($data->total_sales, 2));
            $sheet->setCellValue('D' . $row, '₱' . number_format($data->avg_transaction, 2));
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Save
        $filename = 'sales_report_' . now()->format('Y-m-d_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
        exit;
    }

    // Export Sales Analytics to PDF
    public function salesPdf(Request $request)
    {
        $user = auth()->user();
        $branchId = $user->isAdmin() ? $request->branch_id : $user->branch_id;

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();

        // FIXED: Changed transactions.total to transactions.total_amount
        $salesByBranch = Transaction::select(
                'branches.name as branch',
                DB::raw('COUNT(transactions.id) as transactions'),
                DB::raw('SUM(transactions.total_amount) as total_sales'),
                DB::raw('AVG(transactions.total_amount) as avg_transaction')
            )
            ->join('branches', 'transactions.branch_id', '=', 'branches.id')
            ->when($branchId, fn($q) => $q->where('transactions.branch_id', $branchId))
            ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
            ->groupBy('branches.id', 'branches.name')
            ->orderByDesc('total_sales')
            ->get();

        $pdf = Pdf::loadView('exports.sales-pdf', [
            'salesByBranch' => $salesByBranch,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        return $pdf->download('sales_report_' . now()->format('Y-m-d_His') . '.pdf');
    }

    // Export Customer Analytics to CSV
    public function customersCsv(Request $request)
    {
        $segmentFilter = $request->segment;

        $customers = Customer::query()
            ->when($segmentFilter, fn($q) => $q->where('segment', $segmentFilter))
            ->orderByDesc('total_spent')
            ->get();

        $filename = 'customers_report_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Name', 'Email', 'Segment', 'Total Spent', 'Visit Count', 'Last Visit', 'RFM - R', 'RFM - F', 'RFM - M']);

            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->name,
                    $customer->email,
                    ucfirst($customer->segment),
                    number_format($customer->total_spent, 2),
                    $customer->visit_count,
                    $customer->last_visit_date ? $customer->last_visit_date->format('Y-m-d') : 'N/A',
                    $customer->getRecencyScore(),
                    $customer->getFrequencyScore(),
                    $customer->getMonetaryScore()
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
