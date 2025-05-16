<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AssignCustomerRole
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && !$user->roles()->exists()) {
            $user->assignRole('customer');
        }

        return $next($request);
    }
}
