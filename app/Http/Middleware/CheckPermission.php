<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Admins bypass all permission checks
        if (auth()->user()->isAdmin()) {
            return $next($request);
        }

        // Check if user has the required permission
        if (!auth()->user()->hasPermission($permission)) {
            abort(403, 'Unauthorized: You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
