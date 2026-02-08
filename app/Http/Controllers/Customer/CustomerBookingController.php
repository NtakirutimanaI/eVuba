<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service; // Make sure Service model exists
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerBookingController extends Controller
{
    /**
     * Display a listing of the customer's bookings and show available services.
     */
    public function index(Request $request)
    {
        $query = Booking::where('user_id', Auth::id());

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('booking_date', 'desc')->paginate(10);

        // Global stats (avoiding pagination limits)
        $activeBookings = Booking::where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->count();

        // Also get all services for display
        $services = Service::with('employee')->get();

        return view('customer.bookings.index', compact('bookings', 'services', 'activeBookings'));
    }

    /**
     * Show the form for creating a new booking with available services.
     */
    public function create()
    {
        // Load all services along with their assigned employee
        $services = Service::with('employee')->get();

        return view('customer.bookings.create', compact('services'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
        ]);

        $service = Service::findOrFail($request->service_id);

        // Create booking
        Booking::create([
            'user_id' => Auth::id(),
            'service_id' => $service->id,
            'title' => $service->name,
            'description' => $service->description,
            'employee_id' => $service->employee ? $service->employee_id : null,
            'booking_date' => $request->booking_date,
            'status' => 'pending',
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Booking created successfully.']);
        }

        return redirect()->route('customer.bookings.index')
            ->with('success', 'Booking created successfully.');
    }

    /**
     * Show the form for editing a booking.
     */
    public function edit(Booking $booking)
    {
        // Ensure the booking belongs to the logged-in user
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'You are not authorized to edit this booking.');
        }

        // Load all services for selection in edit form
        $services = Service::with('employee')->get();

        return view('customer.bookings.edit', compact('booking', 'services'));
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        // Ensure the booking belongs to the logged-in user
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'You are not authorized to update this booking.');
        }

        // Validate input
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $service = Service::findOrFail($request->service_id);

        // Update booking
        $booking->update([
            'title' => $service->name,
            'description' => $request->description ?? $service->description,
            'employee_id' => $service->employee_id,
            'service_id' => $service->id,
            'booking_date' => $request->booking_date,
            'status' => $request->status,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Booking updated successfully.']);
        }

        return redirect()->route('customer.bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Booking $booking)
    {
        // ... existing destroy code ...
        // Ensure the booking belongs to the logged-in user
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'You are not authorized to delete this booking.');
        }

        $booking->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Booking deleted successfully.']);
        }

        return redirect()->route('customer.bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'booking' => $booking->load(['service', 'employee'])
        ]);
    }
}
