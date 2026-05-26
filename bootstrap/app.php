<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 1. Tambahkan pengecualian CSRF untuk Midtrans Callback
        $middleware->validateCsrfTokens(except: [
            'midtrans/callback',
            'daftar/midtrans/callback',
            'midtrans-callback',
        ]);

        // 2. Alias Middleware yang sudah kamu buat
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // 3. Pengaturan Redirect User berdasarkan Role
        $middleware->redirectUsersTo(function () {
            $userRole = strtolower(auth()->user()->role ?? '');
            if ($userRole === 'ketua') return route('dashboard.ketua');
            if ($userRole === 'bendahara') return route('dashboard.bendahara');
            if ($userRole === 'humas') return route('dashboard.humas');
            if ($userRole === 'siswa') return route('dashboard.siswa');
            return '/';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
