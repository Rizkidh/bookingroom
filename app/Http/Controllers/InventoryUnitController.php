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

    public function update(Request $request, InventoryItem $inventory, InventoryUnit $unit)
    {
        $this->authorize('update', $unit);

        abort_if($unit->inventory_item_id !== $inventory->id, 404);

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

    public function destroy(InventoryItem $inventory, InventoryUnit $unit)
    {
        $this->authorize('delete', $unit);

        abort_if($unit->inventory_item_id !== $inventory->id, 404);

        ImageHelper::delete($unit->photo);
        $unit->delete();

        return redirect()
            ->route('inventories.show', $inventory)
            ->with('success', 'Unit berhasil dihapus');
    }

    public function show(InventoryItem $inventory, InventoryUnit $unit)
    {
        $this->authorize('view', $unit);

        abort_if($unit->inventory_item_id !== $inventory->id, 404);

        return view('inventory_units.show', compact('inventory', 'unit'));
    }
}
