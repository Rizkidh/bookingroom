<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryUnit;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InventoryUnitController extends Controller
{
    use AuthorizesRequests;

    public function scanPage()
    {
        return view('inventory_units.scan');
    }

    public function processScan(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $barcode = trim($request->input('barcode'));

        $unit = InventoryUnit::with('item')->find($barcode);

        if (!$unit) {
            \Illuminate\Support\Facades\Log::warning('Unit not found on scan', ['barcode' => $barcode]);
            return redirect()
                ->route('units.scan')
                ->with('error', 'Unit dengan ID "' . $barcode . '" tidak ditemukan');
        }

        try {
            $this->authorize('view', $unit);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()
                ->route('units.scan')
                ->with('error', 'Anda tidak memiliki akses untuk melihat unit ini');
        }

        $inventory = $unit->item;

        if (!$inventory) {
            \Illuminate\Support\Facades\Log::error('Unit has no related inventory item', [
                'unit_id' => $unit->id,
                'inventory_item_id' => $unit->inventory_item_id,
            ]);
            return redirect()
                ->route('units.scan')
                ->with('error', 'Data integritas rusak: Unit #' . $unit->id . ' tidak terhubung ke inventaris apapun');
        }

        \Illuminate\Support\Facades\Log::info('Unit scanned successfully', [
            'unit_id' => $unit->id,
            'inventory_id' => $inventory->id,
        ]);

        return redirect()
            ->route('inventories.units.show', [$inventory->id, $unit->id])
            ->with('success', 'Unit ditemukan!');
    }

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
            'condition_status' => 'required|in:available,in_use,maintenance,damaged',
            'current_holder'   => 'required|string|max:255',
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
            ->with('success', 'Unit berhasil ditambahkan! QR code sudah di-generate otomatis.');
    }

    public function update(Request $request, InventoryItem $inventory, string $unitId)
    {

        $unit = $inventory->units()->findOrFail($unitId);
        $this->authorize('update', $unit);

        $data = $request->validate([
            'serial_number'    => 'nullable|string|max:255|unique:inventory_units,serial_number,' . $unitId . ',id',
            'photo'            => 'nullable|image|max:2048',
            'condition_status' => 'required|in:available,in_use,maintenance,damaged',
            'current_holder'   => 'required|string|max:255',
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
            ->with('success', 'Unit berhasil diperbarui!');
    }

    public function destroy(InventoryItem $inventory, string $unitId)
    {
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
        $unit = $inventory->units()->findOrFail($unitId);

        $this->authorize('view', $unit);

        return view('inventory_units.show', compact('inventory', 'unit'));
    }

    public function edit(InventoryItem $inventory, string $unitId)
    {
        $unit = $inventory->units()->findOrFail($unitId);

        $this->authorize('update', $unit);

        $conditionStatuses = ['available', 'in_use', 'maintenance', 'damaged', 'lost'];

        return view('inventory_units.edit', compact('inventory', 'unit', 'conditionStatuses'));
    }
}
