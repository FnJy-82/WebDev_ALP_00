<?php

namespace App\Http\Controllers;

use App\Models\OrganizerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizerController extends Controller
{
    public function create()
    {
        return view('organizer.apply');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string',
            'bank_name' => 'required|string',
            'bank_account_number' => 'required|numeric',
            'document_path' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $path = $request->file('document_path')->store('documents', 'public');

        OrganizerProfile::create([
            'user_id' => Auth::id(),
            'company_name' => $request->company_name,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'document_path' => $path,
            'verification_status' => 'pending'
        ]);

        return redirect()->route('dashboard')->with('success', 'Aplikasi Anda dikirim. Tunggu verifikasi admin.');
    }
}
