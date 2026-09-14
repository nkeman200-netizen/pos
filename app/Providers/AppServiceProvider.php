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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request): void
    {
        // Memaksa Laravel mempercayai proxy eksternal (Cloudflare Tunnel)
        $request->server->set('HTTPS', 'on');
        
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
