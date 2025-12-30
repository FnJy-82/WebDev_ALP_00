<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Event Saya') }}
            </h2>
            <a href="{{ route('events.create') }}"
                class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg shadow-lg flex items-center transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Event Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($events as $event)
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden flex flex-col group hover:shadow-xl transition duration-300">
                            {{-- Image --}}
                            <div class="h-48 overflow-hidden relative">
                                <img src="{{ $event->banner_image }}"
                                    class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500 {{ $event->status == 'cancelled' ? 'grayscale' : '' }}">
                                <div class="absolute top-2 right-2">
                                    {{-- Dynamic Badge Color --}}
                                    @php
                                        $badgeColor = match ($event->status) {
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            'active' => 'bg-green-100 text-green-800',
                                            'draft' => 'bg-gray-100 text-gray-800',
                                            default => 'bg-blue-100 text-blue-800',
                                        };
                                    @endphp
                                    <span
                                        class="px-2 py-1 text-xs font-bold uppercase rounded shadow {{ $badgeColor }}">
                                        {{ $event->status }}
                                    </span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="mb-4">
                                    <p class="text-xs font-bold text-blue-600 mb-1">
                                        {{ \Carbon\Carbon::parse($event->start_time)->format('d M Y, H:i') }}
                                    </p>
                                    <h3 class="text-lg font-bold text-gray-900 line-clamp-2 leading-tight">
                                        {{ $event->title }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $event->venue->name ?? 'TBA' }}
                                    </p>
                                </div>

                                {{-- Stats Mini --}}
                                <div class="grid grid-cols-2 gap-4 mb-6 border-t border-b border-gray-100 py-3">
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase">Harga</p>
                                        <p class="font-bold text-gray-800">Rp
                                            {{ number_format($event->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase">Kuota</p>
                                        <p class="font-bold text-gray-800">{{ $event->quota }}</p>
                                    </div>
                                </div>

                                {{-- Action Buttons Container --}}
                                <div class="mt-auto flex flex-col gap-3">

                                    {{-- 1. View Attendees Button (Primary Action) --}}
                                    <a href="{{ route('events.attendees', $event->id) }}"
                                        class="w-full bg-blue-50 text-blue-700 border border-blue-200 py-2 rounded-lg font-bold text-sm text-center hover:bg-blue-100 transition flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Lihat Peserta
                                    </a>

                                    {{-- 2. Edit & Cancel Row --}}
                                    <div class="flex gap-3">
                                        {{-- Edit Button --}}
                                        @if ($event->status !== 'cancelled')
                                            <a href="{{ route('events.edit', $event->id) }}"
                                                class="flex-1 bg-yellow-50 text-yellow-700 border border-yellow-200 py-2 rounded-lg font-bold text-sm text-center hover:bg-yellow-100 transition">
                                                Edit
                                            </a>
                                        @else
                                            <button disabled
                                                class="flex-1 bg-gray-100 text-gray-400 border border-gray-200 py-2 rounded-lg font-bold text-sm cursor-not-allowed">
                                                Edit
                                            </button>
                                        @endif

                                        {{-- Cancel Button --}}
                                        @if ($event->status !== 'cancelled')
                                            <form action="{{ route('events.cancel', $event->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin membatalkan event ini?');"
                                                class="flex-1">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="w-full bg-red-50 text-red-700 border border-red-200 py-2 rounded-lg font-bold text-sm hover:bg-red-100 transition">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @else
                                            <button disabled
                                                class="flex-1 bg-gray-200 text-gray-500 border border-gray-300 py-2 rounded-lg font-bold text-sm cursor-not-allowed">
                                                Dibatalkan
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                {{-- End Action Buttons --}}

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-300">
                    <div class="bg-gray-50 w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Belum Ada Event</h3>
                    <p class="text-gray-500 mt-2 mb-6">Mulai perjalananmu sebagai Organizer dengan membuat event
                        pertamamu.</p>
                    <a href="{{ route('events.create') }}"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                        + Buat Event Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
