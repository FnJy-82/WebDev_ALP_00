<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        // Nanti kamu bisa mengambil data event dari database disini
        // $events = Event::where('status', 'active')->latest()->get();

        // Untuk sekarang kita pakai data dummy dulu
        $events = Event::with('venue')->where('status', 'active')->latest()->get();

        return view('home', compact('events'));
    }
}
