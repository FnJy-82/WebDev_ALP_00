<x-guest-layout>
    <div class="min-h-screen w-full flex flex-col lg:flex-row bg-gray-50">

        {{-- BAGIAN KIRI: FORM LOGIN --}}
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:w-[500px] xl:w-[600px] bg-white border-r border-gray-200 shadow-xl z-10">
            <div class="mx-auto w-full max-w-sm lg:w-96">

                <div class="text-center lg:text-left">
                    <h2 class="mt-6 text-3xl font-extrabold text-slate-800">Welcome Back</h2>
                    <p class="mt-2 text-sm text-slate-500">Masuk untuk mengelola tiketmu.</p>
                </div>

                <div class="mt-8">
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-bold text-slate-700">Email Address</label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" autocomplete="email" required
                                    class="appearance-none block w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm transition"
                                    value="{{ old('email') }}">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" autocomplete="current-password" required
                                    class="appearance-none block w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm transition">
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded cursor-pointer">
                                <label for="remember_me" class="ml-2 block text-sm text-slate-600 cursor-pointer">Remember me</label>
                            </div>

                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-cyan-700 hover:text-cyan-600">Forgot password?</a>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transform transition hover:-translate-y-0.5 duration-200">
                                SIGN IN
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-slate-500">
                            Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-cyan-700 hover:text-cyan-600">Daftar sekarang</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: GAMBAR (Hanya muncul di Laptop ke atas) --}}
        <div class="hidden lg:block relative w-0 flex-1">
            <img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Concert">

            <div class="absolute inset-0 bg-gradient-to-br from-cyan-900 to-blue-900 opacity-80 mix-blend-multiply"></div>

            <div class="absolute bottom-0 left-0 p-16 text-white w-full">
                <h1 class="text-5xl font-extrabold mb-4 tracking-tight">TicketPro.</h1>
                <p class="text-xl font-light opacity-90 leading-relaxed">Platform manajemen event paling aman dengan teknologi <br> <span class="font-bold text-cyan-300">Face-Lock Verification.</span></p>
            </div>
        </div>

    </div>
</x-guest-layout>
