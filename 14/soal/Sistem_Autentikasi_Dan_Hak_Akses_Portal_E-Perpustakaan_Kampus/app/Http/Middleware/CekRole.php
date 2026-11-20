<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('/perpus/login');
        }

        if (Auth::user()->role !== $role) {
            abort(403, 'AKSES DITOLAK: Halaman ini khusus untuk Role ' . strtoupper($role) . '!');
        }

        return $next($request);
    }
}
