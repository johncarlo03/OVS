<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = null)
{
    if (Auth::guard($guard)->check()) {
        // Redirect authenticated users to their appropriate page
        return redirect()->route('voting'); // For regular users
        // OR
        // return redirect()->route('admin'); // For admin users
    }

    return $next($request);
}

}
