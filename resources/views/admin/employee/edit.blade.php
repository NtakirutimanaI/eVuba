@include('layouts.header')
@include('layouts.sidebar')

<div class="page-wrapper main-content">
    <div class="edit-profile-container fade-in">
        
        <!-- Header Section -->
        <div class="hub-header">
            <div class="header-content">
                <div class="title-group">
                    <h1>Edit Professional Identity</h1>
                    <p>Updating profile for <span class="highlight-text">{{ $employee->name }}</span></p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.employees.index') }}" class="glass-btn secondary">
                        <i class="fas fa-chevron-left"></i> Back to Team
                    </a>
                </div>
            </div>
        </div>

        <!-- Feedback Messages -->
        @if(session('success'))
            <div class="notif-toast success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="notif-toast error">
                <i class="fas fa-exclamation-circle"></i>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data" class="hub-layout">
            @csrf
            @method('PUT')

            <!-- Sidebar: Identity Card -->
            <div class="hub-sidebar">
                <div class="glass-panel identity-card">
                    <div class="avatar-upload-section">
                        <div class="avatar-preview-wrap">
                            @if($employee->image)
                                <img src="{{ asset('storage/' . $employee->image) }}" id="avatarPreview" alt="Profile avatar">
                            @else
                                <div class="avatar-placeholder" id="avatarPreview">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            <label for="image" class="upload-badge" title="Change Photo">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" id="image" name="image" accept="image/*" class="hidden-input" onchange="previewImage(this)">
                        </div>
                        <div class="identity-info">
                            <h3>{{ $employee->name }}</h3>
                            <span class="role-badge">{{ $employee->position ?? 'Professional' }}</span>
                        </div>
                    </div>
                    
                    <div class="completion-grid">
                        <div class="meta-item">
                            <span class="m-label">Department</span>
                            <span class="m-val">{{ $employee->department ?? 'Unassigned' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="m-label">Member Since</span>
                            <span class="m-val">{{ $employee->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="glass-panel quick-stats">
                    <h4>Quick Stats</h4>
                    <div class="stat-row">
                        <i class="fas fa-briefcase"></i>
                        <span>{{ $employee->specialization ?? 'Generalist' }}</span>
                    </div>
                    <div class="stat-row">
                        <i class="fas fa-envelope"></i>
                        <span class="truncate">{{ $employee->email }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Panel: Form Sections -->
            <div class="hub-main">
                <div class="glass-panel form-section">
                    <div class="section-header">
                        <i class="fas fa-user-edit"></i>
                        <h2>Personal Information</h2>
                    </div>
                    <div class="form-grid">
                        <div class="form-group floating">
                            <label>Full Name *</label>
                            <div class="input-wrap">
                                <i class="fas fa-id-card"></i>
                                <input type="text" name="name" value="{{ old('name', $employee->name) }}" required placeholder="Enter full name">
                            </div>
                        </div>
                        <div class="form-group floating">
                            <label>Email Address *</label>
                            <div class="input-wrap">
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="email" value="{{ old('email', $employee->email) }}" required placeholder="example@evuba.com">
                            </div>
                        </div>
                        <div class="form-group floating">
                            <label>Phone Number</label>
                            <div class="input-wrap">
                                <i class="fas fa-phone"></i>
                                <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" placeholder="+XXX XXX XXX XXX">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-panel form-section">
                    <div class="section-header">
                        <i class="fas fa-briefcase"></i>
                        <h2>Professional Profile</h2>
                    </div>
                    <div class="form-grid">
                        <div class="form-group floating">
                            <label>Job Title / Position</label>
                            <div class="input-wrap">
                                <i class="fas fa-user-tag"></i>
                                <input type="text" name="position" value="{{ old('position', $employee->position) }}" placeholder="e.g. Senior Developer">
                            </div>
                        </div>
                        <div class="form-group floating">
                            <label>Department</label>
                            <div class="input-wrap">
                                <i class="fas fa-sitemap"></i>
                                <input type="text" name="department" value="{{ old('department', $employee->department) }}" placeholder="e.g. Technology">
                            </div>
                        </div>
                        <div class="form-group floating full-width">
                            <label>Core Specialization</label>
                            <div class="input-wrap">
                                <i class="fas fa-star"></i>
                                <input type="text" name="specialization" value="{{ old('specialization', $employee->specialization) }}" placeholder="e.g. Fullstack Engineering, Cloud Architecture">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="glass-btn primary large-btn">
                        <i class="fas fa-save"></i> Synchronize Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
/* Hub Layout */
.edit-profile-container {
    margin-left: 222px;
    padding: 30px 50px;
    max-width: none;
}

.hub-header {
    margin-bottom: 30px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.title-group h1 {
    font-size: 28px;
    font-weight: 800;
    margin: 0;
    color: var(--text-primary);
    letter-spacing: -0.5px;
}

.title-group p {
    margin: 5px 0 0;
    color: var(--text-muted);
}

.highlight-text {
    color: var(--indigo);
    font-weight: 700;
}

.hub-layout {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 30px;
    align-items: flex-start;
}

/* Glass Panels */
.glass-panel {
    background: var(--surface);
    border: 1px solid var(--header-border);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    padding: 25px;
    box-shadow: var(--glass-shadow);
    margin-bottom: 25px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Sidebar Identity Card */
.identity-card {
    text-align: center;
}

.avatar-upload-section {
    margin-bottom: 20px;
}

.avatar-preview-wrap {
    position: relative;
    width: 140px;
    height: 140px;
    margin: 0 auto 20px;
}

.avatar-preview-wrap img, .avatar-placeholder {
    width: 100%;
    height: 100%;
    border-radius: 40px;
    object-fit: cover;
    border: 4px solid var(--body-bg);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    background: var(--body-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 50px;
    color: var(--text-muted);
}

.upload-badge {
    position: absolute;
    bottom: -5px;
    right: -5px;
    width: 40px;
    height: 40px;
    background: var(--indigo);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: 3px solid var(--surface);
    transition: 0.2s;
}

.upload-badge:hover {
    transform: scale(1.1) rotate(5deg);
    background: var(--primary);
}

.identity-info h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 800;
    color: var(--text-primary);
}

.role-badge {
    display: inline-block;
    padding: 4px 12px;
    background: var(--indigo-glow);
    color: var(--indigo);
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    margin-top: 8px;
    text-transform: uppercase;
}

.completion-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid var(--header-border);
}

.meta-item {
    text-align: left;
}

.m-label {
    display: block;
    font-size: 10px;
    color: var(--text-muted);
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 2px;
}

.m-val {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-primary);
}

/* Quick Stats */
.quick-stats h4 {
    margin: 0 0 15px;
    font-size: 14px;
    font-weight: 800;
}

.stat-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    color: var(--text-primary);
    font-size: 13px;
}

.stat-row i {
    width: 32px;
    height: 32px;
    background: var(--body-bg);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--indigo);
    font-size: 14px;
}

.truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}

/* Form Sections */
.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 25px;
}

.section-header i {
    font-size: 20px;
    color: var(--indigo);
}

.section-header h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
    color: var(--text-primary);
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.full-width {
    grid-column: span 2;
}

.form-group label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: var(--text-muted);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.input-wrap {
    position: relative;
}

.input-wrap i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 14px;
}

.input-wrap input {
    width: 100%;
    padding: 12px 15px 12px 45px;
    background: var(--body-bg);
    border: 1.5px solid var(--header-border);
    border-radius: 12px;
    color: var(--text-primary);
    font-family: inherit;
    font-size: 14px;
    transition: 0.2s;
}

.input-wrap input:focus {
    outline: none;
    border-color: var(--indigo);
    background: var(--surface);
    box-shadow: 0 0 0 4px var(--indigo-glow);
}

/* Buttons */
.glass-btn {
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: 0.3s;
    text-decoration: none;
    border: none;
}

.glass-btn.primary {
    background: var(--indigo);
    color: white;
}

.glass-btn.primary:hover {
    background: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px var(--indigo-glow);
}

.glass-btn.secondary {
    background: var(--body-bg);
    color: var(--text-primary);
    border: 1.5px solid var(--header-border);
}

.glass-btn.secondary:hover {
    background: var(--surface);
    border-color: var(--indigo);
}

.large-btn {
    padding: 15px 30px;
    font-size: 16px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 10px;
}

/* Toasts */
.notif-toast {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 25px;
    border-radius: 15px;
    margin-bottom: 25px;
    font-weight: 600;
    animation: slideDown 0.4s ease;
}

.notif-toast.success {
    background: #dcfce7;
    color: #166534;
    border-left: 5px solid #10b981;
}

.notif-toast.error {
    background: #fee2e2;
    color: #991b1b;
    border-left: 5px solid #ef4444;
}

.notif-toast ul {
    margin: 0;
    padding-left: 20px;
}

/* Animations & Utils */
@keyframes fade-in { 
    from { opacity: 0; transform: translateY(10px); } 
    to { opacity: 1; transform: translateY(0); } 
}
.fade-in { animation: fade-in 0.5s ease-out; }

.hidden-input {
    display: none;
}

@media (max-width: 992px) {
    .edit-profile-container {
        margin-left: 0;
        padding: 20px;
    }
    .hub-layout {
        grid-template-columns: 1fr;
    }
    .hub-sidebar {
        order: 2;
    }
    .hub-main {
        order: 1;
    }
}

@media (max-width: 600px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    .full-width {
        grid-column: span 1;
    }
    .header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
}
</style>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                // Handle placeholder transition
                const img = document.createElement('img');
                img.src = e.target.result;
                img.id = 'avatarPreview';
                preview.parentNode.replaceChild(img, preview);
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
