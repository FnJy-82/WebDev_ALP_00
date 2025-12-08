<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrganizerApiKey;
use Illuminate\Support\Str;

class OrganizerApiKeyController extends Controller
{
    public function index() {
        return view('organizer.api-key');
    }

    public function generate(Request $request) {
        OrganizerApiKey::updateOrCreate(
            ['user_id' => Auth::id()],
            ['api_key' => Str::random(40)]
        );
        return back()->with('success', 'API Key Generated.');
    }
}