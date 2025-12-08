<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class GatekeeperController extends Controller
{
    public function index()
    {
        return view('gatekeeper.scan');
    }

    // API Endpoint untuk Scanner
    public function store(Request $request)
    {
        $request->validate(['qr_data' => 'required']);
        
        // Parsing format: TICKET_ID|TIMESTAMP (Logic sederhana)
        $parts = explode('|', $request->qr_data);
        $ticketId = $parts[0] ?? null;

        $ticket = Ticket::with('user')->find($ticketId);

        if (!$ticket) {
            return response()->json(['status' => 'error', 'message' => 'Tiket Tidak Valid']);
        }

        // Cek Double Check-in
        if ($ticket->status === 'checked_in') {
            return response()->json([
                'status' => 'warning', 
                'message' => 'PENGUNJUNG SUDAH MASUK!',
                'user_name' => $ticket->user->name,
                'face_image' => asset('storage/' . $ticket->face_photo_path)
            ]);
        }

        // Update Status
        $ticket->update(['status' => 'checked_in']);

        return response()->json([
            'status' => 'success',
            'message' => 'CHECK-IN BERHASIL',
            'user_name' => $ticket->user->name,
            'seat' => $ticket->seat_number,
            'face_image' => asset('storage/' . $ticket->face_photo_path)
        ]);
    }
}