<x-app-layout>
    <x-breadcrumbs :items="['Inventaris' => route('inventories.index'), $inventory->name => route('inventories.show', $inventory->id), 'Tambah Unit' => route('inventories.units.create', $inventory->id)]" />

    <div class="flex-1 flex flex-col min-h-0 bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <div class="section-header p-3 flex-shrink-0">
            <h2 class="text-xs font-bold text-white uppercase tracking-wider">Tambah Unit untuk: {{ $inventory->name }}</h2>
        </div>

        <div class="flex-1 overflow-y-auto p-5">
            <form action="{{ route('inventories.units.store', $inventory->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="unitForm"
                  class="h-full flex flex-col">
                @csrf

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-3">
                        <ul class="list-disc pl-4 text-xs text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 flex-1">
                    <!-- Column 1: Scanner & Media (Smaller) -->
                    <div class="lg:col-span-4 flex flex-col gap-4">
                        <!-- Scanner Section -->
                        <div class="bg-blue-50/50 rounded-lg p-3 border border-blue-100">
                            <label class="text-[10px] font-bold text-blue-600 uppercase mb-2 block">Scan Barcode (HP)</label>
                            
                            <div class="flex flex-col gap-2">
                                <button type="button" id="startScanBtn" class="w-full btn-primary text-xs py-2 justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 16h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    Mulai Scan
                                </button>
                                <button type="button" id="stopScanBtn" class="w-full btn-danger text-xs py-2 justify-center hidden">
                                    Hentikan Scan
                                </button>
                            </div>

                            <div id="reader" class="rounded overflow-hidden mt-2 border border-gray-200" style="display:none;"></div>
                            <p class="text-[9px] text-gray-500 mt-2 text-center">Arahkan kamera ke barcode/QR unit</p>
                        </div>

                        <!-- Photo Section -->
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                            <label class="text-[10px] font-bold text-gray-500 uppercase mb-2 block">Foto Unit (Opsional)</label>
                            <input type="file" name="photo" class="block w-full text-[10px] text-gray-500
                                file:mr-2 file:py-1.5 file:px-3
                                file:rounded-md file:border-0
                                file:text-[10px] file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100">
                        </div>
                    </div>

                    <!-- Column 2: Details & Inputs (Larger) -->
                    <div class="lg:col-span-8 flex flex-col gap-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Serial Number -->
                            <div class="md:col-span-2">
                                <label class="form-label text-[10px] uppercase mb-1 block">Nomor Serial / Barcode <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       name="serial_number" 
                                       id="serial_number"
                                       value="{{ old('serial_number') }}" 
                                       class="form-input w-full font-mono text-sm font-bold text-blue-600 placeholder:font-sans placeholder:font-normal placeholder:text-gray-400"
                                       placeholder="Scan barcode atau ketik manual..." autofocus>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="form-label text-[10px] uppercase mb-1 block">Status Kondisi <span class="text-red-500">*</span></label>
                                <select name="condition_status" required class="form-input w-full text-sm">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="available" selected>Tersedia</option>
                                    <option value="in_use">Sedang Dipakai</option>
                                    <option value="maintenance">Perawatan</option>
                                    <option value="damaged">Rusak</option>
                                </select>
                            </div>

                            <!-- Holder -->
                            <div>
                                <label class="form-label text-[10px] uppercase mb-1 block">Pemegang Saat Ini <span class="text-red-500">*</span></label>
                                <select name="current_holder" required class="form-input w-full text-sm">
                                    <option value="">-- Pilih Pemegang --</option>
                                    <option value="Gudang" selected>Gudang</option>
                                    <option value="Angpen">Angpen</option>
                                </select>
                            </div>
                        </div>

                        <!-- Note -->
                        <div class="flex-1 min-h-[100px]">
                            <label class="form-label text-[10px] uppercase mb-1 block">Catatan Tambahan</label>
                            <textarea name="note" 
                                      class="form-input w-full h-full min-h-[100px] resize-none text-sm" 
                                      placeholder="Keterangan kondisi fisik, kelengkapan, dll.">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-auto pt-10 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('inventories.show', $inventory->id) }}" 
                       class="btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-success px-8">
                        Simpan Unit
                    </button>
                </div>
            </form>
        </div>
    </div>

    @php
        $successMessage = session('success');
        $successRoute = route('inventories.show', $inventory->id);
    @endphp

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        /* eslint-disable */
        let html5QrCode;
        let isScanning = false;
        const reader = document.getElementById('reader');
        const serialInput = document.getElementById('serial_number');
        const startBtn = document.getElementById('startScanBtn');
        const stopBtn = document.getElementById('stopScanBtn');

        startBtn.addEventListener('click', async () => {
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
                reader.style.display = 'block';
                startBtn.classList.add('hidden');
                stopBtn.classList.remove('hidden');

                // Clear any existing instance
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
                    qrbox: { width: 200, height: 200 },
                    aspectRatio: 1.0
                };

                // Get cameras first to ensure we have permission
                const cameras = await Html5Qrcode.getCameras().catch(err => {
                    throw new Error("Akses kamera ditolak atau tidak ada kamera terdeteksi.");
                });

                if (cameras && cameras.length) {
                    await html5QrCode.start(
                        { facingMode: "environment" },
                        config,
                        (decodedText) => {
                            serialInput.value = decodedText;
                            
                            // Play sound
                            const audio = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-positive-notification-951.mp3');
                            audio.play().catch(e => console.log('Audio error', e));
                            
                            stopScan();
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Scan Berhasil!',
                                text: 'Serial: ' + decodedText,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        },
                        (errorMessage) => {
                            // Ignore scan errors for better UX
                        }
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
                
                stopScan();
            }
        });

        stopBtn.addEventListener('click', stopScan);

        async function stopScan() {
            if (html5QrCode && isScanning) {
                try {
                    await html5QrCode.stop();
                    html5QrCode.clear();
                } catch (err) {
                    console.error("Failed to stop", err);
                }
                
                reader.style.display = 'none';
                startBtn.classList.remove('hidden');
                stopBtn.classList.add('hidden');
                isScanning = false;
                html5QrCode = null;
            }
        }

        document.getElementById('unitForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const conditionStatus = document.querySelector('select[name="condition_status"]').value;
            const currentHolder = document.querySelector('select[name="current_holder"]').value;

            if (!conditionStatus || !currentHolder) {
                Swal.fire({
                    title: 'Data Belum Lengkap',
                    text: 'Mohon lengkapi Status dan Pemegang Unit.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#10b981'
                });
                return;
            }

            Swal.fire({
                title: 'Menyimpan...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            this.submit();
        });
        /* eslint-enable */
    </script>
</x-app-layout>
