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
    <p class="subtitle">Period: {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Branch</th>
                <th>Transactions</th>
                <th>Total Sales</th>
                <th>Avg Transaction</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesByBranch as $row)
            <tr>
                <td>{{ $row->branch }}</td>
                <td>{{ number_format($row->transactions) }}</td>
                <td>₱{{ number_format($row->total_sales, 2) }}</td>
                <td>₱{{ number_format($row->avg_transaction, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('F d, Y \a\t h:i A') }} | RetailAnalytics Platform
    </div>
</body>
</html>
