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
        $totalSpent = $user->orders()->where('status', 'completed')->sum(DB::raw('quantity * price'));

        // Recent activity
        $recentBookings = $user->bookings()->with('service')->latest()->take(5)->get();
        $recentAppointments = $user->appointments()->latest()->take(5)->get();
        $recentOrders = $user->orders()->latest()->take(5)->get();

        // Charts
        // Spending over last 6 months
        $spendingData = $user->orders()
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(quantity * price) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $spendingLabels = $spendingData->pluck('month')->map(fn($m) => Carbon::create()->month($m)->format('F'))->toArray();
        $spendingValues = $spendingData->pluck('total')->toArray();

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
            'totalSpent',
            'recentBookings',
            'recentAppointments',
            'recentOrders',
            'spendingLabels',
            'spendingValues',
            'bookingLabels',
            'bookingCounts'
        ));
    }
}
