<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dompet Digital') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KOLOM KIRI: KARTU SALDO --}}
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-blue-700 to-cyan-500 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden mb-6 transform transition hover:scale-105 duration-300">
                        <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 rounded-full bg-white opacity-10 blur-2xl"></div>
                        <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 rounded-full bg-blue-900 opacity-20 blur-2xl"></div>

                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-8">
                                <h3 class="font-bold tracking-widest uppercase text-sm opacity-80">TicketPro Wallet</h3>
                                <svg class="w-10 h-10 text-white opacity-80" fill="currentColor" viewBox="0 0 24 24"><path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4V7zm-1-5C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                            </div>

                            <div class="mb-6">
                                <p class="text-sm opacity-70 mb-1">Total Saldo Aktif</p>
                                <h2 class="text-3xl font-extrabold tracking-tight">
                                    Rp {{ number_format($balance, 0, ',', '.') }}
                                </h2>
                            </div>

                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-xs opacity-60 uppercase">Card Holder</p>
                                    <p class="font-bold tracking-wide">{{ strtoupper(Auth::user()->name) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs opacity-60">Status</p>
                                    <span class="inline-block bg-green-400/30 border border-green-400/50 rounded px-2 py-0.5 text-xs font-bold">ACTIVE</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button onclick="alert('Fitur Top Up tersambung ke Payment Gateway (Midtrans/Xendit) dalam versi Production.')" class="flex items-center justify-center bg-blue-800 text-white py-3 rounded-xl font-bold hover:bg-blue-900 transition shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Top Up
                        </button>
                        <button disabled class="flex items-center justify-center bg-white text-gray-400 border border-gray-200 py-3 rounded-xl font-bold cursor-not-allowed">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Tarik
                        </button>
                    </div>
                </div>

                {{-- KOLOM KANAN: RIWAYAT TRANSAKSI --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 text-lg">Riwayat Transaksi</h3>
                            <button class="text-blue-600 text-sm font-semibold hover:underline">Lihat Semua</button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                                    <tr>
                                        <th class="px-6 py-4 font-semibold">Deskripsi</th>
                                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                                        <th class="px-6 py-4 font-semibold">Status</th>
                                        <th class="px-6 py-4 font-semibold text-right">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($transactions as $trx)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="p-2 rounded-lg mr-3 {{ $trx->type == 'credit' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                                        @if($trx->type == 'credit')
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                                        @else
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-gray-800 text-sm">{{ $trx->description }}</p>
                                                        <p class="text-xs text-gray-400">{{ $trx->id }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($trx->date)->format('d M Y, H:i') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($trx->status == 'success')
                                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">Berhasil</span>
                                                @elseif($trx->status == 'pending')
                                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold">Pending</span>
                                                @else
                                                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Gagal</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right font-bold {{ $trx->type == 'credit' ? 'text-green-600' : 'text-slate-800' }}">
                                                {{ $trx->type == 'credit' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                                Belum ada transaksi.
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
    </div>
</x-app-layout>