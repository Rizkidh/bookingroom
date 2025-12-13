<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Penting untuk validasi

class InventoryController extends Controller
{
    /**
     * Menampilkan daftar semua item inventaris (Halaman utama CRUD).
     */
    public function index()
    {
        // Ambil semua item inventaris, diurutkan berdasarkan nama
        $inventoryItems = InventoryItem::orderBy('name', 'asc')->get();

        // Mengembalikan view index, mengirimkan data list inventaris
        return view('inventories.index', compact('inventoryItems'));
    }

    /**
     * Menampilkan form untuk membuat item inventaris baru.
     */
    public function create()
    {
        // Mengembalikan view form create (tambah baru)
        return view('inventories.create');
    }

    /**
     * Menyimpan data item inventaris baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Logika Validasi Data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:inventory_items,name', // Nama harus unik
            'total_stock' => 'required|integer|min:0',
            'available_stock' => 'required|integer|min:0',
            'damaged_stock' => 'required|integer|min:0',
            // Tambahkan validasi kustom: available_stock + damaged_stock TIDAK BOLEH melebihi total_stock
            // Meskipun logikanya harus sama dengan total_stock, kita buat validasi dasar dulu
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        // 2. Logika Penyimpanan Data
        InventoryItem::create([
            'name' => $request->name,
            'total_stock' => $request->total_stock,
            'available_stock' => $request->available_stock,
            'damaged_stock' => $request->damaged_stock,
        ]);

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('inventories.index')
                         ->with('success', 'Item inventaris baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit item inventaris tertentu.
     * (Route Model Binding: Laravel otomatis menemukan item berdasarkan ID)
     */
    public function edit(InventoryItem $inventory)
    {
        // Mengembalikan view edit, mengirimkan item yang akan diedit
        return view('inventories.edit', compact('inventory'));
    }

    /**
     * Memperbarui item inventaris tertentu di database.
     */
    public function update(Request $request, InventoryItem $inventory)
    {
        // 1. Logika Validasi Data
        $validator = Validator::make($request->all(), [
            // Nama harus unik, kecuali jika nama tersebut adalah nama item yang sedang diedit
            'name' => 'required|string|max:255|unique:inventory_items,name,' . $inventory->id, 
            'total_stock' => 'required|integer|min:0',
            'available_stock' => 'required|integer|min:0',
            'damaged_stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        // 2. Logika Pembaruan Data
        $inventory->update([
            'name' => $request->name,
            'total_stock' => $request->total_stock,
            'available_stock' => $request->available_stock,
            'damaged_stock' => $request->damaged_stock,
        ]);

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('inventories.index')
                         ->with('success', 'Item inventaris berhasil diperbarui.');
    }

    /**
     * Menghapus item inventaris tertentu dari database.
     */
    public function destroy(InventoryItem $inventory)
    {
        // Logika Penghapusan Data
        $inventory->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('inventories.index')
                         ->with('success', 'Item inventaris berhasil dihapus.');
    }
}