@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-star-half-alt" style="color: var(--primary);"></i> Submit Feedback</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Provide SLA-based structured evaluation for
                {{ class_basename($feedback->feedbackable_type) }}
                #{{ $feedback->feedbackable->ticket_no ?? $feedback->feedbackable->id }}
            </p>
        </div>
        <div>
            <a href="{{ route('customer.feedback.index') }}" class="btn"
                style="background: var(--surface); padding: 8px 16px; border-radius: 6px; text-decoration: none; color: var(--text-primary); border: 1px solid var(--border-color);">Back</a>
        </div>
    </div>

    @php
        $isTicket = $feedback->feedbackable_type === 'App\Models\Ticket';
        $serviceId = $isTicket ? $feedback->feedbackable->ticket_no : 'APT-' . str_pad($feedback->feedbackable->id, 3, '0', STR_PAD_LEFT);
        $serviceTypeStr = $isTicket ? 'Support Ticket' : 'Appointment';
        $serviceIconColor = $isTicket ? '#8b5cf6' : '#3b82f6';
        $serviceIconBg = $isTicket ? '#f3e8ff' : '#eff6ff';
        $title = $isTicket ? $feedback->feedbackable->subject : ($feedback->feedbackable->title ?? 'Service Appointment');
        $description = $isTicket ? $feedback->feedbackable->description : ($feedback->feedbackable->description ?? 'Scheduled service delivery');
        $technician = $isTicket ? ($feedback->feedbackable->assigned->name ?? 'Unassigned') : ($feedback->feedbackable->employee->name ?? 'Unassigned');
        $completedAt = $feedback->updated_at->format('d/m/Y, H:i:s');
    @endphp

    {{-- Top Service Info Box --}}
    <div
        style="background: var(--bg-main); border: 1px solid rgba({{ $isTicket ? '139, 92, 246' : '59, 130, 246' }}, 0.3); border-radius: 12px; padding: 20px; display: flex; gap: 15px; margin-bottom: 30px;">
        <div
            style="width: 45px; height: 45px; border-radius: 10px; background: {{ $serviceIconBg }}; color: {{ $serviceIconColor }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.2rem;">
            <i class="fas fa-bullseye"></i>
        </div>
        <div>
            <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                <span
                    style="font-size: 0.8rem; background: var(--surface); color: var(--text-primary); border: 1px solid var(--border-color); padding: 2px 8px; border-radius: 4px; font-weight: 500;">{{ $serviceTypeStr }}</span>
                <span style="font-size: 0.9rem; color: var(--secondary);">{{ $serviceId }}</span>
            </div>
            <h3 style="margin: 0 0 5px 0; font-size: 1.2rem;">{{ $title }}</h3>
            <p style="margin: 0 0 10px 0; color: var(--secondary); font-size: 0.95rem;">{{ $description }}</p>
            <div style="display: flex; gap: 20px; font-size: 0.85rem; color: var(--secondary);">
                <span>Technician: <strong style="color: var(--text-primary);">{{ $technician }}</strong></span>
                <span>Completed: <strong style="color: var(--text-primary);">{{ $completedAt }}</strong></span>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 30px;">
        <h2 style="margin: 0 0 5px 0;">Service Feedback</h2>
        <p style="color: var(--secondary); margin: 0;">Your feedback helps us improve our service quality and maintain
            SLA standards</p>
    </div>

    <form action="{{ route('customer.feedback.store', $feedback->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Overall Satisfaction --}}
        <div style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 1px solid var(--border-color);">
            <label style="display: block; font-weight: 600; font-size: 0.95rem; margin-bottom: 10px;">Overall
                Satisfaction *</label>
            <div class="star-rating" data-input="rating">
                <i class="far fa-star" data-val="1"></i>
                <i class="far fa-star" data-val="2"></i>
                <i class="far fa-star" data-val="3"></i>
                <i class="far fa-star" data-val="4"></i>
                <i class="far fa-star" data-val="5"></i>
                <span class="rating-text" style="color: var(--secondary); font-size: 0.9rem; margin-left: 10px;">Not
                    rated</span>
            </div>
            <input type="hidden" name="rating" id="rating" required>
        </div>

        {{-- SLA Performance --}}
        <div style="margin-bottom: 40px;">
            <h3
                style="display: flex; align-items: center; gap: 8px; margin: 0 0 5px 0; font-size: 1.1rem; color: #3b82f6;">
                <i class="far fa-clock"></i> SLA Performance Evaluation
            </h3>
            <p style="color: var(--secondary); font-size: 0.9rem; margin-bottom: 25px;">Please rate the following
                aspects of our service</p>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: 500; font-size: 0.95rem; margin-bottom: 8px;">Response Time
                    <span style="color: var(--secondary); font-weight: normal;">(How quickly we responded to your
                        request)</span> *</label>
                <div class="star-rating" data-input="response_time_rating">
                    <i class="far fa-star" data-val="1"></i>
                    <i class="far fa-star" data-val="2"></i>
                    <i class="far fa-star" data-val="3"></i>
                    <i class="far fa-star" data-val="4"></i>
                    <i class="far fa-star" data-val="5"></i>
                    <span class="rating-text" style="color: var(--secondary); font-size: 0.9rem; margin-left: 10px;">Not
                        rated</span>
                </div>
                <input type="hidden" name="response_time_rating" id="response_time_rating" required>
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: 500; font-size: 0.95rem; margin-bottom: 8px;">Resolution
                    Quality <span style="color: var(--secondary); font-weight: normal;">(How well your issue was
                        resolved)</span> *</label>
                <div class="star-rating" data-input="resolution_quality_rating">
                    <i class="far fa-star" data-val="1"></i>
                    <i class="far fa-star" data-val="2"></i>
                    <i class="far fa-star" data-val="3"></i>
                    <i class="far fa-star" data-val="4"></i>
                    <i class="far fa-star" data-val="5"></i>
                    <span class="rating-text" style="color: var(--secondary); font-size: 0.9rem; margin-left: 10px;">Not
                        rated</span>
                </div>
                <input type="hidden" name="resolution_quality_rating" id="resolution_quality_rating" required>
            </div>

            <div style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 1px solid var(--border-color);">
                <label style="display: block; font-weight: 500; font-size: 0.95rem; margin-bottom: 8px;">Communication
                    <span style="color: var(--secondary); font-weight: normal;">(Clarity and professionalism)</span>
                    *</label>
                <div class="star-rating" data-input="communication_rating">
                    <i class="far fa-star" data-val="1"></i>
                    <i class="far fa-star" data-val="2"></i>
                    <i class="far fa-star" data-val="3"></i>
                    <i class="far fa-star" data-val="4"></i>
                    <i class="far fa-star" data-val="5"></i>
                    <span class="rating-text" style="color: var(--secondary); font-size: 0.9rem; margin-left: 10px;">Not
                        rated</span>
                </div>
                <input type="hidden" name="communication_rating" id="communication_rating" required>
            </div>
        </div>

        {{-- Comments --}}
        <div style="margin-bottom: 30px;">
            <h3 style="display: flex; align-items: center; gap: 8px; margin: 0 0 10px 0; font-size: 1.05rem;">
                <i class="far fa-comment-dots"></i> Additional Comments
            </h3>
            <textarea name="comments" rows="4" placeholder="Tell us more about your experience..." class="form-control"
                style="width: 100%; padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-main); color: var(--text-primary); resize: vertical;"></textarea>
        </div>

        {{-- Attachemnt --}}
        <div style="margin-bottom: 30px;">
            <h3 style="display: flex; align-items: center; gap: 8px; margin: 0 0 5px 0; font-size: 1.05rem;">
                <i class="fas fa-upload"></i> Attachments (Optional)
            </h3>
            <p style="color: var(--secondary); font-size: 0.9rem; margin-bottom: 15px;">You can attach screenshots,
                documents, or other relevant files</p>

            <div style="border: 2px dashed var(--border-color); border-radius: 12px; padding: 30px; text-align: center; position: relative; background: var(--bg-main); transition: background 0.2s;"
                id="uploadBox">
                <input type="file" name="attachment" id="attachmentInput" class="form-control"
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                <i class="fas fa-file-upload"
                    style="font-size: 2rem; color: var(--secondary); margin-bottom: 10px;"></i>
                <p style="margin: 0; font-weight: 500; color: var(--text-primary);">Click to upload or drag and drop</p>
                <p style="margin: 5px 0 0 0; font-size: 0.8rem; color: var(--secondary);">PNG, JPG, PDF, DOC up to 10MB
                </p>
                <div id="fileNameDisplay" style="margin-top: 15px; font-weight: 600; color: #10b981; display: none;">
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 15px;">
            <button type="submit" class="btn btn-primary"
                style="padding: 12px 24px; border-radius: 8px; background: #0f172a; color: white; border: none; cursor: pointer; font-weight: 600; font-family: inherit; display: flex; align-items: center; gap: 8px;">
                <i class="far fa-check-circle"></i> Submit Feedback
            </button>
            <a href="{{ route('customer.feedback.index') }}" class="btn"
                style="padding: 12px 24px; border-radius: 8px; background: var(--surface); color: var(--text-primary); text-decoration: none; border: 1px solid var(--border-color); font-weight: 500; cursor: pointer;">Cancel</a>
        </div>
    </form>
</div>
</div>

<style>
    .star-rating {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 1.5rem;
        color: #d1d5db;
    }

    .star-rating i {
        cursor: pointer;
        transition: color 0.2s, transform 0.1s;
    }

    .star-rating i:hover {
        transform: scale(1.2);
    }

    .star-rating i.active {
        color: #fbbf24;
        font-weight: 900;
    }

    #uploadBox:hover {
        background: var(--surface);
    }
</style>

<script>
    // Star Rating Logic
    const ratingTexts = ['Not rated', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

    document.querySelectorAll('.star-rating').forEach(group => {
        const stars = group.querySelectorAll('i');
        const inputId = group.getAttribute('data-input');
        const inputField = document.getElementById(inputId);
        const textDisplay = group.querySelector('.rating-text');

        stars.forEach(star => {
            star.addEventListener('click', (e) => {
                const val = parseInt(e.target.getAttribute('data-val'));
                inputField.value = val;

                // Update text
                textDisplay.textContent = ratingTexts[val];
                textDisplay.style.color = 'var(--text-primary)';

                // Update stars visual
                stars.forEach(s => {
                    if (parseInt(s.getAttribute('data-val')) <= val) {
                        s.classList.remove('far');
                        s.classList.add('fas', 'active');
                    } else {
                        s.classList.remove('fas', 'active');
                        s.classList.add('far');
                    }
                });
            });

            // Hover effects
            star.addEventListener('mouseenter', (e) => {
                const hoverVal = parseInt(e.target.getAttribute('data-val'));
                stars.forEach(s => {
                    const sVal = parseInt(s.getAttribute('data-val'));
                    if (sVal <= hoverVal) {
                        s.style.color = '#fbbf24';
                    } else if (inputField.value && sVal <= parseInt(inputField.value)) {
                        s.style.color = '#fbbf24';
                    } else {
                        s.style.color = '#d1d5db';
                    }
                });
            });

            group.addEventListener('mouseleave', () => {
                const currentVal = parseInt(inputField.value) || 0;
                stars.forEach(s => {
                    const sVal = parseInt(s.getAttribute('data-val'));
                    if (sVal <= currentVal) {
                        s.style.color = '#fbbf24';
                    } else {
                        s.style.color = '#d1d5db';
                    }
                });
            });
        });
    });

    // File Upload Display
    document.getElementById('attachmentInput').addEventListener('change', function (e) {
        const display = document.getElementById('fileNameDisplay');
        if (this.files && this.files.length > 0) {
            display.textContent = 'Selected: ' + this.files[0].name;
            display.style.display = 'block';
        } else {
            display.style.display = 'none';
        }
    });
</script>