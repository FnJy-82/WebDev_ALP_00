<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Nanti kamu bisa mengambil data event dari database disini
        // $events = Event::where('status', 'active')->latest()->get();

        // Untuk sekarang kita pakai data dummy dulu
        $events = Event::with('venue')->where('status', 'active')->latest()->get();

        // 1. Start the query for active events
        $query = Event::where('status', 'active');

        // 2. Filter by Name (Search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        // 3. Filter by Category (Many-to-Many Relationship)
        if ($request->has('category') && $request->category != '') {
            $categoryId = $request->category;
            $query->whereHas('categories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        // 4. Get results (ordered by newest)
        $events = $query->orderBy('start_time', 'asc')->get();

        // 5. Get all categories for the dropdown
        $categories = Category::all();

        return view('home', compact('events', 'categories'));

        return view('home', compact('events'));
    }
}
