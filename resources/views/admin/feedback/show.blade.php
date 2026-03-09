@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <style>
        .custom-scrollbar {
            max-height: 250px;
            overflow-y: auto;
            padding-right: 15px;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.05); 
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--border-color); 
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: var(--secondary); 
        }
    </style>
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-receipt" style="color: var(--primary);"></i> Feedback Details #{{ $feedback->id }}</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">View detailed customer evaluations and metrics.</p>
        </div>
        <div>
            <a href="{{ route('admin.feedback.index') }}" class="btn"
                style="background: var(--surface); padding: 8px 16px; border-radius: 6px; text-decoration: none; color: var(--text-primary); border: 1px solid var(--border-color);">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="main-grid" style="grid-template-columns: 2fr 1fr; gap: 20px;">

        {{-- Left Column: Feedback Metrics & Details --}}
        <div>
            <div class="pro-card glass-panel" style="margin-bottom: 20px;">
                <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
                    <h3><i class="fas fa-star" style="color: #fbbf24;"></i> Ratings Overview</h3>
                </div>

                @if($feedback->status === 'submitted')
                    <div style="padding: 20px;">
                        {{-- Overall Rating --}}
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                            <div>
                                <h4 style="margin: 0; font-size: 1.1rem; color: var(--text-primary);">Overall Satisfaction
                                </h4>
                                <small style="color: var(--secondary);">The customer's primary score</small>
                            </div>
                            <div
                                style="font-size: 1.5rem; color: #fbbf24; font-weight: bold; display: flex; align-items: center; gap: 5px;">
                                {{ $feedback->rating }} / 5
                            </div>
                        </div>

                        {{-- SLA Components --}}
                        <div
                            style="background: var(--surface); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color);">
                            <h4 style="margin: 0 0 15px 0; color: var(--text-primary); font-size: 0.95rem;">SLA Component
                                Breakdown</h4>

                            <div style="display: flex; flex-direction: column; gap: 15px;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="color: var(--secondary);">Response Time</span>
                                    <div style="display: flex; gap: 3px; font-size: 1rem;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star"
                                                style="color: {{ $i <= $feedback->response_time_rating ? '#fbbf24' : '#e5e7eb' }};"></i>
                                        @endfor
                                    </div>
                                </div>

                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="color: var(--secondary);">Resolution Quality</span>
                                    <div style="display: flex; gap: 3px; font-size: 1rem;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star"
                                                style="color: {{ $i <= $feedback->resolution_quality_rating ? '#fbbf24' : '#e5e7eb' }};"></i>
                                        @endfor
                                    </div>
                                </div>

                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="color: var(--secondary);">Communication</span>
                                    <div style="display: flex; gap: 3px; font-size: 1rem;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star"
                                                style="color: {{ $i <= $feedback->communication_rating ? '#fbbf24' : '#e5e7eb' }};"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div style="padding: 30px; text-align: center; color: var(--secondary);">
                        <i class="far fa-clock" style="font-size: 2.5rem; margin-bottom: 10px; opacity: 0.5;"></i>
                        <p>This feedback request is still pending customer submission.</p>
                    </div>
                @endif
            </div>

            {{-- Written Comments Box --}}
            <div class="pro-card glass-panel" style="margin-bottom: 20px;">
                <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
                    <h3><i class="far fa-comment-dots" style="color: var(--primary);"></i> Customer Comments</h3>
                </div>
                <div style="padding: 20px;">
                    @if($feedback->status === 'submitted' && !empty($feedback->comments))
                        <div class="custom-scrollbar"
                            style="background: var(--surface); padding: 15px; border-radius: 8px; border-left: 4px solid var(--primary); font-size: 0.95rem; line-height: 1.6; color: var(--text-primary); white-space: pre-wrap;">
                            {{ $feedback->comments }}
                        </div>
                    @else
                        <p style="color: var(--secondary); font-style: italic; margin: 0;">No written comments provided.</p>
                    @endif
                </div>
            </div>

            {{-- Attachments --}}
            @if($feedback->attachment)
                <div class="pro-card glass-panel" style="margin-bottom: 20px;">
                    <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
                        <h3><i class="fas fa-paperclip" style="color: #6366f1;"></i> Attached File</h3>
                    </div>
                    <div style="padding: 20px;">
                        <a href="{{ Storage::url($feedback->attachment) }}" target="_blank" class="btn"
                            style="background: var(--surface); border: 1px solid var(--border-color); color: var(--text-primary); font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; padding: 10px 15px; border-radius: 6px;">
                            <i class="fas fa-file-download" style="color: var(--primary);"></i> Download / View Attachment
                        </a>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column: Context Information --}}
        <div>
            <div class="pro-card glass-panel" style="margin-bottom: 20px;">
                <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
                    <h3><i class="fas fa-info-circle" style="color: var(--primary);"></i> Submission Details</h3>
                </div>
                <div style="padding: 15px;">
                    <ul
                        style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px; font-size: 0.9rem;">
                        <li style="display: flex; justify-content: space-between;">
                            <span style="color: var(--secondary);">Status</span>
                            @if($feedback->status === 'submitted')
                                <span style="font-weight: 600; color: #10b981;">Submitted</span>
                            @else
                                <span style="font-weight: 600; color: #f59e0b;">Pending</span>
                            @endif
                        </li>
                        <li style="display: flex; justify-content: space-between;">
                            <span style="color: var(--secondary);">Generated Date</span>
                            <span
                                style="font-weight: 500; color: var(--text-primary);">{{ $feedback->created_at->format('d M, Y H:i') }}</span>
                        </li>
                        <li style="display: flex; justify-content: space-between;">
                            <span style="color: var(--secondary);">Submitted Date</span>
                            <span
                                style="font-weight: 500; color: var(--text-primary);">{{ $feedback->status === 'submitted' ? $feedback->updated_at->format('d M, Y H:i') : 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pro-card glass-panel" style="margin-bottom: 20px;">
                <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
                    <h3><i class="fas fa-user-circle" style="color: var(--primary);"></i> Customer Info</h3>
                </div>
                <div style="padding: 15px;">
                    <div style="margin-bottom: 15px;">
                        <strong
                            style="color: var(--text-primary); font-size: 1.1rem; display: block;">{{ $feedback->customer->name ?? 'Unknown Customer' }}</strong>
                        <span
                            style="color: var(--secondary); font-size: 0.9rem;">{{ $feedback->customer->email ?? 'No email' }}</span>
                    </div>
                </div>
            </div>

            <div class="pro-card glass-panel" style="margin-bottom: 20px;">
                <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
                    <h3><i class="fas fa-wrench" style="color: var(--primary);"></i> Target Service</h3>
                </div>
                <div style="padding: 15px;">
                    <div style="margin-bottom: 10px;">
                        <span
                            style="background: var(--surface); color: var(--text-primary); padding: 3px 8px; border-radius: 4px; font-size: 0.8rem; border: 1px solid var(--border-color); font-weight: 500;">
                            {{ class_basename($feedback->feedbackable_type) }}
                        </span>
                    </div>
                    <div style="color: var(--text-primary); font-weight: 500;">
                        ID: #{{ $feedback->feedbackable->ticket_no ?? $feedback->feedbackable->id }}
                    </div>
                    <div style="color: var(--secondary); font-size: 0.9rem; margin-top: 5px;">
                        {{ $feedback->feedbackable->subject ?? $feedback->feedbackable->title ?? 'Service Task' }}
                    </div>

                    <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 15px 0;">

                    <div style="font-size: 0.9rem;">
                        <div style="margin-bottom: 8px;">
                            <span style="color: var(--secondary);">SLA Threshold:</span>
                            <strong
                                style="color: var(--text-primary);">{{ $feedback->sla_threshold > 60 ? floor($feedback->sla_threshold / 60) . ' hrs' : $feedback->sla_threshold . ' min' }}</strong>
                        </div>
                        <div style="margin-bottom: 8px;">
                            <span style="color: var(--secondary);">Completion Time:</span>
                            <strong
                                style="color: var(--text-primary);">{{ $feedback->actual_completion_time ? ($feedback->actual_completion_time > 60 ? floor($feedback->actual_completion_time / 60) . ' hrs' : $feedback->actual_completion_time . ' min') : 'N/A' }}</strong>
                        </div>
                        <div>
                            <span style="color: var(--secondary);">SLA Met?</span>
                            @if($feedback->sla_compliant)
                                <span style="color: #10b981; font-weight: 600;"><i class="fas fa-check-circle"></i>
                                    Yes</span>
                            @else
                                <span style="color: #ef4444; font-weight: 600;"><i class="fas fa-times-circle"></i>
                                    No</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top: 30px;">
                <form action="{{ route('admin.feedback.destroy', $feedback->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to permanently delete this feedback?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn"
                        style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid #ef4444; width: 100%; padding: 10px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-trash-alt"></i> Delete Feedback
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>