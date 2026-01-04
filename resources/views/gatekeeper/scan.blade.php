<x-app-layout>
    <div class="min-h-screen bg-black text-white flex flex-col items-center pt-8 px-4">
        
        <h2 class="text-xl font-bold text-center mb-6 text-gray-200">Gatekeeper Scanner</h2>

        {{-- SCANNER --}}
        <div class="relative w-full max-w-sm aspect-square bg-gray-900 rounded-3xl overflow-hidden border border-gray-700 shadow-2xl">
            <div id="reader" class="w-full h-full object-cover"></div>
            {{-- Viewfinder Overlay --}}
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="w-64 h-64 border-2 border-indigo-500 rounded-lg opacity-50"></div>
            </div>
        </div>

        {{-- Message Area (Optional) --}}
        <p class="mt-8 text-gray-500 text-sm text-center">
            Arahkan kamera ke QR Code Tiket.<br>
            Sistem akan otomatis memverifikasi.
        </p>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        function onScanSuccess(decodedText) {
            // 1. Stop Camera
            html5QrcodeScanner.clear();
            
            // 2. Parse Data (Format: UUID|TIMESTAMP)
            let parts = decodedText.split('|');
            let uuid = parts[0];

            // 3. Redirect to Verify Page
            window.location.href = "/gatekeeper/verify/" + uuid;
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { fps: 10, qrbox: {width: 250, height: 250} }, 
            false
        );
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</x-app-layout>