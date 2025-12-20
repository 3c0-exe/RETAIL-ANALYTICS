<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = Branch::with('manager')
            ->withCount('users');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Validate sort field
        $allowedSortFields = ['name', 'code', 'status', 'created_at'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        // Validate direction
        $sortDirection = in_array($sortDirection, ['asc', 'desc']) ? $sortDirection : 'desc';

        $query->orderBy($sortField, $sortDirection);

        $branches = $query->paginate(10)->appends($request->except('page'));

        ActivityLog::log('viewed_branches');

        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        // Get managers without branches or not already managing a branch
        $managers = User::where('role', 'branch_manager')
            ->whereDoesntHave('managedBranch')
            ->orderBy('name')
            ->get();

        return view('admin.branches.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:branches,code',
            'timezone' => 'required|string',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'currency' => 'required|string|size:3',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        $branch = Branch::create($validated);

        // If manager assigned, update their branch_id
        if ($validated['manager_id']) {
            User::find($validated['manager_id'])->update([
                'branch_id' => $branch->id
            ]);
        }

        ActivityLog::log('created_branch', Branch::class, $branch->id, ['name' => $branch->name]);

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Branch created successfully!');
    }

    public function edit(Branch $branch)
    {
        // Get available managers (not managing other branches)
        $managers = User::where('role', 'branch_manager')
            ->where(function($query) use ($branch) {
                $query->whereDoesntHave('managedBranch')
                      ->orWhere('id', $branch->manager_id);
            })
            ->orderBy('name')
            ->get();

        return view('admin.branches.edit', compact('branch', 'managers'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:10', Rule::unique('branches')->ignore($branch->id)],
            'timezone' => 'required|string',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'currency' => 'required|string|size:3',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle manager change
        $oldManagerId = $branch->manager_id;

        if ($oldManagerId != $validated['manager_id']) {
            // Remove old manager's branch assignment
            if ($oldManagerId) {
                User::find($oldManagerId)->update(['branch_id' => null]);
            }

            // Assign new manager
            if ($validated['manager_id']) {
                User::find($validated['manager_id'])->update(['branch_id' => $branch->id]);
            }
        }

        $branch->update($validated);

        ActivityLog::log('updated_branch', Branch::class, $branch->id, ['name' => $branch->name]);

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Branch updated successfully!');
    }

    public function destroy(Branch $branch)
    {
        // Check if branch has users
        if ($branch->users()->count() > 0) {
            return back()->with('error', 'Cannot delete branch with assigned users!');
        }

        $branchName = $branch->name;
        $branchId = $branch->id;
        $branch->delete();

        ActivityLog::log('deleted_branch', Branch::class, $branchId, ['name' => $branchName]);

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Branch deleted successfully!');
    }
}
