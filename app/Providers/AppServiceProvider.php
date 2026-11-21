<?php
namespace App\Providers;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Wasmer Edge menyuntikkan DB_HOST, DB_PORT, DB_NAME, DB_USERNAME, DB_PASSWORD (Laravel membaca DB_DATABASE)
        if (env('DB_NAME') && env('DB_HOST')) {
            config(['database.default' => 'mysql', 'database.connections.mysql.host' => env('DB_HOST'), 'database.connections.mysql.port' => env('DB_PORT', '3306'),
                'database.connections.mysql.database' => env('DB_NAME'), 'database.connections.mysql.username' => env('DB_USERNAME'), 'database.connections.mysql.password' => env('DB_PASSWORD')]);
        }
    }

    public function boot(): void
    {
        // Di hosting (production) semua tautan harus https agar callback Google cocok
        if (app()->environment('production')) URL::forceScheme('https');
    }
}
