<x-app-layout>
    @php
        $successMessage = session('success');
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <x-breadcrumbs :items="['Inventaris' => route('inventories.index')]" />

    <div class="flex-1 flex flex-col min-h-0">
        <div class="pro-table-wrapper flex-1 flex flex-col min-h-0">
            <div class="section-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 p-4 flex-shrink-0">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Manajemen Inventaris Barang
                </h2>
                <div class="flex gap-2 w-full sm:w-auto">
                    <a href="{{ route('inventories.export') }}" class="flex items-center justify-center bg-green-500 text-white px-3 py-1.5 rounded-lg hover:bg-green-600 transition shadow-sm font-semibold text-xs">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export
                    </a>
                    <a href="{{ route('inventories.create') }}" class="add-btn text-xs px-3 py-1.5 flex-1 sm:flex-none justify-center">
                        + Tambah Barang
                    </a>
                </div>
            </div>

            @if ($inventoryItems->isEmpty())
                <div class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-50 text-gray-400 rounded-full mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Belum ada item inventaris</h3>
                    <p class="text-xs text-gray-500 mt-1">Mulai dengan menambahkan barang baru ke sistem.</p>
                </div>
            @else
                <!-- Desktop View -->
                <div class="pro-table-desktop flex-1 min-h-0">
                    <table class="pro-table">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th class="text-center w-32">Total Stok</th>
                                <th class="text-center w-32">Tersedia</th>
                                <th class="text-center w-32">Rusak</th>
                                <th class="text-right w-40">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventoryItems as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('inventories.show', $item->id) }}" class="font-bold text-gray-900 hover:text-blue-600 transition truncate block max-w-sm">
                                        {{ $item->name }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700">
                                        {{ $item->total_stock }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700">
                                        {{ $item->available_stock }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700">
                                        {{ $item->damaged_stock }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('inventories.edit', $item->id) }}" 
                                           class="pro-btn-action bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-2 py-1">
                                            <span class="text-[10px]">Edit</span>
                                        </a>
                                        @can('delete', $item)
                                        <form action="{{ route('inventories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item {{ $item->name }}?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="pro-btn-action bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-2 py-1">
                                                <span class="text-[10px]">Hapus</span>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View -->
                <div class="pro-card-view p-4 flex-1 overflow-y-auto min-h-0">
                    @foreach ($inventoryItems as $item)
                    <div class="pro-card mb-3 last:mb-0 border-none shadow-sm ring-1 ring-gray-200">
                        <div class="mb-3">
                            <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Nama Barang</div>
                            <a href="{{ route('inventories.show', $item->id) }}" class="text-base font-bold text-gray-900 hover:text-blue-600 transition">
                                {{ $item->name }}
                            </a>
                        </div>

                        <div class="grid grid-cols-3 gap-2 mb-4">
                            <div class="bg-gray-50 rounded-lg p-1.5 text-center">
                                <div class="text-[8px] font-bold text-gray-400 uppercase">Total</div>
                                <div class="text-sm font-bold text-blue-600">{{ $item->total_stock }}</div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-1.5 text-center">
                                <div class="text-[8px] font-bold text-green-400 uppercase">Siap</div>
                                <div class="text-sm font-bold text-green-600">{{ $item->available_stock }}</div>
                            </div>
                            <div class="bg-red-50 rounded-lg p-1.5 text-center">
                                <div class="text-[8px] font-bold text-red-400 uppercase">Rusak</div>
                                <div class="text-sm font-bold text-red-600">{{ $item->damaged_stock }}</div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('inventories.edit', $item->id) }}" class="flex-1 text-center bg-blue-50 text-blue-600 py-1.5 rounded-lg font-bold text-xs hover:bg-blue-100 transition">
                                Edit
                            </a>
                            @can('delete', $item)
                            <form action="{{ route('inventories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item {{ $item->name }}?');" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-50 text-red-600 py-1.5 rounded-lg font-bold text-xs hover:bg-red-100 transition">
                                    Hapus
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            @if ($inventoryItems->isNotEmpty())
                <div class="p-3 border-t bg-gray-50 flex-shrink-0">
                    {{ $inventoryItems->links() }}
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
                confirmButtonText: 'Lanjut',
                confirmButtonColor: '#3b82f6',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });
    </script>
    @endif
</x-app-layout>
