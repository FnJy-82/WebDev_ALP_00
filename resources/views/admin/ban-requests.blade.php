<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ban Requests Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- SECTION 1: PENDING REQUESTS --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Pending Actions Needed
                    </h3>

                    @if($pendingRequests->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reported By</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pendingRequests as $req)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $req->organizer->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $req->targetUser->name }}</div>
                                            <div class="text-xs text-gray-400">{{ $req->targetUser->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 italic">
                                            "{{ $req->reason }}"
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500">
                                            {{ $req->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-3">
                                                {{-- Reject Button --}}
                                                <form action="{{ route('admin.ban-request.update', $req->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button name="action" value="reject" class="text-gray-500 hover:text-gray-700 font-bold text-xs bg-gray-100 hover:bg-gray-200 py-2 px-3 rounded transition">
                                                        Ignore
                                                    </button>
                                                </form>

                                                {{-- Approve Button --}}
                                                <form action="{{ route('admin.ban-request.update', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure? This user will be blocked from logging in.');">
                                                    @csrf @method('PATCH')
                                                    <button name="action" value="approve" class="text-white bg-red-600 hover:bg-red-700 font-bold text-xs py-2 px-3 rounded shadow transition">
                                                        BAN USER
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 bg-gray-50 p-4 rounded text-center">No pending ban requests.</p>
                    @endif
                </div>
            </div>

            {{-- SECTION 2: HISTORY LOG --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg opacity-75">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 text-gray-500">History (Last 10)</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="divide-y divide-gray-200">
                            @foreach($historyRequests as $hist)
                            <tr>
                                <td class="px-6 py-3 text-sm text-gray-500">
                                    {{ $hist->organizer->name }} reported <strong>{{ $hist->targetUser->name }}</strong>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $hist->status === 'approved' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($hist->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-xs text-gray-400 text-right">
                                    {{ $hist->updated_at->diffForHumans() }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
