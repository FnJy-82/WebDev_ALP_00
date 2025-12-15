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
        // AUTHORIZATION: Hanya EO dan Admin yang boleh masuk
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
            'categories' => 'array'
        ]);

        // Upload Gambar
        $imagePath = null;
        if ($request->hasFile('banner_image')) {
            // Simpan di storage/app/public/events
            $path = $request->file('banner_image')->store('events', 'public');
            // Simpan URL aksesnya
            $imagePath = Storage::url($path);
        }

        // Create Event
        $event = Event::create([
            'user_id' => Auth::id(),
            'venue_id' => $request->venue_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'banner_image' => $imagePath,
            'status' => 'active',
        ]);

        if ($request->has('categories')) {
            $event->categories()->attach($request->categories);
        }

        return redirect()->route('home')->with('success', 'Event berhasil dibuat!');
    }

    public function edit(Event $event)
    {
        // AUTHORIZATION: Cek apakah user adalah pembuat event ini
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

        // Cek jika ada upload gambar baru
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('events', 'public');
            $data['banner_image'] = Storage::url($path);
        }

        $event->update($data);

        // Sync Categories (Hapus yang lama, ganti yang baru)
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
        return redirect()->route('home')->with('success', 'Event berhasil dihapus.');
    }
    
    public function index()
    {
        // Ambil event HANYA milik user yang sedang login
        $events = Event::where('user_id', Auth::id())
            ->with(['venue', 'category']) // Eager load biar ringan
            ->latest()
            ->get();

        return view('events.index', compact('events'));
    }
}
