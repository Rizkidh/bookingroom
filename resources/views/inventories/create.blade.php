<x-app-layout>
    <x-breadcrumbs :items="['Inventaris' => route('inventories.index'), 'Tambah Barang' => route('inventories.create')]" />

    <div class="flex-1 flex flex-col min-h-0 bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <div class="section-header p-3 flex-shrink-0">
            <h2 class="text-xs font-bold text-white uppercase tracking-wider">Tambah Item Inventaris Baru</h2>
        </div>

        <div class="flex-1 overflow-y-auto p-5">
            <form action="{{ route('inventories.store') }}" method="POST" id="inventoryForm" class="h-full flex flex-col max-w-3xl mx-auto">
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

                <div class="flex flex-col gap-6 flex-1">
                    <!-- Name Input -->
                    <div>
                        <label for="name" class="form-label text-[10px] uppercase mb-1 block">Nama Barang / Kategori <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}" 
                               required 
                               placeholder="Contoh: Laptop, Printer, Monitor, dll" 
                               class="form-input w-full text-sm font-semibold text-gray-800 placeholder:font-normal placeholder:text-gray-400 py-2.5"
                               autofocus>
                    </div>

                    <!-- Note Input -->
                    <div class="flex-1 flex flex-col">
                        <label for="note" class="form-label text-[10px] uppercase mb-1 block">Catatan / Deskripsi (Opsional)</label>
                        <textarea name="note" 
                                  id="note" 
                                  placeholder="Contoh: Alasan penambahan item, deskripsi spesifikasi umum, dll..." 
                                  maxlength="500" 
                                  class="form-input w-full flex-1 resize-none text-sm p-3 leading-relaxed">{{ old('note') }}</textarea>
                        <p class="text-right text-[9px] text-gray-400 mt-1">Maksimal 500 karakter</p>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-auto pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('inventories.index') }}" class="btn-cancel">
                        Batal
                    </a>
                    <button type="submit" class="btn-success px-8">
                        Simpan Item
                    </button>
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
        document.getElementById('inventoryForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const nameInput = document.getElementById('name').value;
            if (!nameInput.trim()) {
                 Swal.fire({
                    title: 'Nama Wajib Diisi',
                    text: 'Mohon masukkan nama barang terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#10b981'
                });
                return;
            }

            Swal.fire({
                title: 'Menambahkan Item...',
                html: 'Mohon tunggu',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(() => {
                this.submit();
            }, 500);
        });
    </script>
</x-app-layout>
