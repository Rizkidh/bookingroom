<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\InventoryItem;
use App\Models\InventoryUnit;
use App\Policies\InventoryItemPolicy;
use App\Policies\InventoryUnitPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        InventoryItem::class => InventoryItemPolicy::class,
        InventoryUnit::class => InventoryUnitPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
