<x-app-layout>
    <x-breadcrumbs :items="['Inventaris' => route('inventories.index'), $inventory->name => route('inventories.show', $inventory->id), 'Edit Unit' => route('inventories.units.edit', [$inventory->id, $unit->id])]" />

    <div class="flex-1 flex flex-col min-h-0 bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <div class="section-header p-3 flex-shrink-0">
            <h2 class="text-xs font-bold text-white uppercase tracking-wider">Update Unit: {{ $unit->serial_number ?? 'ID ' . $unit->id }}</h2>
        </div>

        <div class="flex-1 overflow-y-auto p-5">
            <form action="{{ route('inventories.units.update', [$inventory->id, $unit->id]) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="editUnitForm"
                  class="h-full flex flex-col">
                @csrf
                @method('PATCH')

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
                    <!-- Column 1: Media (Photo & Delete Option) -->
                    <div class="lg:col-span-4 flex flex-col gap-4">
                        <div class="bg-blue-50/50 rounded-lg p-3 border border-blue-100 flex flex-col gap-3">
                            <label class="text-[10px] font-bold text-blue-600 uppercase block">Foto Unit Saat Ini</label>
                            
                            @if ($unit->photo)
                                <div class="relative group rounded-lg overflow-hidden border border-gray-200 bg-white shadow-sm aspect-square flex items-center justify-center">
                                    <img src="{{ asset($unit->photo) }}" class="max-full max-h-full object-contain">
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <p class="text-white text-[10px] font-bold">Foto Terpasang</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 p-2 bg-red-50 rounded border border-red-100">
                                    <input type="checkbox" name="remove_photo" id="remove_photo" value="1" class="w-3 h-3 text-red-600 rounded focus:ring-red-500">
                                    <label for="remove_photo" class="text-[10px] font-bold text-red-700 cursor-pointer">Hapus foto ini?</label>
                                </div>
                            @else
                                <div class="aspect-square bg-white rounded-lg border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-[10px]">Belum ada foto</span>
                                </div>
                            @endif
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                            <label class="text-[10px] font-bold text-gray-500 uppercase mb-2 block">Ganti Foto (Opsional)</label>
                            <input type="file" name="photo" class="block w-full text-[10px] text-gray-500
                                file:mr-2 file:py-1.5 file:px-3
                                file:rounded-md file:border-0
                                file:text-[10px] file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100">
                        </div>
                    </div>

                    <!-- Column 2: Details & Inputs -->
                    <div class="lg:col-span-8 flex flex-col gap-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Serial Number -->
                            <div class="md:col-span-2">
                                <label class="form-label text-[10px] uppercase mb-1 block">Nomor Serial / Barcode <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       name="serial_number" 
                                       id="serial_number"
                                       value="{{ old('serial_number', $unit->serial_number) }}" 
                                       class="form-input w-full font-mono text-sm font-bold text-blue-600 placeholder:font-sans placeholder:font-normal placeholder:text-gray-400"
                                       placeholder="Contoh: SN123456...">
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="form-label text-[10px] uppercase mb-1 block">Status Kondisi <span class="text-red-500">*</span></label>
                                <select name="condition_status" required class="form-input w-full text-sm">
                                    <option value="">-- Pilih Status --</option>
                                    @foreach (['available', 'in_use', 'maintenance', 'damaged', 'lost'] as $status)
                                        <option value="{{ $status }}" {{ old('condition_status', $unit->condition_status) == $status ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Holder -->
                            <div>
                                <label class="form-label text-[10px] uppercase mb-1 block">Pemegang Saat Ini <span class="text-red-500">*</span></label>
                                <select name="current_holder" required class="form-input w-full text-sm">
                                    <option value="">-- Pilih Pemegang --</option>
                                    <option value="Angpen" {{ old('current_holder', $unit->current_holder) == 'angpen' ? 'selected' : '' }}>Angpen</option>
                                    <option value="Gudang" {{ old('current_holder', $unit->current_holder) == 'gudang' || !$unit->current_holder ? 'selected' : '' }}>Gudang</option>
                                </select>
                            </div>
                        </div>

                        <!-- Note -->
                        <div class="flex-1 min-h-[100px]">
                            <label class="form-label text-[10px] uppercase mb-1 block">Catatan Tambahan</label>
                            <textarea name="note" 
                                      class="form-input w-full h-full min-h-[100px] resize-none text-sm" 
                                      placeholder="Keterangan kondisi, dll...">{{ old('note', $unit->note) }}</textarea>
                            <div class="text-right text-[9px] text-gray-400 mt-1">Max 500 karakter</div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-auto pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('inventories.show', $inventory->id) }}" 
                       class="btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-success px-8">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @php
        $successMessage = session('success');
        $successRoute = route('inventories.show', $inventory->id);
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('editUnitForm').addEventListener('submit', function(e) {
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
                title: 'Menyimpan Perubahan...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            this.submit();
        });
    </script>
    
    @if ($successMessage)
    <script>
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
                confirmButtonColor: '#10b981',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ $successRoute }}';
                }
            });
        });
    </script>
    @endif
</x-app-layout>
