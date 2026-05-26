<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // Penting untuk styling nomor halaman
use Illuminate\Support\Facades\Schema; // Penting untuk kompatibilitas database

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
        // 1. Memaksa Laravel menggunakan styling Bootstrap 5 untuk pagination
        // Ini agar tombol 'Next' & 'Previous' di daftar pendaftaran tidak berantakan
        Paginator::useBootstrapFive();

        // 2. Mencegah error "Specified key was too long" pada beberapa versi MySQL
        Schema::defaultStringLength(191);
    }
}
