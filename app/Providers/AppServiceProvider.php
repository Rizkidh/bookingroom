<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\InventoryUnit; // Wajib diimport
use App\Observers\InventoryUnitObserver; // Wajib diimport

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
        InventoryUnit::observe(InventoryUnitObserver::class);
    }
}
