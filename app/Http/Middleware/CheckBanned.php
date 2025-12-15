<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in AND their 'is_banned' column is true
        if (Auth::check() && Auth::user()->is_banned) {

            // 1. Force Logout
            Auth::logout();

            // 2. Invalidate their session (security best practice)
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // 3. Redirect back to login with an error message
            return redirect()->route('banned');
        }

        return $next($request);
    }
}
