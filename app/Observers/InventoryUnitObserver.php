<?php

namespace App\Observers;

use App\Models\InventoryUnit;
use App\Models\InventoryItem;

class InventoryUnitObserver
{
    /**
     * Handle the InventoryUnit "created" event.
     */
    public function created(InventoryUnit $inventoryUnit): void
    {
        // Panggil method untuk memperbarui stok item induk
        $this->updateItemStock($inventoryUnit->inventory_item_id);
    }

    /**
     * Handle the InventoryUnit "updated" event. (Opsional, jika Anda punya form edit unit)
     */
    public function updated(InventoryUnit $inventoryUnit): void
    {
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