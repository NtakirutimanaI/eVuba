<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeBookingController extends Controller
{
    public function index()
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $bookings = \App\Models\Booking::where('employee_id', $userId)
            ->with(['user', 'service'])
            ->latest()
            ->paginate(10);
        return view('employee.bookings.index', compact('bookings'));
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = \App\Models\Booking::findOrFail($id);

        if ($booking->employee_id != \Illuminate\Support\Facades\Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $booking->update(['status' => $request->status]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Booking status updated.']);
        }

        return redirect()->back()->with('success', 'Booking status updated.');
    }

    public function create()
    {
        return view('employee.bookings.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function getDetails($id)
    {
        $booking = \App\Models\Booking::with(['user', 'service'])->findOrFail($id);

        if ($booking->employee_id != \Illuminate\Support\Facades\Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $booking->id,
                'title' => $booking->title,
                'booking_date' => $booking->booking_date?->format('M d, Y h:i A') ?? 'N/A',
                'status' => $booking->status,
                'customer_name' => $booking->user?->name ?? 'Guest',
                'customer_email' => $booking->user?->email ?? 'N/A',
                'service_name' => $booking->service?->name ?? 'N/A',
                'service_price' => number_format($booking->service?->price ?? 0) . ' RWF'
            ]
        ]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $booking = \App\Models\Booking::findOrFail($id);

        if ($booking->employee_id != \Illuminate\Support\Facades\Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'booking_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $booking->update([
            'title' => $request->title,
            'booking_date' => $request->booking_date,
            'status' => $request->status,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Booking updated successfully.']);
        }

        return redirect()->back()->with('success', 'Booking updated successfully.');
    }

    public function destroy($id)
    {
        $booking = \App\Models\Booking::findOrFail($id);

        if ($booking->employee_id != \Illuminate\Support\Facades\Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $booking->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Booking deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Booking deleted successfully.');
    }
}
