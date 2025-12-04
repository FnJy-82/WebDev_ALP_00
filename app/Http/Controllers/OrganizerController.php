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
            // Tambahkan validasi file dokumen legalitas jika ada
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->update([
            'company_name' => $request->company_name,
            'bank_name' => $request->bank_name,
            'bank_number' => $request->bank_number,
            'eo_status' => 'pending', // Ubah status jadi pending
        ]);

        return redirect()->route('dashboard')->with('status', 'Aplikasi EO Anda sedang ditinjau Admin.');
    }
}
