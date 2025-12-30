<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'TicketPro') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">

    {{-- 1. NAVBAR TRANSPARAN (PENGHUBUNG KE DASHBOARD) --}}
    <nav class="absolute top-0 w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center">
                    <h1 class="text-2xl font-extrabold text-white tracking-wider">
                        Ticket<span class="text-cyan-400">Pro</span>.
                    </h1>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <span class="text-slate-300 text-sm mr-2">Halo, {{ Auth::user()->name }}</span>
                            <a href="{{ url('/dashboard') }}"
                                class="px-5 py-2.5 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-lg transition shadow-lg hover:shadow-cyan-500/50">
                                MASUK DASHBOARD
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-white hover:text-cyan-400 font-medium transition px-4">Masuk</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-5 py-2.5 bg-white text-slate-900 hover:bg-slate-100 font-bold rounded-lg transition">
                                    Daftar Sekarang
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- 2. HERO SECTION (BANNER UTAMA) --}}
    <div class="relative bg-slate-900 pt-32 pb-40 overflow-hidden">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover opacity-20"
                src="https://images.unsplash.com/photo-1459749411177-260f11c7c8e6?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900 via-slate-900/80 to-slate-900"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            <span
                class="inline-block py-1 px-3 rounded-full bg-cyan-900/50 border border-cyan-500/30 text-cyan-300 text-xs font-bold tracking-wider mb-6 uppercase backdrop-blur-md">
                #1 Secure Ticketing Platform
            </span>
            <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tight mb-6 leading-tight">
                Experience <span class="text-cyan-400">Real Magic.</span>
            </h1>

            <p class="text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                Platform tiket event paling aman dengan teknologi Face-Lock Verification.
                <br>Selamat tinggal calo, selamat datang pengalaman eksklusif.
            </p>

            <div class="flex justify-center gap-4">
                <a href="#event-list"
                    class="px-8 py-4 bg-cyan-600 text-white font-bold rounded-xl shadow-lg hover:bg-cyan-500 transform hover:-translate-y-1 transition duration-300">
                    Cari Tiket Event
                </a>
            </div>
        </div>
    </div>

    {{-- 3. LIST EVENT (Scroll Target) --}}
    <div id="event-list" class="bg-slate-50 py-20 min-h-screen -mt-20 relative z-20 rounded-t-[3rem]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-slate-900">Upcoming Events</h2>
                <p class="text-slate-500 mt-2">Dapatkan tiketmu sebelum kehabisan.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-xl border border-slate-100 mb-16 -mt-8 relative z-30">
                <form action="{{ url('/') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-grow">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Cari Event</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="pl-10 block w-full rounded-lg border-slate-300 bg-slate-50 text-slate-900 focus:ring-cyan-500 focus:border-cyan-500 p-2.5"
                                placeholder="Nama konser, artis, atau event...">
                        </div>
                    </div>

                    <div class="w-full md:w-1/4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                        <select name="category"
                            class="block w-full rounded-lg border-slate-300 bg-slate-50 text-slate-900 focus:ring-cyan-500 focus:border-cyan-500 p-2.5">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Submit Button --}}
                    <div class="w-full md:w-auto flex items-end">
                        <button type="submit"
                            class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2.5 px-6 rounded-lg transition shadow-lg hover:shadow-cyan-500/50 h-[42px]">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            @if (isset($events) && count($events) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($events as $event)
                        <div
                            class="group bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 overflow-hidden border border-slate-100">

                            <div class="h-56 overflow-hidden relative">
                                <img src="{{ $event->banner_image ?? 'https://via.placeholder.com/600x400?text=Event' }}"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                                <div
                                    class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide">
                                    {{ $event->categories->first()->name ?? 'General' }}
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <p class="text-xs font-bold text-cyan-600 uppercase mb-1">
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('d M Y') }}
                                        </p>
                                        <h3
                                            class="text-xl font-bold text-slate-800 leading-tight group-hover:text-cyan-700 transition">
                                            {{ $event->title }}
                                        </h3>
                                    </div>
                                </div>

                                <p class="text-slate-500 text-sm line-clamp-2 mb-6">
                                    {{ $event->description }}
                                </p>

                                <a href="{{ route('events.show', $event->id) }}"
                                    class="block w-full py-2 bg-slate-100 text-slate-800 text-center font-bold rounded-lg hover:bg-slate-200 transition mb-4">
                                    View Details
                                </a>

                                <a href="{{ route('checkout.create', $event->id ?? 1) }}"
                                    class="block w-full py-3 bg-slate-900 text-white text-center font-bold rounded-xl hover:bg-blue-600 transition shadow-lg">
                                    Beli Tiket
                                </a>

                                @if (Auth::user()?->role === 'admin' || Auth::user()?->role === 'eo')
                                    <div class="mt-4 text-center">
                                        <a href="{{ route('events.edit', $event->id) }}"
                                            class="text-sm text-cyan-600 hover:underline">
                                            Kelola Event →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div
                    class="flex flex-col items-center justify-center py-12 border-2 border-dashed border-slate-200 rounded-3xl">
                    <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    <p class="text-slate-500 font-medium">Belum ada event yang tersedia saat ini.</p>
                </div>
            @endif

        </div>
    </div>

    <footer class="bg-white border-t border-slate-200 py-10 mt-10">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-400 text-sm">© 2025 TicketPro. All rights reserved.</p>
        </div>
    </footer>
