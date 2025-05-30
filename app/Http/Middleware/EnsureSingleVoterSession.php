<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class EnsureSingleVoterSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
{
    if (auth()->check()) {
        $voter = auth()->user(); // or auth()->guard('voter')->user();
        $currentToken = session('session_token');

        if ($voter->session_token !== $currentToken) {
            auth()->logout();
            Session::flush();
            return redirect('/login')->withErrors(['message' => 'You have been logged out because your account was accessed from another device.']);
        }
    }

    return $next($request);
}
}
