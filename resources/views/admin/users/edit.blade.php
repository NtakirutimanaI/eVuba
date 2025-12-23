@include('layouts.header')
@include('layouts.sidebar')

<div class="edit-user-wrapper">
    
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <a href="{{ route('admin.users.index') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
            <h1>Edit User Profile</h1>
            <p>Update account details and role permissions for <span class="highlight-name">{{ $user->name }}</span></p>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="form-card glass-panel">
        
        <!-- Validation Errors -->
        @if($errors->any())
            <div class="alert alert-danger">
                <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
                <ul class="alert-list">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="edit-form">
            @csrf
            @method('PATCH')

            <div class="form-grid">
                <!-- Name -->
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="name" name="name" 
                               value="{{ old('name', $user->name) }}" 
                               class="glass-input" placeholder="e.g. John Doe" required>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" 
                               value="{{ old('email', $user->email) }}" 
                               class="glass-input" placeholder="e.g. john@example.com" required>
                    </div>
                </div>

                <!-- Role -->
                <div class="form-group">
                    <label for="role">System Role</label>
                    <div class="input-wrapper">
                        <i class="fas fa-shield-alt"></i>
                        <select id="role" name="role" class="glass-input">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" 
                                    @if($user->roles->pluck('name')->contains($role->name)) selected @endif>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down select-arrow"></i>
                    </div>
                </div>

                <!-- Password (Optional) -->
                <div class="form-group full-width">
                    <div class="divider">
                        <span>Change Password (Optional)</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" 
                               class="glass-input" placeholder="Min. 8 characters">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock-open"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               class="glass-input" placeholder="Confirm new password">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.users.index') }}" class="btn-glass secondary">Cancel</a>
                <button type="submit" class="btn-glass primary">
                    <i class="fas fa-save"></i> Update User
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* WRAPPER & LAYOUT */
.edit-user-wrapper {
    margin-left: 250px; /* Matching index page offset */
    padding: 40px;
    min-height: 100vh;
    background: var(--body-bg, #f8fafc);
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* HEADER */
.page-header {
    width: 100%;
    max-width: 800px;
    margin-bottom: 30px;
    text-align: center;
}
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--secondary, #64748b);
    text-decoration: none;
    font-size: 14px;
    margin-bottom: 15px;
    transition: color 0.2s;
    font-weight: 500;
}
.back-link:hover { color: var(--primary, #3b82f6); }
.page-header h1 {
    font-size: 32px;
    font-weight: 800;
    color: var(--text, #1e293b);
    margin: 0 0 10px 0;
}
.page-header p {
    color: var(--secondary, #64748b);
    margin: 0;
    font-size: 15px;
}
.highlight-name {
    color: var(--primary, #3b82f6);
    font-weight: 600;
}

/* GLASS PANEL FORM */
.glass-panel {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.6);
    box-shadow: 0 10px 40px -5px rgba(0, 0, 0, 0.05);
    border-radius: 24px;
}

.form-card {
    width: 100%;
    max-width: 800px;
    padding: 40px;
}

/* FORM FORM ELEMENTS */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 30px;
}
.form-group { display: flex; flex-direction: column; gap: 8px; }
.form-group.full-width { grid-column: 1 / -1; }

.form-group label {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-muted, #64748b);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-left: 4px;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-wrapper i {
    position: absolute;
    left: 16px;
    color: var(--secondary, #94a3b8);
    pointer-events: none;
    font-size: 14px;
}

.glass-input {
    width: 100%;
    padding: 12px 16px 12px 42px; /* Pad left for icon */
    background: rgba(248, 250, 252, 0.8);
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 15px;
    color: #334155;
    outline: none;
    transition: all 0.2s ease;
}

.glass-input:focus {
    background: #fff;
    border-color: var(--primary, #3b82f6);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.select-arrow {
    left: auto !important;
    right: 16px;
}

/* SEPARATOR */
.divider {
    display: flex;
    align-items: center;
    margin: 15px 0 5px;
}
.divider::before, .divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}
.divider span {
    padding: 0 15px;
    font-size: 13px;
    color: #94a3b8;
    font-weight: 600;
}

/* ACTIONS */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    padding-top: 20px;
    border-top: 1px solid #f1f5f9;
}

.btn-glass {
    padding: 12px 28px;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
}

.btn-glass.primary {
    background: linear-gradient(135deg, var(--primary, #3b82f6), #2563eb);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}
.btn-glass.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
}

.btn-glass.secondary {
    background: white;
    color: #64748b;
    border: 1px solid #e2e8f0;
}
.btn-glass.secondary:hover {
    background: #f8fafc;
    color: #334155;
    border-color: #cbd5e1;
}

/* ALERTS */
.alert {
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 25px;
    display: flex;
    gap: 15px;
    font-size: 14px;
}
.alert-danger {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}
.alert-icon { font-size: 18px; }
.alert-list { margin: 0; padding-left: 15px; }

/* RESPONSIVE */
@media (max-width: 768px) {
    .edit-user-wrapper {
        margin-left: 0;
        padding: 20px;
    }
    .form-grid { grid-template-columns: 1fr; }
    .form-card { padding: 25px; }
    .btn-glass { width: 100%; justify-content: center; }
}
</style>
