<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BranchAccessMiddleware
{
    /**
     * Ensure branch managers only access their own branch data.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Admins and analysts can see all branches
        if ($user->canViewAllBranches()) {
            return $next($request);
        }

        // Branch managers and viewers must have a branch assigned
        if (!$user->branch_id) {
            abort(403, 'No branch assigned to your account.');
        }

        // If route has branch_id parameter, verify it matches user's branch
        if ($request->route('branch')) {
            $branchId = $request->route('branch');

            if ($branchId != $user->branch_id) {
                abort(403, 'You can only access your assigned branch.');
            }
        }

        return $next($request);
    }
}
