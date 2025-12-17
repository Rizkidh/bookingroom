<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\InventoryUnit; // Wajib diimport
use App\Observers\InventoryUnitObserver; // Wajib diimport
use App\Policies\InventoryItemPolicy;
use App\Models\InventoryItem;
use App\Policies\InventoryUnitPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('path.public', function() {
        return base_path('../public_html'); 
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        InventoryUnit::observe(InventoryUnitObserver::class);
    }
}
