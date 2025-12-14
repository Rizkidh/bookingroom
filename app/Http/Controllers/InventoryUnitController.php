<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InventoryUnitController extends Controller
{
    use AuthorizesRequests;

    /**
     * Form tambah unit
     */
    public function create(InventoryItem $inventory)
    {
        $this->authorize('create', InventoryUnit::class);

        $conditionStatuses = ['available', 'in_use', 'damaged', 'maintenance', 'retired'];

        return view('inventory_units.create', compact('inventory', 'conditionStatuses'));
    }

    /**
     * Simpan unit
     */
    public function store(Request $request, InventoryItem $inventory)
    {
        $this->authorize('create', InventoryUnit::class);

        $validator = Validator::make($request->all(), [
            'serial_number' => 'nullable|string|max:255|unique:inventory_units,serial_number',
            'photo' => 'nullable|image|max:2048',
            'condition_status' => 'required',
            'current_holder' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $path = $request->file('photo')
            ? $request->file('photo')->store('unit_photos', 'public')
            : null;

        $inventory->units()->create([
            'serial_number' => $request->serial_number,
            'photo' => $path,
            'condition_status' => $request->condition_status,
            'current_holder' => $request->current_holder,
        ]);

        return redirect()
            ->route('inventories.show', $inventory)
            ->with('success', 'Unit berhasil ditambahkan');
    }

    /**
     * Detail unit
     */
    public function show(InventoryItem $inventory, InventoryUnit $unit)
    {
        $this->authorize('view', $unit);

        abort_if($unit->inventory_item_id !== $inventory->id, 404);

        return view('inventory_units.show', compact('inventory', 'unit'));
    }

    /**
     * Form edit unit
     */
    public function edit(InventoryItem $inventory, InventoryUnit $unit)
    {
        $this->authorize('update', $unit);

        abort_if($unit->inventory_item_id !== $inventory->id, 404);

        $conditionStatuses = ['available', 'in_use', 'damaged', 'maintenance', 'retired'];

        return view('inventory_units.edit', compact('inventory', 'unit', 'conditionStatuses'));
    }

    /**
     * Update unit
     */
    public function update(Request $request, InventoryItem $inventory, InventoryUnit $unit)
    {
        $this->authorize('update', $unit);

        abort_if($unit->inventory_item_id !== $inventory->id, 404);

        $data = $request->validate([
            'serial_number' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'condition_status' => 'required',
            'current_holder' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('photo')) {
            if ($unit->photo) {
                Storage::disk('public')->delete($unit->photo);
            }
            $data['photo'] = $request->file('photo')->store('unit_photos', 'public');
        }

        $unit->update($data);

        return redirect()
            ->route('inventories.show', $inventory)
            ->with('success', 'Unit berhasil diperbarui');
    }

    /**
     * ❗ Delete unit (ADMIN ONLY)
     */
    public function destroy(InventoryItem $inventory, InventoryUnit $unit)
    {
        $this->authorize('delete', $unit);

        abort_if($unit->inventory_item_id !== $inventory->id, 404);

        if ($unit->photo) {
            Storage::disk('public')->delete($unit->photo);
        }

        $unit->delete();

        return redirect()
            ->route('inventories.show', $inventory)
            ->with('success', 'Unit berhasil dihapus');
    }
}
