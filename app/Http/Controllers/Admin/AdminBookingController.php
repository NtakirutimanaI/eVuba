<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookingsExport;

class AdminBookingController extends Controller
{
    /**
     * Display list of bookings (paginated)
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'service', 'employee'])->latest();

        // Date Filtering
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('booking_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('booking_date', '<=', $request->end_date);
        }

        $bookings = $query->paginate(10);

        // ====== Stats (Filtered if needed, but keeping global for now or adapting) ======
        // To strictly match "scope" stats of manager, we might want to filter stats too, 
        // but let's keep it simple or matches the filtered scope if possible.
        // Let's stick to global stats for the cards to avoid complex query duplication 
        // unless requested, but the manager view implies "Target Period" stats.
        // For now, I will keep standard stats but let's try to match the "Scope Bookings" count logic if possible.

        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Generate Report (PDF or Excel)
     */
    public function generateReport(Request $request)
    {
        $query = Booking::with('user');

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('booking_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('booking_date', '<=', $request->end_date);
        }

        $bookings = $query->get();

        // Excel Export
        if ($request->type === 'excel') {
            return Excel::download(new BookingsExport($bookings), 'bookings.xlsx');
        }

        // PDF Export
        $pdf = PDF::loadView('admin.bookings.report', compact('bookings'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('bookings.pdf');
    }

    /**
     * HANDLE /admin/bookings/{booking}
     * Fixes the Internal Server Error when visiting /bookings/report
     */
    public function show($id)
    {
        // Since we are not using a specific show page for bookings, 
        // valid numeric IDs should redirect or show details.
        // But for now, just redirect to index as per previous logic for numeric IDs.

        return redirect()->route('admin.bookings.index');
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, Booking $booking, $status)
    {
        $allowedStatuses = ['approved', 'cancelled', 'rejected', 'completed'];

        if (!in_array($status, $allowedStatuses)) {
            return back()->with('error', 'Invalid status');
        }

        $booking->status = $status;
        $booking->save();

        return back()->with('success', 'Booking updated successfully!');
    }

    /**
     * Show reschedule form
     */
    public function reschedule(Booking $booking)
    {
        return view('admin.bookings.reschedule', compact('booking'));
    }

    /**
     * Update booking date after rescheduling
     */
    public function updateReschedule(Request $request, Booking $booking)
    {
        $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
        ]);

        $booking->booking_date = $request->booking_date;
        $booking->save();

        // Notify Customer
        if ($booking->user) {
            $booking->user->notify(new \App\Notifications\BookingRescheduledNotification($booking));
        }

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Booking rescheduled successfully!');
    }
}
