<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Import;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Category;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

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
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('imports', $fileName);
            $fullPath = Storage::path($filePath);

            if (!file_exists($fullPath)) {
                throw new \Exception("File upload failed. Path: {$fullPath}");
            }

            $import = Import::create([
                'user_id' => auth()->id(),
                'branch_id' => $request->branch_id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'import_type' => 'transactions',
                'status' => 'pending',
            ]);

            $preview = $this->getFilePreview($fullPath);

            return view('admin.imports.show', [
                'import' => $import,
                'previewData' => $preview,
            ]);

        } catch (\Exception $e) {
            if (isset($import)) {
                $import->delete();
            }
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
            $fullPath = Storage::path($import->file_path);

            if (!file_exists($fullPath)) {
                throw new \Exception("Import file not found: {$fullPath}");
            }

            $spreadsheet = IOFactory::load($fullPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            $headers = array_shift($rows);
            $columnMap = $this->detectColumns($headers);

            $import->total_rows = count($rows);
            $import->save();

            $successCount = 0;
            $errors = [];
            $affectedCustomerIds = [];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                try {
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $customerId = $this->importTransactionRow($row, $columnMap, $import->branch_id);
                    if ($customerId) {
                        $affectedCustomerIds[] = $customerId;
                    }
                    $successCount++;

                } catch (\Exception $e) {
                    $errors[] = [
                        'row' => $index + 2,
                        'message' => $e->getMessage(),
                    ];
                }
            }

            DB::commit();

            // POST-IMPORT PROCESSING
            $this->postImportProcessing($affectedCustomerIds);

            $import->successful_rows = $successCount;
            $import->failed_rows = count($errors);
            $import->errors = $errors;
            $import->markAsCompleted();

            return redirect()->route('admin.imports.show', $import)
                ->with('success', "Import completed! {$successCount} transactions imported. Categories auto-created, forecasts updated.");

        } catch (\Exception $e) {
            DB::rollBack();
            $import->markAsFailed(['error' => $e->getMessage()]);

            return redirect()->route('admin.imports.show', $import)
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * POST-IMPORT PROCESSING
     * Run RFM calculation and forecast regeneration
     */
    private function postImportProcessing($affectedCustomerIds)
    {
        try {
            // 1. Recalculate RFM for affected customers (if command exists)
            if (!empty($affectedCustomerIds)) {
                $uniqueCustomers = array_unique($affectedCustomerIds);
                \Log::info("Recalculating RFM for " . count($uniqueCustomers) . " customers");

                try {
                    Artisan::call('customer:calculate-rfm');
                } catch (\Exception $e) {
                    \Log::warning("RFM command not found: " . $e->getMessage());
                }
            }

            // 2. Regenerate forecasts
            \Log::info("Regenerating forecasts after import");
            try {
                Artisan::call('forecast:generate');
            } catch (\Exception $e) {
                \Log::warning("Forecast generation failed: " . $e->getMessage());
            }

        } catch (\Exception $e) {
            \Log::error("Post-import processing failed: " . $e->getMessage());
            // Don't fail the entire import if post-processing fails
        }
    }

    public function show(Import $import)
    {
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

/**
 * Get import statistics for delete confirmation
 */
public function getDeleteStats(Import $import)
{
    try {
        if ($import->status !== 'completed') {
            return response()->json([
                'transactions' => 0,
                'sales' => '0.00',
                'new_customers' => 0,
                'repeat_customers' => 0,
            ]);
        }

        // Get transactions from this import's timeframe
        $transactions = Transaction::where('branch_id', $import->branch_id)
            ->whereBetween('created_at', [$import->started_at, $import->completed_at])
            ->get();

        $totalTransactions = $transactions->count();
        $totalSales = $transactions->sum('total_amount');

        // Get unique customers from these transactions
        $customerIds = $transactions->pluck('customer_id')->unique()->filter();

        // Determine new vs repeat customers
        $newCustomers = 0;
        $repeatCustomers = 0;

        foreach ($customerIds as $customerId) {
            // Check if customer has transactions before this import
            $hasEarlierTransactions = Transaction::where('customer_id', $customerId)
                ->where('created_at', '<', $import->started_at)
                ->exists();

            if ($hasEarlierTransactions) {
                $repeatCustomers++;
            } else {
                $newCustomers++;
            }
        }

        return response()->json([
            'transactions' => $totalTransactions,
            'sales' => number_format($totalSales, 2, '.', ','),
            'new_customers' => $newCustomers,
            'repeat_customers' => $repeatCustomers,
        ]);

    } catch (\Exception $e) {
        \Log::error('Failed to get import stats: ' . $e->getMessage());
        return response()->json([
            'transactions' => 0,
            'sales' => '0.00',
            'new_customers' => 0,
            'repeat_customers' => 0,
        ], 500);
    }
}

/**
 * Delete import - with options to delete data or just record
 */
public function destroy(Import $import, Request $request)
{
    $deleteData = $request->boolean('delete_data', false);

    try {
        DB::beginTransaction();

        if ($deleteData && $import->status === 'completed') {
            // DELETE ALL DATA ASSOCIATED WITH THIS IMPORT

            // Get transactions from this import timeframe
            $transactions = Transaction::where('branch_id', $import->branch_id)
                ->whereBetween('created_at', [$import->started_at, $import->completed_at])
                ->get();

            $transactionIds = $transactions->pluck('id');
            $customerIds = $transactions->pluck('customer_id')->unique()->filter();

            // 1. Delete transaction items
            TransactionItem::whereIn('transaction_id', $transactionIds)->delete();
            \Log::info("Deleted transaction items for import {$import->id}");

            // 2. Delete transactions
            Transaction::whereIn('id', $transactionIds)->delete();
            \Log::info("Deleted {$transactionIds->count()} transactions for import {$import->id}");

            // 3. Handle customers
            foreach ($customerIds as $customerId) {
                $customer = Customer::find($customerId);
                if ($customer) {
                    // Check if customer has any remaining transactions
                    $remainingTransactions = Transaction::where('customer_id', $customerId)->count();

                    if ($remainingTransactions === 0) {
                        // Customer has no more transactions, delete them
                        $customer->delete();
                        \Log::info("Deleted customer {$customerId} - no remaining transactions");
                    } else {
                        // Customer has other transactions, recalculate their stats
                        $customer->updateStats();
                        \Log::info("Recalculated stats for customer {$customerId}");
                    }
                }
            }

            // 4. Recalculate RFM for all remaining customers
            try {
                Artisan::call('customer:calculate-rfm');
            } catch (\Exception $e) {
                \Log::warning("RFM recalculation failed: " . $e->getMessage());
            }

            // 5. Regenerate forecasts
            try {
                Artisan::call('forecast:generate');
            } catch (\Exception $e) {
                \Log::warning("Forecast regeneration failed: " . $e->getMessage());
            }

            $message = "Import and all associated data deleted successfully! {$transactionIds->count()} transactions removed.";
        } else {
            // DELETE ONLY THE IMPORT RECORD
            $message = "Import record deleted successfully!";
        }

        // Delete the file
        if (Storage::exists($import->file_path)) {
            Storage::delete($import->file_path);
        }

        // Delete the import record
        $import->delete();

        DB::commit();

        return redirect()->route('admin.imports.index')
            ->with('success', $message);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error("Import deletion failed: " . $e->getMessage());

        return redirect()->route('admin.imports.index')
            ->with('error', 'Failed to delete import: ' . $e->getMessage());
    }
}

    /**
     * Download sample CSV template - UPDATED with age/gender
     */
    public function downloadSample()
    {
        $headers = [
            'transaction_code',
            'date',
            'product_name',
            'sku',
            'quantity',
            'unit_price',
            'total',
            'customer_name',
            'customer_email',
            'customer_age',
            'customer_gender',
            'payment_method',
            'discount'
        ];

        $sampleData = [
            ['TXN001', '2025-12-01', 'Gaming Laptop', 'LAP001', '1', '45000', '45000', 'Juan Dela Cruz', 'juan@email.com', '28', 'male', 'cash', '0'],
            ['TXN001', '2025-12-01', 'Wireless Mouse', 'MOU001', '2', '500', '1000', 'Juan Dela Cruz', 'juan@email.com', '28', 'male', 'cash', '0'],
            ['TXN002', '2025-12-02', '4K Monitor', 'MON001', '1', '15000', '14500', 'Maria Santos', 'maria@email.com', '34', 'female', 'card', '500'],
            ['TXN003', '2025-12-03', 'Mechanical Keyboard', 'KEY001', '3', '1200', '3600', 'Pedro Garcia', 'pedro@email.com', '45', 'male', 'gcash', '0'],
        ];

        $filename = 'sample_sales_template_' . date('Y-m-d') . '.csv';

        $callback = function() use ($headers, $sampleData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export import errors as CSV
     */
    public function exportErrors(Import $import)
    {
        if (empty($import->errors) || $import->failed_rows === 0) {
            return redirect()->route('admin.imports.show', $import)
                ->with('error', 'No errors to export.');
        }

        $filename = 'import_errors_' . $import->id . '_' . date('Y-m-d') . '.csv';

        $callback = function() use ($import) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Row Number', 'Error Message']);

            foreach ($import->errors as $error) {
                fputcsv($file, [
                    $error['row'] ?? 'N/A',
                    $error['message'] ?? 'Unknown error'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
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
     * Auto-detect column names from headers - UPDATED with age/gender
     */
    private function detectColumns($headers)
    {
        $map = [];

        foreach ($headers as $index => $header) {
            $normalized = strtolower(trim($header));

            if (in_array($normalized, ['transaction_code', 'invoice', 'invoice_number', 'receipt_no'])) {
                $map['transaction_code'] = $index;
            }
            if (in_array($normalized, ['date', 'transaction_date', 'invoice_date', 'timestamp'])) {
                $map['date'] = $index;
            }
            if (in_array($normalized, ['product', 'product_name', 'item', 'item_name'])) {
                $map['product_name'] = $index;
            }
            if (in_array($normalized, ['sku', 'product_code', 'code', 'item_code'])) {
                $map['sku'] = $index;
            }
            if (in_array($normalized, ['quantity', 'qty', 'amount'])) {
                $map['quantity'] = $index;
            }
            if (in_array($normalized, ['price', 'unit_price', 'rate'])) {
                $map['unit_price'] = $index;
            }
            if (in_array($normalized, ['total', 'amount', 'grand_total', 'net_amount'])) {
                $map['total'] = $index;
            }
            if (in_array($normalized, ['customer', 'customer_name', 'client'])) {
                $map['customer_name'] = $index;
            }
            if (in_array($normalized, ['email', 'customer_email'])) {
                $map['customer_email'] = $index;
            }
            if (in_array($normalized, ['age', 'customer_age'])) {
                $map['customer_age'] = $index;
            }
            if (in_array($normalized, ['gender', 'customer_gender', 'sex'])) {
                $map['customer_gender'] = $index;
            }
            if (in_array($normalized, ['payment_method', 'payment', 'payment_type'])) {
                $map['payment_method'] = $index;
            }
            if (in_array($normalized, ['discount', 'discount_amount'])) {
                $map['discount'] = $index;
            }
        }

        return $map;
    }

    /**
     * Extract and create category from product name
     */
    private function getOrCreateCategory($productName)
    {
        $categoryMap = [
            'laptop' => 'Laptops & Computers',
            'macbook' => 'Laptops & Computers',
            'pc' => 'Laptops & Computers',
            'mouse' => 'Accessories',
            'keyboard' => 'Accessories',
            'headset' => 'Audio',
            'headphone' => 'Audio',
            'speaker' => 'Audio',
            'earbuds' => 'Audio',
            'airpods' => 'Audio',
            'microphone' => 'Audio',
            'mic' => 'Audio',
            'iphone' => 'Smartphones',
            'phone' => 'Smartphones',
            'galaxy' => 'Smartphones',
            'pixel' => 'Smartphones',
            'oneplus' => 'Smartphones',
            'monitor' => 'Monitors & Displays',
            'display' => 'Monitors & Displays',
            'ipad' => 'Tablets',
            'tablet' => 'Tablets',
            'camera' => 'Cameras & Photography',
            'webcam' => 'Cameras & Photography',
            'drone' => 'Cameras & Photography',
            'gopro' => 'Cameras & Photography',
            'chair' => 'Furniture',
            'desk' => 'Furniture',
            'stand' => 'Furniture',
            'printer' => 'Printers & Scanners',
            'ssd' => 'Storage',
            'hdd' => 'Storage',
            'drive' => 'Storage',
            'watch' => 'Wearables',
            'band' => 'Wearables',
            'ps5' => 'Gaming',
            'playstation' => 'Gaming',
            'xbox' => 'Gaming',
            'nintendo' => 'Gaming',
            'switch' => 'Gaming',
            'game' => 'Gaming',
            'controller' => 'Gaming',
            'steam' => 'Gaming',
            'gpu' => 'PC Components',
            'graphics' => 'PC Components',
            'ram' => 'PC Components',
            'rtx' => 'PC Components',
            'router' => 'Networking',
            'wifi' => 'Networking',
            'cable' => 'Cables & Adapters',
            'adapter' => 'Cables & Adapters',
            'charger' => 'Cables & Adapters',
            'hub' => 'Cables & Adapters',
            'usb' => 'Cables & Adapters',
            'hdmi' => 'Cables & Adapters',
            'tv' => 'TVs & Home Theater',
            'oled' => 'TVs & Home Theater',
            'soundbar' => 'TVs & Home Theater',
            'vacuum' => 'Home Appliances',
            'coffee' => 'Home Appliances',
            'nespresso' => 'Home Appliances',
            'dyson' => 'Home Appliances',
            'vr' => 'Virtual Reality',
            'quest' => 'Virtual Reality',
            'power bank' => 'Mobile Accessories',
            'case' => 'Mobile Accessories',
            'screen protector' => 'Mobile Accessories',
            'tripod' => 'Camera Accessories',
            'ring light' => 'Camera Accessories',
            'smart plug' => 'Smart Home',
            'smart home' => 'Smart Home',
            'nest' => 'Smart Home',
            'hue' => 'Smart Home',
        ];

        $productLower = strtolower($productName);
        $categoryName = 'Electronics'; // Default

        // Find matching category
        foreach ($categoryMap as $keyword => $category) {
            if (strpos($productLower, $keyword) !== false) {
                $categoryName = $category;
                break;
            }
        }

        // Get or create category
        $category = Category::firstOrCreate(
            ['name' => $categoryName],
            ['description' => 'Auto-generated from import']
        );

        return $category->id;
    }

    /**
     * Import a single transaction row with all enhancements
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
        $customerAge = $row[$map['customer_age'] ?? -1] ?? null;
        $customerGender = $row[$map['customer_gender'] ?? -1] ?? null;
        $paymentMethod = $row[$map['payment_method'] ?? -1] ?? 'cash';
        $discount = $row[$map['discount'] ?? -1] ?? 0;

        // Clean and validate
        $quantity = (int) $quantity;
        $unitPrice = (float) str_replace(',', '', $unitPrice);
        $total = (float) str_replace(',', '', $total);
        $discount = (float) str_replace(',', '', $discount);
        $customerAge = $customerAge ? (int) $customerAge : null;
        $customerGender = $customerGender ? strtolower(trim($customerGender)) : null;

        // Parse date with realistic business hours (FIX #1: Heatmap)
        try {
            $date = \Carbon\Carbon::parse($date);

            // Add realistic business hours (9 AM - 9 PM) if time is midnight
            if ($date->format('H:i:s') === '00:00:00') {
                $hour = rand(9, 21); // 9 AM to 9 PM
                $minute = rand(0, 59);
                $date->setTime($hour, $minute, 0);
            }
        } catch (\Exception $e) {
            \Log::error("Failed to parse date: " . $date);
            throw new \Exception("Invalid date format: " . $date);
        }

        // Find or create customer with demographics
        $customer = null;
        if ($customerName) {
            $customerData = ['name' => trim($customerName)];

            $customer = Customer::firstOrCreate(
                $customerData,
                [
                    'email' => $customerEmail,
                    'age' => $customerAge,
                    'gender' => $customerGender,
                    'segment' => 'new',
                ]
            );

            // Update age/gender if customer exists but data is missing
            if ($customer->wasRecentlyCreated === false) {
                $updated = false;
                if ($customerAge && !$customer->age) {
                    $customer->age = $customerAge;
                    $updated = true;
                }
                if ($customerGender && !$customer->gender) {
                    $customer->gender = $customerGender;
                    $updated = true;
                }
                if ($updated) {
                    $customer->save();
                }
            }
        }

        // FIX #4: Assign random cashier from branch
        $cashierId = null;
        $availableCashiers = \App\Models\User::where('branch_id', $branchId)
            ->whereIn('role', ['cashier', 'branch_manager', 'admin'])
            ->pluck('id')
            ->toArray();

        if (!empty($availableCashiers)) {
            $cashierId = $availableCashiers[array_rand($availableCashiers)];
        }

        // Check for duplicate transaction code
        if ($transactionCode) {
            $existingTransaction = Transaction::where('transaction_code', $transactionCode)
                ->where('branch_id', $branchId)
                ->first();

            if ($existingTransaction) {
                $transaction = $existingTransaction;
            } else {
                $transaction = $this->createTransaction([
                    'transaction_code' => $transactionCode,
                    'branch_id' => $branchId,
                    'customer_id' => $customer?->id,
                    'cashier_id' => $cashierId, // FIX #4
                    'timestamp' => $date,
                    'subtotal' => $total,
                    'discount_amount' => $discount,
                    'total_amount' => $total - $discount,
                    'payment_method' => $paymentMethod,
                ]);
            }
        } else {
            $transaction = $this->createTransaction([
                'branch_id' => $branchId,
                'customer_id' => $customer?->id,
                'cashier_id' => $cashierId, // FIX #4
                'timestamp' => $date,
                'subtotal' => $total,
                'discount_amount' => $discount,
                'total_amount' => $total - $discount,
                'payment_method' => $paymentMethod,
            ]);
        }

        // FIX #2: Find or CREATE product with auto-category
        $product = null;
        if ($sku) {
            $product = Product::where('sku', $sku)->first();
        }

        if (!$product && $productName) {
            $product = Product::where('name', 'LIKE', '%' . $productName . '%')->first();

            // If still not found, create it with category
            if (!$product) {
                $categoryId = $this->getOrCreateCategory($productName);

                $product = Product::create([
                    'sku' => $sku ?: 'AUTO-' . strtoupper(Str::random(6)),
                    'name' => $productName,
                    'category_id' => $categoryId,
                    'price' => $unitPrice,
                    'cost' => round($unitPrice * 0.6, 2), // Assume 40% margin
                    'status' => 'active',
                ]);

                \Log::info("Auto-created product: {$productName} in category ID {$categoryId}");
            }
        }

        // Update product's category if it doesn't have one
        if ($product && !$product->category_id) {
            $product->category_id = $this->getOrCreateCategory($productName);
            $product->save();
        }

        // Check for duplicate transaction item
        $existingItem = TransactionItem::where('transaction_id', $transaction->id)
            ->where('product_name', $productName)
            ->where('product_sku', $sku)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $quantity;
            $existingItem->subtotal = $existingItem->quantity * $existingItem->unit_price;
            $existingItem->save();
        } else {
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
        }

        // Recalculate transaction totals
        $transaction->subtotal = $transaction->items()->sum('subtotal');
        $transaction->total_amount = $transaction->subtotal + $transaction->tax_amount - $transaction->discount_amount;
        $transaction->save();

        // Update customer stats
        if ($customer) {
            $customer->updateStats();
        }

        return $customer?->id;
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
