<x-app-layout>
    <style>
        /* ==================== BASE STYLE ==================== */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f4f4;
            line-height: 1.6;
        }

        /* ==================== DASHBOARD WRAPPER ==================== */
        .dashboard-wrapper {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            max-width: 1240px;
            margin: 0 auto;
            padding: 20px;
        }

        /* ==================== STAT CARDS ==================== */
        .dashboard {
            background: white;
            padding: 24px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dashboard:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .dashboard h1 {
            color: #333;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .dashboard p {
            color: #666;
            font-size: 0.9rem;
            margin-top: 8px;
        }

        .inventory-count {
            font-size: 48px;
            font-weight: 700;
            margin: 16px 0;
        }

        .count-green {
            color: #22c55e;
        }

        .count-blue {
            color: #3b82f6;
        }

        .count-red {
            color: #ef4444;
        }

        /* ==================== INVENTORY TABLE WRAPPER ==================== */
        .inventory-list-wrapper {
            max-width: 1240px;
            margin: 0 auto 20px;
            padding: 20px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .list-header {
            font-size: 1.25rem;
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f4f4f4;
        }

        /* ==================== TABLE STYLES ==================== */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            min-width: 600px;
        }

        .inventory-table th,
        .inventory-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #eee;
        }

        .inventory-table th {
            background-color: #f8f9fa;
            color: #555;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .inventory-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .inventory-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .inventory-table td {
            color: #333;
            font-size: 0.95rem;
        }

        .status-available {
            color: #22c55e;
            font-weight: 600;
        }

        .status-damaged {
            color: #ef4444;
            font-weight: 600;
        }

        /* ==================== RESPONSIVE - TABLET ==================== */
        @media (max-width: 992px) {
            .dashboard-wrapper {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
                padding: 16px;
            }

            .dashboard-wrapper .dashboard:last-child {
                grid-column: span 2;
            }
        }

        /* ==================== RESPONSIVE - MOBILE ==================== */
        @media (max-width: 640px) {
            .dashboard-wrapper {
                grid-template-columns: 1fr;
                gap: 12px;
                padding: 12px;
            }

            .dashboard-wrapper .dashboard:last-child {
                grid-column: span 1;
            }

            .dashboard {
                padding: 20px 16px;
            }

            .dashboard h1 {
                font-size: 1rem;
            }

            .inventory-count {
                font-size: 36px;
                margin: 12px 0;
            }

            .inventory-list-wrapper {
                margin: 0 12px 12px;
                padding: 16px;
                border-radius: 10px;
            }

            .list-header {
                font-size: 1.1rem;
                margin-bottom: 16px;
                padding-bottom: 10px;
            }

            .inventory-table th,
            .inventory-table td {
                padding: 12px 14px;
                font-size: 0.85rem;
            }

            .inventory-table th {
                font-size: 0.75rem;
            }
        }

        /* ==================== EMPTY STATE ==================== */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #888;
        }
    </style>

    <div class="dashboard-wrapper">
        <div class="dashboard">
            <h1>Total Inventory Items</h1>
            <div class="inventory-count count-green">{{ $total }}</div>
            <p>Current Stock</p>
        </div>

        <div class="dashboard">
            <h1>Functional / Available Items</h1>
            <div class="inventory-count count-blue">{{ $functional }}</div>
            <p>Items ready for use</p>
        </div>

        <div class="dashboard">
            <h1>Damaged Items</h1>
            <div class="inventory-count count-red">{{ $damaged }}</div>
            <p>Items needing repair/disposal</p>
        </div>

    </div>
    <div class="inventory-list-wrapper">
        <h2 class="list-header">Detail Inventaris Barang</h2>
        <div class="table-container">
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Total Stok</th>
                        <th>Tersedia (Functional)</th>
                        <th>Rusak (Damaged)</th>
                        <th>Terakhir Update</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inventoryList as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->total_stock }}</td>
                        <td>
                            <span class="status-available">{{ $item->available_stock }}</span>
                        </td>
                        <td>
                            <span class="status-damaged">{{ $item->damaged_stock }}</span>
                        </td>
                        <td>{{ $item->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @endforeach

                    {{-- Tampilkan pesan jika list kosong --}}
                    @if ($inventoryList->isEmpty())
                    <tr>
                        <td colspan="5" style="text-align: center;">Tidak ada data inventaris yang ditemukan.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>

    </body>
</x-app-layout>