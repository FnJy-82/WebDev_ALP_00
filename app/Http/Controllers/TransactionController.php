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
        $request->validate([
            'event_id'        => 'required|exists:events,id',
            'seat_number'     => 'required|string',
            'identity_number' => 'required|numeric|digits:16',
            'consent'         => 'accepted',
            'face_image'      => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        return DB::transaction(function () use ($request) {
            $user = User::find(Auth::id());
            if ($user) {
                $user->identity_number = $request->identity_number;
                $user->save();
            }

            $ticket = Ticket::where('event_id', $request->event_id)
                ->where('seat_number', $request->seat_number)
                // Lock for update ensures atomic consistency during high traffic
                ->lockForUpdate()
                ->first();

            if (!$ticket || $ticket->status !== 'available') {
                return back()->withErrors(['seat_number' => 'Maaf, kursi ini baru saja diambil orang lain!']);
            }

            $imagePath = $request->file('face_image')->store('face_verif', 'public');

            $ticketPrice = $ticket->category->price ?? 0;

            $transaction = Transaction::create([
                'user_id'          => Auth::id(),
                'event_id'         => $request->event_id,
                'ticket_id'        => $ticket->id,
                'seat_number'      => $ticket->seat_number,
                'identity_number'  => $request->identity_number,
                'face_image_path'  => $imagePath,
                'transaction_date' => Carbon::now(),
                'total_amount'     => $ticketPrice,
                'status'           => 'success',
            ]);

            $ticket->update([
                'user_id'         => Auth::id(),
                'transaction_id'  => $transaction->id,
                'face_photo_path' => $imagePath,
                'status'          => 'sold',
                'qr_code_hash'    => Str::uuid(),
            ]);

            return redirect()->route('tickets.show', $ticket->id)
                ->with('success', 'Pembelian Berhasil! Tiket ' . $ticket->seat_number . ' diamankan.');
        });
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'event_id'        => 'required|exists:events,id',
    //         'seat_number'     => 'required|string',
    //         'identity_number' => 'required|numeric|digits:16',
    //         'consent'         => 'accepted',
    //         'face_image'      => 'required|image|mimes:jpeg,png,jpg|max:4096',
    //     ]);

    //     return DB::transaction(function () use ($request) {
    //         $user = User::find(Auth::id());
    //         if ($user) {
    //             $user->identity_number = $request->identity_number;
    //             $user->save();
    //         }

    //         $ticket = Ticket::where('event_id', $request->event_id)
    //             ->where('seat_number', $request->seat_number)
    //             ->lockForUpdate()
    //             ->first();

    //         if (!$ticket || $ticket->status !== 'available') {
    //             return back()->withErrors(['seat_number' => 'Maaf, kursi ini baru saja diambil orang lain!']);
    //         }

    //         $imagePath = $request->file('face_image')->store('face_verif', 'public');
    //         $bookingCode = 'TRX-' . strtoupper(Str::random(10));
    //         $ticketPrice = $ticket->category->price ?? 0;

    //         $transaction = Transaction::create([
    //             'user_id'          => Auth::id(),
    //             'event_id'         => $request->event_id,
    //             'ticket_id'        => $ticket->id,
    //             'seat_number'      => $ticket->seat_number,
    //             'identity_number'  => $request->identity_number,
    //             'face_image_path'  => $imagePath,
    //             'transaction_date' => Carbon::now(),
    //             'total_amount'     => $ticketPrice,
    //             'status' => 'pending',
    //             'midtrans_booking_code' => $bookingCode,
    //         ]);


    //         Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    //         Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
    //         Config::$isSanitized = true;
    //         Config::$is3ds = true;

    //         $params = [
    //             'transaction_details' => [
    //                 'order_id'     => $bookingCode,
    //                 'gross_amount' => (int) $transaction->total_amount,
    //             ],
    //             'customer_details' => [
    //                 'first_name' => $user->name,
    //                 'email'      => $user->email,
    //             ],
    //         ];

    //         try {
    //             // 4. Dapatkan Snap Token
    //             $snapToken = Snap::getSnapToken($params);
                
    //             // Simpan token ke database untuk digunakan di frontend
    //             $transaction->update(['snap_token' => $snapToken]);

    //             // Ubah status tiket menjadi 'pending_payment' agar tidak bisa dipilih orang lain saat proses bayar
    //             $ticket->update(['status' => 'pending_payment']);

    //             // 5. Tampilkan View Pembayaran (Jangan langsung redirect ke tiket sukses)
    //             return view('checkout.payment', compact('snapToken', 'transaction'));

    //         } catch (\Exception $e) {
    //             return back()->with('error', 'Gagal terhubung ke Midtrans: ' . $e->getMessage());
    //         }

    //         $ticket->update([
    //             'user_id'         => Auth::id(),
    //             'transaction_id'  => $transaction->id,
    //             'face_photo_path' => $imagePath,
    //             'status'          => 'sold',
    //             'qr_code_hash'    => Str::uuid(),
    //         ]);

    //         return redirect()->route('tickets.show', $ticket->id)
    //             ->with('success', 'Pembelian Berhasil! Tiket ' . $ticket->seat_number . ' diamankan.');
    //     });
    // }

    // public function handleCallback(Request $request)
    // {
    //     $serverKey = env('MIDTRANS_SERVER_KEY');
    //     $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

    //     if ($hashed == $request->signature_key) {
    //         $transaction = Transaction::where('midtrans_booking_code', $request->order_id)->first();
            
    //         if ($request->transaction_status == 'settlement' || $request->transaction_status == 'capture') {
    //             DB::transaction(function() use ($transaction, $request) {
    //                 $transaction->update(['status' => 'success', 'payment_type' => $request->payment_type]);
                    
    //                 // Update tiket menjadi sold dan generate QR Code
    //                 $transaction->ticket->update([
    //                     'status'          => 'sold',
    //                     'user_id'         => $transaction->user_id,
    //                     'qr_code_hash'    => (string) Str::uuid(),
    //                     'transaction_id'  => $transaction->id,
    //                     'is_checked_in'   => false,
    //                 ]);
    //             });
    //         } elseif (in_array($request->transaction_status, ['expire', 'cancel', 'deny'])) {
    //             $transaction->update(['status' => 'failed']);
    //             $transaction->ticket->update(['status' => 'available']);
    //         }
    //     }
    // }
}
