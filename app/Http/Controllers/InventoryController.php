<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; // Wajib: Import Storage untuk manipulasi file

class InventoryController extends Controller
{
    /**
     * Menampilkan daftar semua item inventaris (Halaman utama CRUD).
     */
    public function index()
    {
        $inventoryItems = InventoryItem::orderBy('name', 'asc')->get();
        return view('inventories.index', compact('inventoryItems'));
    }

    /**
     * Menampilkan form untuk membuat item inventaris baru.
     */
    public function create()
    {
        return view('inventories.create');
    }

    /**
     * Menyimpan data item inventaris baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Logika Validasi Data (Termasuk Foto)
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:inventory_items,name',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi Foto
            'total_stock' => 'required|integer|min:0',
            'available_stock' => 'required|integer|min:0',
            'damaged_stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $path = null;

        // 2. Logika Upload Foto
        if ($request->hasFile('photo')) {
            // Simpan foto di storage/app/public/inventory_photos
            $path = $request->file('photo')->store('inventory_photos', 'public');
        }

        // 3. Logika Penyimpanan Data
        InventoryItem::create([
            'name' => $request->name,
            'photo' => $path, // Simpan path foto
            'total_stock' => $request->total_stock,
            'available_stock' => $request->available_stock,
            'damaged_stock' => $request->damaged_stock,
        ]);

        // 4. Redirect
        return redirect()->route('inventories.index')
                         ->with('success', 'Item inventaris baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail inventaris tertentu. (Fungsi Show/Detail)
     */
    public function show(InventoryItem $inventory)
    {
        // Ambil semua unit yang terkait dengan item ini
        $units = $inventory->units()->get(); 
        
        return view('inventories.show', compact('inventory', 'units'));
    }

    /**
     * Menampilkan form untuk mengedit item inventaris tertentu.
     */
    public function edit(InventoryItem $inventory)
    {
        return view('inventories.edit', compact('inventory'));
    }

    /**
     * Memperbarui item inventaris tertentu di database.
     */
    public function update(Request $request, InventoryItem $inventory)
    {
        // 1. Logika Validasi Data (Termasuk Foto)
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('inventory_items')->ignore($inventory->id)], 
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi Foto
            'total_stock' => 'required|integer|min:0',
            'available_stock' => 'required|integer|min:0',
            'damaged_stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['name', 'total_stock', 'available_stock', 'damaged_stock']);
        
        // 2. Logika Update dan Hapus Foto
        if ($request->hasFile('photo')) {
            // Hapus foto lama sebelum upload baru
            if ($inventory->photo) {
                Storage::disk('public')->delete($inventory->photo);
            }
            // Upload foto baru
            $data['photo'] = $request->file('photo')->store('inventory_photos', 'public');
        } elseif ($request->input('remove_photo')) {
            // Hapus foto jika checkbox 'remove_photo' dicentang
            if ($inventory->photo) {
                Storage::disk('public')->delete($inventory->photo);
            }
            $data['photo'] = null; // Set path ke null di database
        }

        // 3. Logika Pembaruan Data
        $inventory->update($data);

        // 4. Redirect
        return redirect()->route('inventories.index')
                         ->with('success', 'Item inventaris berhasil diperbarui.');
    }

    /**
     * Menghapus item inventaris tertentu dari database.
     */
    public function destroy(InventoryItem $inventory)
    {
        // 1. Hapus Foto dari Storage sebelum menghapus record
        if ($inventory->photo) {
            Storage::disk('public')->delete($inventory->photo);
        }

        // 2. Logika Penghapusan Data
        $inventory->delete();

        // 3. Redirect
        return redirect()->route('inventories.index')
                         ->with('success', 'Item inventaris berhasil dihapus.');
    }
}