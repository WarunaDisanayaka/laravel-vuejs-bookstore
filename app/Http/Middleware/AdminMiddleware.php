<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedRoles = [1, 2, 3]; // Define the allowed isAdmin values

        if (auth()->check() && in_array(auth()->user()->isAdmin, $allowedRoles)) {
            return $next($request);
        }

        return redirect()->route('home')->with('error', 'Access denied. You are not authorized.');
    }

}
