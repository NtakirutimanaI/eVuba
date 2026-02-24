@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-star-half-alt" style="color: var(--primary);"></i> Submit Feedback</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Provide SLA-based structured evaluation for
                {{ class_basename($feedback->feedbackable_type) }}
                #{{ $feedback->feedbackable->ticket_no ?? $feedback->feedbackable->id }}</p>
        </div>
        <div>
            <a href="{{ route('customer.feedback.index') }}" class="btn"
                style="background: var(--surface); padding: 8px 16px; border-radius: 6px; text-decoration: none; color: var(--text-primary); border: 1px solid var(--border-color);">Back</a>
        </div>
    </div>

    <div class="pro-card glass-panel" style="max-width: 800px; margin: 0 auto;">
        <form action="{{ route('customer.feedback.store', $feedback->id) }}" method="POST" enctype="multipart/form-data"
            style="padding: 20px;">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Overall Rating (1-5) *</label>
                <input type="number" name="rating" min="1" max="5" required class="form-control"
                    style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; background: var(--bg-main); color: var(--text-primary);">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Response Time SLA Rating (1-5)
                    *</label>
                <input type="number" name="response_time_rating" min="1" max="5" required class="form-control"
                    style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; background: var(--bg-main); color: var(--text-primary);">
                <small style="color: var(--secondary);">How quickly did we respond?</small>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Resolution Quality SLA Rating (1-5)
                    *</label>
                <input type="number" name="resolution_quality_rating" min="1" max="5" required class="form-control"
                    style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; background: var(--bg-main); color: var(--text-primary);">
                <small style="color: var(--secondary);">Was the problem successfully resolved?</small>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Communication SLA Rating (1-5)
                    *</label>
                <input type="number" name="communication_rating" min="1" max="5" required class="form-control"
                    style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; background: var(--bg-main); color: var(--text-primary);">
                <small style="color: var(--secondary);">How effective was our communication?</small>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Written Comments</label>
                <textarea name="comments" rows="4" class="form-control"
                    style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; background: var(--bg-main); color: var(--text-primary);"></textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Optional Attachment</label>
                <input type="file" name="attachment" class="form-control"
                    style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; background: var(--bg-main); color: var(--text-primary);">
            </div>

            <button type="submit" class="btn btn-primary"
                style="padding: 10px 20px; border-radius: 8px; background: var(--primary); color: white; border: none; cursor: pointer; width: 100%; font-weight: 600;">Submit
                Feedback</button>
        </form>
    </div>
</div>