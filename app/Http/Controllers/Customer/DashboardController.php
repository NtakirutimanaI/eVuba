<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Counts
        $bookingsCount = $user->bookings()->count();
        $appointmentsCount = $user->appointments()->count();
        $ordersCount = $user->orders()->count();


        // Recent activity
        $recentBookings = $user->bookings()->with('service')->latest()->take(5)->get();
        $recentAppointments = $user->appointments()->latest()->take(5)->get();
        $recentOrders = $user->orders()->latest()->take(5)->get();

        // Charts


        // Booking Status
        $bookingStatusData = $user->bookings()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $bookingLabels = $bookingStatusData->pluck('status')->map(fn($s) => ucfirst($s))->toArray();
        $bookingCounts = $bookingStatusData->pluck('total')->toArray();

        return view('customer.dashboard', compact(
            'bookingsCount',
            'appointmentsCount',
            'ordersCount',

            'recentBookings',
            'recentAppointments',
            'recentOrders',

            'bookingLabels',
            'bookingCounts'
        ));
    }
}
