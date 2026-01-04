<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function pendingOrganizers()
    {
        $pendingUsers = User::whereHas('organizerProfile', function ($q) {
            $q->where('verification_status', 'pending');
        })->with('organizerProfile')->get();

        return view('admin.verify-eo', compact('pendingUsers'));
    }

    public function approveOrganizer($id)
    {
        $user = User::findOrFail($id);

        // 1. Update status Profile jadi Verified
        $user->organizerProfile()->update(['verification_status' => 'verified']);

        // 2. Update Role User jadi EO (agar bisa akses menu create event)
        $user->update(['role' => 'eo']);

        return back()->with('success', 'Organizer berhasil diverifikasi!');
    }

    public function allEvents()
    {
        $events = Event::with(['organizer', 'venue'])
            ->withCount(['tickets as total_tickets', 'tickets as sold_tickets' => function($q) {
                // Count tickets that are actually sold or checked-in
                $q->whereIn('status', ['sold', 'checked_in']);
            }])
            ->latest()
            ->paginate(15);

        return view('admin.events.index', compact('events'));
    }


    public function eventAttendees($id)
    {
        $event = Event::findOrFail($id);

        // Fetch valid tickets (Sold or Checked-in) with the User data
        $attendees = $event->tickets()
            ->whereIn('status', ['sold', 'checked_in'])
            ->with('user')
            ->latest()
            ->get();

        return view('admin.events.attendees', compact('event', 'attendees'));
    }

    public function approveEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->update(['status' => 'active']);

        return back()->with('success', 'Event berhasil dipublish!');
    }

    public function getPendingEOsAttribute()
    {
        return OrganizerProfile::where('verification_status', 'pending')->count();
    }

    // 2. Hitung jumlah Event yang pending approval
    public function getPendingEventsAttribute()
    {
        return Event::where('status', 'pending')->count();
    }

}
