<x-app-layout>
    <!-- Header -->
    <div class="dashboard-header">
        <h1>Dashboard Inventaris</h1>
        <p>Monitoring dan pengelolaan inventaris barang Anda secara real-time</p>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Barang Card -->
        <a href="{{ route('dashboard') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-5 text-white cursor-pointer hover:shadow-xl transition-shadow transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium opacity-90">Total Barang</div>
                    <div class="text-3xl font-bold mt-2">{{ $total }}</div>
                    <div class="text-xs opacity-75 mt-1">Total unit dalam inventaris</div>
                </div>
                <svg class="w-12 h-12 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042L5.85 15.5h5.3L9.05 9.5h8.5a1 1 0 00.957-1.387l-1.476-5A1 1 0 0015.582 3H9.5a1 1 0 00-.986.836l-.564-2.26A1 1 0 006 1H3z"/>
                </svg>
            </div>
        </a>

        <!-- Tersedia Card -->
        <a href="?condition=functional" class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-5 text-white cursor-pointer hover:shadow-xl transition-shadow transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium opacity-90">Tersedia</div>
                    <div class="text-3xl font-bold mt-2">{{ $functional }}</div>
                    <div class="text-xs opacity-75 mt-1">Unit siap digunakan</div>
                </div>
                <svg class="w-12 h-12 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
        </a>

        <!-- Rusak Card -->
        <a href="?condition=damaged" class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-5 text-white cursor-pointer hover:shadow-xl transition-shadow transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium opacity-90">Rusak</div>
                    <div class="text-3xl font-bold mt-2">{{ $damaged }}</div>
                    <div class="text-xs opacity-75 mt-1">Unit perlu perbaikan</div>
                </div>
                <svg class="w-12 h-12 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 2.476M14.89 2.11a6 6 0 01.9 8.12l2.736-2.735a1 1 0 10-1.414-1.414L14.475 8.586a6 6 0 01-6.365-2.475" clip-rule="evenodd"/>
                </svg>
            </div>
        </a>

        <!-- Jenis Barang Card -->
        <a href="{{ route('inventories.index') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-5 text-white cursor-pointer hover:shadow-xl transition-shadow transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium opacity-90">Jenis Barang</div>
                    <div class="text-3xl font-bold mt-2">{{ $inventoryList->count() }}</div>
                    <div class="text-xs opacity-75 mt-1">Tipe barang terdaftar</div>
                </div>
                <svg class="w-12 h-12 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h12a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zm0 8a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z"/>
                </svg>
            </div>
        </a>
    </div>

    <!-- Inventory Table Section -->
    <div class="inventory-section">
        <div class="section-header">
            <h2>Detail Inventaris
                @if (request('condition') === 'functional')
                    <span class="text-green-600 text-lg">(Unit Tersedia)</span>
                @elseif (request('condition') === 'damaged')
                    <span class="text-red-600 text-lg">(Unit Rusak)</span>
                @endif
            </h2>
            <a href="{{ route('inventories.create') }}" class="add-btn">
                Tambah Item Baru
            </a>
        </div>

        @php
            $filteredList = $inventoryList;
            if (request('condition') === 'functional') {
                $filteredList = $inventoryList->filter(fn($item) => $item->available_stock > 0);
            } elseif (request('condition') === 'damaged') {
                $filteredList = $inventoryList->filter(fn($item) => $item->damaged_stock > 0);
            }
        @endphp

        @if ($filteredList->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">Belum ada data</div>
                <p class="empty-state-text">Belum ada data inventaris. Mulai dengan menambahkan item baru.</p>
                <a href="{{ route('inventories.create') }}" class="empty-state-btn">Tambah Item Pertama</a>
            </div>
        @else
            <div class="table-wrapper max-h-[400px] overflow-y-auto border rounded-lg">
                <table class="inventory-table">
                        <thead class="sticky top-0 bg-gray-50 z-10">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Total Stok</th>
                                <th>Tersedia</th>
                                <th>Rusak</th>
                                <th>Terakhir Update</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filteredList as $item)
                            <tr>
                                <td class="item-name">{{ $item->name }}</td>
                                <td><span class="stock-badge badge-total">{{ $item->total_stock }}</span></td>
                                <td><span class="stock-badge badge-available">{{ $item->available_stock }}</span></td>
                                <td><span class="stock-badge badge-damaged">{{ $item->damaged_stock }}</span></td>
                                <td><span class="updated-time">{{ $item->updated_at->format('Y-m-d H:i') }}</span></td>
                                <td style="text-align: center;">
                                    <a href="{{ route('inventories.show', $item->id) }}" title="Lihat Detail" class="action-link">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
    </div>
</x-app-layout>
