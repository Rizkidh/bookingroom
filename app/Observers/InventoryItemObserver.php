<?php

namespace App\Observers;

use App\Models\InventoryItem;
use App\Services\ActivityLogService;

class InventoryItemObserver
{
    private static $oldValues = [];

    public function updating(InventoryItem $inventoryItem): void
    {
        self::$oldValues[$inventoryItem->id] = $inventoryItem->getOriginal();
    }

    public function updated(InventoryItem $inventoryItem): void
    {
        $oldValues = self::$oldValues[$inventoryItem->id] ?? $inventoryItem->getOriginal();
        $note = request()->input('note');
        ActivityLogService::logUpdate($inventoryItem, $oldValues, $note);

        unset(self::$oldValues[$inventoryItem->id]);
    }

    public function created(InventoryItem $inventoryItem): void
    {
        $note = request()->input('note');
        ActivityLogService::logCreate($inventoryItem, $note);
    }

    public function deleting(InventoryItem $inventoryItem): void
    {
    }

    public function deleted(InventoryItem $inventoryItem): void
    {
        $note = request()->input('note') ?? 'Item deleted';
        ActivityLogService::logDelete($inventoryItem, $note);
    }
}
