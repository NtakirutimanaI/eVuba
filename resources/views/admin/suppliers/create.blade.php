@include('layouts.header')
@include('layouts.sidebar')

<div class="page-wrapper">
    <div class="glass-container">
        <!-- Header -->
        <div class="form-header">
            <div class="header-icon">
                <i class="fas fa-truck-loading"></i>
            </div>
            <h2>Add New Supplier</h2>
            <p>Register a new partner to your supply chain</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="glass-alert success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="glass-alert error">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('admin.stock_in.storeSupplier') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Supplier Name <span class="required">*</span></label>
                <div class="input-wrapper">
                    <i class="fas fa-building inside-icon"></i>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Acme Logistics Corp" required autofocus>
                </div>
            </div>

            <div class="row-group">
                <div class="form-group half">
                    <label>Contact Person</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user inside-icon"></i>
                        <input type="text" name="contact" value="{{ old('contact') }}" placeholder="e.g. John Doe">
                    </div>
                </div>
                <div class="form-group half">
                    <label>Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope inside-icon"></i>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="contact@acme.com">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-map-marker-alt inside-icon top-align"></i>
                    <textarea name="address" rows="3" placeholder="Full business address...">{{ old('address') }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-plus"></i> Register Supplier
                </button>
                <a href="{{ route('admin.stock_in.index') }}" class="btn-cancel">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
/* --- PREMIUM THEME --- */
body { background: var(--body-bg); font-family: 'Inter', sans-serif; font-size: 13px; color: var(--text-primary); margin: 0; }
.page-wrapper { margin-left: 250px; padding: 40px 20px; min-height: 100vh; display: flex; justify-content: center; align-items: flex-start; }

.glass-container {
    width: 600px; /* Centered narrow card */
    background: var(--surface);
    border: 1px solid var(--header-border);
    border-radius: 16px;
    box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05);
    padding: 35px;
    animation: slideUp 0.4s ease-out;
}

@keyframes slideUp { from{opacity:0; transform:translateY(20px);} to{opacity:1; transform:translateY(0);} }

/* Header */
.form-header { text-align: center; margin-bottom: 30px; }
.header-icon { 
    width: 50px; height: 50px; background: rgba(79, 70, 229, 0.1); color: var(--primary); 
    border-radius: 50%; display: flex; align-items: center; justify-content: center; 
    font-size: 20px; margin: 0 auto 15px; 
}
.form-header h2 { margin: 0; font-size: 20px; font-weight: 700; color: var(--text-primary); letter-spacing: -0.5px; }
.form-header p { margin: 5px 0 0; font-size: 13px; color: var(--text-muted); }

/* Alerts */
.glass-alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 12px; display: flex; align-items: center; gap: 8px; }
.glass-alert.success { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
.glass-alert.error { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
.glass-alert ul { list-style: none; margin: 0; padding: 0; }

/* Inputs */
.form-group { margin-bottom: 18px; }
.form-group label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; color: var(--text-muted); }
.required { color: #ef4444; }

.input-wrapper { position: relative; }
.inside-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 13px; pointer-events: none; }
.inside-icon.top-align { top: 12px; transform: none; }

.page-wrapper input, .page-wrapper textarea {
    width: 100%; padding: 10px 12px 10px 36px; /* Space for icon */
    border: 1px solid var(--header-border); border-radius: 8px;
    background: var(--body-bg); font-size: 13px; color: var(--text-primary);
    transition: all 0.2s; outline: none; font-family: inherit;
    box-sizing: border-box; /* Crucial fix for 100% width */
}
.page-wrapper input:focus, .page-wrapper textarea:focus {
    background: var(--surface); border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.row-group { display: flex; gap: 15px; }
.half { flex: 1; }

/* Actions */
.form-actions { margin-top: 30px; display: flex; flex-direction: column; gap: 10px; }
.btn-submit {
    width: 100%; padding: 12px; background: var(--primary); color: white;
    border: none; border-radius: 8px; font-weight: 600; font-size: 13px;
    cursor: pointer; transition: filter 0.2s, transform 0.1s; display: flex; align-items: center; justify-content: center; gap: 8px;
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
}
.btn-submit:hover { filter: brightness(110%); transform: translateY(-1px); }

.btn-cancel {
    text-align: center; font-size: 12px; color: var(--text-muted); text-decoration: none; padding: 8px;
    border-radius: 6px; transition: color 0.2s;
}
.btn-cancel:hover { color: var(--text-primary); background: var(--surface); }

</style>
