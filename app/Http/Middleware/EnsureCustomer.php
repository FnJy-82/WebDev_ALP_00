<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user login DAN role-nya customer
        if (Auth::check() && Auth::user()->role === 'customer') {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Halaman ini khusus Customer.');
    }
}
