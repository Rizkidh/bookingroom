<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">
                    📱 Scan Barcode/QR Code
                </h2>

                {{-- Success Message --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Error Message --}}
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Video Scanner Area --}}
                <div class="bg-gray-100 rounded-lg p-4 mb-6">
                    <div id="reader" class="mb-4"></div>
                    
                    <div class="flex gap-2 justify-center">
                        <button type="button" 
                                id="startScanBtn"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            🎬 Mulai Scan
                        </button>

                        <button type="button" 
                                id="stopScanBtn"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded hidden">
                            ⏹️ Hentikan
                        </button>

                        <button type="button"
                                id="switchCameraBtn"
                                class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded hidden">
                            🔄 Tukar Kamera
                        </button>
                    </div>

                    <p class="text-gray-500 text-center text-sm mt-3">
                        Arahkan kamera ke QR code atau barcode unit
                    </p>
                </div>

                {{-- Manual Input Fallback --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">
                        Atau Masukkan Manual
                    </h3>

                    <form action="{{ route('units.process-scan') }}" method="POST" class="flex gap-2">
                        @csrf

                        <input type="text"
                               name="barcode"
                               id="manualBarcode"
                               class="flex-1 border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Scan atau ketik ID unit (contoh: 00001)"
                               autofocus>

                        <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                            Cari
                        </button>
                    </form>

                    @error('barcode')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Information Card --}}
                <div class="bg-blue-50 rounded-lg p-4 mt-6 border border-blue-200">
                    <h3 class="text-sm font-semibold text-blue-900 mb-2">💡 Tips:</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>✓ Pastikan cahaya cukup untuk scan yang lebih baik</li>
                        <li>✓ Arahkan kamera ke QR code secara tegak lurus</li>
                        <li>✓ QR code akan ter-scan otomatis dan menampilkan detail unit</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    {{-- QR Code Scanner Library --}}
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        let html5QrCode;
        let isScanning = false;
        const reader = document.getElementById('reader');
        const startBtn = document.getElementById('startScanBtn');
        const stopBtn = document.getElementById('stopScanBtn');
        const switchCameraBtn = document.getElementById('switchCameraBtn');
        const manualInput = document.getElementById('manualBarcode');

        // Start scanning when button clicked
        startBtn.addEventListener('click', startScanning);
        stopBtn.addEventListener('click', stopScanning);
        switchCameraBtn.addEventListener('click', switchCamera);

        function startScanning() {
            reader.style.display = 'block';
            startBtn.classList.add('hidden');
            stopBtn.classList.remove('hidden');
            switchCameraBtn.classList.remove('hidden');

            html5QrCode = new Html5Qrcode("reader");
            isScanning = true;

            html5QrCode.start(
                { facingMode: "environment" },
                { 
                    fps: 10, 
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                },
                onScanSuccess,
                onScanError
            );
        }

        function stopScanning() {
            if (html5QrCode && isScanning) {
                html5QrCode.stop().then(() => {
                    reader.style.display = 'none';
                    startBtn.classList.remove('hidden');
                    stopBtn.classList.add('hidden');
                    switchCameraBtn.classList.add('hidden');
                    isScanning = false;
                });
            }
        }

        function switchCamera() {
            if (html5QrCode && isScanning) {
                html5QrCode.stop().then(() => {
                    const newFacingMode = 
                        switchCameraBtn.dataset.facingMode === 'user' 
                            ? 'environment' 
                            : 'user';
                    
                    switchCameraBtn.dataset.facingMode = newFacingMode;

                    html5QrCode.start(
                        { facingMode: newFacingMode },
                        { 
                            fps: 10, 
                            qrbox: { width: 250, height: 250 },
                            aspectRatio: 1.0
                        },
                        onScanSuccess,
                        onScanError
                    );
                });
            }
        }

        function onScanSuccess(decodedText) {
            // Stop scanning
            stopScanning();

            // Set manual input value dan submit
            manualInput.value = decodedText;
            
            // Highlight success
            manualInput.classList.add('border-green-500', 'bg-green-50');
            
            // Submit form after 500ms
            setTimeout(() => {
                manualInput.closest('form').submit();
            }, 500);
        }

        function onScanError(errorMessage) {
            // Silently handle error - no need to log every frame
            // console.log(errorMessage);
        }

        // Set default facing mode
        switchCameraBtn.dataset.facingMode = 'environment';
    </script>
</x-app-layout>
