<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Feedback;

class FeedbackAnalyticsController extends Controller
{
    public function index()
    {
        $totalFeedbacks = Feedback::where('status', 'submitted')->count();
        $averageRating = Feedback::where('status', 'submitted')->avg('rating') ?? 0;
        $slaCompliantCount = Feedback::where('status', 'submitted')->where('sla_compliant', true)->count();
        $slaComplianceRate = $totalFeedbacks > 0 ? ($slaCompliantCount / $totalFeedbacks) * 100 : 0;

        $recentFeedbacks = Feedback::with(['feedbackable', 'customer'])
            ->where('status', 'submitted')
            ->latest()
            ->limit(10)
            ->get();

        $avgResponse = Feedback::where('status', 'submitted')->avg('response_time_rating') ?? 0;
        $avgResolution = Feedback::where('status', 'submitted')->avg('resolution_quality_rating') ?? 0;
        $avgCommunication = Feedback::where('status', 'submitted')->avg('communication_rating') ?? 0;

        return view('admin.feedback.dashboard', compact(
            'totalFeedbacks',
            'averageRating',
            'slaComplianceRate',
            'recentFeedbacks',
            'avgResponse',
            'avgResolution',
            'avgCommunication'
        ));
    }
}
