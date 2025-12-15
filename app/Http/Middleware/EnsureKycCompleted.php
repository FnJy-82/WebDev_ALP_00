<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureKycCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    $user = $request->user();

    // if ($user && (is_null($user->identity_number) || is_null($user->phone_number))) {

    //     return redirect()->route('profile.edit')
    //         ->with('error', 'You must fill in your identity number and phone number before continuing');
    // }

    return $next($request);
}
}
