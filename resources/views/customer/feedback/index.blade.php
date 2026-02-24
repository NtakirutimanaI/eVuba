@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-star" style="color: var(--primary);"></i> My Feedback</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Manage your service feedback and ratings.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success"
            style="padding: 15px; margin-bottom: 20px; background: #d1fae5; color: #065f46; border-radius: 8px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="main-grid">
        {{-- Pending Feedback --}}
        <div class="pro-card glass-panel" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3><i class="fas fa-clock"></i> Pending Feedback</h3>
            </div>
            <div class="table-container">
                <table class="pro-table">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedbacks as $fb)
                            <tr>
                                <td>
                                    <strong>{{ class_basename($fb->feedbackable_type) }}
                                        #{{ $fb->feedbackable->ticket_no ?? $fb->feedbackable->id }}</strong><br>
                                    <small>{{ $fb->feedbackable->subject ?? $fb->feedbackable->title }}</small>
                                </td>
                                <td>{{ $fb->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('customer.feedback.submit', $fb->id) }}"
                                        class="btn btn-primary btn-sm"
                                        style="padding: 6px 12px; border-radius: 6px; text-decoration: none; background: var(--primary); color: white;">Submit
                                        Feedback</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 2rem;">No pending feedback requests.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Submitted Feedback --}}
        <div class="pro-card glass-panel">
            <div class="card-header">
                <h3><i class="fas fa-check-circle"></i> Submitted Feedback</h3>
            </div>
            <div class="table-container">
                <table class="pro-table">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Rating</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submitted as $fb)
                            <tr>
                                <td>
                                    <strong>{{ class_basename($fb->feedbackable_type) }}
                                        #{{ $fb->feedbackable->ticket_no ?? $fb->feedbackable->id }}</strong>
                                </td>
                                <td>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star"
                                            style="color: {{ $i <= $fb->rating ? '#fbbf24' : '#e5e7eb' }};"></i>
                                    @endfor
                                </td>
                                <td>{{ $fb->updated_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 2rem;">No feedback submitted yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>