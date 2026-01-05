<?php

namespace App\Observers;

use App\Models\InventoryItem;
use App\Services\ActivityLogService;

class InventoryItemObserver
{
    /**
     * Store old values for models before they're updated
     * Using a static array keyed by model id
     */
    private static $oldValues = [];

    /**
     * Handle the InventoryItem "updating" event.
     * Capture original values before update
     */
    public function updating(InventoryItem $inventoryItem): void
    {
        // Store the original attributes before they are changed
        // Use static property to avoid adding to model attributes
        self::$oldValues[$inventoryItem->id] = $inventoryItem->getOriginal();
    }

    /**
     * Handle the InventoryItem "updated" event.
     */
    public function updated(InventoryItem $inventoryItem): void
    {
        $oldValues = self::$oldValues[$inventoryItem->id] ?? $inventoryItem->getOriginal();
        $note = request()->input('note');
        ActivityLogService::logUpdate($inventoryItem, $oldValues, $note);

        // Clean up
        unset(self::$oldValues[$inventoryItem->id]);
    }

    /**
     * Handle the InventoryItem "created" event.
     */
    public function created(InventoryItem $inventoryItem): void
    {
        $note = request()->input('note');
        ActivityLogService::logCreate($inventoryItem, $note);
    }

    /**
     * Handle the InventoryItem "deleting" event.
     */
    public function deleting(InventoryItem $inventoryItem): void
    {
        // Can add additional logic before deletion
    }

    /**
     * Handle the InventoryItem "deleted" event.
     */
    public function deleted(InventoryItem $inventoryItem): void
    {
        $note = request()->input('note') ?? 'Item deleted';
        ActivityLogService::logDelete($inventoryItem, $note);
    }
}
