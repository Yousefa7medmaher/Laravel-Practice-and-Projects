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
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Redirect based on user role
                if ($user->role === 'manager') {
                    return redirect('/dashboard');
                } elseif ($user->role === 'instructor') {
                    return redirect('/courses');
                } elseif ($user->role === 'student') {
                    return redirect('/student/dashboard');
                }

                // Default fallback
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
