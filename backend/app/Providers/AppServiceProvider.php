<?php

namespace App\Providers;

use App\Support\ServiceHistoryReset;
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
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        if (! $this->app->runningInConsole() && (config('app.env') === 'production' || env('RAILWAY_PUBLIC_DOMAIN'))) {
            ServiceHistoryReset::runOnce('reset_service_history_20260528_force');
        }
    }
}
