<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { color: #8b5cf6; font-size: 24px; margin-bottom: 5px; }
        .subtitle { color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #8b5cf6; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .footer { margin-top: 30px; text-align: center; color: #999; font-size: 10px; }
    </style>
</head>
<body>
    <h1>Sales Analytics Report</h1>
    <p class="period">Period: {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Branch</th>
                <th class="text-right">Transactions</th>
                <th class="text-right">Total Sales</th>
                <th class="text-right">Avg Transaction</th>
            </tr>
        </thead>
        <tbody>
            @forelse($salesByBranch as $branch)
                <tr>
                    <td>{{ $branch->branch }}</td>
                    <td class="text-right">{{ number_format($branch->transactions) }}</td>
                    <td class="text-right">PHP {{ number_format($branch->total_sales, 2) }}</td>
                    <td class="text-right">PHP {{ number_format($branch->avg_transaction, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 30px; color: #999;">
                        No sales data available for the selected period
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('F d, Y \a\t h:i A') }} | RetailAnalytics Platform
    </div>
</body>
</html>
