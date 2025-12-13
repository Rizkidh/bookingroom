<x-app-layout>
    <style>
        /* ========================================
   UNIT FORMS (CREATE & EDIT) STYLES
   ======================================== */

        .unit-form-container {
            padding: 3rem 1rem;
            max-width: 56rem;
            margin: 0 auto;
        }

        .unit-form-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 1rem;
            box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            animation: fadeIn 0.4s ease-out;
        }

        .unit-form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .unit-form-title strong {
            color: #6366f1;
        }

        /* Error Alert */
        .unit-error-alert {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 1px solid #fca5a5;
            border-left: 4px solid #ef4444;
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .unit-error-alert ul {
            margin: 0;
            padding-left: 1.25rem;
            color: #dc2626;
            font-size: 0.875rem;
        }

        /* Form Groups */
        .unit-form-group {
            margin-bottom: 1.25rem;
        }

        .unit-form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .unit-form-input,
        .unit-form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            color: #1e293b;
            background-color: #fff;
            transition: all 0.2s ease;
        }

        .unit-form-input:focus,
        .unit-form-select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .unit-form-input.input-error,
        .unit-form-select.input-error {
            border-color: #ef4444;
        }

        .unit-input-hint {
            font-size: 0.75rem;
            color: #ef4444;
            margin-top: 0.25rem;
            font-style: italic;
        }

        /* File Upload Styling */
        .unit-file-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px dashed #cbd5e1;
            border-radius: 0.5rem;
            background-color: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .unit-file-input:hover {
            border-color: #6366f1;
            background-color: #f1f5f9;
        }

        .unit-file-input:focus {
            outline: none;
            border-color: #6366f1;
            border-style: solid;
        }

        /* Photo Section (Edit Form) */
        .unit-photo-section {
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1.25rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            margin-bottom: 1.25rem;
        }

        .unit-current-photo {
            margin-bottom: 1rem;
        }

        .unit-current-photo-label {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .unit-current-photo img {
            width: 6rem;
            height: 6rem;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 2px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .unit-remove-photo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background-color: #fef2f2;
            border-radius: 0.375rem;
        }

        .unit-remove-photo input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            accent-color: #ef4444;
        }

        .unit-remove-photo label {
            font-size: 0.875rem;
            color: #dc2626;
            font-weight: 500;
        }

        /* Status Select with Colors */
        .unit-status-select option[value="available"] {
            color: #16a34a;
        }

        .unit-status-select option[value="in_use"] {
            color: #ca8a04;
        }

        .unit-status-select option[value="damaged"] {
            color: #dc2626;
        }

        .unit-status-select option[value="maintenance"] {
            color: #ea580c;
        }

        /* Form Actions */
        .unit-form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e2e8f0;
        }

        .unit-btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            font-weight: 600;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
        }

        .unit-btn-submit:hover {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
        }

        .unit-btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            font-weight: 600;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .unit-btn-cancel:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        /* ========================================
   UNIT DETAIL/SHOW PAGE STYLES
   ======================================== */

        .unit-show-container {
            padding: 3rem 1rem;
            max-width: 56rem;
            margin: 0 auto;
        }

        .unit-show-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 1rem;
            box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            animation: fadeIn 0.4s ease-out;
        }

        .unit-show-header {
            margin-bottom: 0.5rem;
        }

        .unit-show-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .unit-show-subtitle {
            font-size: 0.95rem;
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        .unit-show-subtitle a {
            color: #6366f1;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .unit-show-subtitle a:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        /* Grid Layout */
        .unit-show-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        /* Photo Column */
        .unit-photo-column {
            grid-column: span 1;
        }

        .unit-section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #374151;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .unit-photo-display {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 0.75rem;
            border: 2px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .unit-no-photo {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            padding: 3rem;
            border-radius: 0.75rem;
            text-align: center;
            color: #64748b;
            border: 2px dashed #cbd5e1;
        }

        .unit-no-photo-icon {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            opacity: 0.5;
        }

        /* Info Column */
        .unit-info-column {
            grid-column: span 1;
        }

        .unit-info-item {
            margin-bottom: 1.25rem;
        }

        .unit-info-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .unit-info-value {
            font-size: 1rem;
            color: #1e293b;
            font-weight: 600;
        }

        .unit-serial-value {
            font-family: 'Courier New', monospace;
            background-color: #f1f5f9;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.95rem;
        }

        /* Status Badges */
        .unit-status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.875rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 9999px;
        }

        .unit-status-available {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
            border: 1px solid #86efac;
        }

        .unit-status-in-use {
            background: linear-gradient(135deg, #fef9c3 0%, #fef08a 100%);
            color: #854d0e;
            border: 1px solid #fde047;
        }

        .unit-status-damaged,
        .unit-status-maintenance {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /* Actions Section */
        .unit-show-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e2e8f0;
        }

        .unit-btn-edit {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            font-weight: 600;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .unit-btn-edit:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-1px);
        }

        .unit-btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            color: white;
            font-weight: 600;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(100, 116, 139, 0.3);
        }

        .unit-btn-back:hover {
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
            transform: translateY(-1px);
        }

        /* Animation */
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

        /* ========================================
   RESPONSIVE STYLES
   ======================================== */

        @media (max-width: 768px) {

            .unit-form-container,
            .unit-show-container {
                padding: 1.5rem 1rem;
            }

            .unit-form-card,
            .unit-show-card {
                padding: 1.5rem;
            }

            .unit-form-title,
            .unit-show-title {
                font-size: 1.25rem;
            }

            .unit-show-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .unit-photo-column,
            .unit-info-column {
                grid-column: span 1;
            }

            .unit-form-actions {
                flex-direction: column;
                gap: 0.75rem;
            }

            .unit-btn-submit,
            .unit-btn-cancel {
                width: 100%;
                justify-content: center;
            }

            .unit-show-actions {
                flex-direction: column;
            }

            .unit-btn-edit,
            .unit-btn-back {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {

            .unit-form-card,
            .unit-show-card {
                padding: 1rem;
                border-radius: 0.75rem;
            }

            .unit-current-photo img {
                width: 5rem;
                height: 5rem;
            }
        }
    </style>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Tambah Unit untuk: **{{ $inventory->name }}**</h2>

                <form action="{{ route('inventories.units.store', $inventory->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mb-4">
                        <label for="serial_number" class="block text-gray-700 text-sm font-bold mb-2">Nomor Serial / ID Unit (Opsional):</label>
                        <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('serial_number') border-red-500 @enderror">
                        @error('serial_number') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="photo" class="block text-gray-700 text-sm font-bold mb-2">Foto Unit (Opsional):</label>
                        <input type="file" name="photo" id="photo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('photo') border-red-500 @enderror">
                        @error('photo')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="condition_status" class="block text-gray-700 text-sm font-bold mb-2">Status Kondisi:</label>
                        <select name="condition_status" id="condition_status" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('condition_status') border-red-500 @enderror">
                            <option value="available" {{ old('condition_status') == 'available' ? 'selected' : '' }}>Tersedia (Available)</option>
                            <option value="in_use" {{ old('condition_status') == 'in_use' ? 'selected' : '' }}>Sedang Dipakai (In Use)</option>
                            <option value="damaged" {{ old('condition_status') == 'damaged' ? 'selected' : '' }}>Rusak (Damaged)</option>
                            <option value="maintenance" {{ old('condition_status') == 'maintenance' ? 'selected' : '' }}>Perawatan (Maintenance)</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="current_holder" class="block text-gray-700 text-sm font-bold mb-2">Pemegang Saat Ini (Opsional):</label>
                        <input type="text" name="current_holder" id="current_holder" value="{{ old('current_holder') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('current_holder') border-red-500 @enderror">
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Simpan Unit
                        </button>
                        <a href="{{ route('inventories.show', $inventory->id) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>