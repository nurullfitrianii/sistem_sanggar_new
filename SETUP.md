# Panduan Menjalankan Sistem Sanggar

Aplikasi ini adalah modul Laravel (routes, controllers, models, views, migrations). Agar bisa berjalan, pastikan:

## 1. Lingkungan Laravel

- Project ini harus berada **di dalam** project Laravel lengkap (yang punya `composer.json`, `artisan`, `bootstrap/`, `config/`, `public/`).
- Jika belum: buat project Laravel baru (`composer create-project laravel/laravel nama_project`), lalu salin/merge folder `app`, `database`, `resources`, `routes` dari folder ini ke project Laravel tersebut.

## 2. Middleware Role

Route memakai middleware `role:ketua`, `role:humas`, dll. Daftarkan alias `role`:

**Laravel 10** – di `app/Http/Kernel.php`, tambahkan di `$middlewareAliases`:

```php
'role' => \App\Http\Middleware\RoleMiddleware::class,
```

**Laravel 11** – di `bootstrap/app.php`, dalam `->withMiddleware()`:

```php
->alias([
    'role' => \App\Http\Middleware\RoleMiddleware::class,
])
```

## 3. Database

- Buat database MySQL: `db_sistem_sanggar`
- Import: `db_sistem_sanggar.sql`
- Atur `.env`: `DB_DATABASE=db_sistem_sanggar`, `DB_USERNAME`, `DB_PASSWORD`

## 4. Menjalankan

```bash
php artisan serve
```

Buka http://localhost:8000. Login contoh: `ketua@sanggar.test` / `password` (lihat data di `db_sistem_sanggar.sql`).
