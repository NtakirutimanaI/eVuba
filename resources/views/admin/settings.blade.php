@include('layouts.header')
@include('layouts.sidebar')

<div class="settings-wrapper">
    <!-- Header -->
    <div class="settings-header">
        <h1><i class="fas fa-cogs"></i> System Settings</h1>
        <p>Manage your application preferences, business details, and module configurations.</p>
    </div>

    @if(session('success'))
        <div class="alert-success-glass">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="settings-container glass-panel">
        <!-- Sidebar Tabs -->
        <div class="settings-sidebar">
            <div class="tab-item active" onclick="switchTab('general')">
                <i class="fas fa-sliders-h"></i> General
            </div>
            <div class="tab-item" onclick="switchTab('business')">
                <i class="fas fa-briefcase"></i> Business Info
            </div>
            <div class="tab-item" onclick="switchTab('modules')">
                <i class="fas fa-cubes"></i> Modules
            </div>
            <div class="tab-item" onclick="switchTab('notifications')">
                <i class="fas fa-bell"></i> Notifications
            </div>
            <div class="tab-item" onclick="switchTab('security')">
                <i class="fas fa-shield-alt"></i> Security
            </div>
        </div>

        <!-- Content Area -->
        <div class="settings-content">
            <form action="{{ route('admin.settings.store') }}" method="POST">
                @csrf
                
                <!-- GENERAL TAB -->
                <div id="tab-general" class="tab-pane active">
                    <h3>General Application Settings</h3>
                    <div class="form-group">
                        <label>Application Name</label>
                        <input type="text" name="app_name" value="{{ $get('app_name') }}" class="glass-input">
                        <input type="hidden" name="group_app_name" value="general">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" name="app_desc" value="{{ $get('app_desc') }}" class="glass-input">
                         <input type="hidden" name="group_app_desc" value="general">
                    </div>
                     <div class="form-group">
                        <label>Admin Email</label>
                        <input type="email" name="admin_email" value="{{ $get('admin_email') }}" class="glass-input">
                        <input type="hidden" name="group_admin_email" value="general">
                    </div>
                     <div class="form-row">
                        <div class="form-group half">
                            <label>Currency Symbol</label>
                            <input type="text" name="currency_symbol" value="{{ $get('currency_symbol') }}" class="glass-input">
                            <input type="hidden" name="group_currency_symbol" value="general">
                        </div>
                    </div>
                </div>

                <!-- BUSINESS TAB -->
                <div id="tab-business" class="tab-pane">
                    <h3>Business Information (for Invoices)</h3>
                    <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" name="company_name" value="{{ $get('company_name') }}" class="glass-input">
                        <input type="hidden" name="group_company_name" value="business">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="company_address" class="glass-input" rows="3">{{ $get('company_address') }}</textarea>
                         <input type="hidden" name="group_company_address" value="business">
                    </div>
                    <div class="form-row">
                        <div class="form-group half">
                            <label>Phone Number</label>
                            <input type="text" name="company_phone" value="{{ $get('company_phone') }}" class="glass-input">
                             <input type="hidden" name="group_company_phone" value="business">
                        </div>
                        <div class="form-group half">
                            <label>Tax ID / VAT</label>
                            <input type="text" name="tax_id" value="{{ $get('tax_id') }}" class="glass-input">
                            <input type="hidden" name="group_tax_id" value="business">
                        </div>
                    </div>
                </div>

                <!-- MODULES TAB -->
                <div id="tab-modules" class="tab-pane">
                    <h3>Enable / Disable Modules</h3>
                    <div class="toggle-list">
                        <div class="toggle-item">
                            <div class="info">
                                <strong>Stock Management</strong>
                                <p>Enable stock in/out and inventory tracking.</p>
                            </div>
                            <label class="switch">
                                <input type="hidden" name="module_stock" value="0">
                                <input type="checkbox" name="module_stock" value="1" {{ $get('module_stock') ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                             <input type="hidden" name="group_module_stock" value="modules">
                        </div>
                        
                        <div class="toggle-item">
                            <div class="info">
                                <strong>Invoicing System</strong>
                                <p>Enable creating and managing customer invoices.</p>
                            </div>
                            <label class="switch">
                                <input type="hidden" name="module_invoices" value="0">
                                <input type="checkbox" name="module_invoices" value="1" {{ $get('module_invoices') ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <input type="hidden" name="group_module_invoices" value="modules">
                        </div>

                         <div class="toggle-item">
                            <div class="info">
                                <strong>Customer Support</strong>
                                <p>Enable ticketing and messaging system.</p>
                            </div>
                           <label class="switch">
                                <input type="hidden" name="module_support" value="0">
                                <input type="checkbox" name="module_support" value="1" {{ $get('module_support') ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <input type="hidden" name="group_module_support" value="modules">
                        </div>
                    </div>
                </div>

                <!-- NOTIFICATIONS TAB -->
                <div id="tab-notifications" class="tab-pane">
                    <h3>Notification Preferences</h3>
                    <div class="toggle-list">
                        <div class="toggle-item">
                            <div class="info"><strong>New Orders</strong></div>
                            <label class="switch">
                                <input type="hidden" name="notify_new_order" value="0">
                                <input type="checkbox" name="notify_new_order" value="1" {{ $get('notify_new_order') ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                             <input type="hidden" name="group_notify_new_order" value="notifications">
                        </div>
                        <div class="toggle-item">
                            <div class="info"><strong>Low Stock Alert</strong></div>
                            <label class="switch">
                                <input type="hidden" name="notify_low_stock" value="0">
                                <input type="checkbox" name="notify_low_stock" value="1" {{ $get('notify_low_stock') ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <input type="hidden" name="group_notify_low_stock" value="notifications">
                        </div>
                         <div class="toggle-item">
                            <div class="info"><strong>New Messages</strong></div>
                             <label class="switch">
                                <input type="hidden" name="notify_new_message" value="0">
                                <input type="checkbox" name="notify_new_message" value="1" {{ $get('notify_new_message') ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <input type="hidden" name="group_notify_new_message" value="notifications">
                        </div>
                    </div>
                </div>

                <!-- SECURITY TAB -->
                <div id="tab-security" class="tab-pane">
                    <h3>Security Settings</h3>
                    <div class="form-group">
                        <label>Session Timeout (minutes)</label>
                        <input type="number" name="session_timeout" value="{{ $get('session_timeout') ?? 120 }}" class="glass-input">
                        <input type="hidden" name="group_session_timeout" value="security">
                    </div>
                    <div class="toggle-item" style="margin-top:20px;">
                        <div class="info"><strong>Enforce Strong Passwords</strong></div>
                        <label class="switch">
                            <input type="hidden" name="strong_password" value="0">
                            <input type="checkbox" name="strong_password" value="1" {{ $get('strong_password') ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                        <input type="hidden" name="group_strong_password" value="security">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="save-btn"><i class="fas fa-save"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Theme Variables */
/* Rely on global variables from app layout */

body { background: var(--body-bg); font-family: 'Inter', sans-serif; color: var(--text-primary); margin: 0; }

.settings-wrapper {
    margin-left: 250px;
    padding: 30px 40px;
    min-height: 100vh;
    background: var(--body-bg);
}

.settings-header { margin-bottom: 30px; }
.settings-header h1 { font-size: 28px; font-weight: 800; color: var(--text-primary); margin: 0 0 5px 0; }
.settings-header p { color: var(--text-muted); margin: 0; font-size: 15px; }

.alert-success-glass {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 25px;
    display: flex; align-items: center; gap: 10px;
    font-weight: 600;
}

.settings-container {
    display: flex;
    min-height: 600px;
}

.glass-panel {
    background: var(--surface);
    backdrop-filter: blur(20px);
    border: 1px solid var(--header-border);
    border-radius: 20px;
    box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05);
    overflow: hidden;
}

/* SIDEBAR */
.settings-sidebar {
    width: 260px;
    background: var(--surface);
    border-right: 1px solid var(--header-border);
    padding: 20px 0;
    flex-shrink: 0;
}

.tab-item {
    padding: 15px 25px;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.2s;
    display: flex; align-items: center; gap: 12px;
    border-left: 3px solid transparent;
}
.tab-item:hover { color: var(--primary); background: var(--body-bg); }
.tab-item.active { color: var(--primary); background: var(--body-bg); border-left-color: var(--primary); box-shadow: 0 2px 10px rgba(0,0,0,0.03); }

/* CONTENT */
.settings-content {
    flex: 1;
    padding: 40px;
    overflow-y: auto;
}

.tab-pane { display: none; animation: fadeIn 0.3s ease; }
.tab-pane.active { display: block; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.tab-pane h3 {
    margin: 0 0 25px 0;
    font-size: 18px;
    color: var(--text-primary);
    border-bottom: 1px solid var(--header-border);
    padding-bottom: 10px;
}

/* FORMS */
.form-group { margin-bottom: 20px; }
.form-row { display: flex; gap: 20px; }
.form-group.half { flex: 1; }

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 13px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
}

.glass-input {
    width: 100%;
    padding: 12px 15px;
    border-radius: 10px;
    border: 1px solid var(--header-border);
    background: var(--body-bg);
    font-size: 14px;
    transition: all 0.2s;
    font-family: inherit;
    color: var(--text-primary);
}
.glass-input:focus {
    outline: none;
    background: var(--surface);
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}

.form-actions {
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid var(--header-border);
    text-align: right;
}

.save-btn {
    background: var(--primary);
    color: #fff;
    border: none;
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
}
.save-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4); }

/* TOGGLES */
.toggle-list { display: flex; flex-direction: column; gap: 20px; }
.toggle-item {
    display: flex; justify-content: space-between; align-items: center;
    padding: 15px; border-radius: 12px;
    background: var(--body-bg); border: 1px solid var(--header-border);
}
.toggle-item .info strong { display: block; color: var(--text-primary); font-size: 14px; }
.toggle-item .info p { margin: 2px 0 0 0; color: var(--text-muted); font-size: 12px; }

/* SWITCH */
.switch { position: relative; display: inline-block; width: 50px; height: 26px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--header-border); transition: .4s; border-radius: 34px; }
.slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
input:checked + .slider { background-color: var(--primary); }
input:checked + .slider:before { transform: translateX(24px); }

@media (max-width: 900px) {
    .settings-wrapper { margin-left: 0; padding: 20px; }
    .settings-container { flex-direction: column; }
    .settings-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--header-border); display: flex; overflow-x: auto; }
    .tab-item { white-space: nowrap; border-left: none; border-bottom: 3px solid transparent; }
    .tab-item.active { border-left: none; border-bottom-color: var(--primary); }
}
</style>

<script>
function switchTab(tabId) {
    // Hide all tabs
    document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-item').forEach(el => el.classList.remove('active'));

    // Show target
    document.getElementById('tab-' + tabId).classList.add('active');
    
    // Highlight sidebar
    // Note: finding by text content or index is brittle, ideally add IDs to tabs
    const sidebarItems = document.querySelectorAll('.tab-item');
    if(tabId === 'general') sidebarItems[0].classList.add('active');
    if(tabId === 'business') sidebarItems[1].classList.add('active');
    if(tabId === 'modules') sidebarItems[2].classList.add('active');
    if(tabId === 'notifications') sidebarItems[3].classList.add('active');
    if(tabId === 'security') sidebarItems[4].classList.add('active');
}
</script>
