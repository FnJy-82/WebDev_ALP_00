<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Event Baru') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Ada kesalahan!</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                <div class="p-8">
                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Informasi
                                Dasar</h3>

                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Event</label>
                                <input type="text" name="title"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Contoh: Konser Amal 2025" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Banner Event</label>
                                <div class="flex items-center justify-center w-full">
                                    <label
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                            </svg>
                                            <p class="text-sm text-gray-500"><span class="font-semibold">Klik untuk
                                                    upload</span> atau drag and drop</p>
                                            <p class="text-xs text-gray-500">JPG, PNG, GIF (Max. 2MB)</p>
                                        </div>
                                        <input type="file" name="banner_image" class="hidden" required />
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Event</label>
                                <textarea name="description" rows="4"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Jelaskan detail acara Anda..." required></textarea>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Waktu &
                                Lokasi</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Waktu Mulai</label>
                                    <input type="datetime-local" name="start_time"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Waktu Selesai</label>
                                    <input type="datetime-local" name="end_time"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                        required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Venue (Lokasi)</label>
                                <select name="venue_id"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                    <option value="" disabled selected>Pilih Lokasi</option>
                                    @foreach ($venues as $venue)
                                        <option value="{{ $venue->id }}">{{ $venue->name }} (Kap:
                                            {{ $venue->capacity }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Tiket &
                                Kategori</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga Tiket (Rp)</label>
                                    <input type="number" name="price"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="0" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kuota Tiket</label>
                                    <input type="number" name="quota"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="100" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kategori (Pilih salah
                                    satu)</label>
                                <select name="category_id"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Kategori
                                Event</h3>
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Event</label>
                                <select name="category_id"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500" required>
                                    <option value="" disabled selected>Pilih Kategori (Musik, Seminar, dll)
                                    </option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-blue-700 text-white font-bold py-3 px-8 rounded-lg">
                                LANJUT: ATUR KURSI & TIKET &rarr;
                            </button>
                        </div>

                        {{-- <div class="pt-4 flex justify-end">
                            <button type="submit"
                                class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition hover:-translate-y-0.5">
                                PUBLISH EVENT
                            </button>
                        </div> --}}

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
