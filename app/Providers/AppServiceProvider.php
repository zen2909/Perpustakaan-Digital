<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        URL::forceScheme('https');

        Blade::if('role', function ($roles) {
            if (!auth()->check()) {
                return false;
            }

            $roles = is_array($roles)
                ? $roles
                : explode(',', $roles);

            return in_array(auth()->user()->role, $roles);
        });

    }
}