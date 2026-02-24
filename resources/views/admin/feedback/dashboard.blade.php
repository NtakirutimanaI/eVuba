@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Admin Feedback & SLA Analytics</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Monitor customer satisfaction and SLA compliance across
                all services.</p>
        </div>
    </div>

    {{-- Stats Grid --}}
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
        <div class="pro-card glass-panel" style="margin-bottom: 2rem;">
            <div class="card-header">
                <h3><i class="fas fa-chart-bar"></i> SLA Components Performance</h3>
            </div>
            <div style="display: flex; gap: 2rem; padding: 20px;">
                <div style="flex: 1; text-align: center;">
                    <h4>Response Time</h4>
                    <h2 style="color: var(--primary);">{{ number_format($avgResponse, 1) }} <small
                            style="font-size: 1rem; color: var(--secondary);">/ 5</small></h2>
                </div>
                <div style="flex: 1; text-align: center;">
                    <h4>Resolution Quality</h4>
                    <h2 style="color: #10b981;">{{ number_format($avgResolution, 1) }} <small
                            style="font-size: 1rem; color: var(--secondary);">/ 5</small></h2>
                </div>
                <div style="flex: 1; text-align: center;">
                    <h4>Communication</h4>
                    <h2 style="color: #f59e0b;">{{ number_format($avgCommunication, 1) }} <small
                            style="font-size: 1rem; color: var(--secondary);">/ 5</small></h2>
                </div>
            </div>
        </div>
    </div>

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
                            <td>{{ class_basename($fb->feedbackable_type) }}
                                #{{ $fb->feedbackable->ticket_no ?? $fb->feedbackable->id }}</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star"
                                        style="color: {{ $i <= $fb->rating ? '#fbbf24' : '#e5e7eb' }}; font-size: 0.8rem;"></i>
                                @endfor
                            </td>
                            <td>
                                @if($fb->sla_compliant)
                                    <span class="status-badge status-completed"
                                        style="background: rgba(16,185,129,0.1); color: #10b981; padding: 4px 8px; border-radius: 4px;">Yes</span>
                                @else
                                    <span class="status-badge status-cancelled"
                                        style="background: rgba(239,68,68,0.1); color: #ef4444; padding: 4px 8px; border-radius: 4px;">No</span>
                                @endif
                            </td>
                            <td style="font-size: 0.8rem; color: var(--secondary);">{{ $fb->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--secondary); padding: 2rem;">No feedback
                                available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>