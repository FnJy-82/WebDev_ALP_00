<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">

                {{-- Banner Image --}}
                <div class="relative h-64 md:h-96 w-full">
                    <img src="{{ $event->banner_image }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                        <div class="p-6 text-white w-full">
                            <div class="flex justify-between items-end">
                                <div>
                                    <span
                                        class="bg-blue-600 text-xs font-bold px-2 py-1 rounded uppercase tracking-wide">
                                        {{ $event->status }}
                                    </span>
                                    <h1 class="text-3xl md:text-4xl font-bold mt-2">{{ $event->title }}</h1>
                                    <p class="text-gray-200 mt-1 flex items-center">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $event->venue->name ?? 'Venue TBA' }}
                                    </p>
                                </div>
                                <div class="hidden md:block text-right">
                                    <p class="text-sm text-gray-300">Kategori Event</p>

                                    <p class="text-3xl font-bold text-yellow-400">
                                        {{ $event->categories->first()->name ?? 'General' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-6 md:p-8">

                    {{-- Left Column: Description --}}
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Deskripsi Event</h3>
                            <div class="prose max-w-none text-gray-600 leading-relaxed">
                                {!! nl2br(e($event->description)) !!}
                            </div>
                        </div>

                        {{-- Event Schedule --}}
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                            <h3 class="font-bold text-gray-800 mb-4">Jadwal Pelaksanaan</h3>
                            <div class="flex items-center gap-4">
                                <div class="bg-blue-100 text-blue-600 p-3 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Mulai</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($event->start_time)->format('l, d F Y - H:i') }} WIB
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 mt-4">
                                <div class="bg-red-100 text-red-600 p-3 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Selesai</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($event->end_time)->format('l, d F Y - H:i') }} WIB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Ticket Card --}}
                    <div>
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 sticky top-24">
                            <h3 class="font-bold text-lg text-gray-800 mb-4">Informasi Tiket</h3>

                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-100">
                                <span class="text-gray-600">Sisa Kuota</span>
                                <span class="font-bold {{ $event->quota > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $event->quota }} Tiket
                                </span>
                            </div>

                            <div class="mb-6">
                                <span class="block text-gray-500 text-sm">Harga per tiket</span>
                                <span class="block text-3xl font-bold text-blue-600">
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                </span>
                            </div>

                            @if ($event->quota > 0)
                                @auth
                                    <a href="{{ route('checkout.create', $event->id) }}"
                                        class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition transform hover:scale-105 shadow-md">
                                        Beli Tiket Sekarang
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="block w-full text-center bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-4 rounded-lg transition">
                                        Login untuk Membeli
                                    </a>
                                @endauth
                            @else
                                <button disabled
                                    class="w-full bg-gray-300 text-gray-500 font-bold py-3 px-4 rounded-lg cursor-not-allowed">
                                    Tiket Habis
                                </button>
                            @endif

                            <p class="text-xs text-center text-gray-400 mt-4">
                                Transaksi aman dan terverifikasi.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
