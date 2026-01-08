@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-calendar-alt" style="color: var(--primary);"></i> Book Appointment</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Schedule a new service request or consultation.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('customer.appointments.index') }}" class="action-btn secondary" style="text-decoration:none;">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="appointment-container" style="max-width: 900px; margin: 0 auto;">
        
        @if($errors->any())
            <div class="alert-box error" style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 15px; border-radius: 12px; margin-bottom: 25px;">
                <div style="display: flex; gap: 10px; align-items: center; font-weight: 700; margin-bottom: 5px;">
                    <i class="fas fa-exclamation-triangle"></i> Please correct the following errors:
                </div>
                <ul style="margin: 0; padding-left: 25px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customer.appointments.store') }}" method="POST" class="modern-form">
            @csrf

            <!-- Section 1: Contact Information -->
            <div class="form-section glass-panel">
                <div class="section-header">
                    <div class="icon-box"><i class="fas fa-id-card"></i></div>
                    <div>
                        <h3>Contact Information</h3>
                        <p>Your details are auto-filled for your convenience.</p>
                    </div>
                </div>
                
                <div class="form-grid-3">
                    <div class="input-group">
                        <label>Full Name</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" name="user_name" class="modern-input" value="{{ old('user_name', auth()->user()->name) }}" readonly style="background: var(--bg-secondary); cursor: not-allowed;">
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Email Address</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" name="email" class="modern-input" value="{{ old('email', auth()->user()->email) }}" readonly style="background: var(--bg-secondary); cursor: not-allowed;">
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Phone Number <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <i class="fas fa-phone input-icon"></i>
                            <input type="text" name="phone" class="modern-input" placeholder="+250 7..." required value="{{ old('phone') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Appointment Details -->
            <div class="form-section glass-panel">
                <div class="section-header">
                    <div class="icon-box warning"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <h3>Appointment Details</h3>
                        <p>Tell us what you need and when you need it.</p>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="input-group">
                        <label>Service Title/Subject <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <i class="fas fa-heading input-icon"></i>
                            <input type="text" name="title" class="modern-input" placeholder="e.g. Regular Vehicle Maintenance" required value="{{ old('title') }}">
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Preferred Date & Time <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <i class="fas fa-clock input-icon"></i>
                            <input type="datetime-local" name="scheduled_at" class="modern-input" required value="{{ old('scheduled_at') }}">
                        </div>
                    </div>
                </div>

                <div class="input-group full-width">
                    <label>Description / Notes <span class="required">*</span></label>
                    <textarea name="description" class="modern-input" rows="4" placeholder="Please describe your request in detail..." style="height: auto; padding-top: 15px;">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Section 3: Location (Optional) -->
            <div class="form-section glass-panel">
                <div class="section-header">
                    <div class="icon-box success"><i class="fas fa-map-marked-alt"></i></div>
                    <div>
                        <h3>Location (Optional)</h3>
                        <p>Where should we meet you? Leave blank if visiting us.</p>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="input-group">
                        <label>Street Address</label>
                        <input type="text" name="street" class="modern-input" placeholder="123 Main St" value="{{ old('street') }}">
                    </div>
                    <div class="input-group">
                        <label>City</label>
                        <input type="text" name="city" class="modern-input" placeholder="Kigali" value="{{ old('city') }}">
                    </div>
                </div>
                <div class="form-grid-3">
                    <div class="input-group">
                        <label>State/Province</label>
                        <input type="text" name="state" class="modern-input" placeholder="Region" value="{{ old('state') }}">
                    </div>
                    <div class="input-group">
                        <label>Zip/Postal Code</label>
                        <input type="text" name="postal_code" class="modern-input" placeholder="00000" value="{{ old('postal_code') }}">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="form-actions">
                <button type="button" onclick="window.history.back()" class="cancel-btn">Cancel</button>
                <button type="submit" class="submit-btn" id="submitBtn">
                    Confirm Booking <i class="fas fa-arrow-right"></i>
                </button>
            </div>

        </form>
    </div>
</div>

<style>
    /* Modern Form Styling */
    .appointment-container { padding-bottom: 50px; }
    
    .form-section { 
        margin-bottom: 25px; 
        padding: 30px; 
        border-radius: 20px; 
        border: 1px solid var(--header-border);
    }
    
    .section-header { 
        display: flex; 
        gap: 15px; 
        margin-bottom: 25px; 
        border-bottom: 1px solid var(--header-border); 
        padding-bottom: 15px;
    }
    
    .section-header h3 { margin: 0 0 5px 0; font-size: 1.1rem; color: var(--text-primary); }
    .section-header p { margin: 0; font-size: 0.9rem; color: var(--text-muted); }
    
    .icon-box { 
        width: 45px; height: 45px; 
        background: rgba(59, 130, 246, 0.1); 
        color: var(--primary); 
        border-radius: 12px; 
        display: flex; align-items: center; justify-content: center; 
        font-size: 1.2rem;
    }
    .icon-box.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .icon-box.success { background: rgba(16, 185, 129, 0.1); color: #10b981; }

    /* Grid Layouts */
    .form-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .form-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    
    /* Input Styling */
    .input-group { margin-bottom: 15px; }
    .input-group label { display: block; margin-bottom: 8px; font-size: 0.9rem; font-weight: 600; color: var(--text-secondary); }
    .input-group label .required { color: #ef4444; }
    
    .input-wrapper { position: relative; }
    .input-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; }
    
    .modern-input {
        width: 100%;
        padding: 12px 15px; /* Default padding without icon */
        border-radius: 12px;
        border: 1px solid var(--header-border);
        background: var(--bg-main);
        color: var(--text-primary);
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    
    /* When inside a wrapper, add left padding for icon */
    .input-wrapper .modern-input {
        padding-left: 45px;
    }

    .modern-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    /* Buttons */
    .form-actions { display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px; }
    
    .submit-btn {
        background: var(--primary);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 15px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3); }
    
    .cancel-btn {
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--header-border);
        padding: 15px 30px;
        border-radius: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .cancel-btn:hover { background: var(--bg-secondary); color: var(--text-primary); }

    /* Responsive */
    @media (max-width: 768px) {
        .form-grid-3, .form-grid-2 { grid-template-columns: 1fr; }
    }
</style>

<script>
    // Add loading state on submit
    document.querySelector('.modern-form').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        const orgHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        btn.style.opacity = '0.8';
    });
</script>
