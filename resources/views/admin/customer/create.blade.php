@include('layouts.header')
@include('layouts.sidebar')

<div class="create-customer-wrapper">
    <div class="glass-form-container">
        <div class="form-header">
            <div class="icon-circle">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>New Customer</h1>
            <p>Add a new client to your customer database.</p>
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

        <form action="{{ route('admin.customers.store') }}" method="POST">
            @csrf
            
            <div class="form-section">
                <h3><i class="fas fa-id-card"></i> Client Details</h3>
                <div class="form-group">
                    <label>Full Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" class="glass-input" placeholder="e.g. Acme Corp" value="{{ old('name') }}" required autofocus>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label>Email Address</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" class="glass-input" placeholder="contact@acme.com" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="form-group half">
                        <label>Phone Number</label>
                        <div class="input-wrapper">
                            <i class="fas fa-phone"></i>
                            <input type="text" name="phone" class="glass-input" placeholder="+1 (555) 000-0000" value="{{ old('phone') }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Billing / Shipping Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" name="address" class="glass-input" placeholder="123 Business Rd, City, Country" value="{{ old('address') }}">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.customers.index') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-check"></i> Create Customer
                </button>
            </div>
        </form>
    </div>
</div>

<style>
:root {
    --primary: #4f46e5;
    --primary-glow: rgba(79, 70, 229, 0.4);
    --bg-app: #f1f5f9;
    --text-main: #1e293b;
    --text-muted: #64748b;
    --danger: #ef4444;
}

body { background: var(--bg-app); font-family: 'Inter', sans-serif; }

.create-customer-wrapper {
    margin-left: 250px;
    min-height: 100vh;
    display: flex;
    align-items: center; justify-content: center;
    padding: 40px;
}

.glass-form-container {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    width: 100%; max-width: 600px;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 20px 50px -10px rgba(0,0,0,0.1);
    border: 1px solid rgba(255, 255, 255, 0.5);
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp { from{opacity:0; transform:translateY(20px);} to{opacity:1; transform:translateY(0);} }

.form-header { text-align: center; margin-bottom: 30px; }
.icon-circle {
    width: 60px; height: 60px; background: #e0e7ff; color: var(--primary);
    border-radius: 50%; margin: 0 auto 15px;
    display: flex; align-items: center; justify-content: center; font-size: 24px;
}
.form-header h1 { font-size: 24px; color: var(--text-main); margin: 0 0 5px; font-weight: 800; }
.form-header p { color: var(--text-muted); margin: 0; font-size: 14px; }

.form-section { padding-bottom: 10px; }
.form-section h3 { font-size: 15px; color: var(--text-main); margin-bottom: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
.form-section h3 i { color: var(--primary); }

.alert-glass.error {
    background: #fef2f2; color: var(--danger);
    padding: 15px; border-radius: 12px; border: 1px solid #fecaca;
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
    color: #94a3b8; font-size: 16px; pointer-events: none;
}
.glass-input {
    width: 100%; padding: 12px 15px 12px 45px;
    border-radius: 10px; border: 1px solid #cbd5e1;
    background: #f8fafc; font-size: 14px; color: var(--text-main);
    transition: all 0.2s; outline: none;
}
.glass-input:focus {
    background: #fff; border-color: var(--primary);
    box-shadow: 0 0 0 4px var(--primary-glow);
}

.form-actions {
    margin-top: 20px; border-top: 1px solid #cbd5e1; padding-top: 25px;
    display: flex; justify-content: space-between; align-items: center;
}
.btn-cancel {
    color: var(--text-muted); font-weight: 600; text-decoration: none; font-size: 14px;
    padding: 10px 20px; border-radius: 8px; transition: background 0.2s;
}
.btn-cancel:hover { background: #f1f5f9; color: var(--text-main); }
.btn-submit {
    background: var(--primary); color: #fff; border: none;
    padding: 12px 30px; border-radius: 10px; font-weight: 600; font-size: 14px;
    cursor: pointer; display: flex; align-items: center; gap: 8px;
    transition: all 0.2s; box-shadow: 0 4px 15px var(--primary-glow);
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 25px var(--primary-glow); background: #4338ca; }

@media (max-width: 768px) {
    .create-customer-wrapper { margin-left: 0; padding: 20px; }
    .form-row { flex-direction: column; gap: 0; }
}
</style>
