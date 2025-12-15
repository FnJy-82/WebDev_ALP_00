<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Nama Event')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $event->title)" required />
                        </div>

                        <!-- Venue Dropdown -->
                        <div class="mb-4">
                            <x-input-label for="venue_id" :value="__('Lokasi (Venue)')" />
                            <select id="venue_id" name="venue_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" {{ $event->venue_id == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->name }} ({{ $venue->city }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kategori (Checkbox) -->
                        <div class="mb-4">
                            <span class="block font-medium text-sm text-gray-700 mb-2">Kategori</span>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @php $eventCatIds = $event->categories->pluck('id')->toArray(); @endphp
                                @foreach($categories as $category)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            {{ in_array($category->id, $eventCatIds) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Waktu -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="start_time" :value="__('Waktu Mulai')" />
                                <x-text-input id="start_time" class="block mt-1 w-full" type="datetime-local" name="start_time" :value="old('start_time', \Carbon\Carbon::parse($event->start_time)->format('Y-m-d\TH:i'))" required />
                            </div>
                            <div>
                                <x-input-label for="end_time" :value="__('Waktu Selesai')" />
                                <x-text-input id="end_time" class="block mt-1 w-full" type="datetime-local" name="end_time" :value="old('end_time', \Carbon\Carbon::parse($event->end_time)->format('Y-m-d\TH:i'))" required />
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Deskripsi Event')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('description', $event->description) }}</textarea>
                        </div>

                        <!-- Banner Image -->
                        <div class="mb-6">
                            <x-input-label for="banner_image" :value="__('Ganti Banner (Opsional)')" />
                            <div class="my-2">
                                <p class="text-sm text-gray-500 mb-1">Banner Saat Ini:</p>
                                <img src="{{ $event->banner_image }}" alt="Current Banner" class="h-20 w-auto rounded border">
                            </div>
                            <input id="banner_image" name="banner_image" type="file" class="block mt-1 w-full border border-gray-300 rounded-md p-2" accept="image/*">
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                                Batal
                            </a>
                            <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition hover:-translate-y-0.5">
                                {{ __('Update Event') }}
                            </button>
                        </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
