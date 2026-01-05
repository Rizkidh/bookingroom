<x-app-layout>
    {{-- Store session data in PHP variable to avoid Blade syntax in JavaScript --}}
    @php
        $successMessage = session('success');
    @endphp

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="management-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0 mb-4 sm:mb-6">
        <h2 class="management-title text-lg sm:text-xl lg:text-2xl">Manajemen Inventaris Barang</h2>
        <a href="{{ route('inventories.create') }}" class="btn-add text-sm sm:text-base px-4 py-2">
            + Tambah Barang Baru
        </a>
    </div>

    @if ($inventoryItems->isEmpty())
        <div class="empty-state p-8 text-center">
            <p class="text-gray-600">Belum ada item inventaris yang ditambahkan.</p>
        </div>
    @else
        <!-- Desktop Table View -->
        <div class="hidden md:block table-container max-h-[400px] overflow-y-auto border rounded-lg">
            <table class="management-table">
                <thead class="sticky top-0 bg-gray-50 z-10">
                    <tr>
                        <th>Nama Barang</th>
                        <th>Total Stok</th>
                        <th>Tersedia</th>
                        <th>Rusak</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inventoryItems as $item)
                    <tr>
                        <td class="item-name">
                            <a href="{{ route('inventories.show', $item->id) }}" class="item-name-link">
                                {{ $item->name }}
                            </a>
                        </td>
                        <td class="stock-total">{{ $item->total_stock }}</td>
                        <td><span class="stock-available">{{ $item->available_stock }}</span></td>
                        <td><span class="stock-damaged">{{ $item->damaged_stock }}</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('inventories.edit', $item->id) }}" class="btn-edit">Edit</a>
                                @can('delete', $item)
                                <form action="{{ route('inventories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item {{ $item->name }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Hapus</button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @foreach ($inventoryItems as $item)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                <div class="mb-3">
                    <a href="{{ route('inventories.show', $item->id) }}" class="text-lg font-semibold text-blue-600 hover:underline">
                        {{ $item->name }}
                    </a>
                </div>

                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div class="text-center">
                        <div class="text-xs text-gray-500 mb-1">Total</div>
                        <div class="text-lg font-bold text-blue-600">{{ $item->total_stock }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xs text-gray-500 mb-1">Tersedia</div>
                        <div class="text-lg font-bold text-green-600">{{ $item->available_stock }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xs text-gray-500 mb-1">Rusak</div>
                        <div class="text-lg font-bold text-red-600">{{ $item->damaged_stock }}</div>
                    </div>
                </div>

                <div class="flex gap-2 pt-3 border-t">
                    <a href="{{ route('inventories.edit', $item->id) }}" class="flex-1 text-center bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Edit
                    </a>
                    @can('delete', $item)
                    <form action="{{ route('inventories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item {{ $item->name }}?');" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg font-semibold hover:bg-red-700 transition">
                            Hapus
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
            @endforeach
        </div>
    @endif


    @if ($successMessage)
    <script>
        /* eslint-disable */
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
        /* eslint-enable */
    </script>
    @endif
</x-app-layout>
