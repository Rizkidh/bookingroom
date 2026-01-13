<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Models\InventoryItem; 
use App\Http\Controllers\InventoryUnitController;

// 2. Rute Terotentikasi dan Terverifikasi
Route::middleware(['auth', 'verified'])->group(function () {

    // A. Rute Dashboard (TANPA middleware tambahan)
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Rute CRUD Inventaris Baru
    Route::get('/inventories/export', [InventoryController::class, 'export'])->name('inventories.export');
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
