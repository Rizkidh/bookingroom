<?php

namespace App\Observers;

use App\Models\InventoryUnit;
use App\Models\InventoryItem;
use App\Services\ActivityLogService;

class InventoryUnitObserver
{
    private static $oldValues = [];

    public function created(InventoryUnit $inventoryUnit): void
    {
        $note = request()->input('note');
        ActivityLogService::logCreate($inventoryUnit, $note);

        $this->updateItemStock($inventoryUnit->inventory_item_id);
    }

    public function updating(InventoryUnit $inventoryUnit): void
    {
        self::$oldValues[$inventoryUnit->id] = $inventoryUnit->getOriginal();
    }

    public function updated(InventoryUnit $inventoryUnit): void
    {
        $oldValues = self::$oldValues[$inventoryUnit->id] ?? $inventoryUnit->getOriginal();
        $note = request()->input('note');
        ActivityLogService::logUpdate($inventoryUnit, $oldValues, $note);

        unset(self::$oldValues[$inventoryUnit->id]);

        if ($inventoryUnit->isDirty('condition_status')) {
             $this->updateItemStock($inventoryUnit->inventory_item_id);
        }
    }

    public function deleted(InventoryUnit $inventoryUnit): void
    {
        $note = request()->input('note') ?? 'Unit deleted';
        ActivityLogService::logDelete($inventoryUnit, $note);

        $this->updateItemStock($inventoryUnit->inventory_item_id);
    }

    protected function updateItemStock(int $itemId): void
    {
        $item = InventoryItem::find($itemId);

        if (!$item) {
            return;
        }

        $totalUnits = $item->units()->count();

        $availableStock = $item->units()
                               ->whereIn('condition_status', ['available', 'in_use'])
                               ->count();

        $damagedStock = $item->units()
                             ->whereIn('condition_status', ['damaged', 'maintenance'])
                             ->count();

        $item->update([
            'total_stock'     => $totalUnits,
            'available_stock' => $availableStock,
            'damaged_stock'   => $damagedStock,
        ]);
    }
}
