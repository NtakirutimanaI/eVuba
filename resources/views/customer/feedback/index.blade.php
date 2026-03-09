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

    <style>
        .custom-scrollbar {
            max-height: 60vh;
            overflow-y: auto;
            padding-right: 15px;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: var(--secondary);
        }

        @media (max-width: 768px) {
            .mobile-flex-col {
                flex-direction: column !important;
            }

            .mobile-action-btn {
                margin-left: 0 !important;
                margin-top: 15px !important;
                width: 100%;
                justify-content: center;
            }

            .mobile-stats-wrap {
                gap: 10px !important;
                flex-direction: column;
                align-items: flex-start !important;
            }
        }
    </style>

    <div class="main-grid">
        {{-- Pending Feedback List (Completed Services) --}}
        <h2 style="margin-bottom: 5px; color: var(--text-primary);"><i class="fas fa-list-check"></i> Completed Services
        </h2>
        <p style="color: var(--secondary); margin-bottom: 20px;">Please provide feedback for your recently completed
            services</p>

        <div class="custom-scrollbar">
            @forelse($feedbacks as $fb)
                @php
                    $isTicket = $fb->feedbackable_type === 'App\Models\Ticket';
                    $serviceId = $isTicket ? $fb->feedbackable->ticket_no : 'APT-' . str_pad($fb->feedbackable->id, 3, '0', STR_PAD_LEFT);
                    $serviceTypeStr = $isTicket ? 'Support Ticket' : 'Appointment';
                    $serviceIcon = $isTicket ? 'fa-ticket-alt' : 'fa-calendar-alt';
                    $serviceIconColor = $isTicket ? '#8b5cf6' : '#3b82f6';
                    $serviceIconBg = $isTicket ? '#f3e8ff' : '#eff6ff';
                    $title = $isTicket ? $fb->feedbackable->subject : ($fb->feedbackable->title ?? 'Service Appointment');
                    $description = $isTicket ? $fb->feedbackable->description : ($fb->feedbackable->description ?? 'Scheduled service delivery');
                    $technician = $isTicket ? ($fb->feedbackable->assigned->name ?? 'Unassigned') : ($fb->feedbackable->employee->name ?? 'Unassigned');
                    $completedAt = $fb->updated_at->format('d/m/Y \a\t H:i');

                    $durationStr = 'N/A';
                    if ($fb->actual_completion_time) {
                        $hours = floor($fb->actual_completion_time / 60);
                        $durationStr = $hours > 0 ? $hours . ' hours' : $fb->actual_completion_time . ' mins';
                    }
                @endphp

                <div class="pro-card glass-panel"
                    style="margin-bottom: 1.5rem; border-radius: 12px; padding: 20px; border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;"
                        class="mobile-flex-col">

                        {{-- Left side Content --}}
                        <div style="display: flex; gap: 15px; width: 100%;">
                            <div
                                style="min-width: 40px; height: 40px; background: {{ $serviceIconBg }}; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: {{ $serviceIconColor }}; font-size: 1.2rem;">
                                <i class="fas {{ $serviceIcon }}"></i>
                            </div>

                            <div style="flex-grow: 1;">
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 5px;">
                                    <span style="color: var(--secondary); font-size: 0.9rem;">{{ $serviceId }}</span>
                                    <span
                                        style="background: rgba(148, 163, 184, 0.1); color: var(--text-primary); font-size: 0.8rem; padding: 2px 8px; border-radius: 4px; font-weight: 500;">{{ $serviceTypeStr }}</span>
                                </div>

                                <h3 style="margin: 0 0 5px 0; font-size: 1.2rem; color: var(--text-primary);">{{ $title }}
                                </h3>
                                <p style="color: var(--secondary); font-size: 0.95rem; margin: 0 0 15px 0;">
                                    {{ Str::limit($description, 80) }}
                                </p>

                                <div class="mobile-stats-wrap"
                                    style="display: flex; flex-wrap: wrap; gap: 20px; align-items: center; font-size: 0.9rem; color: var(--text-primary); margin-bottom: 15px;">
                                    <div><i class="far fa-clock" style="color: var(--secondary); margin-right: 5px;"></i>
                                        Completed: {{ $completedAt }}</div>
                                    <div><span style="color: var(--secondary);">Technician:</span>
                                        <strong>{{ $technician }}</strong>
                                    </div>
                                    <div><span style="color: var(--secondary);">Duration:</span>
                                        <strong>{{ $durationStr }}</strong>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    <div
                                        style="background: var(--bg-main); padding: 5px 12px; border-radius: 6px; font-size: 0.85rem; color: var(--text-primary); border: 1px solid var(--border-color);">
                                        Response SLA:
                                        <strong>{{ $fb->sla_threshold > 60 ? floor($fb->sla_threshold / 60) . ' hrs' : $fb->sla_threshold . ' min' }}</strong>
                                    </div>
                                    <div
                                        style="background: var(--bg-main); padding: 5px 12px; border-radius: 6px; font-size: 0.85rem; color: var(--text-primary); border: 1px solid var(--border-color);">
                                        Resolution SLA: <strong>{{ floor($fb->sla_threshold / 2 / 60) }} hrs</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right side Action --}}
                        <div style="flex-shrink: 0; margin-left: 20px;" class="mobile-action-btn">
                            <a href="{{ route('customer.feedback.submit', $fb->id) }}" class="btn"
                                style="background: #0f172a; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: background 0.2s;">
                                <i class="far fa-comment-alt"></i> Provide Feedback
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="pro-card glass-panel"
                    style="text-align: center; padding: 3rem; border: 1px dashed var(--border-color);">
                    <i class="fas fa-check-double"
                        style="font-size: 2.5rem; color: #10b981; margin-bottom: 15px; opacity: 0.5;"></i>
                    <h3 style="color: var(--secondary);">You're all caught up!</h3>
                    <p style="color: var(--secondary);">You have provided feedback for all completed services.</p>
                </div>
            @endforelse
        </div>

        {{-- Submitted Feedback --}}
        @if($submitted->count() > 0)
            <div style="margin-top: 40px; border-top: 1px solid var(--border-color); padding-top: 30px;">
                <h2 style="margin-bottom: 5px; color: var(--text-primary);"><i class="fas fa-history"></i> Feedback History
                </h2>
                <p style="color: var(--secondary); margin-bottom: 20px;">Your previously submitted evaluations</p>

                <div class="custom-scrollbar">
                    @foreach($submitted as $fb)
                        @php
                            $isTicket = $fb->feedbackable_type === 'App\Models\Ticket';
                            $serviceId = $isTicket ? $fb->feedbackable->ticket_no : 'APT-' . str_pad($fb->feedbackable->id, 3, '0', STR_PAD_LEFT);
                            $serviceTypeStr = $isTicket ? 'Support Ticket' : 'Appointment';
                            $serviceIcon = $isTicket ? 'fa-ticket-alt' : 'fa-calendar-alt';
                            $serviceIconColor = $isTicket ? '#8b5cf6' : '#3b82f6';
                            $serviceIconBg = $isTicket ? '#f3e8ff' : '#eff6ff';
                            $title = $isTicket ? $fb->feedbackable->subject : ($fb->feedbackable->title ?? 'Service Appointment');
                            $description = $isTicket ? $fb->feedbackable->description : ($fb->feedbackable->description ?? 'Scheduled service delivery');
                            $technician = $isTicket ? ($fb->feedbackable->assigned->name ?? 'Unassigned') : ($fb->feedbackable->employee->name ?? 'Unassigned');
                            $completedAt = $fb->updated_at->format('d/m/Y \a\t H:i');

                            $durationStr = 'N/A';
                            if ($fb->actual_completion_time) {
                                $hours = floor($fb->actual_completion_time / 60);
                                $durationStr = $hours > 0 ? $hours . ' hours' : $fb->actual_completion_time . ' mins';
                            }
                        @endphp
                        <div class="pro-card glass-panel"
                            style="margin-bottom: 1.5rem; border-radius: 12px; padding: 20px; border: 1px solid var(--border-color); opacity: 0.85;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;"
                                class="mobile-flex-col">

                                {{-- Left side Content --}}
                                <div style="display: flex; gap: 15px; width: 100%;">
                                    <div
                                        style="min-width: 40px; height: 40px; background: {{ $serviceIconBg }}; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: {{ $serviceIconColor }}; font-size: 1.2rem;">
                                        <i class="fas {{ $serviceIcon }}"></i>
                                    </div>

                                    <div style="flex-grow: 1;">
                                        <div
                                            style="display: flex; align-items: center; gap: 10px; margin-bottom: 5px; flex-wrap: wrap;">
                                            <span style="color: var(--secondary); font-size: 0.9rem;">{{ $serviceId }}</span>
                                            <span
                                                style="background: #0f172a; color: white; font-size: 0.8rem; padding: 2px 8px; border-radius: 4px; font-weight: 500;">{{ $serviceTypeStr }}</span>
                                            <span
                                                style="border: 1px solid #10b981; color: #10b981; font-size: 0.8rem; padding: 2px 8px; border-radius: 12px; font-weight: 500; display: flex; align-items: center; gap: 4px;"><i
                                                    class="far fa-check-circle"></i> Feedback Submitted</span>
                                        </div>

                                        <h3 style="margin: 0 0 5px 0; font-size: 1.2rem; color: var(--text-primary);">
                                            {{ $title }}</h3>
                                        <p style="color: var(--secondary); font-size: 0.95rem; margin: 0 0 15px 0;">
                                            {{ Str::limit($description, 80) }}</p>

                                        <div class="mobile-stats-wrap"
                                            style="display: flex; flex-wrap: wrap; gap: 20px; align-items: center; font-size: 0.9rem; color: var(--text-primary);">
                                            <div><i class="far fa-clock"
                                                    style="color: var(--secondary); margin-right: 5px;"></i> Completed:
                                                {{ $completedAt }}</div>
                                            <div><span style="color: var(--secondary);">Technician:</span>
                                                <strong>{{ $technician }}</strong></div>
                                            <div><span style="color: var(--secondary);">Duration:</span>
                                                <strong>{{ $durationStr }}</strong></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Right side Action --}}
                                <div style="flex-shrink: 0; margin-left: 20px;" class="mobile-action-btn">
                                    <div
                                        style="border: 1px solid var(--border-color); color: var(--secondary); padding: 8px 16px; border-radius: 8px; font-weight: 500; display: flex; align-items: center; gap: 8px; justify-content: center;">
                                        <i class="far fa-check-circle"></i> Submitted
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>