@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-shield-alt" style="color: var(--primary);"></i> Account Security</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Manage your access credentials and secure your account.</p>
        </div>
    </div>

    <div class="security-container">
        <div class="security-card glass-panel">
            <div class="card-header">
                <div class="icon-box">
                    <i class="fas fa-lock"></i>
                </div>
                <div>
                    <h2>Update Password</h2>
                    <p>Ensure your account remains secure by using a strong, unique password.</p>
                </div>
            </div>

            @if (session('status') === 'password-updated')
                <div class="alert-success-glass">
                    <i class="fas fa-check-circle"></i> Password updated successfully.
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="security-form">
                @csrf

                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-key input-icon"></i>
                        <input type="password" name="current_password" id="current_password" class="pro-input" placeholder="Enter your current password" required>
                    </div>
                     @error('current_password')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-fingerprint input-icon"></i>
                        <input type="password" name="password" id="password" class="pro-input" placeholder="Enter new password" required autocomplete="new-password">
                    </div>
                     @error('password')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-check-double input-icon"></i>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="pro-input" placeholder="Confirm new password" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="action-btn btn-primary" style="width: 100%; justify-content: center;">
                        <i class="fas fa-save"></i> Update Credentials
                    </button>
                </div>

                <div class="security-tips">
                    <h4><i class="fas fa-shield-virus"></i> Security Recommendations:</h4>
                    <ul>
                        <li>Use at least 8 characters.</li>
                        <li>Include a mix of letters, numbers, and symbols.</li>
                        <li>Avoid using personal information like birthdays.</li>
                    </ul>
                </div>
            </form>
        </div>

         <div class="security-illustration glass-panel">
            <div style="text-align: center; color: var(--secondary);">
                <i class="fas fa-user-shield" style="font-size: 5rem; margin-bottom: 20px; color: var(--primary); opacity: 0.8;"></i>
                <h3>Two-Factor Authentication</h3>
                <p style="margin-bottom: 20px;">Add an extra layer of security to your account.</p>
                <button class="action-btn secondary" disabled style="opacity: 0.6; cursor: not-allowed; width: 100%; justify-content: center;">Coming Soon</button>
            </div>
            
            <div style="margin-top: 40px; text-align: center; color: var(--secondary); border-top: 1px solid var(--glass-border); padding-top: 30px;">
                <i class="fas fa-history" style="font-size: 3rem; margin-bottom: 20px; color: #10b981; opacity: 0.8;"></i>
                <h3>Login Activity</h3>
                <p style="margin-bottom: 20px;">Review your recent login sessions.</p>
                <button class="action-btn secondary" disabled style="opacity: 0.6; cursor: not-allowed; width: 100%; justify-content: center;">Coming Soon</button>
            </div>
        </div>
    </div>
</div>


