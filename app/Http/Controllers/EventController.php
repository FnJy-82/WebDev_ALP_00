<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Venue;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function create()
    {
        if (Auth::user()->role !== 'eo' && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk membuat event.');
        }

        $venues = Venue::all();
        $categories = Category::all();
        return view('events.create', compact('venues', 'categories'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'eo' && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'venue_id' => 'required|exists:venues,id',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id'
        ]);

        $imagePath = null;
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('events', 'public');
            $imagePath = Storage::url($path);
        }

        $event = Event::create([
            'user_id' => Auth::id(),
            'venue_id' => $request->venue_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'banner_image' => $imagePath,
            'status' => 'active',
            'price' => 0,
            'quota' => 0,
        ]);

        $event->categories()->attach([$request->category_id]);

        return redirect()->route('events.manage-seats', $event->id)
            ->with('success', 'Event dibuat! Sekarang atur layout kursi.');
    }

    public function manageSeats(Event $event)
    {
        if (Auth::id() !== $event->user_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('events.manage-seats', compact('event'));
    }

    public function edit(Event $event)
    {
        if (Auth::id() !== $event->user_id) {
            abort(403, 'Anda tidak berhak mengedit event ini.');
        }

        $venues = Venue::all();
        $categories = Category::all();
        return view('events.edit', compact('event', 'venues', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        if (Auth::id() !== $event->user_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'venue_id' => 'required|exists:venues,id',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'array'
        ]);

        $data = [
            'venue_id' => $request->venue_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ];

        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('events', 'public');
            $data['banner_image'] = Storage::url($path);
        }

        $event->update($data);

        if ($request->has('categories')) {
            $event->categories()->sync($request->categories);
        }

        return redirect()->route('home')->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        if (Auth::id() !== $event->user_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus.');
    }

    public function index()
    {
        $events = Event::where('user_id', Auth::id())
            ->with(['venue', 'ticketCategories'])
            ->latest()
            ->get();

        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load(['venue', 'categories']);
        return view('events.show', compact('event'));
    }

    public function cancel(Event $event)
    {
        if (Auth::id() !== $event->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $event->update([
            'status' => 'cancelled'
        ]);

        return back()->with('success', 'Event berhasil dibatalkan. Status telah diperbarui.');
    }

    public function attendees(Event $event)
    {
        $user = Auth::user(); // Use the Facade consistently

        // 1. Safety check: Ensure user is logged in
        if (!$user) {
            abort(403, 'Please login first.');
        }

        // 2. Authorization Check
        // FIX: Changed 'organizer_id' to 'user_id' to match your database structure
        // defined in your store() method.
        if ($event->user_id !== $user->id && $user->role !== 'admin') {
            abort(403, 'Unauthorized action. You are not the organizer of this event.');
        }

        // 3. Fetch Tickets
        // The 'with('user')' here requires the user() function in Ticket.php
        $tickets = $event->tickets()->whereNotNull('user_id')->with('user')->latest()->paginate(20);

        return view('events.attendees', compact('event', 'tickets'));
    }
}
