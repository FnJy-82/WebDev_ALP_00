<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tiket Saya</h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($tickets as $ticket)
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-2 py-1 rounded-lg uppercase">
                                {{ $ticket->status }}
                            </span>
                            <span class="text-xs text-gray-400">#{{ $ticket->id }}</span>
                        </div>
                        
                        <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $ticket->event->title }}</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            {{ \Carbon\Carbon::parse($ticket->event->start_time)->format('d M Y, H:i') }}
                        </p>

                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 flex justify-between items-center mb-6">
                            <span class="text-xs font-bold text-gray-500 uppercase">Kursi</span>
                            <span class="text-lg font-black text-indigo-600">{{ $ticket->seat_number }}</span>
                        </div>
                    </div>

                    <a href="{{ route('tickets.show', $ticket->id) }}" class="block w-full text-center bg-gray-900 text-white py-3 rounded-xl font-bold text-sm hover:bg-gray-800 transition">
                        Buka E-Ticket
                    </a>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">Anda belum memiliki tiket.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>