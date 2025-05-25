<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // If no specific roles are required or user is a manager (who has all access)
        if (empty($roles) || $request->user()->role === 'manager') {
            return $next($request);
        }

        // Check if user has one of the required roles
        if (in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        // If the request expects JSON (API request)
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized. Insufficient permissions.'], 403);
        }

        // For web requests, redirect to home with an error message
        return redirect()->route('home')->with('error', 'You do not have permission to access this page.');
    }
}
