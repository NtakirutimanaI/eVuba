@include('layouts.header')
@include('layouts.sidebar')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-wrapper main-content">

    <!-- Flash Alerts -->
    <div id="alertContainer"></div>
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => showAlert('success', '{{ session('success') }}'));</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => showAlert('error', '{{ session('error') }}'));</script>
    @endif

    <div class="appt-container">

        <!-- Page Header -->
        <div class="page-title-section">
            <div class="title-left">
                <h2><i class="fas fa-calendar-alt"></i> Appointment Management</h2>
                <p class="subtitle">Manage internal tasks and customer appointment requests separately</p>
            </div>
            <div class="title-right">
                <button class="btn-create-task" onclick="openCreateTaskModal()">
                    <i class="fas fa-plus-circle"></i> New Task
                </button>
            </div>
        </div>

        <!-- ===================== TAB NAV ===================== -->
        <div class="tab-nav">
            <button class="tab-btn active" id="tab-tasks-btn" onclick="switchTab('tasks')">
                <i class="fas fa-tasks"></i>
                Internal Tasks
                <span class="tab-count">{{ $taskStats['total'] }}</span>
            </button>
            <button class="tab-btn" id="tab-requests-btn" onclick="switchTab('requests')">
                <i class="fas fa-calendar-check"></i>
                Customer Appointment
                @if($requestStats['pending'] > 0)
                    <span class="tab-count urgent">{{ $requestStats['pending'] }} new</span>
                @else
                    <span class="tab-count">{{ $requestStats['total'] }}</span>
                @endif
            </button>
        </div>

        <!-- ==================== TAB 1: TASKS ==================== -->
        <div id="tab-tasks" class="tab-panel">

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="icon-bg gray"><i class="fas fa-clipboard-list"></i></div>
                    <div class="stat-info">
                        <h4>Total Tasks</h4><span>{{ $taskStats['total'] }}</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-bg orange"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <h4>Pending</h4><span>{{ $taskStats['pending'] }}</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-bg blue"><i class="fas fa-spinner"></i></div>
                    <div class="stat-info">
                        <h4>In Progress</h4><span>{{ $taskStats['confirmed'] }}</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-bg green"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <h4>Completed</h4><span>{{ $taskStats['completed'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Toolbar -->
            <div class="toolbar-section">
                <div class="toolbar-left">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="taskSearch" placeholder="Search tasks...">
                    </div>
                    <select id="statusFilter" onchange="filterTasks()">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <select id="employeeFilter" onchange="filterTasks()">
                        <option value="">All Employees</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tasks Table -->
            <div class="glass-card table-container">
                <table class="tasks-table" id="tasksTable">
                    <thead>
                        <tr>
                            <th width="4%">#</th>
                            <th width="22%">Task Title</th>
                            <th width="15%">Assigned To</th>
                            <th width="12%">Customer</th>
                            <th width="13%">Due Date</th>
                            <th width="10%">Priority</th>
                            <th width="10%">Status</th>
                            <th width="7%">Progress</th>
                            <th width="7%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $index => $task)
                            <tr data-status="{{ $task->status }}" data-employee="{{ $task->employee_id ?? '' }}">
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <div class="task-title-cell">
                                        <strong>{{ $task->title }}</strong>
                                        @if($task->description)
                                            <p class="task-desc">{{ Str::limit($task->description, 55) }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="employee-cell">
                                        @if($task->employee_id)
                                            <div class="employee-avatar">{{ substr($task->employee->name ?? 'U', 0, 1) }}</div>
                                            <span>{{ $task->employee->name ?? 'Unassigned' }}</span>
                                        @else
                                            <button class="btn-assign" onclick="openAssignModal({{ $task->id }})">
                                                <i class="fas fa-user-plus"></i> Assign
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td><span class="customer-name">{{ $task->user->name ?? '—' }}</span></td>
                                <td>
                                    <div class="date-cell">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ \Carbon\Carbon::parse($task->scheduled_at)->format('M d, Y') }}
                                        <small>{{ \Carbon\Carbon::parse($task->scheduled_at)->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <select class="priority-select" onchange="updatePriority({{ $task->id }}, this.value)">
                                        <option value="low" {{ ($task->priority ?? 'medium') == 'low' ? 'selected' : '' }}>Low
                                        </option>
                                        <option value="medium" {{ ($task->priority ?? 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ ($task->priority ?? 'medium') == 'high' ? 'selected' : '' }}>
                                            High</option>
                                        <option value="urgent" {{ ($task->priority ?? 'medium') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $task->status }}">
                                        {{ $task->status == 'confirmed' ? 'In Progress' : ucfirst($task->status) }}
                                    </span>
                                </td>
                                <td>
                                    @php $progress = $task->status == 'completed' ? 100 : ($task->status == 'confirmed' ? 50 : 0); @endphp
                                    <div class="progress-cell">
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width:{{ $progress }}%"></div>
                                        </div>
                                        <span class="progress-text">{{ $progress }}%</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action view" onclick="viewTask({{ $task->id }})" title="View"><i
                                                class="fas fa-eye"></i></button>
                                        <a href="{{ route('admin.appointments.edit', $task->id) }}" class="btn-action edit"
                                            title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.appointments.destroy', $task->id) }}" method="POST"
                                            style="display:inline;" onsubmit="return confirm('Delete this task?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-action delete" title="Delete"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-state">
                                <td colspan="9"><i class="fas fa-tasks"></i>
                                    <p>No internal tasks found. Create your first task!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrapper">{{ $tasks->links('pagination::bootstrap-4') }}</div>
        </div><!-- /tab-tasks -->


        <!-- ==================== TAB 2: CUSTOMER REQUESTS ==================== -->
        <div id="tab-requests" class="tab-panel hidden">

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="icon-bg indigo"><i class="fas fa-user-clock"></i></div>
                    <div class="stat-info">
                        <h4>Total Requests</h4><span>{{ $requestStats['total'] }}</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-bg orange"><i class="fas fa-hourglass-half"></i></div>
                    <div class="stat-info">
                        <h4>Awaiting Action</h4><span>{{ $requestStats['pending'] }}</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-bg blue"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-info">
                        <h4>Confirmed</h4><span>{{ $requestStats['confirmed'] }}</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-bg green"><i class="fas fa-check-double"></i></div>
                    <div class="stat-info">
                        <h4>Completed</h4><span>{{ $requestStats['completed'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Toolbar -->
            <div class="toolbar-section">
                <div class="toolbar-left">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="reqSearch" placeholder="Search requests...">
                    </div>
                    <select id="reqStatusFilter" onchange="filterRequests()">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <!-- Customer Requests Cards -->
            <div class="requests-grid" id="requestsGrid">
                @forelse($customerRequests as $req)
                    <div class="request-card" data-status="{{ $req->status }}">
                        <!-- Header -->
                        <div class="req-header">
                            <div class="req-customer">
                                <div class="req-avatar">{{ substr($req->user->name ?? 'C', 0, 1) }}</div>
                                <div>
                                    <strong>{{ $req->user->name ?? 'Unknown Customer' }}</strong>
                                    <small>{{ $req->user->email ?? '' }}</small>
                                </div>
                            </div>
                            <span class="status-badge status-{{ $req->status }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </div>

                        <!-- Body -->
                        <div class="req-body">
                            <h4 class="req-title"><i class="fas fa-calendar-alt"
                                    style="color:var(--primary);font-size:0.85rem;"></i> {{ $req->title }}</h4>
                            @if($req->description)
                                <p class="req-desc">{{ Str::limit($req->description, 120) }}</p>
                            @endif

                            <div class="req-meta">
                                <div class="req-meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ \Carbon\Carbon::parse($req->scheduled_at)->format('D, M d Y · h:i A') }}</span>
                                </div>
                                <div class="req-meta-item">
                                    <i class="fas fa-user-tie"></i>
                                    @if($req->employee_id)
                                        <span>{{ $req->employee->name ?? 'Assigned' }}</span>
                                    @else
                                        <span class="text-muted">No employee assigned</span>
                                    @endif
                                </div>
                                <div class="req-meta-item">
                                    <i class="fas fa-calendar-plus"></i>
                                    <span>Submitted {{ \Carbon\Carbon::parse($req->created_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="req-actions">
                            <button class="req-btn req-btn-view" onclick="viewTask({{ $req->id }})">
                                <i class="fas fa-eye"></i> View
                            </button>
                            @if($req->status === 'pending')
                                <form action="{{ route('admin.appointments.updateStatus', $req->id) }}" method="POST"
                                    style="display:inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="req-btn req-btn-confirm">
                                        <i class="fas fa-check"></i> Confirm
                                    </button>
                                </form>
                                <form action="{{ route('admin.appointments.updateStatus', $req->id) }}" method="POST"
                                    style="display:inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="req-btn req-btn-cancel"
                                        onclick="return confirm('Cancel this request?')">
                                        <i class="fas fa-times"></i> Decline
                                    </button>
                                </form>
                            @elseif($req->status === 'confirmed')
                                <form action="{{ route('admin.appointments.updateStatus', $req->id) }}" method="POST"
                                    style="display:inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="req-btn req-btn-complete">
                                        <i class="fas fa-check-double"></i> Mark Done
                                    </button>
                                </form>
                            @endif
                            @if(!$req->employee_id)
                                <button class="req-btn req-btn-assign" onclick="openAssignModal({{ $req->id }})">
                                    <i class="fas fa-user-plus"></i> Assign
                                </button>
                            @endif
                            <a href="{{ route('admin.appointments.edit', $req->id) }}" class="req-btn req-btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-requests">
                        <i class="fas fa-calendar-times"></i>
                        <p>No customer appointment requests yet.</p>
                        <small>Requests submitted by customers through their portal will appear here.</small>
                    </div>
                @endforelse
            </div>
            <div class="pagination-wrapper">{{ $customerRequests->links('pagination::bootstrap-4') }}</div>
        </div><!-- /tab-requests -->

    </div><!-- /appt-container -->
</div><!-- /page-wrapper -->


<!-- ==================== Modals ==================== -->

<!-- Create/Edit Task Modal -->
<div id="taskModal" class="modal-overlay hidden">
    <div class="modal-glass modal-large">
        <div class="modal-header">
            <h3><i class="fas fa-tasks"></i> <span id="modalTitle">Create New Task</span></h3>
            <button class="btn-close" onclick="closeTaskModal()">&times;</button>
        </div>
        <form id="taskForm">
            @csrf
            <input type="hidden" id="taskId" name="task_id">
            <div class="form-row">
                <div class="form-group flex-2">
                    <label>Task Title <span class="req">*</span></label>
                    <input type="text" name="title" id="taskTitle" required placeholder="Enter task title">
                </div>
                <div class="form-group">
                    <label>Priority <span class="req">*</span></label>
                    <select name="priority" id="taskPriority" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="taskDescription" rows="3" placeholder="Task description..."></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Assign To Employee</label>
                    <select name="employee_id" id="taskEmployee">
                        <option value="">Unassigned</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Customer (optional)</label>
                    <select name="user_id" id="taskCustomer">
                        <option value="">No Customer</option>
                        @foreach($customers ?? [] as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Due Date <span class="req">*</span></label>
                    <input type="date" name="scheduled_date" id="taskDate" required>
                </div>
                <div class="form-group">
                    <label>Due Time <span class="req">*</span></label>
                    <input type="time" name="scheduled_time" id="taskTime" required>
                </div>
                <div class="form-group">
                    <label>Status <span class="req">*</span></label>
                    <select name="status" id="taskStatus" required>
                        <option value="pending">Pending</option>
                        <option value="confirmed">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-primary-block">
                <i class="fas fa-save"></i> <span id="submitBtnText">Create Task</span>
            </button>
        </form>
    </div>
</div>

<!-- Assign Employee Modal -->
<div id="assignModal" class="modal-overlay hidden">
    <div class="modal-glass">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Assign Employee</h3>
            <button class="btn-close" onclick="closeAssignModal()">&times;</button>
        </div>
        <form id="assignForm">
            @csrf
            <input type="hidden" id="assignTaskId">
            <div class="form-group">
                <label>Select Employee <span class="req">*</span></label>
                <select id="assignEmployee" required>
                    <option value="">Choose employee...</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary-block">
                <i class="fas fa-check"></i> Assign
            </button>
        </form>
    </div>
</div>

<!-- View Task Detail Modal -->
<div id="viewModal" class="modal-overlay hidden">
    <div class="modal-glass modal-large">
        <div class="modal-header">
            <h3><i class="fas fa-info-circle"></i> Appointment Details</h3>
            <button class="btn-close" onclick="closeViewModal()">&times;</button>
        </div>
        <div id="taskDetailsContent"></div>
    </div>
</div>

<!-- Report Modal -->
<div id="reportModal" class="modal-overlay hidden">
    <div class="modal-glass">
        <div class="modal-header">
            <h3><i class="fas fa-file-alt"></i> Generate Report</h3>
            <button class="btn-close" onclick="closeReportModal()">&times;</button>
        </div>
        <form id="reportForm" action="{{ route('admin.appointments.generateReport') }}" method="GET" target="_blank">
            <div class="form-group">
                <label>Select Employee</label>
                <select name="employee_id">
                    <option value="">All Employees</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Start Date</label><input type="date" name="start_date"></div>
                <div class="form-group"><label>End Date</label><input type="date" name="end_date"></div>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="button" onclick="submitReport('pdf')" class="btn-primary-block"
                    style="flex:1;background:#ef4444;"><i class="fas fa-file-pdf"></i> PDF</button>
                <button type="button" onclick="submitReport('excel')" class="btn-primary-block"
                    style="flex:1;background:#10b981;"><i class="fas fa-file-excel"></i> Excel</button>
            </div>
        </form>
    </div>
</div>


<!-- ==================== STYLES ==================== -->
<style>
    body {
        background: var(--body-bg);
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        color: var(--text-primary);
    }

    .page-wrapper {
        width: 80% !important;
        margin-left: 222px !important;
        padding: 25px;
        min-height: 100vh;
        box-sizing: border-box;
    }

    .appt-container {
        max-width: 100%;
    }

    /* ---- Page Title ---- */
    .page-title-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .title-left h2 {
        margin: 0 0 4px;
        font-size: 22px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-primary);
    }

    .title-left h2 i {
        color: var(--primary);
    }

    .title-left .subtitle {
        margin: 0;
        color: var(--text-muted);
        font-size: 13px;
    }

    .btn-create-task {
        padding: 10px 22px;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .btn-create-task:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    /* ---- Tabs ---- */
    .tab-nav {
        display: flex;
        gap: 0;
        margin-bottom: 20px;
        border-bottom: 2px solid var(--header-border);
    }

    .tab-btn {
        padding: 12px 28px;
        border: none;
        background: transparent;
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        transition: all 0.2s;
    }

    .tab-btn:hover {
        color: var(--primary);
    }

    .tab-btn.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }

    .tab-count {
        background: var(--body-bg);
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 12px;
        border: 1px solid var(--header-border);
    }

    .tab-count.urgent {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.6;
        }
    }

    .tab-panel {
        display: block;
    }

    .tab-panel.hidden {
        display: none;
    }

    /* ---- Stats ---- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 10px;
        margin-bottom: 16px;
    }

    .stat-card {
        background: var(--surface);
        border-radius: 10px;
        padding: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px solid var(--header-border);
    }

    .icon-bg {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .icon-bg.gray {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
    }

    .icon-bg.orange {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .icon-bg.blue {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .icon-bg.green {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .icon-bg.indigo {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }

    .stat-info h4 {
        margin: 0 0 2px;
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-info span {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-primary);
    }

    /* ---- Toolbar ---- */
    .toolbar-section {
        background: var(--surface);
        border-radius: 10px;
        padding: 12px 18px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        border: 1px solid var(--header-border);
    }

    .toolbar-left {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 13px;
    }

    .search-box input {
        padding: 9px 10px 9px 34px;
        border: 1px solid var(--header-border);
        border-radius: 8px;
        width: 220px;
        font-size: 13px;
        background: var(--body-bg);
        color: var(--text-primary);
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
    }

    .toolbar-section select {
        padding: 9px 12px;
        border: 1px solid var(--header-border);
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        background: var(--body-bg);
        color: var(--text-primary);
    }

    /* ---- Tasks Table ---- */
    .glass-card {
        background: var(--surface);
        border: 1px solid var(--header-border);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        padding: 16px;
    }

    .table-container {
        overflow-x: auto;
    }

    .tasks-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 6px;
    }

    .tasks-table thead th {
        padding: 10px 12px;
        background: var(--body-bg);
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: 0.5px;
        text-align: left;
        border-radius: 6px;
    }

    .tasks-table tbody tr {
        background: var(--surface);
        transition: all 0.2s;
    }

    .tasks-table tbody tr:not(.empty-state):hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
    }

    .tasks-table td {
        padding: 13px 12px;
        border-top: 1px solid var(--body-bg);
        border-bottom: 1px solid var(--body-bg);
        vertical-align: middle;
        color: var(--text-primary);
    }

    .tasks-table td:first-child {
        border-left: 1px solid var(--body-bg);
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    .tasks-table td:last-child {
        border-right: 1px solid var(--body-bg);
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    .text-center {
        text-align: center;
    }

    .task-title-cell strong {
        font-size: 13px;
        display: block;
        color: var(--text-primary);
    }

    .task-desc {
        margin: 3px 0 0;
        font-size: 11px;
        color: var(--text-muted);
    }

    .employee-cell {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .employee-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
    }

    .customer-name {
        font-size: 12px;
    }

    .date-cell {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    .date-cell i {
        color: var(--primary);
        font-size: 11px;
    }

    .date-cell small {
        color: var(--text-muted);
        font-size: 10px;
    }

    .priority-select {
        padding: 5px 8px;
        border: 1px solid var(--header-border);
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        background: var(--body-bg);
        width: auto;
    }

    .btn-assign {
        padding: 5px 10px;
        background: var(--body-bg);
        color: var(--primary);
        border: 1px solid var(--header-border);
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-assign:hover {
        background: var(--primary);
        color: #fff;
    }

    .progress-cell {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .progress-bar {
        flex: 1;
        height: 5px;
        background: var(--body-bg);
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #10b981, #059669);
        transition: width 0.3s;
    }

    .progress-text {
        font-size: 10px;
        font-weight: 700;
        color: var(--text-muted);
    }

    .action-buttons {
        display: flex;
        gap: 4px;
    }

    .btn-action {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        border: 1px solid var(--header-border);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        background: var(--body-bg);
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-action.view {
        color: var(--primary);
    }

    .btn-action.view:hover {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    .btn-action.edit {
        color: #f59e0b;
    }

    .btn-action.edit:hover {
        background: #f59e0b;
        color: #fff;
        border-color: #f59e0b;
    }

    .btn-action.delete {
        color: #ef4444;
    }

    .btn-action.delete:hover {
        background: #ef4444;
        color: #fff;
        border-color: #ef4444;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }

    .empty-state i {
        font-size: 48px;
        opacity: 0.2;
        display: block;
        margin-bottom: 12px;
        color: var(--text-muted);
    }

    .empty-state p {
        margin: 0;
        font-size: 14px;
        color: var(--text-muted);
    }

    /* ---- Status Badges ---- */
    .status-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        border: 1px solid transparent;
    }

    .status-pending {
        background: #fff7ed;
        color: #c2410c;
        border-color: #ffedd5;
    }

    .status-confirmed {
        background: #eff6ff;
        color: #1e40af;
        border-color: #dbeafe;
    }

    .status-completed {
        background: #f0fdf4;
        color: #15803d;
        border-color: #dcfce7;
    }

    .status-cancelled {
        background: #fef2f2;
        color: #b91c1c;
        border-color: #fee2e2;
    }

    /* ---- Customer Request Cards ---- */
    .requests-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .request-card {
        background: var(--surface);
        border: 1px solid var(--header-border);
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.25s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .request-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    .req-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 18px 12px;
        border-bottom: 1px solid var(--header-border);
    }

    .req-customer {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .req-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
        flex-shrink: 0;
    }

    .req-customer strong {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .req-customer small {
        color: var(--text-muted);
        font-size: 11px;
    }

    .req-body {
        padding: 14px 18px;
    }

    .req-title {
        margin: 0 0 8px;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .req-desc {
        margin: 0 0 12px;
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.6;
    }

    .req-meta {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .req-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--text-muted);
    }

    .req-meta-item i {
        width: 14px;
        text-align: center;
        color: var(--primary);
        font-size: 11px;
    }

    .req-actions {
        padding: 12px 18px;
        border-top: 1px solid var(--header-border);
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        background: var(--body-bg);
    }

    .req-btn {
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .req-btn-view {
        background: var(--surface);
        color: var(--text-primary);
        border-color: var(--header-border);
    }

    .req-btn-view:hover {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    .req-btn-confirm {
        background: #dcfce7;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .req-btn-confirm:hover {
        background: #10b981;
        color: #fff;
    }

    .req-btn-cancel {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .req-btn-cancel:hover {
        background: #ef4444;
        color: #fff;
    }

    .req-btn-complete {
        background: #dbeafe;
        color: #1e40af;
        border-color: #bfdbfe;
    }

    .req-btn-complete:hover {
        background: #3b82f6;
        color: #fff;
    }

    .req-btn-assign {
        background: #fef9c3;
        color: #854d0e;
        border-color: #fde68a;
    }

    .req-btn-assign:hover {
        background: #f59e0b;
        color: #fff;
    }

    .req-btn-edit {
        background: var(--surface);
        color: #f59e0b;
        border-color: #fde68a;
    }

    .req-btn-edit:hover {
        background: #f59e0b;
        color: #fff;
    }

    .empty-requests {
        grid-column: 1/-1;
        text-align: center;
        padding: 60px 20px;
    }

    .empty-requests i {
        font-size: 56px;
        opacity: 0.15;
        display: block;
        margin-bottom: 16px;
        color: var(--text-muted);
    }

    .empty-requests p {
        font-size: 16px;
        color: var(--text-muted);
        font-weight: 600;
        margin: 0 0 6px;
    }

    .empty-requests small {
        color: var(--text-muted);
        font-size: 12px;
    }

    /* ---- Modals ---- */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
        padding: 20px;
    }

    .modal-glass {
        background: var(--surface);
        padding: 25px;
        border-radius: 16px;
        width: 500px;
        max-width: 90%;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        animation: zoomIn 0.2s ease;
        max-height: 90vh;
        overflow-y: auto;
        border: 1px solid var(--header-border);
    }

    .modal-large {
        width: 780px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--header-border);
    }

    .modal-header h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 22px;
        cursor: pointer;
        color: var(--text-muted);
    }

    .btn-close:hover {
        color: #ef4444;
    }

    .hidden {
        display: none !important;
    }

    @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* ---- Form ---- */
    .form-row {
        display: flex;
        gap: 14px;
    }

    .form-group {
        margin-bottom: 14px;
        flex: 1;
    }

    .form-group.flex-2 {
        flex: 2;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 5px;
    }

    .req {
        color: #ef4444;
    }

    input,
    select,
    textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 9px 12px;
        border: 1px solid var(--header-border);
        border-radius: 8px;
        background: var(--body-bg);
        font-size: 13px;
        color: var(--text-primary);
        outline: none;
        transition: all 0.2s;
        font-family: inherit;
    }

    input:focus,
    select:focus,
    textarea:focus {
        border-color: var(--primary);
        background: var(--surface);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    textarea {
        resize: vertical;
    }

    .btn-primary-block {
        width: 100%;
        padding: 12px;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        transition: background 0.2s;
    }

    .btn-primary-block:hover {
        background: var(--primary-dark);
    }

    /* ---- Alerts ---- */
    #alertContainer {
        position: fixed;
        top: 80px;
        right: 25px;
        z-index: 10000;
        max-width: 400px;
    }

    .glass-alert {
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        animation: slideInRight 0.3s ease;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .glass-alert.success {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #10b981;
        border-left: 4px solid #10b981;
    }

    .glass-alert.error {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border-left: 4px solid #ef4444;
    }

    .alert-content {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
    }

    .btn-close-alert {
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        color: inherit;
        opacity: 0.7;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* ---- Pagination ---- */
    .pagination-wrapper {
        margin-top: 16px;
        display: flex;
        justify-content: center;
    }

    .pagination-wrapper .page-link {
        padding: 7px 13px;
        border-radius: 8px;
        background: var(--surface);
        border: 1px solid var(--header-border);
        color: var(--text-primary);
        font-weight: 600;
        font-size: 12px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .pagination-wrapper .page-link:hover {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    .pagination-wrapper .page-item.active .page-link {
        background: var(--primary);
        color: #fff;
    }

    .pagination-wrapper .page-item.disabled .page-link {
        opacity: 0.5;
        pointer-events: none;
    }

    @media (max-width:768px) {
        .page-wrapper {
            width: 100% !important;
            margin-left: 0 !important;
            padding: 15px;
        }

        .requests-grid {
            grid-template-columns: 1fr;
        }

        .tab-btn {
            padding: 10px 16px;
            font-size: 13px;
        }
    }
</style>


<!-- ==================== JAVASCRIPT ==================== -->
<script>
    /* ---- Tab Switching ---- */
    function switchTab(tab) {
        const panels = document.querySelectorAll('.tab-panel');
        const btns = document.querySelectorAll('.tab-btn');
        panels.forEach(p => p.classList.add('hidden'));
        btns.forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.remove('hidden');
        document.getElementById('tab-' + tab + '-btn').classList.add('active');
        // persist tab in URL without reload
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.replaceState({}, '', url);
    }
    // Read tab from URL on load
    document.addEventListener('DOMContentLoaded', function () {
        const params = new URLSearchParams(window.location.search);
        const tab = params.get('tab');
        if (tab === 'requests') switchTab('requests');
    });

    /* ---- Alerts ---- */
    function showAlert(type, message) {
        const container = document.getElementById('alertContainer');
        const alert = document.createElement('div');
        alert.className = `glass-alert ${type}`;
        alert.innerHTML = `<div class="alert-content"><i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}</div>
            <button class="btn-close-alert" onclick="this.parentElement.remove()">&times;</button>`;
        container.appendChild(alert);
        setTimeout(() => alert.remove(), 5000);
    }

    /* ---- Task Modal ---- */
    function openCreateTaskModal() {
        document.getElementById('modalTitle').textContent = 'Create New Task';
        document.getElementById('submitBtnText').textContent = 'Create Task';
        document.getElementById('taskForm').reset();
        document.getElementById('taskId').value = '';
        document.getElementById('taskModal').classList.remove('hidden');
    }
    function closeTaskModal() { document.getElementById('taskModal').classList.add('hidden'); }

    document.getElementById('taskForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const taskId = document.getElementById('taskId').value;
        const formData = {
            title: document.getElementById('taskTitle').value,
            description: document.getElementById('taskDescription').value,
            employee_id: document.getElementById('taskEmployee').value || null,
            user_id: document.getElementById('taskCustomer').value || null,
            scheduled_at: document.getElementById('taskDate').value + ' ' + document.getElementById('taskTime').value,
            status: document.getElementById('taskStatus').value,
            priority: document.getElementById('taskPriority').value
        };
        const url = taskId ? `/admin/appointments/${taskId}` : '{{ route("admin.appointments.store") }}';
        const method = taskId ? 'PUT' : 'POST';

        fetch(url, { method, headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify(formData) })
            .then(r => r.json())
            .then(d => {
                if (d.success) { showAlert('success', taskId ? 'Task updated!' : 'Task created!'); closeTaskModal(); setTimeout(() => location.reload(), 900); }
                else showAlert('error', d.message || 'Error saving task');
            })
            .catch(() => showAlert('error', 'Failed to save task'));
    });

    /* ---- Assign Modal ---- */
    function openAssignModal(taskId) {
        document.getElementById('assignTaskId').value = taskId;
        document.getElementById('assignModal').classList.remove('hidden');
    }
    function closeAssignModal() { document.getElementById('assignModal').classList.add('hidden'); document.getElementById('assignForm').reset(); }

    document.getElementById('assignForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const taskId = document.getElementById('assignTaskId').value;
        const employeeId = document.getElementById('assignEmployee').value;
        fetch(`{{ url('admin/appointments') }}/${taskId}/assign`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ employee_id: employeeId }) })
            .then(r => r.json())
            .then(d => {
                if (d.success) { showAlert('success', 'Employee assigned!'); closeAssignModal(); setTimeout(() => location.reload(), 900); }
                else showAlert('error', d.message || 'Error assigning');
            })
            .catch(() => showAlert('error', 'Failed to assign employee'));
    });

    /* ---- View Task ---- */
    function viewTask(id) {
        fetch(`/admin/appointments/${id}/json`)
            .then(r => r.json())
            .then(task => {
                const customer = task.customer || task.user || {};
                const content = `
                <div style="padding:20px;">
                    <h3 style="margin:0 0 18px;color:var(--primary);">${task.title}</h3>
                    ${task.source_type === 'customer_request' ? `<div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:12px;color:#0369a1;"><i class="fas fa-user"></i> <strong>Customer Appointment Request</strong> — submitted by ${customer.name || 'Unknown'}</div>` : ''}
                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:18px;">
                        <div><strong style="font-size:11px;color:var(--text-muted);display:block;margin-bottom:4px;">STATUS</strong><span class="status-badge status-${task.status}">${task.status}</span></div>
                        <div><strong style="font-size:11px;color:var(--text-muted);display:block;margin-bottom:4px;">PRIORITY</strong><span style="text-transform:capitalize;font-weight:600;">${task.priority || 'Medium'}</span></div>
                        <div><strong style="font-size:11px;color:var(--text-muted);display:block;margin-bottom:4px;">ASSIGNED TO</strong><span>${task.employee ? task.employee.name : '<em style="color:var(--text-muted)">Unassigned</em>'}</span></div>
                        <div><strong style="font-size:11px;color:var(--text-muted);display:block;margin-bottom:4px;">SCHEDULED</strong><span>${new Date(task.scheduled_at).toLocaleString()}</span></div>
                    </div>
                    <div style="background:var(--body-bg);border-radius:10px;padding:14px;margin-bottom:14px;">
                        <strong style="font-size:11px;color:var(--text-muted);display:block;margin-bottom:10px;">CUSTOMER</strong>
                        <div style="font-weight:700;">${customer.name || '—'}</div>
                        <div style="font-size:12px;color:var(--text-muted);">${customer.email || ''}</div>
                    </div>
                    ${task.description ? `<div><strong style="font-size:11px;color:var(--text-muted);display:block;margin-bottom:6px;">DESCRIPTION</strong><p style="margin:0;line-height:1.7;font-size:13px;">${task.description}</p></div>` : ''}
                </div>`;
                document.getElementById('taskDetailsContent').innerHTML = content;
                document.getElementById('viewModal').classList.remove('hidden');
            })
            .catch(() => showAlert('error', 'Failed to load details'));
    }
    function closeViewModal() { document.getElementById('viewModal').classList.add('hidden'); }

    /* ---- Filter Tasks (Tab 1) ---- */
    function filterTasks() {
        const status = document.getElementById('statusFilter').value;
        const employee = document.getElementById('employeeFilter').value;
        document.querySelectorAll('#tasksTable tbody tr:not(.empty-state)').forEach(row => {
            const match = (!status || row.dataset.status === status) &&
                (!employee || row.dataset.employee === employee);
            row.style.display = match ? '' : 'none';
        });
    }
    document.getElementById('taskSearch').addEventListener('keyup', function () {
        const term = this.value.toLowerCase();
        document.querySelectorAll('#tasksTable tbody tr:not(.empty-state)').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
        });
    });

    /* ---- Filter Requests (Tab 2) ---- */
    function filterRequests() {
        const status = document.getElementById('reqStatusFilter').value;
        document.querySelectorAll('#requestsGrid .request-card').forEach(card => {
            card.style.display = (!status || card.dataset.status === status) ? '' : 'none';
        });
    }
    document.getElementById('reqSearch').addEventListener('keyup', function () {
        const term = this.value.toLowerCase();
        document.querySelectorAll('#requestsGrid .request-card').forEach(card => {
            card.style.display = card.innerText.toLowerCase().includes(term) ? '' : 'none';
        });
    });

    /* ---- Priority Update ---- */
    function updatePriority(taskId, priority) {
        fetch(`/admin/appointments/${taskId}/priority`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ priority }) })
            .then(r => r.json())
            .then(d => { if (d.success) showAlert('success', 'Priority updated!'); })
            .catch(() => { });
    }

    /* ---- Report Modal ---- */
    function openReportModal() { document.getElementById('reportModal').classList.remove('hidden'); }
    function closeReportModal() { document.getElementById('reportModal').classList.add('hidden'); }
    function submitReport(type) {
        const form = document.getElementById('reportForm');
        form.action = type === 'pdf' ? "{{ route('admin.appointments.generateReport') }}" : "{{ route('admin.appointments.exportExcel') }}";
        form.submit(); closeReportModal();
    }

    /* ---- Close on Backdrop ---- */
    window.addEventListener('click', function (e) {
        if (e.target.classList.contains('modal-overlay')) { closeTaskModal(); closeAssignModal(); closeViewModal(); closeReportModal(); }
    });
</script>