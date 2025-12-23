@include('layouts.header')
@include('layouts.sidebar')

<div class="create-user-wrapper">
    <div class="glass-form-container">
        <div class="form-header">
            <div class="icon-circle">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>Create New User</h1>
            <p>Fill in the details to register a new account.</p>
        </div>

        @if($errors->any())
            <div class="alert-glass error">
                <i class="fas fa-exclamation-circle"></i>
                <div class="error-list">
                    @foreach($errors->all() as $err)
                        <span>{{ $err }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Full Name</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" class="glass-input" placeholder="e.g. John Doe" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="glass-input" placeholder="john.doe@example.com" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group half">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="glass-input" placeholder="At least 6 chars" required>
                    </div>
                </div>
                <div class="form-group half">
                    <label>Confirm Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-check-double"></i>
                        <input type="password" name="password_confirmation" class="glass-input" placeholder="Repeat password" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Assign Role</label>
                <div class="input-wrapper">
                    <i class="fas fa-user-tag"></i>
                    <select name="role" class="glass-input" required>
                        <option value="" disabled selected>Select a role...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.users.index') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Create Account
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Remove local :root to use global theme variables */
body { background: var(--body-bg); font-family: 'Inter', sans-serif; }

.create-user-wrapper {
    margin-left: 250px;
    min-height: 100vh;
    display: flex;
    align-items: center; justify-content: center;
    padding: 40px;
}

.glass-form-container {
    background: var(--surface);
    backdrop-filter: blur(20px);
    width: 100%; max-width: 550px;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 20px 50px -10px rgba(0,0,0,0.1);
    border: 1px solid var(--header-border);
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp { from{opacity:0; transform:translateY(20px);} to{opacity:1; transform:translateY(0);} }

.form-header { text-align: center; margin-bottom: 30px; }
.icon-circle {
    width: 60px; height: 60px; background: rgba(79, 70, 229, 0.1); color: var(--primary);
    border-radius: 50%; margin: 0 auto 15px;
    display: flex; align-items: center; justify-content: center; font-size: 24px;
}
.form-header h1 { font-size: 24px; color: var(--text-primary); margin: 0 0 5px; font-weight: 800; }
.form-header p { color: var(--text-muted); margin: 0; font-size: 14px; }

.alert-glass.error {
    background: rgba(239, 68, 68, 0.1); color: #ef4444;
    padding: 15px; border-radius: 12px; border: 1px solid rgba(239, 68, 68, 0.2);
    margin-bottom: 25px; display: flex; gap: 12px; align-items: flex-start;
}
.error-list { display: flex; flex-direction: column; gap: 4px; font-size: 13px; font-weight: 500; }

.form-group { margin-bottom: 20px; }
.form-row { display: flex; gap: 20px; }
.form-group.half { flex: 1; }

.form-group label {
    display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700;
    color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;
}

.input-wrapper { position: relative; }
.input-wrapper i {
    position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); font-size: 16px; pointer-events: none;
}
.glass-input {
    width: 100%; padding: 12px 15px 12px 45px;
    border-radius: 10px; border: 1px solid var(--header-border);
    background: var(--body-bg); font-size: 14px; color: var(--text-primary);
    transition: all 0.2s; outline: none;
}
.glass-input:focus {
    background: var(--surface); border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}
select.glass-input { cursor: pointer; appearance: none; }

.form-actions {
    margin-top: 30px; border-top: 1px solid var(--header-border); padding-top: 20px;
    display: flex; justify-content: space-between; align-items: center;
}

.btn-cancel {
    color: var(--text-muted); font-weight: 600; text-decoration: none; font-size: 14px;
    padding: 10px 20px; border-radius: 8px; transition: background 0.2s;
}
.btn-cancel:hover { background: var(--body-bg); color: var(--text-primary); }

.btn-submit {
    background: var(--primary); color: white; border: none;
    padding: 12px 30px; border-radius: 10px; font-weight: 600; font-size: 14px;
    cursor: pointer; display: flex; align-items: center; gap: 8px;
    transition: all 0.2s; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4); }

@media (max-width: 768px) {
    .create-user-wrapper { margin-left: 0; padding: 20px; }
    .form-row { flex-direction: column; gap: 0; }
}
</style>
