<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with('feedbackable')
            ->where('customer_id', auth()->id())
            ->where('status', 'pending')
            ->get();

        $submitted = Feedback::with('feedbackable')
            ->where('customer_id', auth()->id())
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

        $feedback->update([
            'rating' => $request->rating,
            'response_time_rating' => $request->response_time_rating,
            'resolution_quality_rating' => $request->resolution_quality_rating,
            'communication_rating' => $request->communication_rating,
            'comments' => $request->comments,
            'attachment' => $attachmentPath,
            'status' => 'submitted',
        ]);

        return redirect()->route('customer.feedback.index')->with('success', 'Thank you! Your feedback has been submitted successfully.');
    }
}
