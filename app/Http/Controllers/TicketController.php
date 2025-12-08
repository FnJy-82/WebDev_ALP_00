<?php
namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    
    public function resale($id) {
        // Placeholder untuk fitur resale nanti
        return back()->with('status', 'Fitur Resale belum aktif.');
    }
}