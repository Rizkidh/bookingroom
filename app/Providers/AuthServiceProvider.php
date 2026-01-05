<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\InventoryItem;
use App\Models\InventoryUnit;
use App\Models\ActivityLog;
use App\Policies\InventoryItemPolicy;
use App\Policies\InventoryUnitPolicy;
use App\Policies\ActivityLogPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        InventoryItem::class => InventoryItemPolicy::class,
        InventoryUnit::class => InventoryUnitPolicy::class,
        ActivityLog::class => ActivityLogPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
