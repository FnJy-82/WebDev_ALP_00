<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="mb-4">Status Akun Anda: <span
                            class="badge bg-gray-200 px-2 py-1 rounded">{{ ucfirst(Auth::user()->role) }}</span></p>

                    <hr class="my-6">

                    {{-- LOGIKA TOMBOL DAFTAR EO --}}
                    @if (Auth::user()->role === 'customer')

                        {{-- KONDISI 1: Belum pernah apply (Data profile kosong) --}}
                        @if (!Auth::user()->organizer_profile)
                            <div
                                class="bg-indigo-50 border-l-4 border-indigo-500 p-4 mt-4 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-indigo-700">Ingin membuat Event sendiri?</p>
                                    <p class="text-sm text-indigo-600">Daftarkan diri Anda sebagai Event Organizer
                                        sekarang.</p>
                                </div>
                                <a href="{{ route('organizer.create') }}"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                                    Daftar Jadi EO
                                </a>
                            </div>

                            {{-- KONDISI 2: Sudah apply, status masih pending --}}
                        @elseif(Auth::user()->organizer_profile->verification_status === 'pending')
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                                <p class="font-bold text-yellow-700">Aplikasi EO Sedang Ditinjau</p>
                                <p class="text-sm text-yellow-600">Mohon tunggu 1x24 jam untuk verifikasi admin.</p>
                            </div>

                            {{-- KONDISI 3: Sudah Verified (Menampilkan menu EO) --}}
                        @elseif(Auth::user()->organizer_profile->verification_status === 'verified')
                            <div class="bg-green-50 border-l-4 border-green-400 p-4 mt-4">
                                <p class="font-bold text-green-700">Anda adalah Event Organizer!</p>
                                <a href="#" class="text-green-600 underline text-sm">Buat Event Baru</a>
                            </div>
                        @endif

                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
