<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tighter">
                            Ticket<span class="text-blue-600">Pro</span>.
                        </h1>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    
                    {{-- 1. MENU UMUM (Semua Role Punya) --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-slate-700 hover:text-blue-600 hover:border-blue-600 focus:border-blue-600">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- 2. MENU KHUSUS ADMIN --}}
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.verifications')" :active="request()->routeIs('admin.verifications')" class="text-slate-500 hover:text-blue-600 border-transparent hover:border-gray-300">
                            {{ __('Verifikasi EO') }} 
                            <span class="ml-1 text-xs bg-red-100 text-red-600 px-2 rounded-full">!</span>
                        </x-nav-link>
                    @endif

                    {{-- 3. MENU KHUSUS EO (ORGANIZER) --}}
                    @if(Auth::user()->role === 'organizer')
                        <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.index')" class="text-slate-500 hover:text-blue-600 border-transparent hover:border-gray-300">
                            {{ __('Event Saya') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('organizer.apikey')" :active="request()->routeIs('organizer.apikey')" class="text-slate-500 hover:text-blue-600 border-transparent hover:border-gray-300">
                            {{ __('API Key') }}
                        </x-nav-link>
                        
                        {{-- Tombol CTA Buat Event (Route: events.create) --}}
                        <div class="flex items-center ml-4">
                            <a href="{{ route('events.create') }}" class="px-4 py-2 bg-blue-700 text-white text-sm font-bold rounded-lg hover:bg-blue-800 transition shadow-md">
                                + Buat Event
                            </a>
                        </div>
                    @endif

                    {{-- 4. MENU KHUSUS CUSTOMER --}}
                    @if(Auth::user()->role === 'customer')
                        <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.index')" class="text-slate-500 hover:text-blue-600 border-transparent hover:border-gray-300">
                            {{ __('Tiket Saya') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('wallet.index')" :active="request()->routeIs('wallet.index')" class="text-slate-500 hover:text-blue-600 border-transparent hover:border-gray-300">
                            {{ __('Dompet') }}
                        </x-nav-link>
                    @endif

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-500 bg-white hover:text-slate-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex flex-col text-right mr-2">
                                <span class="font-bold text-slate-800">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-blue-600 uppercase">{{ Auth::user()->role }}</span>
                            </div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.verifications')">{{ __('Verifikasi EO') }}</x-responsive-nav-link>
            @endif

            @if(Auth::user()->role === 'organizer')
                <x-responsive-nav-link :href="route('events.index')">{{ __('Event Saya') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('events.create')">{{ __('+ Buat Event') }}</x-responsive-nav-link>
            @endif

            @if(Auth::user()->role === 'customer')
                <x-responsive-nav-link :href="route('tickets.index')">{{ __('Tiket Saya') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('wallet.index')">{{ __('Dompet') }}</x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>