<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        // Gunakan view Bootstrap 5 untuk pagination di semua halaman.
        // Supaya markup konsisten (.page-item .page-link) dan styling
        // pagination di layouts/admin.blade.php berlaku merata.
        Paginator::useBootstrapFive();
    }
}
