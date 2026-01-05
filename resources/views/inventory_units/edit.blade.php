<x-app-layout>
    <div class="form-container">
        <div class="form-card">
            <h2 class="form-title">Edit Unit: {{ $unit->serial_number ?? 'Unit #' . $unit->id }}</h2>

            <form action="{{ route('inventories.units.update', [$inventory->id, $unit->id]) }}" method="POST" enctype="multipart/form-data" id="editUnitForm">
                @csrf
                @method('PATCH')

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
                    <label for="serial_number" class="form-label">Nomor Serial / ID Unit <span>*</span></label>
                    <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $unit->serial_number) }}" placeholder="Opsional" class="form-input @error('serial_number') input-error @enderror">
                    @error('serial_number') <p class="form-helper" style="color: #dc2626;">{{ $message }}</p> @enderror
                </div>

                <div class="form-group" style="border: 2px solid #e2e8f0; padding: 1rem; border-radius: 8px; background: #f8fafc;">
                    <label for="photo" class="form-label">Ganti Foto Unit</label>

                    @if ($unit->photo)
                        <div style="margin-bottom: 0.75rem;">
                            <p class="form-helper" style="color: #475569;">Foto Saat Ini:</p>
                            <img src="{{ Storage::url($unit->photo) }}" alt="Foto Saat Ini" class="w-24 h-24 object-cover rounded shadow" style="margin-bottom: 0.75rem;">
                        </div>

                        <div style="display: flex; align-items: center; margin-bottom: 0.75rem;">
                            <input type="checkbox" name="remove_photo" id="remove_photo" value="1" style="margin-right: 0.5rem;">
                            <label for="remove_photo" class="form-label" style="margin: 0;">Hapus foto saat ini</label>
                        </div>
                    @endif

                    <input type="file" name="photo" id="photo" class="form-input @error('photo') input-error @enderror">
                    @error('photo')
                        <p class="form-helper" style="color: #dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="condition_status" class="form-label">Status Kondisi <span>*</span></label>
                    <select name="condition_status" id="condition_status" required class="form-input @error('condition_status') input-error @enderror">
                        <option value="">-- Pilih Status --</option>
                        @foreach ($conditionStatuses as $status)
                            <option value="{{ $status }}" {{ old('condition_status', $unit->condition_status) == $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="current_holder" class="form-label">Pemegang Saat Ini <span>*</span></label>
                    <select name="current_holder" id="current_holder" required class="form-input @error('current_holder') input-error @enderror">
                        <option value="">-- Pilih Pemegang --</option>
                        <option value="angpen" {{ old('current_holder', $unit->current_holder) == 'angpen' ? 'selected' : '' }}>Angpen</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="note" class="form-label">Catatan (Opsional)</label>
                    <textarea name="note" id="note" rows="4" placeholder="Contoh: Alasan perubahan status, keterangan update, dll" maxlength="500" class="form-input @error('note') input-error @enderror">{{ old('note') }}</textarea>
                    <p class="form-helper">Maksimal 500 karakter</p>
                    @error('note')
                    <p class="form-helper" style="color: #dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        Update Unit
                    </button>
                    <a href="{{ route('inventories.show', $inventory->id) }}" class="btn-cancel">
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </div>

    @php
        $successMessage = session('success');
        $successRoute = route('inventories.show', $inventory->id);
        $conditionStatuses = ['available', 'in_use', 'maintenance', 'damaged', 'lost'];
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        /* eslint-disable */
        document.getElementById('editUnitForm').addEventListener('submit', function(e) {
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
                title: 'Mengupdate Unit...',
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
