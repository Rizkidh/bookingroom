<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/inventory.css') }}">

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    Tambah Unit untuk: <strong>{{ $inventory->name }}</strong>
                </h2>

                <form action="{{ route('inventories.units.store', $inventory->id) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    {{-- ERROR --}}
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- SCAN BARCODE --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Scan Barcode (HP)
                        </label>

                        <button type="button"
                                id="startScanBtn"
                                class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded mb-2">
                            Mulai Scan
                        </button>

                        <button type="button"
                                id="stopScanBtn"
                                class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded mb-2 hidden">
                            Hentikan Scan
                        </button>

                        <div id="reader"
                             class="border rounded p-2 mt-2"
                             style="max-width:320px; display:none;"></div>

                        <small class="text-gray-500 block mt-1">
                            Klik tombol scan untuk mengaktifkan kamera
                        </small>
                    </div>

                    {{-- SERIAL NUMBER --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Nomor Serial / Barcode
                        </label>

                        <input type="text"
                               name="serial_number"
                               id="serial_number"
                               value="{{ old('serial_number') }}"
                               class="border rounded w-full p-2"
                               placeholder="Scan barcode atau ketik manual">
                    </div>

                    {{-- FOTO --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Foto Unit (Opsional)
                        </label>
                        <input type="file" name="photo" class="border rounded w-full p-2">
                    </div>

                    {{-- KONDISI --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Status Kondisi
                        </label>
                        <select name="condition_status" required class="border rounded w-full p-2">
                            <option value="available">Tersedia</option>
                            <option value="in_use">Sedang Dipakai</option>
                            <option value="maintenance">Perawatan</option>
                            <option value="damaged">Rusak</option>
                        </select>
                    </div>

                    {{-- PEMEGANG --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Pemegang Saat Ini (Opsional)
                        </label>
                        <input type="text" name="current_holder" class="border rounded w-full p-2">
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex justify-between">
                        <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Simpan Unit
                        </button>

                        <a href="{{ route('inventories.show', $inventory->id) }}"
                           class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- LIBRARY --}}
    <script src="https://unpkg.com/html5-qrcode"></script>

    {{-- SCRIPT --}}
    <script>
        let html5QrCode;
        const reader = document.getElementById('reader');
        const serialInput = document.getElementById('serial_number');
        const startBtn = document.getElementById('startScanBtn');
        const stopBtn = document.getElementById('stopScanBtn');

        startBtn.addEventListener('click', () => {
            reader.style.display = 'block';
            startBtn.classList.add('hidden');
            stopBtn.classList.remove('hidden');

            html5QrCode = new Html5Qrcode("reader");

            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                (decodedText) => {
                    serialInput.value = decodedText;
                    stopScan();
                }
            );
        });

        stopBtn.addEventListener('click', stopScan);

        function stopScan() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    reader.style.display = 'none';
                    startBtn.classList.remove('hidden');
                    stopBtn.classList.add('hidden');
                });
            }
        }
    </script>
</x-app-layout>
