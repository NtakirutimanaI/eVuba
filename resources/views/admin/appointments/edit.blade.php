@include('layouts.header')
@include('layouts.sidebar')

<style>
/* Centered container with smaller padding */
.page-center-container {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    background-color: var(--body-bg); /* Global Theme */
    padding: 20px 10px;
}

/* Smaller card for form */
.crm-table-container {
    width: 60%;
    margin-left: 220px;
    max-width: 800px;
    background-color: var(--surface); /* Global Theme */
    color: var(--text-primary); /* Global Theme */
    padding: 20px 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    border: 1px solid var(--header-border); /* Global Theme */
    border-radius: 8px;
    font-family: 'Inter', sans-serif;
}

/* Smaller heading */
.crm-table-container h1 {
    text-align: center;
    margin-bottom: 20px;
    color: var(--text-primary); /* Global Theme */
    font-size: 22px;
}

/* Form labels and inputs */
.crm-table-container form {
    margin-top: 15px;
}

.crm-table-container label {
    font-weight: bold;
    font-size: 13px;
    color: var(--text-muted); /* Global Theme */
    display: block;
    margin-bottom: 5px;
}

.crm-table-container input, 
.crm-table-container select, 
.crm-table-container textarea {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 15px;
    border-radius: 6px;
    border: 1px solid var(--header-border); /* Global Theme */
    background-color: var(--body-bg); /* Global Theme */
    color: var(--text-primary); /* Global Theme */
    font-size: 13px;
    outline: none;
    transition: all 0.2s;
}

.crm-table-container input:focus, 
.crm-table-container select:focus, 
.crm-table-container textarea:focus {
    border-color: var(--primary);
    background-color: var(--surface);
}

/* Buttons */
.crm-table-container .btn {
    padding: 10px 16px;
    border-radius: 6px;
    color: #fff;
    text-decoration: none;
    font-size: 13px;
    cursor: pointer;
    border: none;
    font-weight: 600;
}

.crm-table-container .btn-primary {
    background-color: var(--primary); /* Global Theme */
    transition: 0.3s;
}
.crm-table-container .btn-primary:hover {
    background-color: rgba(79, 70, 229, 0.8);
}

/* Success alert */
.alert {
    margin-bottom: 15px;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 13px;
    text-align: center;
}

/* Customize alert colors based on needs or context, 
   using globals if available or semi-transparent overlays */
.alert-success {
    background-color: rgba(16, 185, 129, 0.2); 
    color: #065f46;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

/* If you need dark mode specific overrides for alerts not fully covered by opacity */
[data-theme='dark'] .alert-success {
    color: #34d399;
}
</style>

<div class="page-center-container">
    <div class="crm-table-container">
        <h1>Edit Appointment</h1>

        {{-- Success message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Validation errors --}}
        @if($errors->any())
            <div class="alert" style="background-color: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);">
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $appointment->title) }}" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3">{{ old('description', $appointment->description) }}</textarea>

            <label for="scheduled_at">Scheduled At</label>
            <input type="datetime-local" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at', \Carbon\Carbon::parse($appointment->scheduled_at)->format('Y-m-d\TH:i')) }}" required>

            <label for="employee_id">Assign Employee</label>
            <select id="employee_id" name="employee_id">
                <option value="">-- Select Employee --</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ $appointment->employee_id == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>

            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <button type="submit" class="btn btn-primary">Update Appointment</button>
        </form>
    </div>
</div>
