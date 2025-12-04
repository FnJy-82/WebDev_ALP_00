<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizerController extends Controller
{
    // Menampilkan form aplikasi
    public function create()
    {
        return view('organizer.apply');
    }

    // Proses submit data
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string',
            'bank_name' => 'required|string',
            'bank_number' => 'required|numeric',
            'document'     => 'required|file|mimes:jpg,pdf|max:2048',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $filePath = null;
        if ($request->hasFile('document')) {
            $filePath = $request->file('document')->store('documents', 'public');
        }

        $user->organizer_profile()->create([
            'company_name'        => $request->company_name,
            'bank_name'           => $request->bank_name,
            'bank_account_number' => $request->bank_number,
            'document_path'       => $filePath,
            'verification_status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('status', 'Aplikasi EO Anda sedang ditinjau Admin.');
    }
}
