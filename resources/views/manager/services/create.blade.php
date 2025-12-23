@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Expand Your Portfolio</h1>
            <p style="color: var(--secondary); margin-top: 0.5rem;">Introduce a new professional service to your catalog</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('manager.services.index') }}" class="action-btn" style="background: var(--white); color: var(--secondary);">
                <i class="fas fa-arrow-left"></i> Back to Portfolio
            </a>
        </div>
    </div>

    <div style="max-width: 800px; margin: 0 auto;">
        <div class="pro-card">
            <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 1.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--dark);">Service Details</h2>
                <p style="font-size: 0.875rem; color: var(--secondary);">Define the service name, description, and assign an expert.</p>
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

            <form action="{{ route('manager.services.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-grid">
                    <div class="form-group" style="grid-column: span 2;">
                        <label><i class="fas fa-tag"></i> Service Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="E.g. Full System Diagnostics">
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label><i class="fas fa-align-left"></i> Service Description</label>
                        <textarea name="description" placeholder="Provide a detailed overview of what this service entails..." style="min-height: 120px;">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-user-tie"></i> Assign Specialist</label>
                        <select name="employee_id" required>
                            <option value="">-- Choose an Expert --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }} ({{ $employee->position ?? 'Staff' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-upload"></i> Service Display Image</label>
                        <input type="file" name="image" class="file-input">
                    </div>
                </div>

                <div style="margin-top: 2.5rem; display: flex; gap: 1rem; border-top: 1px solid var(--glass-border); padding-top: 2rem;">
                    <button type="submit" class="action-btn btn-primary" style="padding: 0.75rem 2rem;">
                        <i class="fas fa-cloud-upload-alt"></i> Launch Service
                    </button>
                    <button type="reset" class="action-btn" style="background: var(--bg-main); color: var(--secondary);">
                        <i class="fas fa-undo"></i> Start Over
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .form-group label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    .form-group label i {
        color: var(--primary);
        font-size: 0.8rem;
    }

    .form-group input, 
    .form-group select, 
    .form-group textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        background: var(--bg-main);
        border: 1px solid var(--glass-border);
        border-radius: 0.75rem;
        font-size: 0.9rem;
        color: var(--dark);
        transition: all 0.2s;
    }

    .form-group input:focus, 
    .form-group select:focus, 
    .form-group textarea:focus {
        background: var(--white);
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    .form-group textarea {
        resize: vertical;
    }

    .file-input {
        padding: 0.6rem !important;
    }

    @media (max-width: 640px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-group {
            grid-column: span 1 !important;
        }
    }
</style>

