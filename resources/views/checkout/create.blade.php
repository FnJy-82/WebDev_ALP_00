<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Checkout Tiket</h1>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada form:</h3>
                            <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2">
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-gray-800">Pilih Kursi</h2>
                        </div>

                        <div class="mb-6 space-y-8">
                            @foreach ($event->ticketCategories as $category)
                                <div class="border-b pb-4">
                                    <h3 class="font-bold text-lg text-gray-800">{{ $category->name }}</h3>
                                    <p class="text-sm text-gray-500">Rp
                                        {{ number_format($category->price, 0, ',', '.') }}</p>
                                </div>

                                <div class="overflow-x-auto py-4">
                                    <div class="inline-flex flex-col gap-3 min-w-full">
                                        @foreach ($category->tickets->groupBy('row_label') as $rowLabel => $ticketsInRow)
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="w-8 text-sm font-bold text-gray-400 text-right mr-2 sticky left-0 bg-white">
                                                    {{ $rowLabel }}
                                                </div>

                                                @foreach ($ticketsInRow as $ticket)
                                                    @php
                                                        $isSold =
                                                            $ticket->status === 'sold' || $ticket->status === 'booked';
                                                        $isSelected = old('seat_number') == $ticket->seat_number;

                                                        $baseClass =
                                                            'w-10 h-10 rounded-lg text-[10px] font-bold transition-all duration-200 flex items-center justify-center border-2';

                                                        if ($isSold) {
                                                            $styleClass =
                                                                'bg-gray-200 border-gray-200 text-gray-400 cursor-not-allowed';
                                                        } elseif ($isSelected) {
                                                            $styleClass =
                                                                'bg-indigo-600 text-white border-transparent shadow-md scale-110';
                                                        } else {
                                                            $styleClass =
                                                                'seat-btn bg-white border-gray-200 text-gray-600 hover:border-indigo-500 hover:text-indigo-600 cursor-pointer';
                                                        }
                                                    @endphp

                                                    <button type="button"
                                                        class="{{ $baseClass }} {{ $styleClass }}"
                                                        {{ $isSold ? 'disabled' : '' }}
                                                        onclick="selectSeat(this, '{{ $ticket->seat_number }}', {{ $category->price }})">
                                                        {{ explode('-', $ticket->seat_number)[1] ?? $ticket->seat_number }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>


                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-3xl shadow-lg border border-indigo-50 sticky top-6">

                        <div class="flex gap-4 mb-6 pb-6 border-b border-gray-100">
                            <img src="{{ $event->banner_image }}" class="w-20 h-20 rounded-xl object-cover shadow-sm">
                            <div>
                                <h3 class="font-bold text-gray-900 line-clamp-1">{{ $event->title }}</h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($event->start_time)->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        <form action="{{ route('transaction.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            <input type="hidden" name="seat_number" id="selected_seat"
                                value="{{ old('seat_number') }}" required>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700">NIK (Nomor Induk
                                    Kependudukan)</label>
                                <input type="text" name="identity_number"
                                    value="{{ old('identity_number', Auth::user()->identity_number) }}" required
                                    minlength="16" maxlength="16"
                                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3 @error('identity_number') border-red-500 @enderror">
                                @error('identity_number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-3 mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Nomor Kursi</span>
                                    <span id="seat_display"
                                        class="font-bold text-indigo-600">{{ old('seat_number', '-') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Estimasi Harga</span>
                                    <span id="price_display" class="font-bold text-gray-900">-</span>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block font-bold text-xs text-gray-700 uppercase tracking-wide mb-2">
                                    Upload Foto Wajah (Verifikasi)
                                </label>
                                <input type="file" name="face_image" accept="image/*" required
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                                <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG. Max 4MB.</p>
                                @error('face_image')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-start mb-6">
                                <input id="consent" name="consent" type="checkbox" required
                                    class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="consent" class="ml-2 text-xs text-gray-500 leading-tight">
                                    Saya setuju wajah saya digunakan untuk validasi tiket.
                                </label>
                            </div>

                            <button type="submit"
                                class="w-full bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg py-3 rounded-xl font-bold text-lg transition-all duration-300">
                                Bayar & Amankan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectSeat(btn, seatNum, price) {
            document.querySelectorAll('.seat-btn').forEach(b => {
                if (!b.hasAttribute('disabled')) {
                    b.className =
                        'seat-btn w-10 h-10 rounded-lg text-[10px] font-bold transition-all duration-200 flex items-center justify-center border-2 bg-white border-gray-200 text-gray-600 hover:border-indigo-500 hover:text-indigo-600 cursor-pointer';
                }
            });

            btn.className =
                'w-10 h-10 rounded-lg text-[10px] font-bold transition-all duration-200 flex items-center justify-center border-2 bg-indigo-600 text-white border-transparent shadow-md scale-110';

            document.getElementById('selected_seat').value = seatNum;

            const formattedPrice = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(price);

            document.getElementById('seat_display').innerText = seatNum;
            document.getElementById('price_display').innerText = formattedPrice;
        }
    </script>
</x-app-layout>
