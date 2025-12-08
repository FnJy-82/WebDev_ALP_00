<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        // DATA DUMMY UNTUK DEMO (Biar pas presentasi langsung muncul angka)
        // Nanti bisa diganti dengan query database beneran: Auth::user()->wallet->balance
        
        $balance = 750000; // Contoh Saldo Rp 750.000
        
        $transactions = [
            (object)[
                'id' => '#TRX-9981',
                'description' => 'Top Up via BCA',
                'amount' => 1000000,
                'type' => 'credit', // credit = uang masuk
                'status' => 'success',
                'date' => now()->subHours(2)
            ],
            (object)[
                'id' => '#TRX-9982',
                'description' => 'Pembelian Tiket Konser Coldplay',
                'amount' => 250000,
                'type' => 'debit', // debit = uang keluar
                'status' => 'success',
                'date' => now()->subDays(1)
            ],
            (object)[
                'id' => '#TRX-9985',
                'description' => 'Top Up via GoPay',
                'amount' => 50000,
                'type' => 'credit',
                'status' => 'pending',
                'date' => now()->subDays(3)
            ],
        ];

        return view('wallet.index', compact('balance', 'transactions'));
    }
}