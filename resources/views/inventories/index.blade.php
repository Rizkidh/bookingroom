<x-app-layout>
    <style>
        /* ==================== MANAJEMEN INVENTARIS PAGE ==================== */

        .management-container {
            padding: 2rem 1rem;
            max-width: 1240px;
            margin: 0 auto;
        }

        .management-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header Section */
        .management-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .management-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
        }

        /* Table Container */
        .table-container {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .management-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        .management-table thead {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        }

        .management-table th {
            padding: 0.875rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #e2e8f0;
        }

        .management-table td {
            padding: 1rem;
            font-size: 0.875rem;
            color: #475569;
            border-bottom: 1px solid #f1f5f9;
        }

        .management-table tbody tr {
            transition: all 0.2s ease;
        }

        .management-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .management-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Cell Styles */
        .item-name {
            font-weight: 600;
            color: #1e293b;
        }

        .stock-available {
            color: #4CAF50;
            font-weight: 700;
            background: rgba(76, 175, 80, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
        }

        .stock-damaged {
            color: #dc3545;
            font-weight: 700;
            background: rgba(220, 53, 69, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
        }

        .stock-total {
            font-weight: 600;
            color: #007bff;
        }

        /* Action Buttons */
        .btn-edit {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.375rem 0.875rem;
            background: #007bff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.8125rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-edit:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }

        .btn-delete {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.375rem 0.875rem;
            background: #dc3545;
            color: white;
            border-radius: 6px;
            border: none;
            font-size: 0.8125rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #94a3b8;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* ==================== RESPONSIVE ==================== */

        @media (max-width: 768px) {
            .management-container {
                padding: 1rem 0.75rem;
            }

            .management-card {
                padding: 1rem;
                border-radius: 8px;
            }

            .management-header {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }

            .management-title {
                font-size: 1.25rem;
            }

            .btn-add {
                justify-content: center;
                width: 100%;
            }

            .management-table th,
            .management-table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.8125rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.375rem;
            }

            .btn-edit,
            .btn-delete {
                justify-content: center;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .management-title {
                font-size: 1.125rem;
            }

            .management-table th,
            .management-table td {
                padding: 0.625rem 0.375rem;
                font-size: 0.75rem;
            }

            .stock-available,
            .stock-damaged {
                padding: 0.125rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* ==================== MANAJEMEN INVENTARIS PAGE ==================== */

        .management-container {
            padding: 2rem 1rem;
            max-width: 1240px;
            margin: 0 auto;
        }

        .management-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header Section */
        .management-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .management-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
        }

        /* Table Container */
        .table-container {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .management-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        .management-table thead {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        }

        .management-table th {
            padding: 0.875rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #e2e8f0;
        }

        .management-table td {
            padding: 1rem;
            font-size: 0.875rem;
            color: #475569;
            border-bottom: 1px solid #f1f5f9;
        }

        .management-table tbody tr {
            transition: all 0.2s ease;
        }

        .management-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .management-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Cell Styles */
        .item-name {
            font-weight: 600;
            color: #1e293b;
        }

        .stock-available {
            color: #4CAF50;
            font-weight: 700;
            background: rgba(76, 175, 80, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
        }

        .stock-damaged {
            color: #dc3545;
            font-weight: 700;
            background: rgba(220, 53, 69, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
        }

        .stock-total {
            font-weight: 600;
            color: #007bff;
        }

        /* Action Buttons */
        .btn-edit {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.375rem 0.875rem;
            background: #007bff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.8125rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-edit:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }

        .btn-delete {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.375rem 0.875rem;
            background: #dc3545;
            color: white;
            border-radius: 6px;
            border: none;
            font-size: 0.8125rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #94a3b8;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* ==================== RESPONSIVE ==================== */

        @media (max-width: 768px) {
            .management-container {
                padding: 1rem 0.75rem;
            }

            .management-card {
                padding: 1rem;
                border-radius: 8px;
            }

            .management-header {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }

            .management-title {
                font-size: 1.25rem;
            }

            .btn-add {
                justify-content: center;
                width: 100%;
            }

            .management-table th,
            .management-table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.8125rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.375rem;
            }

            .btn-edit,
            .btn-delete {
                justify-content: center;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .management-title {
                font-size: 1.125rem;
            }

            .management-table th,
            .management-table td {
                padding: 0.625rem 0.375rem;
                font-size: 0.75rem;
            }

            .stock-available,
            .stock-damaged {
                padding: 0.125rem 0.5rem;
                font-size: 0.75rem;
            }
        }
    </style>
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
                            <td class="item-name">{{ $item->name }}</td>
                            <td class="stock-total">{{ $item->total_stock }}</td>
                            <td><span class="stock-available">{{ $item->available_stock }}</span></td>
                            <td><span class="stock-damaged">{{ $item->damaged_stock }}</span></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('inventories.edit', $item->id) }}" class="btn-edit">Edit</a>
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
    </div>
</x-app-layout>