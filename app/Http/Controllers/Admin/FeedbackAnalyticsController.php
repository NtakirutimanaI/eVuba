<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Carbon\Carbon;

class FeedbackAnalyticsController extends Controller
{
    public function index()
    {
        // Reusable base scope for submitted feedbacks
        $submittedBase = fn() => Feedback::where('status', 'submitted');

        $totalFeedbacks = $submittedBase()->count();
        $averageRating = (float) ($submittedBase()->avg('rating') ?? 0);
        // SLA compliant: must have been time-compliant AND customer gave all ratings >= 3
        $slaCompliantCount = $submittedBase()
            ->where('sla_compliant', true)
            ->where('rating', '>=', 3)
            ->where('response_time_rating', '>=', 3)
            ->where('resolution_quality_rating', '>=', 3)
            ->where('communication_rating', '>=', 3)
            ->count();
        $slaComplianceRate = $totalFeedbacks > 0 ? ($slaCompliantCount / $totalFeedbacks) * 100 : 0;

        $avgResponse = (float) ($submittedBase()->avg('response_time_rating') ?? 0);
        $avgResolution = (float) ($submittedBase()->avg('resolution_quality_rating') ?? 0);
        $avgCommunication = (float) ($submittedBase()->avg('communication_rating') ?? 0);

        // Recent feedbacks (last 10)
        $recentFeedbacks = $submittedBase()
            ->with(['feedbackable', 'customer'])
            ->latest()
            ->limit(10)
            ->get();

        // Technician Performance: aggregate ratings per technician from submitted feedbacks
        $technicianScores = [];
        $submittedBase()->with([
            'feedbackable' => function ($morphTo) {
                $morphTo->morphWith([
                    \App\Models\Ticket::class => ['assigned'],
                    \App\Models\Appointment::class => ['employee'],
                ]);
            }
        ])->get()
            ->each(function ($fb) use (&$technicianScores) {
                $tech = null;

                if ($fb->feedbackable_type === 'App\Models\Ticket') {
                    $tech = optional($fb->feedbackable)->assigned;
                } elseif ($fb->feedbackable_type === 'App\Models\Appointment') {
                    $tech = optional($fb->feedbackable)->employee;
                }

                if ($tech) {
                    $id = $tech->id;
                    if (!isset($technicianScores[$id])) {
                        $technicianScores[$id] = [
                            'name' => $tech->name,
                            'total' => 0,
                            'rating_sum' => 0,
                        ];
                    }
                    $technicianScores[$id]['total']++;
                    $technicianScores[$id]['rating_sum'] += $fb->rating ?? 0;
                }
            });

        foreach ($technicianScores as &$score) {
            $score['average'] = $score['total'] > 0
                ? round($score['rating_sum'] / $score['total'], 1)
                : 0;
        }
        unset($score);

        usort($technicianScores, fn($a, $b) => $b['average'] <=> $a['average']);

        // Satisfaction Trend: Average rating for the last 6 months
        $trendMonths = [];
        $trendData = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $trendMonths[] = $monthDate->format('M Y');
            $trendData[] = round(
                (float) ($submittedBase()
                    ->whereMonth('updated_at', $monthDate->month)
                    ->whereYear('updated_at', $monthDate->year)
                    ->avg('rating') ?? 0),
                1
            );
        }

        return view('admin.feedback.dashboard', compact(
            'totalFeedbacks',
            'averageRating',
            'slaComplianceRate',
            'recentFeedbacks',
            'avgResponse',
            'avgResolution',
            'avgCommunication',
            'technicianScores',
            'trendMonths',
            'trendData'
        ));
    }
}
