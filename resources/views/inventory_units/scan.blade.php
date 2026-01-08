<x-app-layout>
    <x-breadcrumbs :items="['Scan QR' => route('units.scan')]" />

    <div class="flex-1 flex flex-col min-h-0 bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <div class="section-header p-2 sm:p-3 flex-shrink-0">
            <h2 class="text-xs font-bold text-white uppercase tracking-wider">Scan Barcode / QR Code Unit</h2>
        </div>

        <div class="flex-1 p-3 sm:p-5 overflow-y-auto">
            <div class="h-full grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-8 align-items-start">
                
                <!-- Left Column: Camera Scanner -->
                <div class="flex flex-col gap-3 sm:gap-4 h-full">
                    <div class="bg-gray-900 rounded-lg sm:rounded-xl overflow-hidden shadow-lg border border-gray-800 relative flex-1 min-h-[250px] sm:min-h-[300px] flex items-center justify-center">
                        <div id="reader" class="w-full h-full"></div>
                        
                        <!-- Polder Placeholder Animation (visible when not scanning) -->
                        <div id="scanPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center text-gray-500">
                            <svg class="w-12 h-12 sm:w-16 sm:h-16 opacity-20 mb-2 sm:mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 16h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            <span class="text-[10px] sm:text-xs font-mono uppercase tracking-widest opacity-40">Kamera Nonaktif</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 sm:gap-3">
                        <button type="button" id="startScanBtn" class="btn-primary py-2 sm:py-3 justify-center shadow-lg">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="hidden sm:inline">Mulai Scan</span>
                            <span class="sm:hidden">Mulai</span>
                        </button>
                        <button type="button" id="stopScanBtn" class="btn-danger py-2 sm:py-3 justify-center shadow-lg hidden">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                            Berhenti
                        </button>
                        <button type="button" id="switchCameraBtn" class="btn-secondary py-2 sm:py-3 justify-center shadow-lg hidden col-span-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Balik Kamera
                        </button>
                    </div>
                </div>

                <!-- Right Column: Manual Input & Info -->
                <div class="flex flex-col gap-4 sm:gap-6 h-full justify-center">
                    
                    <div class="bg-blue-50/50 border border-blue-100 rounded-lg sm:rounded-xl p-4 sm:p-6">
                        <h3 class="text-xs sm:text-sm font-bold text-blue-800 uppercase mb-3 sm:mb-4 flex items-center gap-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Input Manual
                        </h3>

                        <form action="{{ route('units.process-scan') }}" method="POST" class="flex flex-col gap-2 sm:gap-3">
                            @csrf
                            <div>
                                <label class="text-[10px] uppercase font-bold text-gray-400 mb-1 block">ID Unit / Serial Number</label>
                                <input type="text"
                                       name="barcode"
                                       id="manualBarcode"
                                       class="form-input w-full text-base sm:text-lg font-mono font-bold tracking-wider text-center border-2 focus:border-blue-500 py-2 sm:py-3 rounded-lg shadow-sm"
                                       placeholder="SCAN / KETIK..."
                                       autofocus>
                            </div>
                            <button type="submit" class="btn-success w-full py-2 sm:py-3 justify-center text-sm shadow-md">
                                Cari Unit
                            </button>
                        </form>
                        @error('barcode')
                            <p class="text-red-500 text-xs mt-2 text-center font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg sm:rounded-xl p-3 sm:p-5">
                        <h3 class="text-[10px] sm:text-xs font-bold text-gray-500 uppercase mb-2 sm:mb-3">Panduan Singkat</h3>
                        <ul class="text-[10px] sm:text-xs text-gray-600 space-y-1 sm:space-y-2 list-disc pl-4">
                            <li>Pastikan ruangan memiliki pencahayaan cukup.</li>
                            <li>Pegang perangkat dengan stabil saat scanning.</li>
                            <li>Jika kamera tidak muncul, periksa izin browser Anda.</li>
                            <li>Gunakan input manual jika QR Code rusak atau tidak terbaca.</li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let html5QrCode;
        let isScanning = false;
        const reader = document.getElementById('reader');
        const placeholder = document.getElementById('scanPlaceholder');
        const startBtn = document.getElementById('startScanBtn');
        const stopBtn = document.getElementById('stopScanBtn');
        const switchCameraBtn = document.getElementById('switchCameraBtn');
        const manualInput = document.getElementById('manualBarcode');

        startBtn.addEventListener('click', startScanning);
        stopBtn.addEventListener('click', stopScanning);
        switchCameraBtn.addEventListener('click', switchCamera);

        // Auto-focus manual input on load
        manualInput.focus();

        async function startScanning() {
            if (typeof Html5Qrcode === 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memuat Scanner',
                    text: 'Library QR Code tidak dapat dimuat. Periksa koneksi internet Anda.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            try {
                placeholder.style.display = 'none';
                startBtn.classList.add('hidden');
                stopBtn.classList.remove('hidden');
                switchCameraBtn.classList.remove('hidden');
                
                // Show the reader container
                reader.classList.remove('hidden');

                // If instance exists but logic got messed up, clear it
                if (html5QrCode) {
                    try {
                        await html5QrCode.clear();
                    } catch (e) {
                        console.warn('Clearing existing instance failed', e);
                    }
                }

                html5QrCode = new Html5Qrcode("reader");
                isScanning = true;

                const config = { 
                    fps: 10, 
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                };
                
                // Prefer back camera
                const cameraConfig = { facingMode: "environment" };

                // Get cameras first to ensure we have permission and devices
                const cameras = await Html5Qrcode.getCameras().catch(err => {
                    throw new Error("Akses kamera ditolak atau tidak ada kamera terdeteksi.");
                });

                if (cameras && cameras.length) {
                    await html5QrCode.start(
                        cameraConfig,
                        config,
                        onScanSuccess,
                        onScanError
                    );
                } else {
                    throw new Error("Tidak ada kamera yang ditemukan pada perangkat ini.");
                }

            } catch (err) {
                console.error("Error starting scanner", err);
                
                let errorMessage = "Gagal mengakses kamera.";
                if (err.name === 'NotAllowedError') {
                    errorMessage = "Izin kamera ditolak. Mohon izinkan akses kamera di browser Anda.";
                } else if (err.message) {
                    errorMessage = err.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Kamera Bermasalah',
                    text: errorMessage,
                    confirmButtonText: 'Tutup'
                });
                stopScanning();
            }
        }

        async function stopScanning() {
            if (html5QrCode && isScanning) {
                try {
                    await html5QrCode.stop();
                    html5QrCode.clear(); // Clear the canvas
                } catch (err) {
                    console.error("Failed to stop", err);
                }
                
                placeholder.style.display = 'flex';
                startBtn.classList.remove('hidden');
                stopBtn.classList.add('hidden');
                switchCameraBtn.classList.add('hidden');
                isScanning = false;
                html5QrCode = null;
            }
        }

        async function switchCamera() {
            if (html5QrCode && isScanning) {
                try {
                    await html5QrCode.stop();
                    
                    const currentFacingMode = switchCameraBtn.dataset.facingMode || 'environment';
                    const newFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
                    
                    switchCameraBtn.dataset.facingMode = newFacingMode;

                    const config = { 
                        fps: 10, 
                        qrbox: { width: 250, height: 250 },
                        aspectRatio: 1.0
                    };

                    await html5QrCode.start(
                        { facingMode: newFacingMode },
                        config,
                        onScanSuccess,
                        onScanError
                    );
                } catch (err) {
                    console.error("Failed to switch camera", err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Ganti Kamera',
                        text: 'Terjadi kesalahan saat mengganti kamera.',
                    });
                }
            }
        }

        function onScanSuccess(decodedText) {
            stopScanning();
            manualInput.value = decodedText;
            
            // Visual feedback
            manualInput.classList.add('animate-pulse', 'border-blue-500', 'bg-blue-50');
            
            // Play sound (beep)
            const audio = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-positive-notification-951.mp3');
            audio.play().catch(e => console.log('Audio error', e));

            Swal.fire({
                icon: 'success',
                title: 'QR Ditemukan!',
                text: 'ID: ' + decodedText,
                timer: 1500,
                showConfirmButton: false
            });

            setTimeout(() => {
                manualInput.closest('form').submit();
            }, 1000);
        }

        function onScanError(errorMessage) {
            // ignore errors for better UX (scanning in progress)
        }

        // Initialize state
        switchCameraBtn.dataset.facingMode = 'environment';
    </script>
</x-app-layout>
