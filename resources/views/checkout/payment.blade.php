<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Selesaikan Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                
                <h3 class="text-lg font-bold mb-4">Total Tagihan: Rp {{ number_format($transaction->total_amount) }}</h3>
                <p class="text-gray-500 mb-6">Booking Code: {{ $transaction->midtrans_booking_code }}</p>

                {{-- Tombol Bayar --}}
                <button id="pay-button" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-full hover:bg-indigo-700 transition">
                    BAYAR SEKARANG
                </button>

            </div>
        </div>
    </div>

    {{-- SCRIPT MIDTRANS (Wajib Ada) --}}
    {{-- Kalau Production, ganti url jadi https://app.midtrans.com/snap/snap.js --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // SnapToken diambil dari controller
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    // Redirect jika sukses
                    window.location.href = "{{ route('tickets.index') }}";
                },
                onPending: function(result){
                    alert("Menunggu pembayaran!");
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                },
                onClose: function(){
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        };
    </script>
</x-app-layout>