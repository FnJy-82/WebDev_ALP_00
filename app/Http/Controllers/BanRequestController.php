<?php

namespace App\Http\Controllers;

use App\Models\BanRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BanRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'target_user_id' => 'required|exists:users,id',
            'reason' => 'required|string|min:5',
        ]);

        // Prevent duplicate pending requests
        $exists = BanRequest::where('target_user_id', $request->target_user_id)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Request already pending for this user.');
        }

        BanRequest::create([
            'organizer_id' => Auth::id(),
            'target_user_id' => $request->target_user_id,
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Ban request submitted to Admin.');
    }

    public function create(User $user)
    {
        return view('organizer.ban-requests.create', compact('user'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = collect(); // Empty by default

        if ($query) {
            $users = User::where('role', 'customer')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('identity_number', 'like', "%{$query}%");
                })
                ->limit(20)
                ->get();
        }

        return view('organizer.ban-requests.search', compact('users', 'query'));
    }
}
