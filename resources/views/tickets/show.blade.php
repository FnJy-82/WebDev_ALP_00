<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12 flex items-center justify-center px-4">
        
        {{-- TICKET CARD --}}
        <div class="max-w-sm w-full bg-white rounded-3xl overflow-hidden shadow-2xl relative">
            
            {{-- PUNCH HOLES (Hiasan) --}}
            <div class="absolute left-0 top-1/3 w-6 h-6 bg-gray-900 rounded-full -ml-3 z-10"></div>
            <div class="absolute right-0 top-1/3 w-6 h-6 bg-gray-900 rounded-full -mr-3 z-10"></div>

            {{-- HEADER --}}
            <div class="bg-indigo-600 p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>
                <h2 class="text-xl font-bold leading-tight mb-2">{{ $ticket->event->title }}</h2>
                <p class="text-indigo-100 text-sm">{{ \Carbon\Carbon::parse($ticket->event->start_time)->format('d F Y, H:i') }}</p>
            </div>

            {{-- BODY --}}
            <div class="p-8 pt-10 bg-white">
                <div class="flex justify-between items-center mb-8">
                    <div class="text-center">
                        <p class="text-xs text-gray-400 uppercase tracking-widest">Kursi</p>
                        <p class="text-3xl font-black text-gray-800">{{ $ticket->seat_number }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-400 uppercase tracking-widest">Gate</p>
                        <p class="text-3xl font-black text-gray-800">1</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-400 uppercase tracking-widest">Status</p>
                        <span class="text-sm font-bold text-green-600 uppercase">{{ $ticket->status }}</span>
                    </div>
                </div>

                <div class="border-t-2 border-dashed border-gray-200 mb-8 -mx-8"></div>

                {{-- IDENTITY & QR --}}
                <div class="flex flex-col items-center space-y-6">
                    {{-- Foto Wajah --}}
                    <div class="relative">
                        <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full blur opacity-30"></div>
                        <img src="{{ asset('storage/'.$ticket->face_photo_path) }}" class="relative w-20 h-20 rounded-full border-4 border-white shadow-lg object-cover">
                    </div>
                    
                    <div class="text-center">
                        <p class="font-bold text-gray-900 text-lg">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-400">{{ Auth::user()->identity_number }}</p>
                    </div>

                    {{-- Dynamic QR --}}
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 w-full flex flex-col items-center">
                        <div id="qrcode" class="mix-blend-multiply"></div>
                        <p class="mt-3 text-xs text-red-500 font-bold animate-pulse">
                            Refresh dalam <span id="timer">30</span>s
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script QR --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        const ticketId = "{{ $ticket->id }}";
        const qrContainer = document.getElementById('qrcode');
        let timeLeft = 30;

        function generateQR() {
            qrContainer.innerHTML = "";
            // Logic: TicketID + Timestamp sekarang (Server akan memvalidasi window time ini)
            const secureData = `${ticketId}|${new Date().getTime()}`; 
            new QRCode(qrContainer, { text: secureData, width: 140, height: 140 });
            timeLeft = 30;
        }

        generateQR();
        setInterval(generateQR, 30000); // Ganti tiap 30 detik
        setInterval(() => { timeLeft--; document.getElementById('timer').innerText = timeLeft; }, 1000);
    </script>
</x-app-layout>