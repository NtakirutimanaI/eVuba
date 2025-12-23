@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Scale Your Team</h1>
            <p style="color: var(--secondary); margin-top: 0.5rem;">Onboard new talent to your organizational structure</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('manager.team.index') }}" class="action-btn" style="background: var(--white); color: var(--secondary);">
                <i class="fas fa-arrow-left"></i> Back to Directory
            </a>
        </div>
    </div>

    <div style="max-width: 800px; margin: 0 auto;">
        <div class="pro-card">
            <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 1.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--dark);">Member Information</h2>
                <p style="font-size: 0.875rem; color: var(--secondary);">Complete the profile details to register a new employee.</p>
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

            <form action="{{ route('manager.team.store') }}" method="POST">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Full Name</label>
                        <input type="text" name="name" placeholder="E.g. John Doe" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email Address</label>
                        <input type="email" name="email" placeholder="E.g. john.doe@company.com" value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Contact Number</label>
                        <input type="text" name="phone" placeholder="E.g. +1 (555) 000-0000" value="{{ old('phone') }}">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-id-badge"></i> Professional Role</label>
                        <input type="text" name="position" placeholder="E.g. Senior Developer" value="{{ old('position') }}">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-brain"></i> Specialization</label>
                        <input type="text" name="specialization" placeholder="E.g. Full Stack, AI Research" value="{{ old('specialization') }}">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-building"></i> Department</label>
                        <input type="text" name="department" placeholder="E.g. Engineering, Sales" value="{{ old('department') }}">
                    </div>
                </div>

                <div style="margin-top: 2.5rem; display: flex; gap: 1rem; border-top: 1px solid var(--glass-border); pt: 2rem; padding-top: 2rem;">
                    <button type="submit" class="action-btn btn-primary" style="padding: 0.75rem 2rem;">
                        <i class="fas fa-save"></i> Register Member
                    </button>
                    <button type="reset" class="action-btn" style="background: var(--bg-main); color: var(--secondary);">
                        <i class="fas fa-undo"></i> Reset Form
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


</style>
