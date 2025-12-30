<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Atur Kursi: {{ $event->title }}</h2>
                <p class="text-gray-600">Generate layout kursi untuk event ini.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- LEFT: Generator Form --}}
                <div class="md:col-span-1">
                    <div class="bg-white p-6 rounded-xl shadow-sm border">
                        <h3 class="font-bold text-lg mb-4">Tambah Section Baru</h3>

                        <form action="{{ route('events.seats.generate', $event->id) }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="block text-sm font-bold mb-2">Nama Section</label>
                                <input type="text" name="section_name" class="w-full border rounded p-2"
                                    placeholder="Ex: VIP A" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-bold mb-2">Harga per Kursi</label>
                                <input type="number" name="price" class="w-full border rounded p-2"
                                    placeholder="500000" required>
                            </div>

                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div>
                                    <label class="block text-sm font-bold mb-2">Jml Baris</label>
                                    <input type="number" name="rows" class="w-full border rounded p-2"
                                        placeholder="10" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold mb-2">Kursi/Baris</label>
                                    <input type="number" name="columns" class="w-full border rounded p-2"
                                        placeholder="20" required>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-cyan-600 text-white font-bold py-2 rounded hover:bg-cyan-700">
                                Generate Layout
                            </button>
                        </form>
                    </div>
                </div>

                {{-- RIGHT: Preview Layout --}}
                <div class="md:col-span-2">
                    <div class="bg-white p-6 rounded-xl shadow-sm border min-h-[400px]">
                        <h3 class="font-bold text-lg mb-4">Preview Layout Saat Ini</h3>

                        @if ($event->ticketCategories->count() > 0)
                            @foreach ($event->ticketCategories as $category)
                                <div class="mb-8 border-b pb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <h4 class="font-bold text-cyan-700">{{ $category->name }}</h4>
                                        <span class="text-sm bg-gray-100 px-2 py-1 rounded">Rp
                                            {{ number_format($category->price) }}</span>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <div class="inline-flex flex-col gap-2"> {{-- 1. Group tickets by their Row Label (A, B, C...) --}}
                                            @foreach ($event->tickets->where('category_id', $category->id)->groupBy('row_label') as $rowLabel => $ticketsInRow)
                                                <div class="flex items-center gap-1">
                                                    {{-- Row Label (Left side indicator) --}}
                                                    <div class="w-8 text-sm font-bold text-gray-500 text-right mr-2">
                                                        {{ $rowLabel }}
                                                    </div>

                                                    {{-- 2. Display all seats for THIS row in one straight line --}}
                                                    @foreach ($ticketsInRow as $ticket)
                                                        <div class="w-8 h-8 bg-gray-100 border border-gray-200 text-[10px] flex items-center justify-center rounded text-gray-600 hover:bg-cyan-100 hover:border-cyan-300 transition"
                                                            title="{{ $ticket->seat_number }}">

                                                            {{-- Extract just the number to save space (e.g., "1" from "A-1") --}}
                                                            {{-- Or just use loop iteration if your DB is sorted --}}
                                                            {{ explode('-', $ticket->seat_number)[1] ?? $ticket->seat_number }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <p>Belum ada layout kursi.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
