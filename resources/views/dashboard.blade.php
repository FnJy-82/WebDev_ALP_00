<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- === LOGIKA TAMPILAN BERDASARKAN ROLE === --}}
            
            {{-- 1. TAMPILAN KHUSUS ADMIN (GOD VIEW) --}}
            @if (Auth::user()->role === 'admin')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-600">
                        <div class="text-gray-500 text-sm font-medium uppercase">Total Revenue Platform</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">Rp 125.000.000</div>
                        <div class="text-green-500 text-sm mt-1">▲ 12% dari bulan lalu</div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                        <div class="text-gray-500 text-sm font-medium uppercase">Pending EO Approval</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">3 Organizer</div>
                        <a href="#" class="text-blue-600 text-sm mt-1 hover:underline">Lihat & Verifikasi →</a>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-cyan-500">
                        <div class="text-gray-500 text-sm font-medium uppercase">Total Pengguna</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">1,240 User</div>
                        <div class="text-gray-400 text-sm mt-1">Update Realtime</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4">Aksi Cepat Admin</h3>
                        <div class="flex gap-4">
                            <button class="bg-blue-800 text-white px-4 py-2 rounded hover:bg-blue-900 transition">Verifikasi Event Baru</button>
                            <button class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 transition">Cek Laporan Withdrawal</button>
                        </div>
                    </div>
                </div>


            {{-- 2. TAMPILAN KHUSUS EO (EVENT ORGANIZER) --}}
            @elseif (Auth::user()->role === 'organizer')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                        <div class="text-gray-500 text-sm">Tiket Terjual</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">850</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                        <div class="text-gray-500 text-sm">Total Pendapatan Event</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">Rp 45.000.000</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                        <div class="text-gray-500 text-sm">Event Aktif</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">2 Event</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold">Kelola Event Anda</h3>
                            <p class="text-gray-500 text-sm">Buat event baru atau edit event yang sedang berjalan.</p>
                        </div>
                        <a href="#" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-1">
                            + BUAT EVENT BARU
                        </a>
                    </div>
                </div>


            {{-- 3. TAMPILAN USER BIASA (CUSTOMER) --}}
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h3>
                        <p class="mb-4">Status Akun: <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded text-sm font-bold">{{ ucfirst(Auth::user()->role) }}</span></p>

                        <hr class="my-6 border-gray-200">

                        {{-- LOGIKA DAFTAR EO (SUDAH DIPERBAIKI WARNANYA) --}}
                        
                        {{-- KONDISI 1: Belum pernah apply --}}
                        @if (!Auth::user()->organizer_profile)
                            <div class="bg-blue-50 border-l-4 border-blue-600 p-6 mt-4 flex flex-col md:flex-row justify-between items-center rounded-r-lg">
                                <div class="mb-4 md:mb-0">
                                    <h4 class="font-bold text-blue-800 text-lg">Ingin membuat Event sendiri?</h4>
                                    <p class="text-blue-700">Upgrade akun Anda menjadi Event Organizer untuk mulai menjual tiket.</p>
                                </div>
                                <a href="{{ route('organizer.create') }}" class="bg-blue-700 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-800 transition shadow-md">
                                    Daftar Jadi EO Sekarang
                                </a>
                            </div>

                        {{-- KONDISI 2: Pending --}}
                        @elseif(Auth::user()->organizer_profile->verification_status === 'pending')
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mt-4 rounded-r-lg">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <h4 class="font-bold text-yellow-800 text-lg">Aplikasi Sedang Ditinjau</h4>
                                </div>
                                <p class="text-yellow-700 mt-1 ml-9">Mohon tunggu 1x24 jam. Admin sedang memverifikasi data Anda.</p>
                            </div>

                        {{-- KONDISI 3: Rejected/Verified tapi role belum berubah (Jaga-jaga) --}}
                        @elseif(Auth::user()->organizer_profile->verification_status === 'verified')
                            <div class="bg-green-50 border-l-4 border-green-500 p-6 mt-4 rounded-r-lg">
                                <h4 class="font-bold text-green-800">Selamat! Akun Anda Terverifikasi</h4>
                                <p class="text-green-700">Silakan logout dan login ulang untuk memperbarui status menu Anda.</p>
                            </div>
                        @endif

                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>