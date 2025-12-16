<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/inventory.css') }}">
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
