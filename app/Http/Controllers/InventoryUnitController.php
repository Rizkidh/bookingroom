<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class InventoryUnitController extends Controller
{
    /**
     * Menampilkan form untuk menambah Unit baru.
     */
    public function create(InventoryItem $inventory)
    {
        $conditionStatuses = ['available', 'in_use', 'damaged', 'maintenance', 'retired'];
        
        return view('inventory_units.create', compact('inventory', 'conditionStatuses'));
    }

    /**
     * Menyimpan Unit baru beserta fotonya.
     */
    public function store(Request $request, InventoryItem $inventory)
    {
        $validator = Validator::make($request->all(), [
            'serial_number' => 'nullable|string|max:255|unique:inventory_units,serial_number',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'condition_status' => 'required|in:available,in_use,damaged,maintenance,retired',
            'current_holder' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('unit_photos', 'public');
        }

        $inventory->units()->create([
            'serial_number' => $request->serial_number,
            'photo' => $path,
            'condition_status' => $request->condition_status,
            'current_holder' => $request->current_holder,
        ]);

        // Catatan: InventoryUnitObserver@created otomatis memperbarui stok item induk.

        return redirect()->route('inventories.show', $inventory->id)
                         ->with('success', 'Unit satuan baru berhasil ditambahkan.');
    }
    
    /**
     * Menampilkan detail unit inventaris tertentu.
     */
    public function show(InventoryItem $inventory, InventoryUnit $unit)
    {
        // Pengamanan: Pastikan unit yang ditampilkan milik item induk yang benar
        if ($unit->inventory_item_id !== $inventory->id) {
            abort(404, 'Unit tidak ditemukan pada item ini.');
        }

        return view('inventory_units.show', compact('inventory', 'unit'));
    }

    /**
     * Tampilkan formulir untuk mengedit unit inventaris tertentu.
     */
    public function edit(InventoryItem $inventory, InventoryUnit $unit)
    {
        // Pengamanan: Pastikan unit yang diedit milik item induk yang benar
        if ($unit->inventory_item_id !== $inventory->id) {
            abort(404, 'Unit tidak ditemukan pada item ini.');
        }
        
        $conditionStatuses = ['available', 'in_use', 'damaged', 'maintenance', 'retired'];
        
        return view('inventory_units.edit', compact('inventory', 'unit', 'conditionStatuses'));
    }

    /**
     * Perbarui unit inventaris yang ditentukan di database.
     */
    public function update(Request $request, InventoryItem $inventory, InventoryUnit $unit)
    {
        if ($unit->inventory_item_id !== $inventory->id) {
            return redirect()->back()->with('error', 'Unit tidak valid untuk item ini.');
        }

        $validator = Validator::make($request->all(), [
            'serial_number' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'condition_status' => 'required|in:available,in_use,damaged,maintenance,retired',
            'current_holder' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $data = $request->only(['serial_number', 'condition_status', 'current_holder']);

        // Logika Update Foto
        if ($request->hasFile('photo')) {
            // Hapus foto lama sebelum upload baru
            if ($unit->photo) {
                Storage::disk('public')->delete($unit->photo);
            }
            // Upload foto baru
            $data['photo'] = $request->file('photo')->store('unit_photos', 'public');
        } elseif ($request->input('remove_photo')) {
            // Hapus foto jika checkbox dicentang
            if ($unit->photo) {
                Storage::disk('public')->delete($unit->photo);
            }
            $data['photo'] = null;
        }

        $unit->update($data);
        
        // Catatan: InventoryUnitObserver@updated otomatis memperbarui stok item induk.

        return redirect()->route('inventories.show', $inventory)
                         ->with('success', 'Unit inventaris berhasil diperbarui dan stok dihitung ulang.');
    }

    /**
     * Hapus unit inventaris tertentu dari database.
     */
    public function destroy(InventoryItem $inventory, InventoryUnit $unit)
    {
        if ($unit->inventory_item_id !== $inventory->id) {
            return redirect()->back()->with('error', 'Unit tidak valid untuk item ini.');
        }
        
        // 1. Hapus foto dari storage
        if ($unit->photo) {
            Storage::disk('public')->delete($unit->photo);
        }

        // 2. Hapus unit (Observer@deleted akan otomatis update stok)
        $unit->delete();

        return redirect()->route('inventories.show', $inventory)
                         ->with('success', 'Unit inventaris berhasil dihapus dan stok dihitung ulang.');
    }
}