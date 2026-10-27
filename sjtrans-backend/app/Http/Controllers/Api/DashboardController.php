<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BusFleet;
use App\Models\ChatRoom;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Metrik & statistik untuk Admin Dashboard
     */
    public function stats(): JsonResponse
    {
        $totalBookings = Booking::count();
        $confirmedBookings = Booking::whereIn('status', ['confirmed', 'in_trip'])->count();
        $totalRevenue = Transaction::where('status', 'verified')->sum('amount');
        $totalFleets = BusFleet::count();
        $totalUsers = User::count();
        $waitingChats = ChatRoom::where('status', 'waiting')->count();

        $recentBookings = Booking::with('fleet')
            ->latest()
            ->take(6)
            ->get();

        $recentTransactions = Transaction::with('booking')
            ->latest()
            ->take(6)
            ->get();

        return response()->json([
            'metrics' => [
                'total_bookings' => $totalBookings,
                'confirmed_bookings' => $confirmedBookings,
                'total_revenue' => (float) $totalRevenue,
                'total_fleets' => $totalFleets,
                'total_users' => $totalUsers,
                'waiting_chats' => $waitingChats,
            ],
            'recent_bookings' => $recentBookings,
            'recent_transactions' => $recentTransactions,
        ]);
    }
}
