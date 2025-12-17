<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Cek Mode Maintenance
// Pastikan path ke folder 'bookingroom' sudah benar
if (file_exists($maintenance = __DIR__.'/../bookingroom/storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. Register Autoloader
// Pastikan path ke folder 'bookingroom' sudah benar
require __DIR__.'/../bookingroom/vendor/autoload.php';

// 3. Bootstrap Laravel (Membuat variabel $app)
// Pastikan path ke folder 'bookingroom' sudah benar
$app = require_once __DIR__.'/../bookingroom/bootstrap/app.php';

// --- BAGIAN PENTING (HANYA BOLEH DI SINI) ---
// Memberitahu Laravel bahwa folder ini (public_html) adalah public path-nya
// Kode ini harus diletakkan SETELAH $app dibuat, tapi SEBELUM $kernel dijalankan.
$app->usePublicPath(__DIR__);
// --------------------------------------------

// 4. Jalankan Aplikasi
$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);