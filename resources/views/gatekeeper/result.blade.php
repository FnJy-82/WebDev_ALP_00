<x-app-layout>
    <div class="min-h-screen bg-gray-900 text-white flex flex-col items-center justify-center p-4">
        
        <div class="max-w-md w-full bg-gray-800 rounded-3xl overflow-hidden shadow-2xl border border-gray-700">
            
            {{-- Header Status --}}
            <div class="p-6 text-center {{ $statusColor === 'green' ? 'bg-green-600' : 'bg-red-600' }}">
                <h1 class="text-2xl font-black uppercase tracking-wider text-white">
                    {{ $statusMessage }}
                </h1>
                <p class="text-sm text-white/80 mt-1">
                    {{ \Carbon\Carbon::now()->format('d M Y, H:i:s') }}
                </p>
            </div>

            {{-- User Verification Details --}}
            <div class="p-8 flex flex-col items-center">
                {{-- Face Photo --}}
                <div class="relative mb-6">
                    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full blur opacity-50"></div>
                    @if($ticket->face_photo_path)
                        <img src="{{ asset('storage/' . $ticket->face_photo_path) }}" 
                             class="relative w-40 h-40 rounded-full border-4 border-white object-cover shadow-2xl">
                    @else
                        <div class="relative w-40 h-40 rounded-full border-4 border-white bg-gray-600 flex items-center justify-center">
                            <span class="text-xs text-gray-400">No Photo</span>
                        </div>
                    @endif
                </div>

                <h2 class="text-2xl font-bold text-center mb-1">{{ $ticket->user->name }}</h2>
                <p class="text-gray-400 text-sm mb-6">{{ $ticket->user->email }}</p>

                {{-- Ticket Details Grid --}}
                <div class="w-full grid grid-cols-2 gap-4 text-center">
                    <div class="bg-gray-700/50 p-4 rounded-xl border border-gray-600">
                        <p class="text-xs text-gray-400 uppercase">Nomor Kursi</p>
                        <p class="text-2xl font-mono font-black text-indigo-400">{{ $ticket->seat_number }}</p>
                    </div>
                    <div class="bg-gray-700/50 p-4 rounded-xl border border-gray-600">
                        <p class="text-xs text-gray-400 uppercase">Identitas</p>
                        <p class="text-sm font-bold text-white truncate">{{ $ticket->user->identity_number }}</p>
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="p-6 bg-gray-900/50 border-t border-gray-700">
                <a href="{{ route('gatekeeper.scan') }}" 
                   class="block w-full bg-indigo-600 hover:bg-indigo-500 text-white text-center font-bold py-4 rounded-xl transition shadow-lg transform active:scale-95">
                    SCAN PENONTON BERIKUTNYA
                </a>
            </div>
        </div>
    </div>
</x-app-layout>