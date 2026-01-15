<x-app-layout>
    <x-breadcrumbs :items="['Inventaris' => route('inventories.index'), $inventory->name => route('inventories.show', $inventory->id), 'Detail Unit' => route('inventories.units.show', [$inventory->id, $unit->id])]" />

    <div class="flex-1 flex flex-col min-h-0">
        <!-- Top Title Section -->
        <div class="mb-4 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <div class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-1">Status Unit Saat Ini</div>
                        @php
                            $statusClass = match($unit->condition_status) {
                                'available' => 'status-available',
                                'in_use' => 'badge-primary',
                                'maintenance' => 'status-maintenance',
                                'damaged' => 'status-damaged',
                                default => 'badge-secondary'
                            };
                        @endphp
                        <div class="flex items-center gap-3">
                            <h2 class="text-base font-bold text-gray-900">{{ $unit->serial_number ?? 'Unit #' . $unit->id }}</h2>
                            <span class="status-badge {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $unit->condition_status)) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @can('update', $unit)
                            <a href="{{ route('inventories.units.edit', [$inventory->id, $unit->id]) }}" 
                               class="add-btn">
                                Edit Unit
                            </a>
                        @endcan
                        <a href="{{ route('inventories.show', $inventory->id) }}" 
                           class="btn-cancel">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Info Section -->
        <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col min-h-0">
            <div class="section-header p-3 flex-shrink-0">
                <h3 class="text-xs font-bold text-white uppercase tracking-wider">Informasi Lengkap Unit</h3>
            </div>

            <div class="flex-1 overflow-y-auto p-4">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Left Column: Media -->
                    <div class="lg:col-span-4 space-y-4">
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-100 text-center">
                            <div class="text-[9px] font-bold text-gray-400 uppercase mb-2">Foto Unit</div>
                            <div class="min-h-[150px] flex items-center justify-center bg-white rounded border border-gray-100 overflow-hidden">
                                @if ($unit->photo)
                                    <img src="{{ asset($unit->photo) }}" class="max-w-full max-h-[250px] object-contain" loading="lazy">
                                @else
                                    <div class="text-gray-300 py-10">Tidak ada foto</div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-100 text-center">
                            <div class="text-[9px] font-bold text-gray-400 uppercase mb-2">QR Code</div>
                            <div class="bg-white p-2 rounded border border-gray-100 inline-block">
                                @if($unit->qr_code)
                                    <img src="{{ asset($unit->qr_code) }}" class="w-24 h-24 mx-auto mb-1">
                                    <a href="{{ asset($unit->qr_code) }}" download class="text-[9px] font-bold text-blue-600 hover:underline px-1">Download</a>
                                @else
                                    <span class="text-[10px] text-gray-400">N/A</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Details List -->
                    <div class="lg:col-span-8 flex flex-col">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                            <div class="border-b border-gray-50 pb-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase">ID Sistem</label>
                                <div class="text-sm font-mono font-bold text-blue-600">{{ $unit->id }}</div>
                            </div>

                            <div class="border-b border-gray-50 pb-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Nomor Serial</label>
                                <div class="text-sm font-bold text-gray-900">{{ $unit->serial_number ?: '-' }}</div>
                            </div>

                            <div class="border-b border-gray-50 pb-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Kategori Barang</label>
                                <div class="text-sm font-bold text-gray-900">{{ $inventory->name }}</div>
                            </div>

                            <div class="border-b border-gray-50 pb-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Pemegang Saat Ini</label>
                                <div class="text-sm font-bold text-gray-900">{{ $unit->current_holder ?: 'Gudang' }}</div>
                            </div>

                            <div class="border-b border-gray-50 pb-2 md:col-span-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Catatan / Keterangan</label>
                                <div class="text-xs text-gray-600 italic leading-relaxed">
                                    {{ $unit->note ?: 'Tidak ada catatan tambahan untuk unit ini.' }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto pt-6 flex justify-end">
                            @can('delete', $unit)
                                <form action="{{ route('inventories.units.destroy', [$inventory->id, $unit->id]) }}" 
                                      method="POST" 
                                      class="swal-delete"
                                      data-item-name="unit {{ $unit->serial_number ?? $unit->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-[11px] font-semibold hover:bg-red-700 transition-all">
                                        Hapus Permanen
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>