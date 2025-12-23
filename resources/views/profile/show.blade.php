@include('layouts.header')
@include('layouts.sidebar')

<div class="profile-intelligence-container">
    <!-- TOP COVER & AVATAR HUB -->
    <div class="profile-header-hub glass-panel">
        <div class="cover-photo"></div>
        <div class="profile-identity">
            <div class="avatar-wrap">
                @if($user->photo)
                    <img src="{{ asset('storage/profile-photos/' . $user->photo) }}" alt="User Avatar" id="main-avatar">
                @else
                    <div class="avatar-placeholder-hub">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                @endif
                <button class="camera-trigger" onclick="document.getElementById('photoInput').click()">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
            <div class="identity-text">
                <h1>{{ $user->name }}</h1>
                <p><i class="fas fa-shield-alt text-indigo"></i> {{ ucfirst($user->role) }} Portal · Member since {{ $user->created_at->format('M Y') }}</p>
            </div>
            <div class="header-stats">
                <div class="stat-item">
                    <span class="stat-val">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</span>
                    <span class="stat-lbl">Last Access</span>
                </div>
                <div class="stat-item">
                    <span class="stat-val">{{ $user->status ?? 'Active' }}</span>
                    <span class="stat-lbl">System Status</span>
                </div>
            </div>
        </div>
        
        <!-- HIDDEN PHOTO FORM -->
        <form id="photoForm" action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data" style="display:none;">
            @csrf
            <input type="file" name="photo" id="photoInput" onchange="document.getElementById('photoForm').submit()">
        </form>
    </div>

    @if(session('status'))
        <div class="intelligence-toast success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('status') === 'profile-updated' ? 'Profile Details Synchronized' : (session('status') === 'password-updated' ? 'Security Layer Updated' : 'Photo Successfully Deployed') }}</span>
        </div>
    @endif

    <!-- MAIN CONTENT GRID -->
    <div class="profile-main-grid">
        <!-- LEFT: NAVIGATION & QUICK INFO -->
        <div class="grid-sidebar">
            <div class="nav-stack glass-panel">
                <button class="nav-item active" onclick="switchTab('overview', this)">
                    <i class="fas fa-th-large"></i> Overview
                </button>
                <button class="nav-item" onclick="switchTab('edit', this)">
                    <i class="fas fa-user-edit"></i> Edit Credentials
                </button>
                <button class="nav-item" onclick="switchTab('security', this)">
                    <i class="fas fa-lock"></i> Security & Access
                </button>
            </div>

            <div class="quick-metadata glass-panel">
                <h3>System Metadata</h3>
                <div class="meta-row">
                    <span class="lbl">Account ID</span>
                    <span class="val">#EV-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="meta-row">
                    <span class="lbl">Access Role</span>
                    <span class="val badge">{{ strtoupper($user->role) }}</span>
                </div>
                <div class="meta-row">
                    <span class="lbl">Email Verified</span>
                    <span class="val">{{ $user->email_verified_at ? 'Confirmed' : 'Pending' }}</span>
                </div>
            </div>
        </div>

        <!-- RIGHT: TAB CONTENT -->
        <div class="grid-content">
            <!-- OVERVIEW TAB -->
            <div id="tab-overview" class="tab-pane active">
                <div class="content-card glass-panel">
                    <div class="card-title">
                        <h2><i class="fas fa-id-card text-indigo"></i> Identity Brief</h2>
                    </div>
                    <div class="brief-grid">
                        <div class="brief-item">
                            <label>Full Legal Name</label>
                            <p>{{ $user->name }}</p>
                        </div>
                        <div class="brief-item">
                            <label>Communication Email</label>
                            <p>{{ $user->email }}</p>
                        </div>
                        <div class="brief-item">
                            <label>Contact Phone</label>
                            <p>{{ $user->phone ?? 'Not Registered' }}</p>
                        </div>
                        <div class="brief-item">
                            <label>Gender Orientation</label>
                            <p>{{ ucfirst($user->gender ?? 'Not Specified') }}</p>
                        </div>
                        <div class="brief-item full">
                            <label>Registered Address</label>
                            <p>{{ $user->address ?? 'No physical address on record.' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="activity-timeline glass-panel">
                    <div class="card-title">
                        <h2><i class="fas fa-history text-indigo"></i> Recent Activity</h2>
                    </div>
                    <div class="timeline-empty">
                        <i class="fas fa-stream"></i>
                        <p>No recent security logs or activity detected.</p>
                    </div>
                </div>
            </div>

            <!-- EDIT TAB -->
            <div id="tab-edit" class="tab-pane">
                <div class="content-card glass-panel">
                    <div class="card-title">
                        <h2><i class="fas fa-edit text-indigo"></i> Update Identity Details</h2>
                    </div>
                    <form action="{{ route('profile.update') }}" method="POST" class="modern-form">
                        @csrf
                        @method('patch')
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+1 234 567 890">
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender">
                                    <option value="" {{ !$user->gender ? 'selected' : '' }}>Select Gender</option>
                                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Physical Address</label>
                            <textarea name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="execute-btn">Synchronize Profile</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SECURITY TAB -->
            <div id="tab-security" class="tab-pane">
                <div class="content-card glass-panel">
                    <div class="card-title">
                        <h2><i class="fas fa-key text-indigo"></i> Authentication Layer</h2>
                    </div>
                    <form action="{{ route('password.update') }}" method="POST" class="modern-form">
                        @csrf
                        @method('put')
                        
                        <div class="form-group">
                            <label>Current Gateway Password</label>
                            <input type="password" name="current_password" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>New Vault Password</label>
                                <input type="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="execute-btn">Update Security Key</button>
                        </div>
                    </form>
                </div>

                <!-- DANGER ZONE -->
                <div class="content-card glass-panel danger-zone">
                    <div class="card-title">
                        <h2 class="text-danger"><i class="fas fa-exclamation-triangle"></i> Termination Protocols</h2>
                    </div>
                    <p>Deleting your account is permanent and will cascade across all connected datasets. This action cannot be undone.</p>
                    <button class="delete-btn" onclick="openDeleteModal()">Terminate Account</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DELETE MODAL -->
<div id="deleteModal" class="modal">
    <div class="modal-content glass-panel">
        <h2>Confirm Identity Deletion</h2>
        <p>This will permanently erase your profile and records. Please enter your password to authorize termination.</p>
        <form action="{{ route('profile.destroy') }}" method="POST">
            @csrf
            @method('delete')
            <div class="form-group">
                <input type="password" name="password" placeholder="Authorize with your password" required>
            </div>
            <div class="modal-actions">
                <button type="button" class="glass-btn secondary" onclick="closeDeleteModal()">Abort</button>
                <button type="submit" class="execute-btn danger">Confirm Termination</button>
            </div>
        </form>
    </div>
</div>

<style>
.profile-intelligence-container {
    margin-left: 250px;
    padding: 30px;
    max-width: 1400px;
    background: var(--body-bg);
}

.glass-panel {
    background: var(--surface);
    border: 1px solid var(--header-border);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
}

/* HEADER HUB */
.profile-header-hub {
    position: relative;
    padding: 0;
    overflow: hidden;
    margin-bottom: 30px;
}

.cover-photo {
    height: 180px;
    background: linear-gradient(135deg, #4f46e5, #9333ea);
    opacity: 0.8;
}

.profile-identity {
    display: flex;
    align-items: flex-end;
    padding: 20px 40px 30px;
    margin-top: -60px;
    gap: 30px;
}

.avatar-wrap {
    position: relative;
    width: 140px; height: 140px;
    flex-shrink: 0;
}

.avatar-wrap img, .avatar-placeholder-hub {
    width: 100%; height: 100%;
    border-radius: 30px;
    object-fit: cover;
    border: 6px solid #fff;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.avatar-placeholder-hub {
    background: linear-gradient(135deg, #4f46e5, #9333ea);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 48px; font-weight: 900;
}

.camera-trigger {
    position: absolute; bottom: 10px; right: -5px;
    width: 36px; height: 36px;
    border-radius: 10px; background: var(--indigo);
    color: #fff; border: none; cursor: pointer;
    box-shadow: 0 4px 10px var(--indigo-glow);
    display: flex; align-items: center; justify-content: center;
}

.identity-text h1 { margin: 0; font-size: 28px; font-weight: 800; color: var(--text-primary); }
.identity-text p { margin: 5px 0 0 0; color: var(--text-muted); font-size: 15px; }

.header-stats {
    margin-left: auto;
    display: flex; gap: 30px;
}

.stat-item { text-align: right; }
.stat-val { display: block; font-size: 18px; font-weight: 800; color: var(--text-primary); }
.stat-lbl { font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }

/* MAIN GRID */
.profile-main-grid {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 30px;
}

.grid-sidebar { display: flex; flex-direction: column; gap: 30px; }

.nav-stack { padding: 10px; display: flex; flex-direction: column; gap: 5px; }
.nav-item {
    padding: 14px 20px; border-radius: 12px;
    border: none; background: transparent;
    display: flex; align-items: center; gap: 15px;
    font-size: 15px; font-weight: 700; color: var(--text-muted);
    cursor: pointer; transition: all 0.2s;
    text-align: left;
}
.nav-item i { width: 20px; font-size: 18px; }
.nav-item:hover { background: #f8fafc; color: var(--indigo); }
.nav-item.active { background: var(--indigo); color: #fff; box-shadow: 0 4px 12px var(--indigo-glow); }

.quick-metadata { padding: 25px; }
.quick-metadata h3 { margin: 0 0 20px 0; font-size: 14px; font-weight: 800; text-transform: uppercase; color: var(--text-muted); }
.meta-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--header-border); }
.meta-row:last-child { border-bottom: none; }
.meta-row .lbl { font-size: 13px; font-weight: 600; color: var(--text-muted); }
.meta-row .val { font-size: 13px; font-weight: 800; color: var(--text-primary); }
.badge { background: var(--indigo-glow); color: var(--indigo); padding: 2px 8px; border-radius: 6px; font-size: 11px; }

/* CONTENT */
.tab-pane { display: none; flex-direction: column; gap: 30px; }
.tab-pane.active { display: flex; animation: fadeIn 0.4s ease; }

@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.content-card { padding: 35px; }
.card-title { margin-bottom: 30px; }
.card-title h2 { margin: 0; font-size: 20px; font-weight: 800; color: var(--text-primary); display: flex; align-items: center; gap: 12px; }

.brief-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
.brief-item label { display: block; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
.brief-item p { margin: 0; font-size: 16px; font-weight: 600; color: var(--text-primary); }
.brief-item.full { grid-column: span 2; }

/* FORMS */
.modern-form .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-size: 13px; font-weight: 700; color: var(--text-muted); margin-bottom: 10px; }
.form-group input, .form-group select, .form-group textarea {
    width: 100%; padding: 12px 16px; border-radius: 12px;
    border: 1.5px solid var(--header-border); outline: none; transition: 0.2s;
    font-size: 14px; font-family: inherit;
    background: var(--body-bg); color: var(--text-primary);
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--indigo); box-shadow: 0 0 0 4px var(--indigo-glow); }

.form-footer { margin-top: 30px; display: flex; justify-content: flex-end; }
.execute-btn {
    padding: 12px 30px; background: var(--indigo); color: #fff; border: none;
    border-radius: 12px; font-weight: 800; cursor: pointer; transition: 0.2s;
}
.execute-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px var(--indigo-glow); }
.execute-btn.danger { background: var(--danger); }

/* DANGER ZONE */
.danger-zone { border: 1px solid rgba(239, 68, 68, 0.2); }
.danger-zone p { color: var(--text-muted); font-size: 14px; margin-bottom: 20px; }
.delete-btn {
    padding: 10px 20px; background: #fff; color: var(--danger); border: 1.5px solid var(--danger);
    border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.2s;
}
.delete-btn:hover { background: var(--danger); color: #fff; }

/* MISC */
.intelligence-toast {
    position: fixed; top: 100px; right: 30px; padding: 15px 25px;
    border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    z-index: 10001; display: flex; align-items: center; gap: 12px;
    font-weight: 700; color: #fff; animation: slideLeft 0.5s ease;
}
.intelligence-toast.success { background: #10b981; }

@keyframes slideLeft { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

.activity-timeline { padding: 35px; text-align: center; }
.timeline-empty { padding: 40px 0; color: var(--text-muted); }
.timeline-empty i { font-size: 40px; margin-bottom: 15px; opacity: 0.3; }

/* MODAL */
.modal { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(5px); z-index: 5000; align-items: center; justify-content: center; }
.modal-content { max-width: 450px; padding: 40px; text-align: center; }
.modal-content h2 { font-size: 22px; font-weight: 800; margin: 0 0 15px 0; color: var(--text-primary); }
.modal-content p { color: var(--text-muted); margin-bottom: 25px; }
.modal-actions { display: flex; gap: 15px; justify-content: center; margin-top: 20px; }

.glass-btn { padding: 12px 24px; border-radius: 12px; font-weight: 700; border: none; cursor: pointer; }
.glass-btn.secondary { background: #f1f5f9; color: #475569; }

@media (max-width: 1024px) {
    .profile-intelligence-container { margin-left: 0; padding: 15px; }
    .profile-main-grid { grid-template-columns: 1fr; }
}
</style>

<script>
function switchTab(tabId, btn) {
    // Nav Items
    document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
    btn.classList.add('active');
    
    // Panes
    document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tabId).classList.add('active');
}

function openDeleteModal() { document.getElementById('deleteModal').style.display = 'flex'; }
function closeDeleteModal() { document.getElementById('deleteModal').style.display = 'none'; }

// Auto-hide toast
setTimeout(() => {
    let t = document.querySelector('.intelligence-toast');
    if(t) t.style.display = 'none';
}, 5000);
</script>
