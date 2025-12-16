<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Import;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Branch;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function index()
    {
        $imports = Import::with(['user', 'branch'])
            ->latest()
            ->paginate(15);

        return view('admin.imports.index', compact('imports'));
    }

    public function create()
    {
        $branches = Branch::where('is_active', true)->get();
        return view('admin.imports.create', compact('branches'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240',
            'branch_id' => 'required|exists:branches,id',
        ]);

        try {
            // Store the file
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Store in storage/app/imports directory
            $filePath = $file->storeAs('imports', $fileName);

            // Get the full path for reading
            $fullPath = Storage::path($filePath);

            // Verify file exists
            if (!file_exists($fullPath)) {
                throw new \Exception("File upload failed. Path: {$fullPath}");
            }

            // Create import record
            $import = Import::create([
                'user_id' => auth()->id(),
                'branch_id' => $request->branch_id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'import_type' => 'transactions',
                'status' => 'pending',
            ]);

            // Read and preview file
            $preview = $this->getFilePreview($fullPath);

            return view('admin.imports.show', [
                'import' => $import,
                'previewData' => $preview,
            ]);

        } catch (\Exception $e) {
            // Clean up if import record was created
            if (isset($import)) {
                $import->delete();
            }

            // Clean up uploaded file
            if (isset($filePath) && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            return redirect()->route('admin.imports.create')
                ->with('error', 'Failed to upload file: ' . $e->getMessage());
        }
    }

    public function process(Request $request, Import $import)
    {
        if ($import->status !== 'pending') {
            return redirect()->route('admin.imports.show', $import)
                ->with('error', 'This import has already been processed.');
        }

        $import->markAsProcessing();

        try {
            // Get the full path using Storage facade
            $fullPath = Storage::path($import->file_path);

            if (!file_exists($fullPath)) {
                throw new \Exception("Import file not found: {$fullPath}");
            }

            $spreadsheet = IOFactory::load($fullPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Remove header row
            $headers = array_shift($rows);

            // Auto-detect column indices
            $columnMap = $this->detectColumns($headers);

            $import->total_rows = count($rows);
            $import->save();

            $successCount = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $this->importTransactionRow($row, $columnMap, $import->branch_id);
                    $successCount++;

                } catch (\Exception $e) {
                    $errors[] = [
                        'row' => $index + 2,
                        'message' => $e->getMessage(),
                    ];
                }
            }

            DB::commit();

            $import->successful_rows = $successCount;
            $import->failed_rows = count($errors);
            $import->errors = $errors;
            $import->markAsCompleted();

            return redirect()->route('admin.imports.show', $import)
                ->with('success', "Import completed! {$successCount} transactions imported successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            $import->markAsFailed(['error' => $e->getMessage()]);

            return redirect()->route('admin.imports.show', $import)
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function show(Import $import)
    {
        // Get preview if pending
        $previewData = null;
        if ($import->status === 'pending') {
            try {
                $fullPath = Storage::path($import->file_path);
                if (file_exists($fullPath)) {
                    $previewData = $this->getFilePreview($fullPath, 50);
                }
            } catch (\Exception $e) {
                // Ignore preview errors
            }
        }

        // Get imported transactions if completed
        $transactions = null;
        if ($import->status === 'completed' && $import->started_at && $import->completed_at) {
            $transactions = Transaction::where('branch_id', $import->branch_id)
                ->whereBetween('created_at', [$import->started_at, $import->completed_at])
                ->with(['customer', 'items'])
                ->latest()
                ->limit(20)
                ->get();
        }

        return view('admin.imports.show', compact('import', 'previewData', 'transactions'));
    }

    public function destroy(Import $import)
    {
        // Delete file
        Storage::delete($import->file_path);
        $import->delete();

        return redirect()->route('admin.imports.index')
            ->with('success', 'Import deleted successfully!');
    }

    /**
     * Get preview of file data
     */
    private function getFilePreview($filePath, $rows = 50)
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $data = $worksheet->toArray();

        return array_slice($data, 0, min($rows + 1, count($data)));
    }

    /**
     * Auto-detect column names from headers
     */
    private function detectColumns($headers)
    {
        $map = [];

        foreach ($headers as $index => $header) {
            $normalized = strtolower(trim($header));

            // Transaction code/invoice
            if (in_array($normalized, ['transaction_code', 'invoice', 'invoice_number', 'receipt_no'])) {
                $map['transaction_code'] = $index;
            }
            // Date
            if (in_array($normalized, ['date', 'transaction_date', 'invoice_date', 'timestamp'])) {
                $map['date'] = $index;
            }
            // Product
            if (in_array($normalized, ['product', 'product_name', 'item', 'item_name'])) {
                $map['product_name'] = $index;
            }
            // SKU
            if (in_array($normalized, ['sku', 'product_code', 'code', 'item_code'])) {
                $map['sku'] = $index;
            }
            // Quantity
            if (in_array($normalized, ['quantity', 'qty', 'amount'])) {
                $map['quantity'] = $index;
            }
            // Price
            if (in_array($normalized, ['price', 'unit_price', 'rate'])) {
                $map['unit_price'] = $index;
            }
            // Total
            if (in_array($normalized, ['total', 'amount', 'grand_total', 'net_amount'])) {
                $map['total'] = $index;
            }
            // Customer
            if (in_array($normalized, ['customer', 'customer_name', 'client'])) {
                $map['customer_name'] = $index;
            }
            if (in_array($normalized, ['email', 'customer_email'])) {
                $map['customer_email'] = $index;
            }
            // Payment
            if (in_array($normalized, ['payment_method', 'payment', 'payment_type'])) {
                $map['payment_method'] = $index;
            }
            // Discount
            if (in_array($normalized, ['discount', 'discount_amount'])) {
                $map['discount'] = $index;
            }
        }

        return $map;
    }

    /**
     * Import a single transaction row
     */
    private function importTransactionRow($row, $map, $branchId)
    {
        // Extract data
        $transactionCode = $row[$map['transaction_code'] ?? 0] ?? null;
        $date = $row[$map['date'] ?? 1] ?? now();
        $productName = $row[$map['product_name'] ?? 2] ?? 'Unknown Product';
        $sku = $row[$map['sku'] ?? -1] ?? null;
        $quantity = $row[$map['quantity'] ?? 3] ?? 1;
        $unitPrice = $row[$map['unit_price'] ?? 4] ?? 0;
        $total = $row[$map['total'] ?? 5] ?? ($quantity * $unitPrice);
        $customerName = $row[$map['customer_name'] ?? -1] ?? null;
        $customerEmail = $row[$map['customer_email'] ?? -1] ?? null;
        $paymentMethod = $row[$map['payment_method'] ?? -1] ?? 'cash';
        $discount = $row[$map['discount'] ?? -1] ?? 0;

        // Clean and validate
        $quantity = (int) $quantity;
        $unitPrice = (float) str_replace(',', '', $unitPrice);
        $total = (float) str_replace(',', '', $total);
        $discount = (float) str_replace(',', '', $discount);

        // Parse date
        try {
            $date = \Carbon\Carbon::parse($date);
        } catch (\Exception $e) {
            $date = now();
        }

        // Find or create customer
        $customer = null;
        if ($customerName) {
            $customer = Customer::firstOrCreate(
                ['name' => trim($customerName)],
                [
                    'email' => $customerEmail,
                    'segment' => 'new',
                ]
            );
        }

        // Check if transaction already exists
        if ($transactionCode) {
            $existingTransaction = Transaction::where('transaction_code', $transactionCode)->first();
            if ($existingTransaction) {
                // Add item to existing transaction
                $transaction = $existingTransaction;
            } else {
                // Create new transaction
                $transaction = $this->createTransaction([
                    'transaction_code' => $transactionCode,
                    'branch_id' => $branchId,
                    'customer_id' => $customer?->id,
                    'transaction_date' => $date,
                    'subtotal' => $total,
                    'discount' => $discount,
                    'total' => $total - $discount,
                    'payment_method' => $paymentMethod,
                ]);
            }
        } else {
            // Auto-generate transaction code
            $transaction = $this->createTransaction([
                'branch_id' => $branchId,
                'customer_id' => $customer?->id,
                'transaction_date' => $date,
                'subtotal' => $total,
                'discount' => $discount,
                'total' => $total - $discount,
                'payment_method' => $paymentMethod,
            ]);
        }

        // Find product by name or SKU
        $product = null;
        if ($sku) {
            $product = Product::where('sku', $sku)->first();
        }
        if (!$product && $productName) {
            $product = Product::where('name', 'LIKE', '%' . $productName . '%')->first();
        }

        // Create transaction item
        TransactionItem::create([
            'transaction_id' => $transaction->id,
            'product_id' => $product?->id,
            'product_name' => $productName,
            'product_sku' => $sku,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'discount' => 0,
            'subtotal' => $quantity * $unitPrice,
        ]);

        // Update customer stats
        if ($customer) {
            $customer->updateStats();
        }
    }

    /**
     * Create transaction helper
     */
    private function createTransaction($data)
    {
        if (!isset($data['transaction_code'])) {
            $data['transaction_code'] = 'IMP-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        }

        return Transaction::create($data);
    }
}
