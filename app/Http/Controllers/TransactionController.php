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
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function create($eventId)
    {
        $event = Event::with(['venue', 'ticketCategories.tickets'])->findOrFail($eventId);
        return view('checkout.create', compact('event'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'ticket_id' => 'required|exists:tickets,id',
            'identity_number' => 'required|string',
        ]);

        $ticket = Ticket::find($request->ticket_id);
        $event = Event::find($request->event_id);
        
        // 2. Setup Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        // 3. Buat Transaksi di Database (Status: Pending)
        // Gunakan booking code unik untuk Order ID Midtrans
        $bookingCode = 'TRX-' . mt_rand(10000, 99999) . '-' . time();

        $transaction = Transaction::create([
            'midtrans_booking_code' => $bookingCode,
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'ticket_id' => $ticket->id,
            'total_amount' => $ticket->price, // Pastikan tiket ada harganya
            'status' => 'pending',
            'transaction_date' => now(),
            'identity_number' => $request->identity_number,
            'seat_number' => $ticket->seat_number,
        ]);

        // 4. Siapkan Parameter untuk Midtrans Snap
        $params = [
            'transaction_details' => [
                'order_id' => $bookingCode,
                'gross_amount' => (int) $transaction->total_amount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone_number ?? '08123456789',
            ],
            'item_details' => [
                [
                    'id' => $ticket->id,
                    'price' => (int) $transaction->total_amount,
                    'quantity' => 1,
                    'name' => "Tiket: " . $event->title . " (" . $ticket->seat_number . ")"
                ]
            ]
        ];

        // 5. Dapatkan Snap Token dari Midtrans
        try {
            $snapToken = Snap::getSnapToken($params);
            
            // Simpan token ke database agar tidak hilang
            // Pastikan kolom 'snap_token' ada di tabel transactions
            $transaction->snap_token = $snapToken;
            $transaction->save();

            // Redirect ke halaman pembayaran (bukan langsung success)
            return view('checkout.payment', compact('transaction', 'snapToken'));

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    // --- TAMBAHAN: CALLBACK HANDLER (PENTING) ---
    // Dipanggil otomatis oleh Midtrans saat user selesai bayar
    public function handleCallback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

        if ($hashed == $request->signature_key) {
            $transaction = Transaction::where('midtrans_booking_code', $request->order_id)->first();
            
            if ($transaction) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $transaction->update(['status' => 'success']);
                    
                    // Update Tiket jadi SOLD
                    Ticket::where('id', $transaction->ticket_id)->update([
                        'status' => 'sold',
                        'user_id' => $transaction->user_id
                    ]);
                } elseif ($request->transaction_status == 'expire' || $request->transaction_status == 'cancel') {
                    $transaction->update(['status' => 'failed']);
                }
            }
        }
        return response()->json(['status' => 'ok']);
    }


}
