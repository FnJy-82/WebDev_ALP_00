<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizerController extends Controller
{
    public function create() {
        return view('organizer.apply');
    }

    public function store(Request $request) {
        $request->validate([
            'company_name' => 'required|string',
            'bank_name' => 'required|string',
            'bank_number' => 'required|numeric',
            'document' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $path = $request->file('document')->store('documents', 'public');

        Auth::user()->organizer_profile()->create([
            'company_name' => $request->company_name,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_number,
            'document_path' => $path,
            'verification_status' => 'pending'
        ]);

        return redirect()->route('dashboard')->with('success', 'Aplikasi Anda dikirim. Tunggu verifikasi admin.');
    }
}