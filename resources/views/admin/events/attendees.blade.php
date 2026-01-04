<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Attendees List') }}
            </h2>
            <a href="{{ route('admin.events.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-bold">
                &larr; Back to Events
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Event Summary Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6 flex items-center justify-between border-l-4 border-indigo-500">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h3>
                    <p class="text-gray-500 text-sm mt-1">Organizer: {{ $event->organizer->name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-black text-indigo-600">{{ $attendees->count() }}</p>
                    <p class="text-xs text-gray-400 uppercase tracking-widest">Confirmed Guests</p>
                </div>
            </div>

            {{-- Attendees Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 text-gray-700">Guest List</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Identity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($attendees as $index => $ticket)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($ticket->face_photo_path)
                                                <img class="h-8 w-8 rounded-full object-cover mr-3 border border-gray-200" src="{{ asset('storage/'.$ticket->face_photo_path) }}">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-gray-200 mr-3"></div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $ticket->user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $ticket->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-bold text-gray-700">
                                        {{ $ticket->seat_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $ticket->user->identity_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($ticket->status === 'checked_in')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Inside Venue
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Ticket Sold
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                                        No tickets sold for this event yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>