<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Checkout Tiket</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KOLOM KIRI: Layout Kursi --}}
                <div class="lg:col-span-2">
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-gray-800">Pilih Kursi</h2>
                            <div class="flex space-x-3 text-xs font-semibold">
                                <div class="flex items-center"><span class="w-3 h-3 bg-white border-2 border-gray-300 rounded mr-1"></span> Tersedia</div>
                                <div class="flex items-center"><span class="w-3 h-3 bg-indigo-600 rounded mr-1"></span> Dipilih</div>
                                <div class="flex items-center"><span class="w-3 h-3 bg-gray-200 rounded mr-1"></span> Terjual</div>
                            </div>
                        </div>

                        {{-- STAGE --}}
                        <div class="w-full h-12 bg-gray-100 rounded-t-full mb-10 flex items-center justify-center border-t border-l border-r border-gray-200 shadow-inner">
                            <span class="text-gray-400 font-bold tracking-[0.5em] text-xs uppercase">STAGE / SCREEN</span>
                        </div>
                        
                        {{-- SEAT GRID --}}
                        <div class="grid grid-cols-10 gap-2 sm:gap-3 justify-items-center mb-6">
                            @for ($i = 1; $i <= 50; $i++)
                                <button type="button" 
                                    class="seat-btn w-8 h-8 sm:w-10 sm:h-10 rounded-lg text-xs font-bold transition-all duration-200 
                                    {{ $i % 7 == 0 ? 'bg-gray-100 text-gray-300 cursor-not-allowed' : 'bg-white border-2 border-gray-200 text-gray-600 hover:border-indigo-500 hover:text-indigo-600' }}"
                                    {{ $i % 7 == 0 ? 'disabled' : '' }}
                                    onclick="selectSeat(this, 'A{{ $i }}')">
                                    A{{ $i }}
                                </button>
                            @endfor
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Form & Camera --}}
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-3xl shadow-lg border border-indigo-50 sticky top-6">
                        
                        {{-- Event Info --}}
                        <div class="flex gap-4 mb-6 pb-6 border-b border-gray-100">
                            <img src="{{ $event->banner_image }}" class="w-20 h-20 rounded-xl object-cover shadow-sm">
                            <div>
                                <h3 class="font-bold text-gray-900 line-clamp-1">{{ $event->title }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($event->start_time)->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $event->venue->name }}</p>
                            </div>
                        </div>

                        <form action="{{ route('transaction.store') }}" method="POST" id="checkoutForm">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            <input type="hidden" name="seat_number" id="selected_seat">
                            <input type="hidden" name="face_image" id="face_image_base64">

                            {{-- Summary --}}
                            <div class="space-y-3 mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Nomor Kursi</span>
                                    <span id="seat_display" class="font-bold text-indigo-600">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Harga Tiket</span>
                                    <span class="font-bold text-gray-900">Rp 150.000</span>
                                </div>
                                <div class="pt-2 border-t border-gray-200 flex justify-between items-center">
                                    <span class="font-bold text-gray-900">Total</span>
                                    <span class="text-lg font-extrabold text-indigo-600">Rp 150.000</span>
                                </div>
                            </div>

                            {{-- FACE LOCK CAMERA --}}
                            <div class="mb-6">
                                <label class="block font-bold text-xs text-gray-700 uppercase tracking-wide mb-2">
                                    Selfie Verification (Face-Lock)
                                </label>
                                
                                <div class="relative bg-black rounded-xl overflow-hidden h-48 w-full mb-3 group shadow-inner">
                                    <video id="video" class="absolute inset-0 w-full h-full object-cover opacity-90 group-hover:opacity-100 transition" autoplay playsinline></video>
                                    <canvas id="canvas" class="hidden absolute inset-0 w-full h-full object-cover"></canvas>
                                    {{-- Overlay --}}
                                    <div class="absolute inset-0 border-2 border-indigo-500/30 m-6 rounded-lg pointer-events-none"></div>
                                </div>
                                
                                <div class="flex gap-2">
                                    <button type="button" id="snap" class="flex-1 bg-white border border-gray-300 text-gray-700 py-2 rounded-lg font-bold text-xs hover:bg-gray-50 transition">
                                        Ambil Foto
                                    </button>
                                    <button type="button" id="retake" class="hidden flex-1 bg-red-50 text-red-600 border border-red-100 py-2 rounded-lg font-bold text-xs hover:bg-red-100 transition">
                                        Ulangi
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-start mb-6">
                                <input id="consent" name="consent" type="checkbox" required class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="consent" class="ml-2 text-xs text-gray-500 leading-tight">
                                    Saya setuju wajah saya digunakan untuk validasi tiket saat masuk venue.
                                </label>
                            </div>

                            <button type="submit" id="pay_button" disabled class="w-full bg-gray-300 text-gray-500 py-3 rounded-xl font-bold text-lg transition-all duration-300 cursor-not-allowed">
                                Bayar & Amankan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Logic Kursi
        function selectSeat(btn, seatNum) {
            document.querySelectorAll('.seat-btn:not(:disabled)').forEach(b => {
                b.classList.remove('bg-indigo-600', 'text-white', 'border-transparent', 'shadow-md', 'scale-110');
                b.classList.add('bg-white', 'border-gray-200', 'text-gray-600');
            });
            btn.classList.remove('bg-white', 'border-gray-200', 'text-gray-600');
            btn.classList.add('bg-indigo-600', 'text-white', 'border-transparent', 'shadow-md', 'scale-110');
            
            document.getElementById('selected_seat').value = seatNum;
            document.getElementById('seat_display').innerText = seatNum;
            checkFormValidity();
        }

        // Logic Kamera
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const snap = document.getElementById('snap');
        const retake = document.getElementById('retake');
        const faceInput = document.getElementById('face_image_base64');
        let isPhotoTaken = false;

        navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } })
            .then(stream => { video.srcObject = stream; })
            .catch(err => { console.error("Camera Error", err); });

        snap.addEventListener("click", function() {
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            canvas.classList.remove('hidden'); video.classList.add('hidden');
            faceInput.value = canvas.toDataURL('image/jpeg');
            snap.classList.add('hidden'); retake.classList.remove('hidden');
            isPhotoTaken = true; checkFormValidity();
        });

        retake.addEventListener("click", function() {
            canvas.classList.add('hidden'); video.classList.remove('hidden');
            faceInput.value = '';
            snap.classList.remove('hidden'); retake.classList.add('hidden');
            isPhotoTaken = false; checkFormValidity();
        });

        // Validasi Akhir
        function checkFormValidity() {
            const seat = document.getElementById('selected_seat').value;
            const consent = document.getElementById('consent').checked;
            const btn = document.getElementById('pay_button');
            
            if (seat && isPhotoTaken && consent) {
                btn.disabled = false;
                btn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
                btn.classList.add('bg-indigo-600', 'text-white', 'hover:bg-indigo-700', 'shadow-lg');
            } else {
                btn.disabled = true;
                btn.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
                btn.classList.remove('bg-indigo-600', 'text-white', 'hover:bg-indigo-700', 'shadow-lg');
            }
        }
        document.getElementById('consent').addEventListener('change', checkFormValidity);
    </script>
</x-app-layout>