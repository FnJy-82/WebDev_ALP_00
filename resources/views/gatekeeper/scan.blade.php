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

        {{-- HASIL SCAN --}}
        <div id="result-area" class="hidden w-full max-w-sm mt-6 bg-gray-800 rounded-2xl p-6 border border-gray-700 shadow-xl text-center">
            <img id="user-face" src="" class="w-24 h-24 rounded-full border-4 border-gray-600 object-cover mx-auto mb-4">
            <h3 id="user-name" class="text-lg font-bold text-white">Guest Name</h3>
            <p id="seat-info" class="text-indigo-400 font-mono text-sm mt-1 mb-4"></p>
            
            <div id="status-box" class="py-3 rounded-lg font-bold text-lg uppercase tracking-wide"></div>

            <button onclick="location.reload()" class="mt-4 w-full bg-gray-700 hover:bg-gray-600 text-gray-200 py-3 rounded-xl font-semibold transition">
                Scan Next
            </button>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        function onScanSuccess(decodedText) {
            html5QrcodeScanner.clear();
            fetch('{{ route("gatekeeper.checkin") }}', {
                method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ qr_data: decodedText })
            }).then(r => r.json()).then(data => {
                document.getElementById('result-area').classList.remove('hidden');
                document.getElementById('user-name').innerText = data.user_name || 'Unknown';
                document.getElementById('seat-info').innerText = 'SEAT: ' + (data.seat || '-');
                document.getElementById('user-face').src = data.face_image || '';
                
                const box = document.getElementById('status-box');
                if(data.status === 'success') {
                    box.innerText = "ACCESS GRANTED";
                    box.className = "py-3 rounded-lg font-bold text-lg uppercase tracking-wide bg-green-500/20 text-green-400 border border-green-500/50";
                } else {
                    box.innerText = "ACCESS DENIED";
                    box.className = "py-3 rounded-lg font-bold text-lg uppercase tracking-wide bg-red-500/20 text-red-400 border border-red-500/50";
                }
            });
        }
        let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: {width: 250, height: 250} }, false);
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</x-app-layout>