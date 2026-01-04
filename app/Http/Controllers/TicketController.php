<?php
namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Wallet;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('event')->where('user_id', Auth::id())->latest()->get();
        return view('tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = Ticket::with('event', 'user')->where('user_id', Auth::id())->findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    public function resale($id) 
    {
        $user = Auth::user();
        
        // 1. Cari Tiket milik user ini
        $ticket = Ticket::with('category')->where('id', $id)->where('user_id', $user->id)->firstOrFail();

        // 2. Validasi: Tidak bisa refund jika sudah masuk (Checked In)
        if ($ticket->status === 'checked_in') {
            return back()->with('error', 'Tiket sudah terpakai, tidak bisa di-refund.');
        }

        // 3. Ambil Harga Asli (Full Refund)
        $refundAmount = $ticket->category->price ?? 0; 

        DB::transaction(function() use ($ticket, $user, $refundAmount) {
            // A. Reset Tiket menjadi Available (Bisa dibeli orang lain lagi dengan harga sama)
            $ticket->update([
                'user_id' => null,           
                'status' => 'available',     
                'face_photo_path' => null,   
                'qr_code_hash' => null,      
                'transaction_id' => null,
                'is_checked_in' => false
            ]);

            // B. Tambah Saldo Wallet User (Full Amount)
            $user->increment('balance', $refundAmount);

            // C. Catat Mutasi Wallet
            Wallet::create([
                'user_id' => $user->id,
                'amount' => $refundAmount,
                'type' => 'credit', // Uang Masuk
                'description' => "Refund Tiket {$ticket->seat_number} (Ref: {$ticket->event->title})",
                'status' => 'success'
            ]);
        });

        return redirect()->route('tickets.index')
            ->with('success', 'Tiket berhasil dijual kembali! Dana Rp ' . number_format($refundAmount) . ' (Full) telah masuk ke Wallet Anda.');
    }

    public function allTickets()
{
    // Fetch all tickets with related User and Event data
    // Use latest() to see new purchases first
    $tickets = \App\Models\Ticket::with(['user', 'event'])
                ->latest()
                ->paginate(20);

    return view('admin.tickets.index', compact('tickets'));
}
}
