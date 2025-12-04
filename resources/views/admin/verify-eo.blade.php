<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin - Verifikasi Organizer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-medium mb-4">Daftar Pengajuan Pending</h3>

                    @if($pendingUsers->isEmpty())
                        <p class="text-gray-500 italic">Tidak ada pengajuan baru saat ini.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="py-2 px-4 border-b text-left">Nama User</th>
                                        <th class="py-2 px-4 border-b text-left">Perusahaan</th>
                                        <th class="py-2 px-4 border-b text-left">Bank Info</th>
                                        <th class="py-2 px-4 border-b text-left">Dokumen</th>
                                        <th class="py-2 px-4 border-b text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingUsers as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-4 border-b">
                                            {{ $user->name }} <br>
                                            <span class="text-xs text-gray-500">{{ $user->email }}</span>
                                        </td>
                                        <td class="py-2 px-4 border-b">{{ $user->company_name }}</td>
                                        <td class="py-2 px-4 border-b">
                                            {{ $user->bank_name }} <br>
                                            {{ $user->bank_number }}
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <a href="#" class="text-blue-600 underline text-sm">Lihat Dokumen</a>
                                        </td>
                                        <td class="py-2 px-4 border-b text-center">
                                            <form action="{{ route('admin.approve', $user->id) }}" method="POST" onsubmit="return confirm('Yakin verifikasi user ini?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition">
                                                    Verifikasi
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
