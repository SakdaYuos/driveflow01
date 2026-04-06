<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $isAdmin = auth()->user()->is_admin ?? false;

        if ($role === 'admin' && !$isAdmin) {
            abort(403, 'Access denied. Admin only.');
        }

        if ($role === 'customer' && $isAdmin) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
