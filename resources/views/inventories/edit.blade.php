<x-app-layout>
    <x-breadcrumbs :items="['Inventaris' => route('inventories.index'), 'Edit ' . $inventory->name => route('inventories.edit', $inventory->id)]" />
    <div class="form-container">
        <div class="form-card">
            <h2 class="form-title">Edit Item: {{ $inventory->name }}</h2>

            <form action="{{ route('inventories.update', $inventory->id) }}" method="POST" id="editInventoryForm">
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
                    <label for="name" class="form-label">Nama Barang <span>*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $inventory->name) }}" required placeholder="Contoh: Laptop, Printer, Monitor, dll" class="form-input @error('name') input-error @enderror">
                    @error('name')
                    <p class="form-helper" style="color: #dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="note" class="form-label">Catatan (Opsional)</label>
                    <textarea name="note" id="note" rows="4" placeholder="Contoh: Alasan perubahan, keterangan update, dll" maxlength="500" class="form-input @error('note') input-error @enderror">{{ old('note') }}</textarea>
                    <p class="form-helper">Maksimal 500 karakter</p>
                    @error('note')
                    <p class="form-helper" style="color: #dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        Update Item
                    </button>
                    <a href="{{ route('inventories.index') }}" class="btn-cancel">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    @php
        $successMessage = session('success');
        $successRoute = route('inventories.index');
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        /* eslint-disable */
        document.getElementById('editInventoryForm').addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Mengupdate Item...',
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
