<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Developer API Settings') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Success --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm relative">
                    <span class="block sm:inline font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-8">
                    
                    <div class="flex items-start justify-between mb-8">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">API Key Management</h3>
                            <p class="text-gray-500 mt-1">Gunakan kunci ini untuk mengintegrasikan alat scanning pihak ketiga.</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </div>
                    </div>

                    {{-- DISPLAY KEY --}}
                    <div class="bg-slate-900 rounded-xl p-6 mb-8 relative group">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block">Your Secret API Key</label>
                        
                        <div class="flex items-center justify-between">
                            @if(Auth::user()->organizerApiKey)
                                <code class="text-green-400 font-mono text-lg break-all">
                                    {{ Auth::user()->organizerApiKey->api_key }}
                                </code>
                            @else
                                <span class="text-slate-500 italic">Belum ada API Key yang digenerate.</span>
                            @endif
                        </div>

                        {{-- Decoration dots --}}
                        <div class="absolute top-4 right-4 flex space-x-1">
                            <div class="w-2 h-2 rounded-full bg-red-500"></div>
                            <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                        <h4 class="font-bold text-gray-800 mb-2">Generate / Regenerate Key</h4>
                        <p class="text-sm text-gray-600 mb-4">
                            Jika Anda membuat kunci baru, kunci lama akan hangus dan aplikasi yang menggunakannya akan berhenti bekerja.
                        </p>
                        
                        <form action="{{ route('organizer.apikey.generate') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 px-6 rounded-lg shadow-md transition transform hover:-translate-y-0.5 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                {{ Auth::user()->organizerApiKey ? 'Regenerate API Key' : 'Generate New Key' }}
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>