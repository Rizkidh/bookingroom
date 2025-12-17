<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryUnit;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InventoryUnitController extends Controller
{
    use AuthorizesRequests;

    public function create(InventoryItem $inventory)
    {
        $this->authorize('create', InventoryUnit::class);

        return view('inventory_units.create', compact('inventory'));
    }

    public function store(Request $request, InventoryItem $inventory)
    {
        $this->authorize('create', InventoryUnit::class);

        $data = $request->validate([
            'serial_number'    => 'nullable|string|max:255|unique:inventory_units,serial_number',
            'photo'            => 'nullable|image|max:2048',
            'condition_status' => 'required',
            'current_holder'   => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = ImageHelper::upload(
                $request->file('photo'),
                'unit_photos'
            );
        }

        $inventory->units()->create($data);

        return redirect()
            ->route('inventories.show', $inventory)
            ->with('success', 'Unit berhasil ditambahkan');
    }

    public function update(Request $request, InventoryItem $inventory, string $unitId)
    {

        $unit = $inventory->units()->findOrFail($unitId);
        $this->authorize('update', $unit);

        $data = $request->validate([
            'serial_number'    => 'nullable|string|max:255',
            'photo'            => 'nullable|image|max:2048',
            'condition_status' => 'required',
            'current_holder'   => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('photo')) {
            ImageHelper::delete($unit->photo);

            $data['photo'] = ImageHelper::upload(
                $request->file('photo'),
                'unit_photos'
            );
        }

        $unit->update($data);

        return redirect()
            ->route('inventories.show', $inventory)
            ->with('success', 'Unit berhasil diperbarui');
    }

    public function destroy(InventoryItem $inventory, string $unitId)
    {
        // Cari manual
        $unit = $inventory->units()->findOrFail($unitId);

        $this->authorize('delete', $unit);

        ImageHelper::delete($unit->photo);
        $unit->delete();

        return redirect()
            ->route('inventories.show', $inventory)
            ->with('success', 'Unit berhasil dihapus');
    }

    public function show(InventoryItem $inventory, string $unitId)
    {
        // Cari unit SECARA MANUAL melalui relasi inventory agar foreign key 'inventory_item_id' terbaca
        $unit = $inventory->units()->findOrFail($unitId);

        $this->authorize('view', $unit);

        // Baris ini sebenarnya sudah tidak perlu karena findOrFail di atas sudah memastikan unit milik inventory tersebut
        // abort_if($unit->inventory_item_id !== $inventory->id, 404);

        return view('inventory_units.show', compact('inventory', 'unit'));
    }

    public function edit(InventoryItem $inventory, string $unitId)
    {
        // 1. Cari unit
        $unit = $inventory->units()->findOrFail($unitId);

        // 2. Otorisasi
        $this->authorize('update', $unit);

        // 3. --- TAMBAHAN PENTING ---
        // Definisikan daftar status yang bisa dipilih.
        // Sesuaikan isi array ini dengan kebutuhan aplikasimu (misal: 'available', 'damaged', 'maintenance', dll)
        $conditionStatuses = ['available', 'in_use', 'maintenance', 'damaged', 'lost'];

        // 4. Kirim variabel $conditionStatuses ke view menggunakan compact
        return view('inventory_units.edit', compact('inventory', 'unit', 'conditionStatuses'));
    }
}
