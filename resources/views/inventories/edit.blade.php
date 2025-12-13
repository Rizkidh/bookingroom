<x-app-layout>
    <style>
        /* ==================== FORM INVENTARIS (CREATE & EDIT) ==================== */

        .form-container {
            padding: 2rem 1rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.08);
            padding: 2rem;
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

        .form-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }

        /* Error Alert */
        .error-alert {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            border: 1px solid #fca5a5;
            border-left: 4px solid #dc3545;
            color: #991b1b;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .error-alert ul {
            margin: 0;
            padding-left: 1.25rem;
        }

        .error-alert li {
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .form-label span {
            color: #dc3545;
            margin-left: 2px;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            color: #1e293b;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: #4CAF50;
            background: white;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.15);
        }

        .form-input:hover:not(:focus) {
            border-color: #cbd5e1;
        }

        .form-input.input-error {
            border-color: #dc3545;
            background: #fef2f2;
        }

        .form-input.input-error:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15);
        }

        /* Number Input Styling */
        .form-input[type="number"] {
            -moz-appearance: textfield;
        }

        .form-input[type="number"]::-webkit-outer-spin-button,
        .form-input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Stock Inputs Grid */
        .stock-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stock-group {
            position: relative;
        }

        .stock-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.25rem;
        }

        .stock-input-total .form-input {
            border-color: #007bff;
            background: rgba(0, 123, 255, 0.05);
        }

        .stock-input-total .form-input:focus {
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
        }

        .stock-input-available .form-input {
            border-color: #4CAF50;
            background: rgba(76, 175, 80, 0.05);
        }

        .stock-input-available .form-input:focus {
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.15);
        }

        .stock-input-damaged .form-input {
            border-color: #dc3545;
            background: rgba(220, 53, 69, 0.05);
        }

        .stock-input-damaged .form-input:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15);
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #f1f5f9;
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.9375rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(107, 114, 128, 0.3);
        }

        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
            color: white;
        }

        /* Helper Text */
        .form-helper {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 0.375rem;
        }

        /* ==================== RESPONSIVE ==================== */

        @media (max-width: 768px) {
            .form-container {
                padding: 1rem 0.75rem;
            }

            .form-card {
                padding: 1.25rem;
            }

            .form-title {
                font-size: 1.25rem;
            }

            .stock-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn-submit,
            .btn-cancel {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .form-card {
                padding: 1rem;
                border-radius: 8px;
            }

            .form-title {
                font-size: 1.125rem;
            }

            .form-input {
                padding: 0.625rem 0.875rem;
                font-size: 0.875rem;
            }

            .btn-submit,
            .btn-cancel {
                padding: 0.625rem 1rem;
                font-size: 0.875rem;
            }
        }
    </style>
    <div class="form-container">
        <div class="form-card">
            <h2 class="form-title">Edit Item: {{ $inventory->name }}</h2>

            <form action="{{ route('inventories.update', $inventory->id) }}" method="POST">
                @csrf
                @method('PATCH')

                @if ($errors->any())
                <div class="error-alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="form-group">
                    <label for="name" class="form-label">Nama Barang <span>*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $inventory->name) }}" required
                        class="form-input @error('name') input-error @enderror">
                </div>

                <div class="stock-grid">
                    <div class="form-group stock-input-total">
                        <label for="total_stock" class="form-label">📊 Total Stok</label>
                        <input type="number" name="total_stock" id="total_stock" value="{{ old('total_stock', $inventory->total_stock) }}" required
                            class="form-input" min="0">
                    </div>

                    <div class="form-group stock-input-available">
                        <label for="available_stock" class="form-label">✅ Tersedia</label>
                        <input type="number" name="available_stock" id="available_stock" value="{{ old('available_stock', $inventory->available_stock) }}" required
                            class="form-input" min="0">
                    </div>

                    <div class="form-group stock-input-damaged">
                        <label for="damaged_stock" class="form-label">⚠️ Rusak</label>
                        <input type="number" name="damaged_stock" id="damaged_stock" value="{{ old('damaged_stock', $inventory->damaged_stock) }}" required
                            class="form-input" min="0">
                    </div>
                </div>
                <p class="form-helper">* Stok Tersedia + Rusak = Total Stok</p>

                <div class="form-actions">
                    <a href="{{ route('inventories.index') }}" class="btn-cancel">← Batal</a>
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Script sinkronisasi sama seperti create
        document.addEventListener('DOMContentLoaded', function() {
            const totalStockInput = document.getElementById('total_stock');
            const availableStockInput = document.getElementById('available_stock');
            const damagedStockInput = document.getElementById('damaged_stock');

            function syncStock(changedInput) {
                let total = parseInt(totalStockInput.value) || 0;
                let available = parseInt(availableStockInput.value) || 0;
                let damaged = parseInt(damagedStockInput.value) || 0;

                if (total < 0) total = 0;
                if (available < 0) available = 0;
                if (damaged < 0) damaged = 0;

                if (changedInput === damagedStockInput) {
                    let newAvailable = total - damaged;
                    if (newAvailable < 0) {
                        damagedStockInput.value = total;
                        availableStockInput.value = 0;
                    } else {
                        availableStockInput.value = newAvailable;
                    }
                } else if (changedInput === availableStockInput) {
                    let newDamaged = total - available;
                    if (newDamaged < 0) {
                        availableStockInput.value = total;
                        damagedStockInput.value = 0;
                    } else {
                        damagedStockInput.value = newDamaged;
                    }
                } else if (changedInput === totalStockInput) {
                    let newDamaged = total - available;
                    if (newDamaged < 0) {
                        availableStockInput.value = total;
                        damagedStockInput.value = 0;
                    } else {
                        damagedStockInput.value = newDamaged;
                    }
                }
            }

            damagedStockInput.addEventListener('input', () => syncStock(damagedStockInput));
            availableStockInput.addEventListener('input', () => syncStock(availableStockInput));
            totalStockInput.addEventListener('input', () => syncStock(totalStockInput));
        });
    </script>
</x-app-layout>