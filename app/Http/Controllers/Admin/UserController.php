<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('branch', 'roleModel');

        // Filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $branches = Branch::orderBy('name')->get();

        ActivityLog::log('viewed_users');

        return view('admin.users.index', compact('users', 'branches'));
    }

    public function create()
    {
        $branches = Branch::where('status', 'active')->orderBy('name')->get();
        $roles = Role::orderBy('display_name')->get();

        return view('admin.users.create', compact('branches', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => 'required|exists:roles,id',
            'branch_id' => 'nullable|exists:branches,id',
            'theme' => 'required|in:light,dark',
        ]);

        $role = Role::find($validated['role_id']);

        if (in_array($role->name, ['branch_manager', 'viewer'])) {
            if (!$validated['branch_id']) {
                return back()
                    ->withErrors(['branch_id' => 'Branch is required for this role.'])
                    ->withInput();
            }
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();
        $validated['role'] = $role->name;

        $user = User::create($validated);

        ActivityLog::log('created_user', User::class, $user->id, ['name' => $user->name, 'role' => $role->display_name]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        $branches = Branch::where('status', 'active')->orderBy('name')->get();
        $roles = Role::orderBy('display_name')->get();

        return view('admin.users.edit', compact('user', 'branches', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role_id' => 'required|exists:roles,id',
            'branch_id' => 'nullable|exists:branches,id',
            'theme' => 'required|in:light,dark',
        ]);

        $role = Role::find($validated['role_id']);

        if (in_array($role->name, ['branch_manager', 'viewer'])) {
            if (!$validated['branch_id']) {
                return back()
                    ->withErrors(['branch_id' => 'Branch is required for this role.'])
                    ->withInput();
            }
        } else {
            $validated['branch_id'] = null;
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['role'] = $role->name;

        $user->update($validated);

        ActivityLog::log('updated_user', User::class, $user->id, ['name' => $user->name]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Cannot delete the last admin user!');
        }

        $userName = $user->name;
        $userId = $user->id;
        $user->delete();

        ActivityLog::log('deleted_user', User::class, $userId, ['name' => $userName]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}
