<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        $customerId = auth()->id();

        // 1. Auto-generate missing feedbacks for completed tickets
        $completedTickets = \App\Models\Ticket::where('customer_id', $customerId)
            ->where('status', 'completed')
            ->doesntHave('feedback')
            ->get();

        foreach ($completedTickets as $ticket) {
            $ticket->generateFeedbackRequest();
        }

        // 2. Auto-generate missing feedbacks for completed appointments
        $completedAppointments = \App\Models\Appointment::where('user_id', $customerId)
            ->where('status', 'completed')
            ->doesntHave('feedback')
            ->get();

        foreach ($completedAppointments as $appointment) {
            $appointment->generateFeedbackRequest();
        }

        // 3. Fetch all pending and submitted feedbacks
        $feedbacks = Feedback::with('feedbackable')
            ->where('customer_id', $customerId)
            ->where('status', 'pending')
            ->get();

        $submitted = Feedback::with('feedbackable')
            ->where('customer_id', $customerId)
            ->where('status', 'submitted')
            ->get();

        return view('customer.feedback.index', compact('feedbacks', 'submitted'));
    }

    public function submit(Feedback $feedback)
    {
        if ($feedback->customer_id !== auth()->id()) {
            abort(403);
        }

        return view('customer.feedback.submit', compact('feedback'));
    }

    public function store(Request $request, Feedback $feedback)
    {
        if ($feedback->customer_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'response_time_rating' => 'required|integer|min:1|max:5',
            'resolution_quality_rating' => 'required|integer|min:1|max:5',
            'communication_rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        $attachmentPath = $feedback->attachment;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('feedbacks', 'public');
        }

        // SLA is met only if ALL customer ratings are >= 3 (acceptable or above).
        // If any rating is 1 or 2 (poor/very poor), the SLA is considered violated
        // regardless of the time-based pre-set value.
        $ratingsAllAcceptable = $request->rating >= 3
            && $request->response_time_rating >= 3
            && $request->resolution_quality_rating >= 3
            && $request->communication_rating >= 3;

        // Also factor in whether it was time-compliant originally.
        // SLA is only fully met if both time-based AND rating-based criteria pass.
        $slaCompliant = $ratingsAllAcceptable && (bool) $feedback->sla_compliant;

        $feedback->update([
            'rating' => $request->rating,
            'response_time_rating' => $request->response_time_rating,
            'resolution_quality_rating' => $request->resolution_quality_rating,
            'communication_rating' => $request->communication_rating,
            'comments' => $request->comments,
            'attachment' => $attachmentPath,
            'status' => 'submitted',
            'sla_compliant' => $slaCompliant,
        ]);

        return redirect()->route('customer.feedback.index')->with('success', 'Thank you! Your feedback has been submitted successfully.');
    }
}
