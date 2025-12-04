<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function pendingOrganizers() {
    // Ambil user yang status EO-nya pending
    $pendingUsers = User::where('eo_status', 'pending')->get();
    return view('admin.verify-eo', compact('pendingUsers'));
}

public function approveOrganizer($id) {
    $user = User::findOrFail($id);

    $user->update([
        'eo_status' => 'verified',
        'role' => 'eo' // Otomatis ubah role jadi EO agar bisa akses fitur EO
    ]);

    return back()->with('success', 'User berhasil diverifikasi menjadi EO.');
}
}
