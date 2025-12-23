@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Refine Team Profile</h1>
            <p style="color: var(--secondary); margin-top: 0.5rem;">Update professional credentials for <strong>{{ $employee->name }}</strong></p>
        </div>
        <div class="header-actions">
            <a href="{{ route('manager.team.index') }}" class="action-btn" style="background: var(--white); color: var(--secondary);">
                <i class="fas fa-arrow-left"></i> Back to Directory
            </a>
        </div>
    </div>

    <div style="max-width: 800px; margin: 0 auto;">
        <div class="pro-card">
            <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 1.5rem; display: flex; align-items: center; gap: 1.5rem;">
                <div style="width: 64px; height: 64px; border-radius: 1rem; background: linear-gradient(135deg, var(--primary), var(--info)); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: 800;">
                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                </div>
                <div>
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--dark);">Professional Identity</h2>
                    <p style="font-size: 0.875rem; color: var(--secondary);">Manage roles, departments, and contact information.</p>
                </div>
            </div>

            @if($errors->any())
                <div class="glass-card" style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2); margin-bottom: 2rem; padding: 1rem;">
                    <ul style="margin: 0; padding-left: 1.25rem; color: var(--danger); font-size: 0.875rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('manager.team.update', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $employee->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $employee->email) }}">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Contact Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-id-badge"></i> Professional Role</label>
                        <input type="text" name="position" value="{{ old('position', $employee->position) }}">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-brain"></i> Specialization</label>
                        <input type="text" name="specialization" value="{{ old('specialization', $employee->specialization) }}">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-building"></i> Department</label>
                        <input type="text" name="department" value="{{ old('department', $employee->department) }}">
                    </div>
                </div>

                <div style="margin-top: 2.5rem; display: flex; gap: 1rem; border-top: 1px solid var(--glass-border); padding-top: 2rem;">
                    <button type="submit" class="action-btn btn-primary" style="padding: 0.75rem 2rem;">
                        <i class="fas fa-sync-alt"></i> Update Profile
                    </button>
                    <a href="{{ route('manager.team.index') }}" class="action-btn" style="background: var(--bg-main); color: var(--secondary); text-decoration: none;">
                        <i class="fas fa-times"></i> Cancel
                    </a>
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

    .form-group input {
        width: 100%;
        padding: 0.75rem 1rem;
        background: var(--bg-main);
        border: 1px solid var(--glass-border);
        border-radius: 0.75rem;
        font-size: 0.9rem;
        color: var(--dark);
        transition: all 0.2s;
    }

    .form-group input:focus {
        background: var(--white);
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    @media (max-width: 640px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

