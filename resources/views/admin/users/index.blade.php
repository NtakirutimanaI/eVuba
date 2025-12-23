@include('layouts.header')
@include('layouts.sidebar')

<div class="user-dashboard-wrapper">
    
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-title">
            <h1><i class="fas fa-users-cog"></i> User Management</h1>
            <p>Manage access, roles, and profiles for all system users.</p>
        </div>
        <div class="header-actions">
            <button onclick="toggleReportPanel()" class="btn-glass secondary"><i class="fas fa-file-download"></i> Reports</button>
            <a href="{{ route('admin.users.create') }}" class="btn-glass primary"><i class="fas fa-user-plus"></i> Add User</a>
        </div>
    </div>

    <!-- Report Panel (Hidden by default) -->
    <div id="reportPanel" class="report-panel glass-panel" style="display:none;">
        <div class="rp-header">
            <h3>Generate User Report</h3>
            <button onclick="toggleReportPanel()" class="close-btn">&times;</button>
        </div>
        <div class="rp-body">
            <div class="form-group">
                <label>Date Range</label>
                <div class="date-inputs">
                    <input type="date" id="from_date" value="{{ request('from_date') }}" class="glass-input">
                    <span class="separator">to</span>
                    <input type="date" id="to_date" value="{{ request('to_date') }}" class="glass-input">
                </div>
            </div>
            <div class="rp-actions">
                <button onclick="generateReport('pdf')" class="btn-glass pdf"><i class="fas fa-file-pdf"></i> PDF Export</button>
                <button onclick="generateReport('excel')" class="btn-glass excel"><i class="fas fa-file-excel"></i> Excel Export</button>
            </div>
        </div>
    </div>

    <!-- Metrics Stats -->
    <div class="stats-grid">
        <div class="stat-card glass-panel">
            <div class="icon-box blue"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <h3>{{ $stats['total'] }}</h3>
                <span>Total Users</span>
            </div>
        </div>
        <div class="stat-card glass-panel">
            <div class="icon-box green"><i class="fas fa-user-check"></i></div>
            <div class="stat-info">
                <h3>{{ $stats['active'] }}</h3>
                <span>Active Accounts</span>
            </div>
        </div>
        <div class="stat-card glass-panel">
            <div class="icon-box purple"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <h3>{{ $stats['verified'] }}</h3>
                <span>Verified Email</span>
            </div>
        </div>
        <div class="stat-card glass-panel">
            <div class="icon-box orange"><i class="fas fa-user-clock"></i></div>
            <div class="stat-info">
                <h3>{{ $stats['new_this_month'] }}</h3>
                <span>New This Month</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-panel glass-panel">
        <div class="panel-toolbar">
            <div class="search-box">
                <form action="{{ route('admin.users.index') }}" method="GET">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Search by name, email, or role..." value="{{ request('search') }}">
                </form>
            </div>
            <div class="filter-box">
                <!-- Add role filters here if needed later -->
            </div>
        </div>

        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="25%">User Profile</th>
                        <th width="20%">Role & Status</th>
                        <th width="20%">Email Verification</th>
                        <th width="15%">Joined Date</th>
                        <th width="15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td><span class="id-badge">#{{ $user->id }}</span></td>
                        <td>
                            <div class="user-profile">
                                <div class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <div class="info">
                                    <h4>{{ $user->name }}</h4>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="role-status">
                                <span class="role-badge">{{ ucfirst($user->role ?? 'N/A') }}</span>
                                @if($user->is_active)
                                    <span class="status-dot active" title="Active"></span>
                                @else
                                    <span class="status-dot inactive" title="Inactive"></span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge verified"><i class="fas fa-check"></i> Verified</span>
                            @else
                                <span class="badge pending"><i class="fas fa-clock"></i> Pending</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-icon edit" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                                    @csrf @method('DELETE')
                                    <button class="btn-icon delete" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-users-slash"></i>
                                <p>No users match your search criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $users->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<style>
.user-dashboard-wrapper {
    margin-left: 250px;
    padding: 30px 40px;
    min-height: 100vh;
    background: var(--body-bg);
}

/* HEADER */
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.header-title h1 { font-size: 28px; font-weight: 800; color: var(--text); margin: 0; }
.header-title p { color: var(--secondary); margin: 5px 0 0; }

.header-actions { display: flex; gap: 15px; }
.btn-glass {
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid rgba(255,255,255,0.2);
    display: flex; align-items: center; gap: 8px;
    transition: all 0.2s;
    text-decoration: none;
    font-size: 14px;
}
.btn-glass.primary { background: var(--primary); color: #fff; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
.btn-glass.primary:hover { transform: translateY(-2px); }
.btn-glass.secondary { background: var(--surface); color: var(--text-primary); border: 1px solid var(--header-border); }
.btn-glass.pdf { background: #ef4444; color: #fff; }
.btn-glass.excel { background: #10b981; color: #fff; }

/* REPORT PANEL */
.report-panel {
    background: var(--surface); border-radius: 16px; padding: 25px; margin-bottom: 30px;
    border: 1px solid var(--header-border); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
    animation: slideDown 0.3s ease;
}
@keyframes slideDown { from{opacity:0; transform:translateY(-10px);} to{opacity:1; transform:translateY(0);} }
.rp-header { display: flex; justify-content: space-between; margin-bottom: 20px; }
.rp-header h3 { margin: 0; font-size: 16px; }
.date-inputs { display: flex; gap: 15px; align-items: center; margin-bottom: 20px; }
.glass-input { padding: 10px; border: 1px solid var(--border); border-radius: 8px; outline: none; }
.rp-actions { display: flex; gap: 10px; }

/* STATS GRID */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
.stat-card {
    background: var(--surface); padding: 20px; border-radius: 16px; border: 1px solid var(--header-border);
    display: flex; align-items: center; gap: 20px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
}
.icon-box {
    width: 50px; height: 50px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px;
}
.icon-box.blue { background: #e0e7ff; color: var(--primary); }
.icon-box.green { background: #dcfce7; color: var(--success); }
.icon-box.purple { background: #f3e8ff; color: #9333ea; }
.icon-box.orange { background: #ffedd5; color: var(--warning); }
.stat-info h3 { margin: 0; font-size: 24px; font-weight: 800; color: var(--text); }
.stat-info span { font-size: 13px; color: var(--secondary); font-weight: 500; }

/* CONTENT PANEL */
.content-panel { background: var(--surface); border-radius: 20px; border: 1px solid var(--header-border); overflow: hidden; }
.panel-toolbar { padding: 20px; border-bottom: 1px solid var(--header-border); }
.search-box form { position: relative; max-width: 350px; }
.search-box i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--secondary); }
.search-box input {
    width: 100%; padding: 12px 15px 12px 40px; border-radius: 10px; border: 1px solid var(--header-border);
    background: var(--body-bg); transition: all 0.2s; outline: none; color: var(--text-primary);
}
.search-box input:focus { background: var(--surface); border-color: var(--primary); box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }

/* TABLE */
.modern-table { width: 100%; border-collapse: separate; border-spacing: 0; }
.modern-table th {
    padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 700;
    color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;
    background: var(--body-bg); border-bottom: 1px solid var(--header-border);
}
.modern-table td { padding: 20px; border-bottom: 1px solid var(--header-border); vertical-align: middle; color: var(--text-primary); }
.modern-table tr:last-child td { border-bottom: none; }
.modern-table tr:hover { background: var(--header-bg); }

.id-badge { font-weight: 700; color: var(--secondary); font-family: monospace; }
.user-profile { display: flex; align-items: center; gap: 15px; }
.avatar {
    width: 40px; height: 40px; border-radius: 50%; background: #e2e8f0; color: #64748b;
    display: flex; align-items: center; justify-content: center; font-weight: 700;
}
.info h4 { margin: 0; font-size: 14px; text-transform: capitalize; }
.info span { font-size: 12px; color: var(--secondary); }

.role-badge { 
    padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; 
    background: #f1f5f9; color: var(--text); text-transform: uppercase;
    display: inline-block; margin-right: 8px;
}
.status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
.status-dot.active { background: var(--success); box-shadow: 0 0 10px rgba(16, 185, 129, 0.4); }
.status-dot.inactive { background: var(--secondary); }

.badge { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; }
.badge.verified { background: #ecfdf5; color: #047857; }
.badge.pending { background: #fffbeb; color: #b45309; }

.action-buttons { display: flex; gap: 5px; }
.btn-icon {
    width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center;
    border: none; cursor: pointer; color: var(--secondary); background: transparent; transition: all 0.2s;
    text-decoration: none;
}
.btn-icon:hover { background: #f1f5f9; color: var(--primary); }
.btn-icon.edit:hover { color: var(--primary); }
.btn-icon.delete:hover { color: var(--danger); }

.pagination-wrapper { padding: 20px; display: flex; justify-content: center; }

@media (max-width: 1024px) {
    .user-dashboard-wrapper { margin-left: 0; padding: 20px; }
    .page-header { flex-direction: column; align-items: flex-start; gap: 15px; }
    .modern-table { display: block; overflow-x: auto; }
}

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
</style>

<script>
    function toggleReportPanel() {
        const p = document.getElementById('reportPanel');
        p.style.display = p.style.display === 'none' ? 'block' : 'none';
    }

    function generateReport(type) {
        const from = document.getElementById('from_date').value;
        const to = document.getElementById('to_date').value;
        if(!from || !to) { alert('Please select a date range.'); return; }
        
        let url = type === 'pdf' ? "{{ route('admin.users.report.pdf') }}" : "{{ route('admin.users.report.excel') }}";
        window.location.href = `${url}?from_date=${from}&to_date=${to}`;
    }
</script>
