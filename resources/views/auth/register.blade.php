<x-guest-layout>
    <div class="min-h-screen flex flex-row-reverse"> {{-- Reverse agar gambar di kiri --}}
        
        {{-- BAGIAN KANAN: Form --}}
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white overflow-y-auto">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div class="text-center lg:text-left">
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Buat Akun Baru</h2>
                    <p class="mt-2 text-sm text-gray-600">Bergabung untuk membeli tiket event.</p>
                </div>

                <div class="mt-8">
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIK (Nomor Induk Kependudukan)</label>
                            <input type="text" name="identity_number" value="{{ old('identity_number') }}" required maxlength="16" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3">
                            <x-input-error :messages="$errors->get('identity_number')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor HP / WhatsApp</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number') }}" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3">
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3">
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                Daftar Sekarang
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Sudah punya akun? <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Log in</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN KIRI: Image --}}
        <div class="hidden lg:block relative w-0 flex-1">
            <img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Concert Crowd">
            <div class="absolute inset-0 bg-purple-900 opacity-40 mix-blend-multiply"></div>
        </div>
    </div>
</x-guest-layout>