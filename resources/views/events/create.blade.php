<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Event Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Nama Event')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Venue Dropdown -->
                        <div class="mb-4">
                            <x-input-label for="venue_id" :value="__('Lokasi (Venue)')" />
                            <select id="venue_id" name="venue_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}">{{ $venue->name }} ({{ $venue->city }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('venue_id')" class="mt-2" />
                        </div>

                        <!-- Kategori (Checkbox) -->
                        <div class="mb-4">
                            <span class="block font-medium text-sm text-gray-700 mb-2">Kategori</span>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach($categories as $category)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Waktu -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="start_time" :value="__('Waktu Mulai')" />
                                <x-text-input id="start_time" class="block mt-1 w-full" type="datetime-local" name="start_time" required />
                            </div>
                            <div>
                                <x-input-label for="end_time" :value="__('Waktu Selesai')" />
                                <x-text-input id="end_time" class="block mt-1 w-full" type="datetime-local" name="end_time" required />
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Deskripsi Event')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                        </div>

                        <!-- Banner Image -->
                        <div class="mb-6">
                            <x-input-label for="banner_image" :value="__('Banner Event (Gambar)')" />
                            <input id="banner_image" name="banner_image" type="file" class="block mt-1 w-full border border-gray-300 rounded-md p-2" accept="image/*" required>
                            <x-input-error :messages="$errors->get('banner_image')" class="mt-2" />
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>
                                {{ __('Buat Event') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
