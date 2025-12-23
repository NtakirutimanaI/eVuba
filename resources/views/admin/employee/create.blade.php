@include('layouts.header')
@include('layouts.sidebar')

<div class="create-employee-wrapper">
    <div class="glass-form-container">
        <div class="form-header">
            <div class="icon-circle">
                <i class="fas fa-user-tie"></i>
            </div>
            <h1>New Employee</h1>
            <p>Onboard a new team member to your organization.</p>
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

        <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-section">
                <h3><i class="fas fa-id-card"></i> Personal Details</h3>
                <div class="form-group">
                    <label>Full Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" class="glass-input" placeholder="e.g. Jane Smith" value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label>Email Address</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" class="glass-input" placeholder="jane@company.com" value="{{ old('email') }}" required>
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
            </div>

            <div class="form-section">
                <h3><i class="fas fa-briefcase"></i> Job Information</h3>
                <div class="form-row">
                    <div class="form-group half">
                        <label>Department</label>
                        <div class="input-wrapper">
                            <i class="fas fa-building"></i>
                            <input type="text" name="department" class="glass-input" placeholder="e.g. Marketing" value="{{ old('department') }}" list="deptList">
                            <datalist id="deptList">
                                <option value="IT">
                                <option value="HR">
                                <option value="Sales">
                                <option value="Marketing">
                            </datalist>
                        </div>
                    </div>
                    <div class="form-group half">
                        <label>Position / Title</label>
                        <div class="input-wrapper">
                            <i class="fas fa-chair"></i>
                            <input type="text" name="position" class="glass-input" placeholder="e.g. Senior Manager" value="{{ old('position') }}">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Specialization / Skills</label>
                    <div class="input-wrapper">
                        <i class="fas fa-star"></i>
                        <input type="text" name="specialization" class="glass-input" placeholder="e.g. SEO, Content Strategy, React" value="{{ old('specialization') }}">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-camera"></i> Profile Photo</h3>
                <div class="file-upload-wrapper">
                    <input type="file" name="image" id="fileInput" class="file-input" accept="image/*" onchange="previewImage(this)">
                    <div class="file-label">
                        <div class="preview-area" id="previewArea">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <span id="fileName">Click to upload photo</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.employees.index') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-check"></i> Save Employee
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

.create-employee-wrapper {
    margin-left: 250px;
    min-height: 100vh;
    display: flex;
    align-items: center; justify-content: center;
    padding: 40px;
}

.glass-form-container {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(20px);
    width: 100%; max-width: 650px;
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

.form-section { margin-bottom: 25px; padding-bottom: 25px; border-bottom: 1px solid #e2e8f0; }
.form-section:last-of-type { border-bottom: none; }
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

/* FILE UPLOAD */
.file-upload-wrapper { position: relative; width: 100%; height: 100px; }
.file-input { position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2; }
.file-label {
    position: absolute; top:0; left:0; width: 100%; height: 100%;
    border: 2px dashed #cbd5e1; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; gap: 15px;
    background: #f8fafc; transition: all 0.2s;
}
.file-input:hover + .file-label { border-color: var(--primary); background: #fff; }
.preview-area {
    width: 60px; height: 60px; border-radius: 10px; background: #e2e8f0;
    display: flex; align-items: center; justify-content: center; overflow: hidden;
}
.preview-area img { width: 100%; height: 100%; object-fit: cover; }
.preview-area i { font-size: 24px; color: #94a3b8; }
#fileName { color: var(--text-muted); font-size: 14px; font-weight: 500; }

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
    .create-employee-wrapper { margin-left: 0; padding: 20px; }
    .form-row { flex-direction: column; gap: 0; }
}
</style>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewArea').innerHTML = '<img src="'+e.target.result+'">';
            document.getElementById('fileName').innerText = input.files[0].name;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
