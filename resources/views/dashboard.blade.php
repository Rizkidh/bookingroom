<x-app-layout>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <h1>Dashboard Inventaris</h1>
            <p>Monitoring dan pengelolaan inventaris barang Anda secara real-time</p>
        </div>

        <!-- Stat Cards -->
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-label">Total Barang</div>
                <div class="stat-value">{{ $total }}</div>
                <div class="stat-subtext">Total unit dalam inventaris</div>
            </div>

            <div class="stat-card available">
                <div class="stat-label">Tersedia</div>
                <div class="stat-value">{{ $functional }}</div>
                <div class="stat-subtext">Unit siap digunakan</div>
            </div>

            <div class="stat-card damaged">
                <div class="stat-label">Rusak</div>
                <div class="stat-value">{{ $damaged }}</div>
                <div class="stat-subtext">Unit perlu perbaikan</div>
            </div>

            <div class="stat-card units">
                <div class="stat-label">Jenis Barang</div>
                <div class="stat-value">{{ $inventoryList->count() }}</div>
                <div class="stat-subtext">Tipe barang terdaftar</div>
            </div>
        </div>

        <!-- Inventory Table Section -->
        <div class="inventory-section">
            <div class="section-header">
                <h2>Detail Inventaris</h2>
                <a href="{{ route('inventories.create') }}" class="add-btn">
                    Tambah Item Baru
                </a>
            </div>

            @if ($inventoryList->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">Belum ada data</div>
                    <p class="empty-state-text">Belum ada data inventaris. Mulai dengan menambahkan item baru.</p>
                    <a href="{{ route('inventories.create') }}" class="empty-state-btn">Tambah Item Pertama</a>
                </div>
            @else
                <div class="table-wrapper">
                    <table class="inventory-table">
                        <thead>
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
                            @foreach ($inventoryList as $item)
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
    </div>
</x-app-layout>
