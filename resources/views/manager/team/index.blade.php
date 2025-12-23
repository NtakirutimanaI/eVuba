@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Team Intelligence</h1>
            <p style="color: var(--secondary); margin-top: 0.5rem;">Orchestrate and optimize your workforce performance</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('manager.team.create') }}" class="action-btn btn-primary">
                <i class="fas fa-user-plus"></i> Add Team Member
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="dashboard-grid">
        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Total Members</div>
                    <div class="value">{{ count($employees) }}</div>
                    <div class="stat-trend up">
                        <i class="fas fa-arrow-up"></i> Active Workforce
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Departments</div>
                    <div class="value">{{ count($employees->groupBy('department')) }}</div>
                    <div class="stat-trend">
                        Across Enterprise
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Primary Roles</div>
                    <div class="value">{{ count($employees->groupBy('position')) }}</div>
                    <div class="stat-trend">
                        Specialized Skills
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #f87171);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <div class="label">New Joins</div>
                    <div class="value">{{ $employees->where('created_at', '>=', now()->subMonth())->count() }}</div>
                    <div class="stat-trend">
                        This Month
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="glass-card" style="background: rgba(34, 197, 94, 0.1); border-color: rgba(34, 197, 94, 0.2); margin-bottom: 2rem; padding: 1rem;">
            <div style="color: var(--success); display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="pro-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 700;">Workforce Directory</h2>
            <div class="pro-search">
                <i class="fas fa-search"></i>
                <input type="text" id="teamMemberSearch" placeholder="Search by name, role, or department..." style="background: transparent; border: none; outline: none; padding: 0.5rem; width: 300px;">
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="pro-table" id="teamMembersTable">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Member Details</th>
                        <th>Professional Profile</th>
                        <th>Department</th>
                        <th>Joined Date</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $index => $employee)
                        <tr>
                            <td>
                                <span style="color: var(--secondary); font-weight: 600;">#{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--bg-main); display: flex; align-items: center; justify-content: center; font-weight: 800; color: var(--primary);">
                                        {{ strtoupper(substr($employee->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: var(--dark);">{{ $employee->name }}</div>
                                        <div style="font-size: 0.75rem; color: var(--secondary);">{{ $employee->email ?? 'no-email@company.com' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span class="status-badge status-processing">{{ $employee->position ?? 'Not Assigned' }}</span>
                                    <div style="font-size: 0.75rem; color: var(--secondary); margin-top: 0.25rem;">
                                        <i class="fas fa-laptop-code" style="font-size: 0.7rem;"></i> {{ $employee->specialization ?? 'General' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-building" style="color: var(--secondary); font-size: 0.8rem;"></i>
                                    {{ $employee->department ?? 'Corporate' }}
                                </div>
                            </td>
                            <td>
                                <div style="color: var(--dark); font-weight: 500;">{{ $employee->created_at->format('M d, Y') }}</div>
                                <div style="font-size: 0.75rem; color: var(--secondary);">{{ $employee->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <a href="{{ route('manager.team.edit', $employee->id) }}" class="action-btn" style="padding: 0.5rem; aspect-ratio: 1; min-width: 40px; justify-content: center; background: rgba(99, 102, 241, 0.1); color: var(--primary);">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('manager.team.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Archive this team member? This action cannot be undone.');" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn" style="padding: 0.5rem; aspect-ratio: 1; min-width: 40px; justify-content: center; background: rgba(239, 68, 68, 0.1); color: var(--danger); border: none; cursor: pointer;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div style="text-align: center; padding: 3rem; color: var(--secondary);">
                                    <i class="fas fa-users-slash" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem; display: block;"></i>
                                    <p>No team members found in current directory.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .pro-search {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: var(--bg-main);
        padding: 0.25rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid var(--glass-border);
        transition: all 0.2s;
    }

    .pro-search:focus-within {
        background: var(--white);
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        border-color: var(--primary);
    }

    .pro-search i {
        color: var(--secondary);
    }

    .pro-header h1 {
        margin: 0;
    }

    .action-btn i {
        font-size: 0.9rem;
    }

    .pro-table tbody tr {
        transition: transform 0.2s;
    }

    .pro-table tbody tr:hover {
        transform: scale(1.002);
    }

    .pro-table td {
        border-bottom: 1px solid var(--bg-main);
    }

    .pro-table tr:hover td {
        background: rgba(99, 102, 241, 0.02);
    }
</style>

<script>
document.getElementById('teamMemberSearch').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#teamMembersTable tbody tr');

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

</style>
