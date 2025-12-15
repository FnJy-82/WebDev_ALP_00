<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Find User to Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- SEARCH FORM --}}
                    <form method="GET" action="{{ route('organizer.users.search') }}" class="mb-8 flex gap-2">
                        <input type="text" name="query" value="{{ $query ?? '' }}"
                            placeholder="Search by Name, Email, or NIK..."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 font-bold">
                            Search
                        </button>
                    </form>

                    {{-- RESULTS TABLE --}}
                    @if (isset($users) && $users->count() > 0)
                        <h3 class="text-lg font-bold mb-4">Search Results</h3>
                        <div class="overflow-x-auto border rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIK
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($users as $user)
                                        <tr>
                                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                                {{ $user->name }}
                                                @if ($user->is_banned)
                                                    <span
                                                        class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full">Already
                                                        Banned</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $user->identity_number ?? '-' }}</td>
                                            <td class="px-6 py-4 text-right">
                                                @if (!$user->is_banned)
                                                    {{-- This button links to your Ban Form --}}
                                                    <a href="{{ route('organizer.ban-request.create', $user->id) }}"
                                                        class="bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white px-3 py-1 rounded text-xs font-bold transition">
                                                        Report User
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 text-xs italic">Banned</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif(isset($query))
                        <div class="text-center py-8 text-gray-500">
                            No users found matching "{{ $query }}".
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400 italic">
                            Enter a name or email above to begin search.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
