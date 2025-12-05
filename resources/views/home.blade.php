<x-app-layout>
    {{-- 1. HERO SECTION (Banner Atas) --}}
    <div class="bg-indigo-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">Temukan Event Seru di Sekitarmu!</h1>
            <p class="text-lg text-indigo-100">Bergabunglah dengan ribuan orang lainnya dalam event terbaik tahun ini.</p>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Alert Success --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- 2. DASHBOARD AREA (HANYA MUNCUL JIKA SUDAH LOGIN) --}}
            @auth
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">Halo, {{ Auth::user()->name }}! 👋</h3>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ Auth::user()->role === 'admin' ? 'bg-red-100 text-red-800' : (Auth::user()->role === 'eo' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                        </div>

                        {{-- Logika Tombol EO --}}
                        @if(Auth::user()->role === 'customer')
                            @if(!Auth::user()->organizer_profile)
                                <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                                    <div>
                                        <p class="font-bold text-indigo-700">Ingin membuat Event sendiri?</p>
                                        <p class="text-sm text-indigo-600">Daftarkan diri Anda sebagai Event Organizer sekarang dan mulai jual tiket.</p>
                                    </div>
                                    <a href="{{ route('organizer.create') }}" class="whitespace-nowrap bg-indigo-600 text-white px-5 py-2 rounded-md hover:bg-indigo-700 transition shadow-sm">
                                        Daftar Jadi EO
                                    </a>
                                </div>
                            @elseif(Auth::user()->organizer_profile->verification_status === 'pending')
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                    <div class="flex">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                <span class="font-bold">Aplikasi EO Sedang Ditinjau.</span> Mohon tunggu 1x24 jam.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{-- TOMBOL CREATE EVENT (HANYA MUNCUL JIKA EO VERIFIED ATAU ADMIN) --}}
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'eo')
                            <div class="bg-green-50 border-l-4 border-green-400 p-4 flex justify-between items-center mt-4">
                                <div>
                                    <p class="font-bold text-green-700">Kelola Event Anda</p>
                                    <p class="text-sm text-green-600">Anda dapat membuat dan mengatur event.</p>
                                </div>
                                <a href="{{ route('events.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                                    + Buat Event Baru
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            @endauth

            {{-- 3. LIST EVENT --}}
            <div>
                <div class="flex justify-between items-end mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Event Terbaru</h2>
                </div>

                @if(count($events) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($events as $event)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300 flex flex-col">
                            <!-- Gambar Event -->
                            <div class="h-48 bg-gray-200 relative">
                                <img src="{{ $event->banner_image }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                @if(Auth::check() && Auth::id() === $event->user_id)
                                    <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                        Milik Anda
                                    </div>
                                @endif
                            </div>

                            <div class="p-5 flex-grow">
                                <div class="flex items-center text-xs text-indigo-600 font-semibold mb-2 uppercase tracking-wide">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $event->venue->name ?? 'Online / TBA' }}
                                </div>

                                <h3 class="text-xl font-bold text-gray-900 mb-2 leading-tight hover:text-indigo-600 transition">
                                    {{ $event->title }}
                                </h3>

                                <div class="flex items-center text-gray-500 text-sm mb-4">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ \Carbon\Carbon::parse($event->start_time)->translatedFormat('d F Y, H:i') }} WIB
                                </div>
                            </div>

                            <div class="border-t border-gray-100 p-4 bg-gray-50 mt-auto">
                                @if(Auth::check() && (Auth::id() === $event->user_id || Auth::user()->role === 'admin'))
                                    {{-- TOMBOL EDIT & DELETE (HANYA PEMILIK) --}}
                                    <div class="flex gap-2">
                                        <a href="{{ route('events.edit', $event->id) }}" class="flex-1 text-center bg-yellow-500 text-white py-2 rounded-lg font-semibold text-sm hover:bg-yellow-600 transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus event ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <button class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold text-sm hover:bg-indigo-700 transition">
                                        Lihat Detail
                                    </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <p class="text-gray-500">Belum ada event yang tersedia saat ini.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
