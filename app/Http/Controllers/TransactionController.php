<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TransactionController extends Controller
{
    // Menampilkan halaman Checkout
    public function create(Event $event)
    {
        return view('checkout.create', compact('event'));
    }

    // Proses "WAR TIKET" (Create Transaction & Ticket)
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'seat_number' => 'required|string',
            'face_image' => 'required|string', // Base64 dari kamera
            'consent' => 'accepted',
            'identity_number' => 'required|numeric|digits:16',
        ]);

        $user = User::find(Auth::id());

        if ($user) {
            $user->identity_number = $request->identity_number;
            $user->save();
        }

        // 1. Simpan Foto Wajah (Decode Base64)
        $image_parts = explode(";base64,", $request->face_image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = 'face_verif/' . Str::random(20) . '.jpg';
        Storage::disk('public')->put($fileName, $image_base64);

        // 2. Buat Record Transaksi
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'event_id' => $request->event_id,
            'transaction_date' => Carbon::now(),
            'total_amount' => 150000, // Hardcode dulu atau ambil dari event price
            'status' => 'success', // Asumsi langsung sukses
        ]);

        // 3. Terbitkan Tiket (Create)
        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'event_id' => $request->event_id,
            'transaction_id' => $transaction->id,
            'seat_number' => $request->seat_number,
            'face_photo_path' => $fileName,
            'status' => 'active',
            'qr_code_hash' => Str::uuid(), // Token Unik QR
        ]);

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Pembelian Berhasil! Tiket diamankan.');
    }
}
