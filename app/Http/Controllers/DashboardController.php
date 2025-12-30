<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\OrganizerProfile;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        // === LOGIC FOR ADMIN ===
        if ($user->role === 'admin') {
            // 1. Total Platform Revenue (Sum of all success transactions)
            $data['totalRevenue'] = Transaction::where('status', 'success')->sum('total_amount');

            // 2. Pending EO Approvals
            // Assuming your model is 'Organizer' and column is 'verification_status'
            $data['pendingEOs'] = OrganizerProfile::where('verification_status', 'pending')->count();

            // 3. Total Users
            $data['totalUsers'] = User::count();

            // 4. Growth Calculation (Revenue this month vs last month)
            $currentMonth = Transaction::where('status', 'success')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_amount');

            $lastMonth = Transaction::where('status', 'success')
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->sum('total_amount');

            // Avoid division by zero
            if ($lastMonth > 0) {
                $data['growthPercentage'] = number_format((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
            } else {
                $data['growthPercentage'] = 100; // If last month was 0, it's 100% growth
            }
        }

        // === LOGIC FOR EVENT ORGANIZER (EO) ===
        elseif ($user->role === 'eo') {
            // Get events created by this user
            $myEvents = Event::where('user_id', $user->id);

            // 1. Active Events (Start time is in the future)
            $data['activeEvents'] = (clone $myEvents)->where('start_time', '>=', now())->count();

            // 2. Total Tickets Sold (via relationship)
            // Assuming Event hasMany Tickets
            $data['ticketsSold'] = \App\Models\Ticket::whereHas('event', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'sold')->count();

            // 3. Total Revenue for this EO
            $data['eoRevenue'] = Transaction::whereHas('event', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'success')->sum('total_amount');
        }

        // === LOGIC FOR USER (CUSTOMER) ===
        else {
            // User logic is mostly handled in Blade (checking organizer_profile),
            // but you could pass recent purchases here if you wanted.
        }

        return view('dashboard', $data);
    }
}
