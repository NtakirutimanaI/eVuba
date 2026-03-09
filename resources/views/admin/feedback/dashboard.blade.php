@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Admin Feedback &amp; SLA Analytics</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">
                Monitor customer satisfaction and SLA compliance across all services.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.feedback.index') }}" class="btn"
                style="background: #0f172a; padding: 8px 16px; border-radius: 6px; text-decoration: none; color: white; border: none; font-weight: 500;">
                <i class="fas fa-list"></i> View All Feedback
            </a>
        </div>
    </div>

    {{-- KPI Stats Grid --}}
    <div class="dashboard-grid" style="grid-template-columns: repeat(4, 1fr);">
        <div class="pro-card metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #10b981, #3b82f6);">
                <i class="fas fa-check-double"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">Total Feedbacks</div>
                <div class="metric-value">{{ $totalFeedbacks }}</div>
            </div>
        </div>

        <div class="pro-card metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
                <i class="fas fa-star"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">Average Satisfaction</div>
                <div class="metric-value">{{ number_format($averageRating, 1) }} / 5</div>
            </div>
        </div>

        <div class="pro-card metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">SLA Compliance Rate</div>
                <div class="metric-value">{{ number_format($slaComplianceRate, 1) }}%</div>
            </div>
        </div>

        <div class="pro-card metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #ec4899, #8b5cf6);">
                <i class="fas fa-heart"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">Resolution Quality</div>
                <div class="metric-value">{{ number_format($avgResolution, 1) }} / 5</div>
            </div>
        </div>
    </div>

    <div class="main-grid" style="margin-top: 1.5rem;">
        {{-- SLA Components Panel --}}
        <div class="pro-card glass-panel" style="margin-bottom: 2rem;">
            <div class="card-header">
                <h3><i class="fas fa-chart-bar"></i> SLA Components Performance</h3>
            </div>
            <div style="display: flex; gap: 2rem; padding: 20px; flex-wrap: wrap;">
                <div style="flex: 1; text-align: center; min-width: 120px;">
                    <h4>Response Time</h4>
                    <h2 style="color: var(--primary);">
                        {{ number_format($avgResponse, 1) }}
                        <small style="font-size: 1rem; color: var(--secondary);">/ 5</small>
                    </h2>
                </div>
                <div style="flex: 1; text-align: center; min-width: 120px;">
                    <h4>Resolution Quality</h4>
                    <h2 style="color: #10b981;">
                        {{ number_format($avgResolution, 1) }}
                        <small style="font-size: 1rem; color: var(--secondary);">/ 5</small>
                    </h2>
                </div>
                <div style="flex: 1; text-align: center; min-width: 120px;">
                    <h4>Communication</h4>
                    <h2 style="color: #f59e0b;">
                        {{ number_format($avgCommunication, 1) }}
                        <small style="font-size: 1rem; color: var(--secondary);">/ 5</small>
                    </h2>
                </div>
            </div>
        </div>

        {{-- Satisfaction Trend Chart --}}
        <div class="pro-card glass-panel" style="margin-bottom: 2rem;">
            <div class="card-header">
                <h3><i class="fas fa-chart-line"></i> Satisfaction Trend (Last 6 Months)</h3>
            </div>
            <div id="trendChart" class="chart-container" style="min-height: 300px; padding: 20px;"></div>
        </div>

        {{-- Technician Performance Table --}}
        <div class="pro-card glass-panel" style="margin-bottom: 2rem;">
            <div class="card-header">
                <h3><i class="fas fa-users-cog"></i> Technician Performance</h3>
            </div>
            <div class="table-responsive">
                <table class="pro-table">
                    <thead>
                        <tr>
                            <th>Technician</th>
                            <th>Feedbacks Received</th>
                            <th>Average Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($technicianScores as $score)
                            <tr>
                                <td>{{ $score['name'] }}</td>
                                <td>{{ $score['total'] }}</td>
                                <td>
                                    <span style="font-weight: 700; color: {{ $score['average'] >= 4 ? '#10b981' : ($score['average'] >= 3 ? '#f59e0b' : '#ef4444') }};">
                                        {{ number_format($score['average'], 1) }} / 5
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; color: var(--secondary); padding: 2rem;">
                                    No performance data available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Feedback Table --}}
    <div class="pro-card glass-panel">
        <div class="card-header">
            <h3><i class="fas fa-comments"></i> Recent Feedback</h3>
        </div>
        <div class="table-responsive">
            <table class="pro-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Rating</th>
                        <th>SLA Met?</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentFeedbacks as $fb)
                        <tr>
                            <td>{{ $fb->customer->name ?? 'Unknown' }}</td>
                            <td>
                                {{ class_basename($fb->feedbackable_type) }}
                                #{{ $fb->feedbackable->ticket_no ?? ($fb->feedbackable->id ?? '—') }}
                            </td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star"
                                        style="color: {{ $i <= $fb->rating ? '#fbbf24' : '#e5e7eb' }}; font-size: 0.8rem;"></i>
                                @endfor
                            </td>
                            <td>
                                @php
                                    $ratingsOk = $fb->rating >= 3
                                        && $fb->response_time_rating >= 3
                                        && $fb->resolution_quality_rating >= 3
                                        && $fb->communication_rating >= 3;
                                    $slaMet = $fb->sla_compliant && $ratingsOk;
                                @endphp
                                @if($slaMet)
                                    <span style="background: rgba(16,185,129,0.1); color: #10b981; padding: 4px 8px; border-radius: 4px;">
                                        <i class="fas fa-check"></i> Yes
                                    </span>
                                @else
                                    <span style="background: rgba(239,68,68,0.1); color: #ef4444; padding: 4px 8px; border-radius: 4px;">
                                        <i class="fas fa-times"></i> No
                                    </span>
                                @endif
                            </td>
                            <td style="font-size: 0.8rem; color: var(--secondary);">
                                {{ $fb->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--secondary); padding: 2rem;">
                                No feedback available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var trendOptions = {
        series: [{
            name: 'Average Rating',
            data: @json($trendData)
        }],
        chart: {
            height: 300,
            type: 'area',
            toolbar: { show: false },
            fontFamily: 'inherit',
            background: 'transparent'
        },
        colors: ['#6366f1'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.1, stops: [0, 90, 100] }
        },
        dataLabels: { enabled: true },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: { categories: @json($trendMonths) },
        yaxis: { min: 0, max: 5, tickAmount: 5 },
        tooltip: { y: { formatter: val => val + ' / 5' } }
    };

    new ApexCharts(document.querySelector('#trendChart'), trendOptions).render();
</script>