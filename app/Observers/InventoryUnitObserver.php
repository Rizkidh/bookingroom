<?php

namespace App\Observers;

use App\Models\InventoryUnit;
use App\Models\InventoryItem;
use App\Services\ActivityLogService;

class InventoryUnitObserver
{
    /**
     * Store old values for models before they're updated
     * Using a static array keyed by model id
     */
    private static $oldValues = [];

    /**
     * Handle the InventoryUnit "created" event.
     */
    public function created(InventoryUnit $inventoryUnit): void
    {
        // Log the creation
        $note = request()->input('note');
        ActivityLogService::logCreate($inventoryUnit, $note);

        // Update parent item stock
        $this->updateItemStock($inventoryUnit->inventory_item_id);
    }

    /**
     * Handle the InventoryUnit "updating" event. (Opsional, jika Anda punya form edit unit)
     */
    public function updating(InventoryUnit $inventoryUnit): void
    {
        // Store the original attributes before they are changed
        // Use static property to avoid adding to model attributes
        self::$oldValues[$inventoryUnit->id] = $inventoryUnit->getOriginal();
    }

    /**
     * Handle the InventoryUnit "updated" event. (Opsional, jika Anda punya form edit unit)
     */
    public function updated(InventoryUnit $inventoryUnit): void
    {
        // Log the update
        $oldValues = self::$oldValues[$inventoryUnit->id] ?? $inventoryUnit->getOriginal();
        $note = request()->input('note');
        ActivityLogService::logUpdate($inventoryUnit, $oldValues, $note);

        // Clean up
        unset(self::$oldValues[$inventoryUnit->id]);

        // Panggil method untuk memperbarui stok item induk
        // Ini penting jika status 'available' atau 'damaged' diubah.
        if ($inventoryUnit->isDirty('condition_status')) {
             $this->updateItemStock($inventoryUnit->inventory_item_id);
        }
    }

    /**
     * Handle the InventoryUnit "deleted" event.
     */
    public function deleted(InventoryUnit $inventoryUnit): void
    {
        // Log the deletion
        $note = request()->input('note') ?? 'Unit deleted';
        ActivityLogService::logDelete($inventoryUnit, $note);

        // Panggil method untuk memperbarui stok item induk
        $this->updateItemStock($inventoryUnit->inventory_item_id);
    }

    /**
     * Logika utama untuk menghitung ulang stok dari unit.
     */
    protected function updateItemStock(int $itemId): void
    {
        // 1. Temukan item induk
        $item = InventoryItem::find($itemId);

        if (!$item) {
            return;
        }

        // 2. Hitung jumlah total unit
        $totalUnits = $item->units()->count();

        // 3. Hitung unit yang tersedia (misalnya status: available, in_use)
        $availableStock = $item->units()
                               ->whereIn('condition_status', ['available', 'in_use'])
                               ->count();

        // 4. Hitung unit yang rusak/dalam perawatan (misalnya status: damaged, maintenance)
        $damagedStock = $item->units()
                             ->whereIn('condition_status', ['damaged', 'maintenance'])
                             ->count();

        // 5. Update item induk dengan nilai yang dihitung
        $item->update([
            'total_stock'     => $totalUnits,
            'available_stock' => $availableStock,
            'damaged_stock'   => $damagedStock,
        ]);
    }
}
