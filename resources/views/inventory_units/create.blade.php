<x-app-layout>
    <x-breadcrumbs :items="['Inventaris' => route('inventories.index'), $inventory->name => route('inventories.show', $inventory->id), 'Tambah Unit' => route('inventories.units.create', $inventory->id)]" />
    <div class="form-container">
        <div class="form-card">
            <h2 class="form-title">Tambah Unit untuk: {{ $inventory->name }}</h2>

            <form action="{{ route('inventories.units.store', $inventory->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="unitForm">
                @csrf

                @if ($errors->any())
                    <div class="error-alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Scan Barcode (HP)</label>

                    <button type="button"
                            id="startScanBtn"
                            class="btn-submit" style="margin-bottom: 0.5rem;">
                        Mulai Scan
                    </button>

                    <button type="button"
                            id="stopScanBtn"
                            class="btn-submit" style="margin-bottom: 0.5rem; display:none;">
                        Hentikan Scan
                    </button>

                    <div id="reader"
                         class="border rounded p-2 mt-2"
                         style="max-width:320px; display:none;"></div>

                    <small class="form-helper" style="display: block; margin-top: 0.5rem;">
                        Klik tombol scan untuk mengaktifkan kamera
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Serial / Barcode <span>*</span></label>
                    <input type="text"
                           name="serial_number"
                           id="serial_number"
                           value="{{ old('serial_number') }}"
                           class="form-input"
                           placeholder="Scan barcode atau ketik manual">
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Unit (Opsional)</label>
                    <input type="file" name="photo" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">Status Kondisi <span>*</span></label>
                    <select name="condition_status" required class="form-input">
                        <option value="">-- Pilih Status --</option>
                        <option value="available">Tersedia</option>
                        <option value="in_use">Sedang Dipakai</option>
                        <option value="maintenance">Perawatan</option>
                        <option value="damaged">Rusak</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Pemegang Saat Ini <span>*</span></label>
                    <select name="current_holder" required class="form-input">
                        <option value="">-- Pilih Pemegang --</option>
                        <option value="angpen">Angpen</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan (Opsional)</label>
                    <textarea name="note" rows="4" placeholder="Contoh: Alasan penambahan unit, keterangan khusus, dll" maxlength="500" class="form-input">{{ old('note') }}</textarea>
                    <p class="form-helper">Maksimal 500 karakter</p>
                </div>

                <div class="form-actions">
                    <button type="submit"
                            class="btn-submit">
                        Simpan Unit
                    </button>

                    <a href="{{ route('inventories.show', $inventory->id) }}"
                       class="btn-cancel">
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </div>

    @php
        $successMessage = session('success');
        $successRoute = route('inventories.show', $inventory->id);
    @endphp

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        /* eslint-disable */
        let html5QrCode;
        const reader = document.getElementById('reader');
        const serialInput = document.getElementById('serial_number');
        const startBtn = document.getElementById('startScanBtn');
        const stopBtn = document.getElementById('stopScanBtn');

        startBtn.addEventListener('click', () => {
            reader.style.display = 'block';
            startBtn.classList.add('hidden');
            stopBtn.classList.remove('hidden');

            html5QrCode = new Html5Qrcode("reader");

            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                (decodedText) => {
                    serialInput.value = decodedText;
                    stopScan();
                }
            );
        });

        stopBtn.addEventListener('click', stopScan);

        function stopScan() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    reader.style.display = 'none';
                    startBtn.classList.remove('hidden');
                    stopBtn.classList.add('hidden');
                });
            }
        }

        document.getElementById('unitForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const conditionStatus = document.querySelector('select[name="condition_status"]').value;
            const currentHolder = document.querySelector('select[name="current_holder"]').value;

            if (!conditionStatus) {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Pilih status kondisi terlebih dahulu',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            if (!currentHolder) {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Pilih pemegang terlebih dahulu',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            Swal.fire({
                title: 'Menambahkan Unit...',
                html: 'Mohon tunggu',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(() => {
                this.submit();
            }, 500);
        });
        /* eslint-enable */
    </script>

    @if ($successMessage)
    <script>
        /* eslint-disable */
        window.addEventListener('load', function() {
            if (Swal.isVisible()) {
                Swal.hideLoading();
                Swal.close();
            }

            Swal.fire({
                title: 'Berhasil!',
                text: '{{ $successMessage }}',
                icon: 'success',
                confirmButtonText: 'Lanjut',
                confirmButtonColor: '#3b82f6',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ $successRoute }}';
                }
            });
        });
        /* eslint-enable */
    </script>
    @endif
</x-app-layout>
