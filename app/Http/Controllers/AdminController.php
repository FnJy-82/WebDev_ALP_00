<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function pendingOrganizers() {
        $pendingUsers = User::whereHas('organizer_profile', function ($q) {
            $q->where('verification_status', 'pending');
        })->with('organizer_profile')->get();
        
        return view('admin.verify-eo', compact('pendingUsers'));
    }

    public function approveOrganizer($id) {
        $user = User::findOrFail($id);
        
        // 1. Update status Profile
        $user->organizer_profile()->update(['verification_status' => 'verified']);
        
        // 2. Update Role User jadi EO (agar bisa akses menu create event)
        $user->update(['role' => 'eo']);

        return back()->with('success', 'Organizer berhasil diverifikasi!');
    }
}