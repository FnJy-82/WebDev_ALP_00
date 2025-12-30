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
}
