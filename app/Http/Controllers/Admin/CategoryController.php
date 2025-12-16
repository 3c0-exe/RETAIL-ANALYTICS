<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category = Category::create($validated);

        ActivityLog::log('created', 'Category', $category->id, ['name' => $category->name]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $oldData = $category->toArray();
        $category->update($validated);

        ActivityLog::log('updated', 'Category', $category->id, $oldData, $category->toArray());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with existing products!');
        }

        $categoryName = $category->name;
        $category->delete();

        ActivityLog::log('deleted', 'Category', $category->id, ['name' => $categoryName]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
