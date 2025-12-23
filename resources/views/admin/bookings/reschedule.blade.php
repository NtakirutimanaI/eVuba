@include('layouts.header')
@include('layouts.sidebar')

<div class="reschedule-wrapper">
    <div class="glass-card">
        <div class="card-header">
            <div class="icon-circle">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h2>Reschedule Booking</h2>
            <p>Select a new date and time for this appointment.</p>
        </div>

        <div class="current-info">
            <div class="info-row">
                <span class="label">Service:</span>
                <span class="value">{{ $booking->service->name ?? 'Custom Service' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Client:</span>
                <span class="value">{{ $booking->user->name ?? 'Guest' }}</span>
            </div>
            <div class="info-row highlight">
                <span class="label">Current Date:</span>
                <span class="value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y - h:i A') }}</span>
            </div>
        </div>

        <form action="{{ route('admin.bookings.updateReschedule', $booking->id) }}" method="POST" class="reschedule-form">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="booking_date">New Date & Time</label>
                <div class="input-wrapper">
                    <i class="fas fa-clock"></i>
                    <input type="datetime-local" name="booking_date" id="booking_date" 
                           value="{{ \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d\TH:i') }}" required>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.bookings.index') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">
                    Update Schedule <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
:root {
    --primary: #4f46e5;
    --background: #f1f5f9;
    --text: #1e293b;
    --secondary: #64748b;
    --border: #e2e8f0;
}

body { background: var(--background); font-family: 'Inter', sans-serif; }

.reschedule-wrapper {
    margin-left: 250px; /* Sidebar width */
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
}

.glass-card {
    background: #fff;
    width: 100%;
    max-width: 500px;
    border-radius: 20px;
    border: 1px solid var(--border);
    padding: 40px;
    box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
    animation: slideUp 0.4s ease-out;
}

.card-header { text-align: center; margin-bottom: 30px; }
.card-header h2 { font-size: 24px; font-weight: 800; color: var(--text); margin: 15px 0 5px; }
.card-header p { color: var(--secondary); font-size: 14px; margin: 0; }

.icon-circle {
    width: 60px; height: 60px; background: #e0e7ff; color: var(--primary);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 24px; margin: 0 auto;
}

.current-info {
    background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 30px;
    border: 1px solid var(--border);
}
.info-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
.info-row:last-child { margin-bottom: 0; }
.info-row .label { color: var(--secondary); }
.info-row .value { font-weight: 600; color: var(--text); }
.info-row.highlight .value { color: var(--primary); }

.form-group { margin-bottom: 25px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
.input-wrapper { position: relative; }
.input-wrapper i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--secondary); pointer-events: none; }
.input-wrapper input {
    width: 100%; padding: 12px 15px 12px 40px; border-radius: 10px; border: 1px solid var(--border);
    font-family: inherit; font-size: 14px; outline: none; transition: all 0.2s; background: #fff;
}
.input-wrapper input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }

.form-actions { display: flex; gap: 15px; }
.btn-submit {
    flex: 2; background: var(--primary); color: #fff; border: none; padding: 14px;
    border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s;
    display: flex; align-items: center; justify-content: center; gap: 10px;
}
.btn-submit:hover { background: #4338ca; transform: translateY(-1px); }

.btn-cancel {
    flex: 1; background: #fff; color: var(--text); border: 1px solid var(--border);
    padding: 14px; border-radius: 10px; font-weight: 600; text-align: center;
    text-decoration: none; transition: all 0.2s;
}
.btn-cancel:hover { background: #f8fafc; }

@keyframes slideUp { from{opacity:0; transform:translateY(20px);} to{opacity:1; transform:translateY(0);} }
</style>
