<x-app-layout>
    <div class="management-container">
        <div class="management-card">

            <div class="management-header">
                <h2 class="management-title">Manajemen Inventaris Barang</h2>
                <a href="{{ route('inventories.create') }}" class="btn-add">
                    + Tambah Barang Baru
                </a>
            </div>

            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="table-container">
                <table class="management-table">
                    <thead>
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
                            {{-- KOLOM NAMA BARANG: DIBUAT CLICKABLE KE DETAIL --}}
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
                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('inventories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item {{ $item->name }}?');">
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

            @if ($inventoryItems->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <p>Belum ada item inventaris yang ditambahkan.</p>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>