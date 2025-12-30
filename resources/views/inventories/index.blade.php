<x-app-layout>
    {{-- Store session data in PHP variable to avoid Blade syntax in JavaScript --}}
    @php
        $successMessage = session('success');
    @endphp

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <div class="management-container">
        <div class="management-card">

            <div class="management-header">
                <h2 class="management-title">Manajemen Inventaris Barang</h2>
                <a href="{{ route('inventories.create') }}" class="btn-add">
                    + Tambah Barang Baru
                </a>
            </div>

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
                <p>Belum ada item inventaris yang ditambahkan.</p>
            </div>
            @endif

        </div>
    </div>

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