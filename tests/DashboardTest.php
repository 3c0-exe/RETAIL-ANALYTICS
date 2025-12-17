<?php
// Quick Dashboard Tester
// Run this from your terminal: php tests/DashboardTest.php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Branch;

echo "=== PHASE 5A: Dashboard Data Test ===\n\n";

// Test 1: Check Transaction Data
$transactionCount = Transaction::count();
echo "✓ Transactions in database: " . $transactionCount . "\n";

if ($transactionCount > 0) {
    $firstTransaction = Transaction::first();
    echo "  - First transaction: " . $firstTransaction->transaction_code . "\n";
    echo "  - Amount: ₱" . number_format($firstTransaction->total_amount, 2) . "\n";

    $oldestDate = Transaction::min('timestamp');
    $newestDate = Transaction::max('timestamp');
    echo "  - Date range: " . $oldestDate . " to " . $newestDate . "\n";
}

// Test 2: Today's Sales
$todaySales = Transaction::whereDate('timestamp', today())->sum('total_amount');
echo "\n✓ Today's sales: ₱" . number_format($todaySales, 2) . "\n";

// Test 3: Monthly Sales
$monthlySales = Transaction::whereBetween('timestamp', [
    now()->startOfMonth(),
    now()->endOfMonth()
])->sum('total_amount');
echo "✓ This month's sales: ₱" . number_format($monthlySales, 2) . "\n";

// Test 4: Product Count
$productCount = Product::count();
echo "✓ Products in database: " . $productCount . "\n";

// Test 5: Customer Count
$customerCount = Customer::count();
echo "✓ Customers in database: " . $customerCount . "\n";

// Test 6: Branch Count
$branchCount = Branch::count();
echo "✓ Branches in database: " . $branchCount . "\n";

echo "\n=== Test Complete ===\n";
echo "Now visit: http://localhost/RETAIL-ANALYTICS/public/dashboard\n";
