@include('layouts.header')
@include('layouts.sidebar')

<div class="employee-dashboard-wrapper">
    <!-- Header -->
    <div class="page-header">
        <div class="header-title">
            <h1><i class="fas fa-id-card-alt"></i> Employee Directory</h1>
            <p>Manage your team, track positions, and organize departments.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.employees.create') }}" class="btn-glass primary">
                <i class="fas fa-plus"></i> New Employee
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card glass-panel">
            <div class="icon-box blue"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <h3>{{ $stats['total'] }}</h3>
                <span>Total Employees</span>
            </div>
        </div>
        <div class="stat-card glass-panel">
            <div class="icon-box purple"><i class="fas fa-building"></i></div>
            <div class="stat-info">
                <h3>{{ $stats['departments'] }}</h3>
                <span>Departments</span>
            </div>
        </div>
        <div class="stat-card glass-panel">
            <div class="icon-box green"><i class="fas fa-user-clock"></i></div>
            <div class="stat-info">
                <h3>{{ $stats['recent'] }}</h3>
                <span>Hired This Month</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-panel glass-panel">
        <div class="panel-toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="employeeSearch" placeholder="Search by name, position, or department..." onkeyup="filterEmployees()">
            </div>
        </div>

        <div class="employee-grid" id="employeeGrid">
            @forelse($employees as $employee)
            <div class="employee-card">
                <div class="card-menu">
                    <button class="menu-dots"><i class="fas fa-ellipsis-v"></i></button>
                    <div class="dropdown-menu">
                        <a href="{{ route('admin.employees.edit', $employee->id) }}"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Remove this employee?');">
                            @csrf @method('DELETE')
                            <button type="submit"><i class="fas fa-trash-alt"></i> Delete</button>
                        </form>
                    </div>
                </div>

                <div class="card-profile">
                    @if($employee->image)
                        <img src="{{ Storage::url($employee->image) }}" alt="{{ $employee->name }}" class="avatar-img">
                    @else
                        <div class="avatar-initials">{{ strtoupper(substr($employee->name, 0, 2)) }}</div>
                    @endif
                    <h4>{{ $employee->name }}</h4>
                    <span class="role">{{ $employee->position ?? 'No Position' }}</span>
                </div>

                <div class="card-details">
                    <div class="detail-item">
                        <i class="fas fa-envelope"></i>
                        <span>{{ Str::limit($employee->email, 22) }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-building"></i>
                        <span>{{ $employee->department ?? 'General' }}</span>
                    </div>
                    @if($employee->specialization)
                    <div class="detail-item">
                        <i class="fas fa-star"></i>
                        <span>{{ $employee->specialization }}</span>
                    </div>
                    @endif
                    <!-- Performance Metrics can be re-enabled here nicely -->
                </div>

                <div class="card-actions">
                    <button onclick="openMessageModal('{{ $employee->id }}', '{{ $employee->name }}')" class="btn-action mail" title="Send Message">
                        <i class="fas fa-envelope"></i>
                    </button>
                    <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn-action edit" title="Edit Profile"><i class="fas fa-pen"></i></a>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-user-slash"></i>
                <p>No employees found in the directory.</p>
            </div>
            @endforelse
        </div>

        <div class="pagination-wrapper">
             {{ $employees->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<!-- Message Modal -->
<div id="messageModal" class="modal-overlay" style="display: none;">
    <div class="modal-glass">
        <div class="modal-header">
            <h3><i class="fas fa-paper-plane"></i> Message <span id="msgEmployeeName"></span></h3>
            <button class="btn-close" onclick="closeMessageModal()">&times;</button>
        </div>
        <form id="messageForm" action="" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" class="glass-input" required placeholder="Meeting, Task, etc.">
            </div>

            <div class="form-group">
                <label>Message</label>
                <textarea name="message" class="glass-input" rows="4" required placeholder="Type your message here..."></textarea>
            </div>

            <button type="submit" class="btn-glass primary" style="width: 100%; justify-content: center;">
                <i class="fas fa-paper-plane"></i> Send Message
            </button>
        </form>
    </div>
</div>

<style>
/* Modal Styles specific to this page if not global */
.modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.5); z-index: 9999;
    display: flex; align-items: center; justify-content: center;
    backdrop-filter: blur(5px);
}
.modal-glass {
    background: var(--surface); padding: 30px; border-radius: 20px;
    width: 500px; max-width: 90%;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    border: 1px solid var(--header-border);
    animation: zoomIn 0.2s ease;
}
.modal-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 25px;
}
.modal-header h3 { margin: 0; font-size: 20px; color: var(--text-primary); }
.btn-close { background: none; border: none; font-size: 24px; color: var(--text-muted); cursor: pointer; }
.btn-close:hover { color: #ef4444; }

.glass-input {
    width: 100%; padding: 12px; margin-top: 8px;
    border-radius: 10px; border: 1px solid var(--header-border);
    background: var(--body-bg); color: var(--text-primary);
    font-size: 14px; outline: none; font-family: inherit;
}
.glass-input:focus { border-color: var(--primary); background: var(--surface); }

@keyframes zoomIn { from{opacity:0; transform:scale(0.95);} to{opacity:1; transform:scale(1);} }
</style>

<script>
function openMessageModal(id, name) {
    document.getElementById('msgEmployeeName').innerText = name;
    document.getElementById('messageForm').action = `/admin/employees/${id}/message`;
    document.getElementById('messageModal').style.display = 'flex';
}
function closeMessageModal() {
    document.getElementById('messageModal').style.display = 'none';
}
// Close on outside click
window.onclick = function(event) {
    const modal = document.getElementById('messageModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<style>
/* Global Theme Variables utilized instead of local :root */
body { background: var(--body-bg); font-family: 'Inter', sans-serif; }

.employee-dashboard-wrapper { margin-left: 250px; padding: 30px 40px; min-height: 100vh; }

/* HEADER */
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.header-title h1 { font-size: 28px; font-weight: 800; color: var(--text-primary); margin: 0; }
.header-title p { color: var(--text-muted); margin: 5px 0 0; }

.btn-glass {
    padding: 10px 20px; border-radius: 12px; font-weight: 600; text-decoration: none;
    display: flex; align-items: center; gap: 8px; transition: all 0.2s;
}
.btn-glass.primary { background: var(--primary); color: #fff; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
.btn-glass.primary:hover { transform: translateY(-2px); }

/* STATS */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
.stat-card {
    background: var(--surface); padding: 20px; border-radius: 16px; border: 1px solid var(--header-border);
    display: flex; align-items: center; gap: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
}
.icon-box { width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.icon-box.blue { background: rgba(59, 130, 246, 0.1); color: var(--primary); }
.icon-box.purple { background: rgba(147, 51, 234, 0.1); color: #9333ea; }
.icon-box.green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.stat-info h3 { margin: 0; font-size: 22px; font-weight: 800; color: var(--text-primary); }
.stat-info span { font-size: 12px; color: var(--text-muted); }

/* CONTENT */
.content-panel { background: var(--surface); border-radius: 20px; border: 1px solid var(--header-border); padding: 25px; }

.panel-toolbar { margin-bottom: 30px; }
.search-box { position: relative; max-width: 400px; }
.search-box i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
.search-box input {
    width: 100%; padding: 12px 15px 12px 40px; border-radius: 10px; border: 1px solid var(--header-border);
    background: var(--body-bg); color: var(--text-primary); font-size: 14px; outline: none; transition: all 0.2s;
}
.search-box input:focus { border-color: var(--primary); background: var(--surface); box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }

/* GRID */
.employee-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }

.employee-card {
    background: var(--surface); border: 1px solid var(--header-border); border-radius: 16px; position: relative;
    padding: 25px; display: flex; flex-direction: column; align-items: center; text-align: center;
    transition: all 0.2s;
}
.employee-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08); border-color: var(--primary); }

.card-menu { position: absolute; top: 15px; right: 15px; }
.menu-dots { background: none; border: none; font-size: 14px; color: var(--text-muted); cursor: pointer; }
.dropdown-menu {
    position: absolute; right: 0; top: 20px; background: var(--surface); border: 1px solid var(--header-border);
    border-radius: 8px; width: 120px; display: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); z-index: 10;
}
.card-menu:hover .dropdown-menu { display: block; }
.dropdown-menu a, .dropdown-menu button {
    display: block; width: 100%; padding: 8px 12px; text-align: left; background: none; border: none;
    font-size: 12px; color: var(--text-primary); cursor: pointer; text-decoration: none;
}
.dropdown-menu a:hover, .dropdown-menu button:hover { background: var(--body-bg); color: var(--primary); }

.card-profile { margin-bottom: 15px; }
.avatar-img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid var(--body-bg); }
.avatar-initials {
    width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #a5b4fc, #6366f1);
    color: #fff; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 700; border: 3px solid var(--body-bg);
}
.card-profile h4 { margin: 15px 0 5px; font-size: 16px; color: var(--text-primary); }
.card-profile .role { font-size: 12px; color: var(--primary); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; background: rgba(79, 70, 229, 0.1); padding: 4px 10px; border-radius: 20px; }

.card-details { width: 100%; margin-bottom: 20px; }
.detail-item {
    display: flex; align-items: center; gap: 10px; font-size: 13px; color: var(--text-muted);
    padding: 8px 0; border-bottom: 1px solid var(--header-border);
}
.detail-item:last-child { border-bottom: none; }
.detail-item i { width: 20px; text-align: center; color: var(--text-muted); }

.card-actions { display: flex; gap: 10px; justify-content: center; width: 100%; }
.btn-action {
    width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center;
    border: 1px solid var(--header-border); color: var(--text-muted); transition: all 0.2s; text-decoration: none;
}
.btn-action:hover { background: var(--primary); color: #fff; border-color: var(--primary); }

.pagination-wrapper { margin-top: 30px; display: flex; justify-content: center; }

.empty-state { grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted); }
.empty-state i { font-size: 48px; margin-bottom: 15px; opacity: 0.5; }

/* PAGINATION STYLES */
.pagination {
    display: flex;
    justify-content: center;
    padding-left: 0;
    list-style: none;
    gap: 5px;
}
.page-item .page-link {
    position: relative;
    display: block;
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: var(--primary);
    background-color: var(--surface);
    border: 1px solid var(--header-border);
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s;
    font-size: 14px;
    font-weight: 600;
}
.page-item .page-link:hover {
    background-color: var(--body-bg);
    color: var(--primary);
    border-color: var(--primary);
}
.page-item.active .page-link {
    z-index: 1;
    color: #fff;
    background-color: var(--primary);
    border-color: var(--primary);
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
}
.page-item.disabled .page-link {
    color: var(--secondary);
    pointer-events: none;
    cursor: auto;
    background-color: var(--body-bg);
    border-color: var(--header-border);
    opacity: 0.6;
}

@media (max-width: 1024px) {
    .employee-dashboard-wrapper { margin-left: 0; padding: 20px; }
}
</style>

<script>
function filterEmployees() {
    let input = document.getElementById('employeeSearch').value.toLowerCase();
    let cards = document.querySelectorAll('.employee-card');
    
    cards.forEach(card => {
        let text = card.textContent.toLowerCase();
        card.style.display = text.includes(input) ? 'flex' : 'none';
    });
}
</script>
