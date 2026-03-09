@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>
                <i class="fas fa-comments" style="color: var(--primary);"></i>
                Customer Feedback Data
            </h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">
                Browse and analyze all detailed feedback submissions.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.feedback.dashboard') }}" class="btn"
                style="background: var(--surface); padding: 8px 16px; border-radius: 6px; text-decoration: none; color: var(--text-primary); border: 1px solid var(--border-color);">
                <i class="fas fa-chart-pie"></i> View Analytics Dashboard
            </a>
        </div>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="alert alert-success"
            style="padding: 15px; margin-bottom: 20px; background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid #10b981; border-radius: 8px;">
            <i class="fas fa-check-circle" style="margin-right: 6px;"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="pro-card glass-panel">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> All Feedback Records</h3>
        </div>

        <div class="table-container">
            <table class="pro-table" id="feedbackTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Date Received</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $fb)
                        <tr>
                            <td>#{{ $fb->id }}</td>
                            <td>
                                <strong>{{ $fb->customer->name ?? 'Unknown Customer' }}</strong><br>
                                <small style="color: var(--secondary);">{{ $fb->customer->email ?? '—' }}</small>
                            </td>
                            <td>
                                <span
                                    style="background: var(--surface); color: var(--text-primary); padding: 2px 6px; border-radius: 4px; font-size: 0.8rem; border: 1px solid var(--border-color);">
                                    {{ class_basename($fb->feedbackable_type) }}
                                </span>
                                <br>
                                <small>#{{ $fb->feedbackable->ticket_no ?? ($fb->feedbackable->id ?? '—') }}</small>
                            </td>
                            <td>
                                @if($fb->status === 'submitted' && $fb->rating)
                                    <div style="display: flex; gap: 2px; font-size: 0.9rem;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star"
                                                style="color: {{ $i <= $fb->rating ? '#fbbf24' : '#e5e7eb' }};"></i>
                                        @endfor
                                    </div>
                                @else
                                    <span style="color: var(--secondary); font-size: 0.9rem;">Not Rated</span>
                                @endif
                            </td>
                            <td>
                                @if($fb->status === 'submitted')
                                    <span
                                        style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 500;">
                                        <i class="fas fa-check-circle" style="margin-right: 3px;"></i>Submitted
                                    </span>
                                @else
                                    <span
                                        style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 500;">
                                        <i class="fas fa-clock" style="margin-right: 3px;"></i>Pending
                                    </span>
                                @endif
                            </td>
                            <td style="color: var(--secondary); font-size: 0.9rem;">
                                {{ $fb->created_at->format('d M, Y H:i') }}
                            </td>
                            <td>
                                <a href="{{ route('admin.feedback.show', $fb->id) }}" class="btn"
                                    style="background: var(--primary); color: white; padding: 6px 10px; border-radius: 6px; font-size: 0.85rem; text-decoration: none;">
                                    <i class="far fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 40px; text-align: center; color: var(--secondary);">
                                <i class="fas fa-inbox"
                                    style="font-size: 3rem; opacity: 0.2; display: block; margin-bottom: 10px;"></i>
                                No feedback records found in the database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>