<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Dikosongkan, biarkan Laravel membaca dari env
    }

    public function boot(): void
    {
        // Paksa skema HTTPS agar CSS dan JS tidak diblokir
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
