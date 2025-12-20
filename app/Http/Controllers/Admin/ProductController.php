<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Validate sort field to prevent SQL injection
        $allowedSortFields = ['name', 'sku', 'price', 'created_at', 'is_active'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        // Validate direction
        $sortDirection = in_array($sortDirection, ['asc', 'desc']) ? $sortDirection : 'desc';

        // Apply sorting
        if ($sortField === 'category_id') {
            $query->join('categories', 'products.category_id', '=', 'categories.id')
                  ->select('products.*', 'categories.name as category_name')
                  ->orderBy('category_name', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $products = $query->paginate(15)->appends($request->except('page'));
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $branches = Branch::where('is_active', true)->get();

        return view('admin.products.create', compact('categories', 'branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'branches' => 'nullable|array',
            'branches.*.quantity' => 'required|integer|min:0',
            'branches.*.low_stock_threshold' => 'required|integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // SKU and barcode are auto-generated in the model
        $product = Product::create($validated);

        // Add inventory to selected branches
        if ($request->has('branches')) {
            foreach ($request->branches as $branchId => $data) {
                BranchProduct::create([
                    'branch_id' => $branchId,
                    'product_id' => $product->id,
                    'quantity' => $data['quantity'],
                    'low_stock_threshold' => $data['low_stock_threshold'],
                ]);
            }
        }

        ActivityLog::log('created', 'Product', $product->id, ['name' => $product->name]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'branchProducts.branch']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $branches = Branch::where('is_active', true)->get();

        return view('admin.products.edit', compact('product', 'categories', 'branches'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'branches' => 'nullable|array',
            'branches.*.quantity' => 'required|integer|min:0',
            'branches.*.low_stock_threshold' => 'required|integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $oldData = $product->toArray();
        $product->update($validated);

        // Update inventory for branches
        if ($request->has('branches')) {
            foreach ($request->branches as $branchId => $data) {
                BranchProduct::updateOrCreate(
                    [
                        'branch_id' => $branchId,
                        'product_id' => $product->id,
                    ],
                    [
                        'quantity' => $data['quantity'],
                        'low_stock_threshold' => $data['low_stock_threshold'],
                    ]
                );
            }
        }

        ActivityLog::log('updated', 'Product', $product->id, $oldData, $product->toArray());

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $productName = $product->name;

        // Delete image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        ActivityLog::log('deleted', 'Product', $product->id, ['name' => $productName]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
