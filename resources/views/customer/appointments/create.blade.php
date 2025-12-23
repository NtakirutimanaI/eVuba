@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-calendar-plus" style="color: var(--primary);"></i> Schedule Engagement</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Initiate a new operational service request.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('customer.appointments.index') }}" class="action-btn secondary" style="text-decoration:none;">
                <i class="fas fa-arrow-left"></i> Return to Timeline
            </a>
        </div>
    </div>

    <div class="pro-card glass-panel" style="max-width: 800px; margin: 0 auto;">
        
        @if($errors->any())
            <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; border-radius: 12px; padding: 15px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customer.appointments.store') }}" method="POST">
            @csrf

            <div class="form-section-title">
                <i class="fas fa-user-circle" style="color: var(--primary);"></i> Identity & Contact
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="user_name" class="pro-input" placeholder="Enter Full Name" required value="{{ old('user_name', auth()->user()->name) }}">
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="pro-input" placeholder="Enter Email Address" required value="{{ old('email', auth()->user()->email) }}">
                </div>
            </div>

            <div class="form-group">
                <label>Mobile Number</label>
                <input type="text" name="phone" class="pro-input" placeholder="Enter Mobile Number" required value="{{ old('phone') }}">
            </div>

            <div class="form-section-title" style="margin-top: 30px;">
                <i class="fas fa-clock" style="color: var(--primary);"></i> Engagement Details
            </div>

            <div class="form-group">
                <label>Service Title</label>
                <input type="text" name="title" class="pro-input" placeholder="e.g. Quarterly System Audit" required value="{{ old('title') }}">
            </div>

            <div class="form-group">
                <label>Operational Context (Description)</label>
                <textarea name="description" class="pro-input" rows="4" placeholder="Provide detailed context for this engagement...">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label>Target Execution Time</label>
                <input type="datetime-local" name="scheduled_at" class="pro-input" required value="{{ old('scheduled_at') }}">
            </div>

            <div class="form-section-title" style="margin-top: 30px;">
                <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> Location Coordinates
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Street Address</label>
                    <input type="text" name="street" class="pro-input" placeholder="Enter Street" value="{{ old('street') }}">
                </div>
                <div class="form-group">
                    <label>City / Municipality</label>
                    <input type="text" name="city" class="pro-input" placeholder="Enter City" value="{{ old('city') }}">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>State / Region</label>
                    <input type="text" name="state" class="pro-input" placeholder="Enter State" value="{{ old('state') }}">
                </div>
                <div class="form-group">
                    <label>Postal Code</label>
                    <input type="text" name="postal_code" class="pro-input" placeholder="Postal Code" value="{{ old('postal_code') }}">
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
                <button type="submit" class="action-btn btn-primary" style="padding: 12px 40px; font-size: 1rem;">
                    Confirm & Schedule <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
                </button>
            </div>

        </form>
    </div>
</div>
