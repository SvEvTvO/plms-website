<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // HACK: Paksa konfigurasi agar kebal dari cache Vercel
        if (config('app.env') === 'production') {
            config([
                'session.driver' => 'cookie', // Simpan di browser agar anti-amnesia
                'session.secure' => true,     // Wajib HTTPS
                'cache.default'  => 'array',  // Jangan gunakan file untuk cache
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paksa skema HTTPS agar CSS dan JS tidak diblokir
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
