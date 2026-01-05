<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\InventoryUnit; // Wajib diimport
use App\Models\InventoryItem;
use App\Observers\InventoryUnitObserver; // Wajib diimport
use App\Observers\InventoryItemObserver;
use App\Policies\InventoryItemPolicy;
use App\Policies\InventoryUnitPolicy;
use Illuminate\Support\Facades\URL;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('path.public', function () {
            return base_path('../public_html');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Register observers for activity logging
        InventoryUnit::observe(InventoryUnitObserver::class);
        InventoryItem::observe(InventoryItemObserver::class);

        // Define rate limiters
        RateLimiter::for('form', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
