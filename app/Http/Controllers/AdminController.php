<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function pendingOrganizers()
    {
        // Ambil user yang status EO-nya pending
        $pendingUsers = User::whereHas('organizer_profile', function ($query) {
            $query->where('verification_status', 'pending');
        })->with('organizer_profile')->get();
        return view('admin.verify-eo', compact('pendingUsers'));
    }

    public function approveOrganizer($id)
    {
        // Cari User berdasarkan ID
        $user = User::findOrFail($id);

        // Update status di tabel relasinya
        // Pastikan user punya profile sebelum update
        if ($user->organizer_profile) {
            $user->organizer_profile->update([
                'verification_status' => 'verified'
            ]);

            // Opsi: Ubah juga role di tabel user jadi 'eo' agar lebih mudah kedepannya
            $user->update(['role' => 'eo']);
        }

        return back()->with('success', 'User berhasil diverifikasi.');
    }
}
