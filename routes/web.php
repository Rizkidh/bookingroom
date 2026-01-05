<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Models\InventoryItem; // Pastikan Model InventoryItem sudah diimpor
use App\Models\InventoryUnit;
use App\Http\Controllers\InventoryUnitController;

// 1. Rute Publik (Tambahkan kembali jika hilang, atau asumsikan ada di luar)
// Misalnya:


// 2. Rute Terotentikasi dan Terverifikasi
Route::middleware(['auth', 'verified'])->group(function () {

    // A. Rute Dashboard (TANPA middleware tambahan)
    Route::get('/', function () {
        // 1. Ambil data summary (untuk 3 kotak di atas) - berdasarkan unit
        $totalUnits = InventoryUnit::count();
        $availableUnits = InventoryUnit::where('condition_status', 'available')->count();
        $damagedUnits = InventoryUnit::where('condition_status', 'damaged')->count();
        $inUseUnits = InventoryUnit::where('condition_status', 'in_use')->count();
        $maintenanceUnits = InventoryUnit::where('condition_status', 'maintenance')->count();

        // 2. Ambil data detail (SEMUA unit inventaris dengan relasi item)
        $units = InventoryUnit::with('item')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Filter berdasarkan kondisi jika ada query parameter
        if (request('condition') === 'available') {
            $units = $units->filter(fn($unit) => $unit->condition_status === 'available');
        } elseif (request('condition') === 'damaged') {
            $units = $units->filter(fn($unit) => $unit->condition_status === 'damaged');
        } elseif (request('condition') === 'in_use') {
            $units = $units->filter(fn($unit) => $unit->condition_status === 'in_use');
        } elseif (request('condition') === 'maintenance') {
            $units = $units->filter(fn($unit) => $unit->condition_status === 'maintenance');
        }

        $data = [
            'total' => $totalUnits,
            'available' => $availableUnits,
            'damaged' => $damagedUnits,
            'in_use' => $inUseUnits,
            'maintenance' => $maintenanceUnits,
            'units' => $units,
            'totalItemTypes' => InventoryItem::count(), // Untuk card jenis barang
        ];

        return view('dashboard', $data);
    })->name('dashboard');

    // Rute CRUD Inventaris Baru
    Route::resource('inventories', InventoryController::class)->except(['destroy']);
    Route::delete('/inventories/{inventory}', [InventoryController::class, 'destroy'])->name('inventories.destroy');

    // --- RUTE SCAN BARCODE ---
    Route::get('/scan', [InventoryUnitController::class, 'scanPage'])->name('units.scan');
    Route::post('/scan/process', [InventoryUnitController::class, 'processScan'])->name('units.process-scan');

    // --- RUTE INVENTARIS UNIT SATUAN (NESTED RESOURCE) ---
    Route::resource('inventories.units', InventoryUnitController::class)->except(['index']);

    // --- RUTE ACTIVITY LOG (Audit Trail) ---
    Route::get('/activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
    Route::get('/activity-logs/model/{modelType}/{modelId}', [ActivityLogController::class, 'getModelLogs'])->name('activity-logs.model-logs');
    Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show']);

    // B. Rute Profil (Tambahkan semua rute profil di sini)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// 3. Rute Otentikasi Lainnya (Login, Register, Logout)
require __DIR__ . '/auth.php';
