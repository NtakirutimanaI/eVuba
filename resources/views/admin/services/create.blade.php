@include('layouts.header')
@include('layouts.sidebar')

<div class="create-service-wrapper">
    <div class="glass-form-container">
        <div class="form-header">
            <div class="icon-circle">
                <i class="fas fa-plus-circle"></i>
            </div>
            <h1>New Service</h1>
            <p>Define a new service offering for your clients.</p>
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

        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-section">
                <h3><i class="fas fa-info-circle"></i> Service Information</h3>
                <div class="form-group">
                    <label>Service Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-tag"></i>
                        <input type="text" name="name" class="glass-input" placeholder="e.g. Premium Consulting" value="{{ old('name') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <div class="input-wrapper">
                        <i class="fas fa-align-left textarea-icon"></i>
                        <textarea name="description" class="glass-input textarea" placeholder="Describe the service details..." rows="4">{{ old('description') }}</textarea>
                    </div>
                </div>
            
                <div class="form-group">
                    <label>Assigned Staff</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user-tie"></i>
                        <select name="employee_id" class="glass-input" required>
                            <option value="" disabled selected>Select an employee...</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }} ({{ $employee->position ?? 'Staff' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-image"></i> Service Image</h3>
                <div class="file-upload-wrapper">
                    <input type="file" name="image" id="fileInput" class="file-input" accept="image/*" onchange="previewImage(this)">
                    <div class="file-label">
                        <div class="preview-area" id="previewArea">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <span id="fileName">Upload cover image</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.services.index') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-check"></i> Create Service
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Global Theme Integration */
body { background: var(--body-bg); font-family: 'Inter', sans-serif; }

.create-service-wrapper {
    margin-left: 250px;
    min-height: 100vh;
    display: flex;
    align-items: center; justify-content: center;
    padding: 40px;
}

.glass-form-container {
    background: var(--surface);
    backdrop-filter: blur(20px);
    width: 100%; max-width: 600px;
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

.form-section { padding-bottom: 15px; border-bottom: 1px solid var(--header-border); margin-bottom: 25px; }
.form-section:last-of-type { border-bottom: none; margin-bottom: 0; }
.form-section h3 { font-size: 15px; color: var(--text-primary); margin-bottom: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
.form-section h3 i { color: var(--primary); }

.alert-glass.error {
    background: rgba(239, 68, 68, 0.1); color: #ef4444;
    padding: 15px; border-radius: 12px; border: 1px solid rgba(239, 68, 68, 0.2);
    margin-bottom: 25px; display: flex; gap: 12px; align-items: flex-start;
}
.error-list { display: flex; flex-direction: column; gap: 4px; font-size: 13px; font-weight: 500; }

.form-group { margin-bottom: 20px; }
.form-group label {
    display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700;
    color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;
}

.input-wrapper { position: relative; }
.input-wrapper i {
    position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); font-size: 16px; pointer-events: none;
}
.input-wrapper i.textarea-icon { top: 20px; transform: none; }

.glass-input {
    width: 100%; padding: 12px 15px 12px 45px;
    border-radius: 10px; border: 1px solid var(--header-border);
    background: var(--body-bg); font-size: 14px; color: var(--text-primary);
    transition: all 0.2s; outline: none;
}
.glass-input.textarea { min-height: 100px; resize: vertical; line-height: 1.5; }
.glass-input:focus {
    background: var(--surface); border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}
select.glass-input { appearance: none; cursor: pointer; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 15px center; background-size: 16px; }

/* FILE UPLOAD */
.file-upload-wrapper { position: relative; width: 100%; height: 120px; }
.file-input { position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2; }
.file-label {
    position: absolute; top:0; left:0; width: 100%; height: 100%;
    border: 2px dashed var(--header-border); border-radius: 12px;
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px;
    background: var(--body-bg); transition: all 0.2s;
}
.file-input:hover + .file-label { border-color: var(--primary); background: var(--surface); }
.preview-area {
    width: 100px; height: 60px; border-radius: 8px; background: rgba(0,0,0,0.1);
    display: flex; align-items: center; justify-content: center; overflow: hidden;
}
.preview-area img { width: 100%; height: 100%; object-fit: cover; }
.preview-area i { font-size: 24px; color: var(--text-muted); }
#fileName { color: var(--text-muted); font-size: 13px; font-weight: 500; }

.form-actions {
    margin-top: 10px; border-top: 1px solid var(--header-border); padding-top: 25px;
    display: flex; justify-content: space-between; align-items: center;
}
.btn-cancel {
    color: var(--text-muted); font-weight: 600; text-decoration: none; font-size: 14px;
    padding: 10px 20px; border-radius: 8px; transition: background 0.2s;
}
.btn-cancel:hover { background: var(--body-bg); color: var(--text-primary); }
.btn-submit {
    background: var(--primary); color: #fff; border: none;
    padding: 12px 30px; border-radius: 10px; font-weight: 600; font-size: 14px;
    cursor: pointer; display: flex; align-items: center; gap: 8px;
    transition: all 0.2s; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4); }

@media (max-width: 768px) {
    .create-service-wrapper { margin-left: 0; padding: 20px; }
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
