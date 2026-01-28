<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Silakan login terlebih dahulu']);
        }

        $user = Auth::user();

        foreach ($roles as $role) {
            if ($role === 'Admin' && $user->role_id == 1) {
                return $next($request);
            }

            if ($role === 'User' && $user->role_id == 2) {
                return $next($request);
            }
        }

        abort(403, 'ANDA TIDAK MEMILIKI AKSES');
    }
};