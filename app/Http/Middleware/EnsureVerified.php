<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            Auth::check()
            && Auth::user()->role !== 'admin' // Admin bypass verification
            && !Auth::user()->isVerified()
            && !$request->routeIs('verification')
            && !$request->routeIs('logout')
            && !$request->routeIs('login') // Login page
            && !$request->routeIs('register') // Register page
            && !$request->routeIs('livewire.update') // Specific to Livewire 3 default
            && !$request->routeIs('livewire.message') // Just in case Livewire 2 logic
            && !$request->routeIs('livewire.*') // Catch-all?
            && !$request->header('X-Livewire') // Safest: Check if it's a Livewire request
        ) {
            return redirect()->route('verification');
        }

        return $next($request);
    }
}
