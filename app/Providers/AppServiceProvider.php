<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        // Set global locale to Indonesian
        Carbon::setLocale('id');

        // Mengatur layout berdasarkan role pengguna
        View::composer('*', function ($view) {
            $role = auth()->user()->role ?? null;

            // Tentukan layout berdasarkan role
            if ($role === 'superadmin') {
                $layout = 'superadmin.layout_superadmin';
            } elseif ($role === 'admin') {
                $layout = 'layout'; // Layout untuk admin
            } elseif ($role === 'teknisi') {
                $layout = 'layout2'; // Layout untuk teknisi
            } else {
                $layout = 'layout'; // Layout default jika role tidak dikenali
            }

            // Berikan layout ke view
            $view->with('layout', $layout);
        });
    }
}
