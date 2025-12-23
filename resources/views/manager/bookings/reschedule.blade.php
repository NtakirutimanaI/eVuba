@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Modify Operations Timeline</h1>
            <p style="color: var(--secondary); margin-top: 0.5rem;">Rescheduling <strong>{{ $booking->title }}</strong> for client <strong>{{ $booking->user->name ?? 'External' }}</strong></p>
        </div>
        <div class="header-actions">
            <a href="{{ route('manager.bookings.index') }}" class="action-btn" style="background: var(--white); color: var(--secondary);">
                <i class="fas fa-arrow-left"></i> Back to Ledger
            </a>
        </div>
    </div>

    <div style="max-width: 600px; margin: 0 auto;">
        <div class="pro-card">
            <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 1.5rem; display: flex; align-items: center; gap: 1.5rem;">
                <div style="width: 60px; height: 60px; border-radius: 1rem; background: rgba(99, 102, 241, 0.1); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                    <i class="fas fa-calendar-alt fa-2x"></i>
                </div>
                <div>
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--dark);">Reschedule Appointment</h2>
                    <p style="font-size: 0.875rem; color: var(--secondary);">Choose a new date for this service delivery.</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="glass-card" style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2); margin-bottom: 2rem; padding: 1rem;">
                    <ul style="margin: 0; padding-left: 1.25rem; color: var(--danger); font-size: 0.875rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('manager.bookings.updateReschedule', $booking->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 600; color: var(--secondary); margin-bottom: 0.75rem;">
                        <i class="fas fa-calendar-day text-primary"></i> New Selection Date
                    </label>
                    <input type="date" name="booking_date" value="{{ $booking->booking_date }}" required 
                           style="width: 100%; padding: 0.875rem 1rem; background: var(--bg-main); border: 1px solid var(--glass-border); border-radius: 0.75rem; font-size: 1rem; color: var(--dark); transition: all 0.2s;">
                    <p style="font-size: 0.75rem; color: var(--secondary); margin-top: 0.5rem;">Currently set for: {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</p>
                </div>

                <div style="margin-top: 2.5rem; display: flex; gap: 1rem; border-top: 1px solid var(--glass-border); padding-top: 2rem;">
                    <button type="submit" class="action-btn btn-primary" style="padding: 0.75rem 2rem; flex: 1;">
                        <i class="fas fa-check-circle"></i> Commit Schedule
                    </button>
                    <a href="{{ route('manager.bookings.index') }}" class="action-btn" style="background: var(--bg-main); color: var(--secondary); text-decoration: none; padding: 0.75rem 1.5rem;">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    input[type="date"]:focus {
        background: var(--white);
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
    }
</style>

