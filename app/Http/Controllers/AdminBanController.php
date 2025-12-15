<?php

namespace App\Http\Controllers;

use App\Models\BanRequest;
use Illuminate\Http\Request;

class AdminBanController extends Controller
{
    public function index()
    {
        // Get pending requests first, then history
        $pendingRequests = BanRequest::with(['organizer', 'targetUser'])
                            ->where('status', 'pending')
                            ->latest()
                            ->get();

        $historyRequests = BanRequest::with(['organizer', 'targetUser'])
                            ->where('status', '!=', 'pending')
                            ->latest()
                            ->limit(10) // Show last 10 history items
                            ->get();

        return view('admin.ban-requests', compact('pendingRequests', 'historyRequests'));
    }

    public function update(Request $request, $id)
    {
        $banRequest = BanRequest::findOrFail($id);
        $action = $request->input('action');

        if ($action === 'approve') {
            $banRequest->update(['status' => 'approved']);

            // Ban the user
            $user = $banRequest->targetUser;
            $user->is_banned = true;
            $user->save();

            return back()->with('success', 'User has been BANNED successfully.');
        }

        if ($action === 'reject') {
            $banRequest->update(['status' => 'rejected']);
            return back()->with('success', 'Request rejected. User remains active.');
        }

        return back();
    }
}
