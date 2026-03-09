<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf; // barryvdh/laravel-dompdf
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookingsExport;
use Carbon\Carbon;

class ManagerBookingController extends Controller
{
    // Display bookings
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'service', 'employee']);

        if ($request->filled('start_date')) {
            $query->whereDate('booking_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('booking_date', '<=', $request->end_date);
        }

        // Clone query for stats to avoid pagination interference
        $statsQuery = clone $query;

        $stats = [
            'total' => $statsQuery->count(),
            'approved' => (clone $statsQuery)->where('status', 'approved')->count(),
            'pending' => (clone $statsQuery)->where('status', 'pending')->count(),
            'completed' => (clone $statsQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $statsQuery)->where('status', 'cancelled')->count() + (clone $statsQuery)->where('status', 'rejected')->count(),
        ];

        $bookings = $query->latest()->paginate(10)->withQueryString();

        return view('manager.bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Generate a report of all bookings (PDF or Excel)
     */
    public function generateReport(Request $request)
    {
        $query = Booking::with(['user', 'service', 'employee']);

        if ($request->filled('start_date')) {
            $query->whereDate('booking_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('booking_date', '<=', $request->end_date);
        }

        $bookings = $query->get();

        if ($request->has('type') && $request->type == 'excel') {
            return Excel::download(new BookingsExport($bookings), 'bookings.xlsx');
        } else {
            $pdf = Pdf::loadView('manager.bookings.report', compact('bookings'));
            return $pdf->download('bookings.pdf');
        }
    }

    // Show single booking (redirect to index)
    public function show($id)
    {
        return redirect()->route('manager.bookings.index');
    }

    // Update booking status (approve, cancel, reject, complete)
    public function updateStatus(Request $request, Booking $booking, $status)
    {
        $allowedStatuses = ['approved', 'cancelled', 'rejected', 'completed'];

        if (!in_array($status, $allowedStatuses)) {
            return redirect()->back()->with('error', 'Invalid status!');
        }

        $booking->status = $status;
        $booking->save();

        return redirect()->back()->with('success', 'Booking status updated successfully!');
    }

    // Show reschedule form
    public function reschedule(Booking $booking)
    {
        return view('manager.bookings.reschedule', compact('booking'));
    }

    // Update booking date after rescheduling
    public function updateReschedule(Request $request, Booking $booking)
    {
        $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
        ]);

        $booking->booking_date = $request->booking_date;
        $booking->save();

        return redirect()->route('manager.bookings.index')->with('success', 'Booking rescheduled successfully!');
    }
}
