<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Admin Gates
        Gate::define('manage-users', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-products', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-orders', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-drivers', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('view-reports', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-settings', function ($user) {
            return $user->hasRole('admin');
        });

        // Driver Gates
        Gate::define('view-assigned-orders', function ($user) {
            return $user->hasRole('driver');
        });

        Gate::define('update-order-status', function ($user) {
            return $user->hasRole('driver');
        });

        Gate::define('view-delivery-history', function ($user) {
            return $user->hasRole('driver');
        });

        Gate::define('update-availability', function ($user) {
            return $user->hasRole('driver');
        });

        // Customer Gates
        Gate::define('place-orders', function ($user) {
            return $user->hasRole('customer');
        });

        Gate::define('view-own-orders', function ($user) {
            return $user->hasRole('customer');
        });

        Gate::define('track-orders', function ($user) {
            return $user->hasRole('customer');
        });

        Gate::define('rate-orders', function ($user) {
            return $user->hasRole('customer');
        });
    }
} 