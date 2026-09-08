<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // KUNCI ANTI-419: Paksa HTTPS di Vercel agar CSRF Token tidak diblokir browser
        if (config('app.env') === 'production' || isset($_SERVER['VERCEL']) || env('IS_VERCEL', true)) {
            URL::forceScheme('https');
        }
    }
}
