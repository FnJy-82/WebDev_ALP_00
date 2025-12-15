<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
{
    $user = Auth::user();

    if (! $user) {
        abort(403);
    }

    // Jika mengecek role 'eo'
    if ($role === 'eo') {
        // Cek apakah punya profile DAN status verified
        if ($user->organizerProfile && $user->organizerProfile->verification_status === 'verified') {
            return $next($request);
        }
    }
    // Logic role biasa (admin/customer)
    elseif ($user->role === $role) {
        return $next($request);
    }

    abort(403, 'Akses Ditolak.');
}
}
