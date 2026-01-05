<x-app-layout>
    <x-breadcrumbs :items="['Inventaris' => route('inventories.index'), $inventory->name => route('inventories.show', $inventory->id)]" />
    @php
        $successMessage = session('success');
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex-1 flex flex-col min-h-0">
        <div class="mb-4 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <div class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-1">Detail Barang</div>
                        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">{{ $inventory->name }}</h2>
                    </div>
                    <div class="flex bg-gray-50 rounded-lg p-1 px-3 border border-gray-100">
                        <div class="text-center px-3 border-r border-gray-200">
                            <div class="text-[9px] font-bold text-gray-400 uppercase">Total</div>
                            <div class="text-sm font-bold text-gray-900">{{ $units->count() }}</div>
                        </div>
                        <div class="text-center px-3">
                            <div class="text-[9px] font-bold text-green-500 uppercase">Siap</div>
                            <div class="text-sm font-bold text-green-600">{{ $inventory->available_stock }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-table-wrapper flex-1 min-h-0 flex flex-col">
            <div class="section-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 p-4 flex-shrink-0">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Daftar Unit Satuan
                </h3>
                <a href="{{ route('inventories.units.create', $inventory->id) }}" class="add-btn text-xs px-3 py-1.5 flex items-center gap-1">
                    <span>+ Tambah Unit</span>
                </a>
            </div>

            @if ($units->isEmpty())
                <div class="flex-1 flex flex-col items-center justify-center p-8 text-center bg-white">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-500 rounded-full mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Belum ada unit tersedia</h3>
                    <p class="text-xs text-gray-500 mt-1">Gunakan tombol di atas untuk menambahkan unit satuan pertama.</p>
                </div>
            @else
                <!-- Desktop Table -->
                <div class="pro-table-desktop flex-1 min-h-0">
                    <table class="pro-table">
                        <thead>
                            <tr>
                                <th class="w-20">Foto</th>
                                <th class="w-32">ID Unit</th>
                                <th>Serial Number</th>
                                <th class="w-32">Status</th>
                                <th class="w-40">Holder</th>
                                <th class="text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $unit)
                            <tr>
                                <td>
                                    <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-100 bg-gray-50 flex items-center justify-center">
                                        @if($unit->photo)
                                            <img src="{{ asset($unit->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </td>
                                <td class="font-mono font-bold text-blue-600 text-xs">{{ $unit->id }}</td>
                                <td class="font-mono text-gray-600 text-xs">{{ $unit->serial_number ?: '-' }}</td>
                                <td>
                                    @php
                                        $statusClass = match($unit->condition_status) {
                                            'available' => 'status-available',
                                            'in_use' => 'badge-primary',
                                            'maintenance' => 'status-maintenance',
                                            'damaged' => 'status-damaged',
                                            default => 'badge-secondary'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }} text-[9px] px-1.5 py-0.5">
                                        {{ ucfirst(str_replace('_', ' ', $unit->condition_status)) }}
                                    </span>
                                </td>
                                <td class="text-xs text-gray-800">{{ $unit->current_holder ?: 'Gudang' }}</td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="{{ route('inventories.units.show', [$inventory->id, $unit->id]) }}" 
                                           class="pro-btn-action bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-2 py-1 text-[9px]">
                                            Detail
                                        </a>
                                        <a href="{{ route('inventories.units.edit', [$inventory->id, $unit->id]) }}" 
                                           class="pro-btn-action bg-gray-50 text-gray-600 hover:bg-gray-600 hover:text-white px-2 py-1 text-[9px]">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View -->
                <div class="pro-card-view p-4 flex-1 overflow-y-auto min-h-0">
                    @foreach ($units as $unit)
                    <div class="pro-card mb-3 last:mb-0 border-none shadow-sm ring-1 ring-gray-100 p-0 overflow-hidden">
                        <div class="flex">
                            <div class="w-20 h-20 bg-gray-50 flex-shrink-0">
                                @if($unit->photo)
                                    <img src="{{ asset($unit->photo) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 p-2 flex flex-col justify-between min-w-0">
                                <div class="flex justify-between items-start">
                                    <span class="font-mono font-bold text-blue-600 text-xs text-xs">#{{ $unit->id }}</span>
                                    @php
                                        $statusClass = match($unit->condition_status) {
                                            'available' => 'status-available',
                                            'in_use' => 'badge-primary',
                                            'maintenance' => 'status-maintenance',
                                            'damaged' => 'status-damaged',
                                            default => 'badge-secondary'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }} text-[8px] px-1 py-0.5">
                                        {{ ucfirst(str_replace('_', ' ', $unit->condition_status)) }}
                                    </span>
                                </div>
                                <div class="text-[10px] text-gray-500 truncate mt-1">
                                    {{ $unit->current_holder ?: 'Gudang' }}
                                </div>
                                <div class="flex gap-1 mt-2">
                                    <a href="{{ route('inventories.units.show', [$inventory->id, $unit->id]) }}" class="flex-1 text-center py-1 bg-blue-600 text-white rounded text-[10px] font-bold">Detail</a>
                                    <a href="{{ route('inventories.units.edit', [$inventory->id, $unit->id]) }}" class="flex-1 text-center py-1 bg-gray-100 text-gray-600 rounded text-[10px] font-bold">Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            @if ($units->isNotEmpty())
                <div class="p-3 border-t bg-gray-50 flex-shrink-0">
                    {{ $units->links() }}
                </div>
            @endif
        </div>
    </div>

        @if ($successMessage)
    <script>
        window.addEventListener('load', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ $successMessage }}',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b82f6',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });
    </script>
    @endif
</x-app-layout>
