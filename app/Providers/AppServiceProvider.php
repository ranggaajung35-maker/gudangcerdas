<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Daftarkan binding service khusus aplikasi di sini.
    }

    public function boot(): void
    {
        date_default_timezone_set(config('app.timezone', 'Asia/Makassar'));
    }
}
