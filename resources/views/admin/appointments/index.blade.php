@include('layouts.header')
@include('layouts.sidebar')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-wrapper main-content">
    
    <!-- Success/Error Feedback -->
    <div id="alertContainer"></div>
    
    <div class="tasks-container">
        
        <!-- Page Header -->
        <div class="page-title-section">
            <div class="title-left">
                <h2><i class="fas fa-tasks"></i> Task Management</h2>
                <p class="subtitle">Manage, assign, and track all tasks and activities</p>
            </div>
            <div class="title-right">
                <button class="btn-create-task" onclick="openCreateTaskModal()">
                    <i class="fas fa-plus-circle"></i> Create New Task
                </button>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon-bg blue">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-info">
                    <h4>Total Tasks</h4>
                    <span>{{ $appointments->total() }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-bg orange">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h4>Pending</h4>
                    <span>{{ $appointments->where('status', 'pending')->count() }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-bg purple">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="stat-info">
                    <h4>In Progress</h4>
                    <span>{{ $appointments->where('status', 'confirmed')->count() }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-bg green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h4>Completed</h4>
                    <span>{{ $appointments->where('status', 'completed')->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Filters & Actions Toolbar -->
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
            <!-- Reports removed -->
        </div>

        <!-- Tasks Table -->
        <div class="glass-card table-container">
            <table class="tasks-table" id="tasksTable">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="20%">Task Title</th>
                        <th width="15%">Assigned To</th>
                        <th width="12%">Customer</th>
                        <th width="12%">Due Date</th>
                        <th width="10%">Priority</th>
                        <th width="10%">Status</th>
                        <th width="8%">Progress</th>
                        <th width="8%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $index => $task)
                    <tr data-status="{{ $task->status }}" data-employee="{{ $task->employee_id ?? '' }}">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <div class="task-title-cell">
                                <strong>
                                    {{ $task->title }}
                                    @if($task->auto_assigned)
                                        <span class="badge-auto" title="Auto-assigned from {{ $task->source_type }}">
                                            <i class="fas fa-robot"></i> Auto
                                        </span>
                                    @endif
                                </strong>
                                @if($task->description)
                                    <p class="task-desc">{{ Str::limit($task->description, 50) }}</p>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="employee-cell">
                                @if($task->employee_id)
                                    <div class="employee-avatar">
                                        {{ substr($task->employee->name ?? 'U', 0, 1) }}
                                    </div>
                                    <span>{{ $task->employee->name ?? 'Unassigned' }}</span>
                                @else
                                    <button class="btn-assign" onclick="openAssignModal({{ $task->id }})">
                                        <i class="fas fa-user-plus"></i> Assign
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="customer-name">{{ $task->user->name ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="date-cell">
                                <i class="fas fa-calendar-alt"></i>
                                {{ \Carbon\Carbon::parse($task->scheduled_at)->format('M d, Y') }}
                                <small>{{ \Carbon\Carbon::parse($task->scheduled_at)->format('h:i A') }}</small>
                            </div>
                        </td>
                        <td>
                            <select class="priority-select" onchange="updatePriority({{ $task->id }}, this.value)">
                                <option value="low" {{ ($task->priority ?? 'medium') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ ($task->priority ?? 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ ($task->priority ?? 'medium') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ ($task->priority ?? 'medium') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </td>
                        <td>
                            <select class="status-select status-{{ $task->status }}" onchange="updateStatus({{ $task->id }}, this.value)" style="border:none; background:transparent; font-weight:700; cursor:pointer; padding:5px; border-radius:12px;">
                                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }} style="color:black">Pending</option>
                                <option value="confirmed" {{ $task->status == 'confirmed' ? 'selected' : '' }} style="color:black">In Progress</option>
                                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }} style="color:black">Completed</option>
                                <option value="cancelled" {{ $task->status == 'cancelled' ? 'selected' : '' }} style="color:black">Cancelled</option>
                            </select>
                        </td>
                        <td>
                            <div class="progress-cell">
                                @php
                                    $progress = $task->status == 'completed' ? 100 : ($task->status == 'confirmed' ? 50 : 0);
                                @endphp
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $progress }}%"></div>
                                </div>
                                <span class="progress-text">{{ $progress }}%</span>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action view" onclick="viewTask({{ $task->id }})" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('admin.appointments.edit', $task->id) }}" class="btn-action edit" title="Edit Task">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn-action delete" onclick="deleteTask({{ $task->id }})" title="Delete Task">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-state">
                        <td colspan="9">
                            <i class="fas fa-tasks"></i>
                            <p>No tasks found. Create your first task to get started!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $appointments->links('pagination::bootstrap-4') }}
        </div>

    </div>
</div>

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
                <textarea name="description" id="taskDescription" rows="3" placeholder="Task description and details..."></textarea>
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
                    <label>Customer</label>
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
                <i class="fas fa-check"></i> Assign Task
            </button>
        </form>
    </div>
</div>

<!-- View Task Modal -->
<div id="viewModal" class="modal-overlay hidden">
    <div class="modal-glass modal-large">
        <div class="modal-header">
            <h3><i class="fas fa-info-circle"></i> Task Details</h3>
            <button class="btn-close" onclick="closeViewModal()">&times;</button>
        </div>
        
        <div id="taskDetailsContent">
            <!-- Task details will be loaded here -->
        </div>
    </div>
</div>

<!-- Report Generation Modal -->
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
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date">
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date">
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="submitReport('pdf')" class="btn-primary-block" style="flex:1; background: #ef4444;">
                     <i class="fas fa-file-pdf"></i> PDF
                </button>
                <button type="button" onclick="submitReport('excel')" class="btn-primary-block" style="flex:1; background: #10b981;">
                     <i class="fas fa-file-excel"></i> Excel
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Theme Variables */
/* Rely on global variables from app layout */

body { background: var(--body-bg); font-family: 'Inter', sans-serif; font-size: 13px; color: var(--text-primary); margin: 0; }
.page-wrapper { width: 80% !important; margin-left: 222px !important; padding: 25px; min-height: 100vh; box-sizing: border-box; transition: margin 0.3s; }

/* Alerts */
#alertContainer { position: fixed; top: 80px; right: 25px; z-index: 10000; max-width: 400px; }
.glass-alert {
    padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
    display: flex; justify-content: space-between; align-items: center;
    animation: slideInRight 0.3s ease; box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.glass-alert.success { background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; border-left: 4px solid #10b981; }
.glass-alert.error { background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; border-left: 4px solid #ef4444; }
.alert-content { display: flex; align-items: center; gap: 10px; font-weight: 600; }
.btn-close-alert { background: none; border: none; font-size: 18px; cursor: pointer; color: inherit; opacity: 0.7; }
.btn-close-alert:hover { opacity: 1; }

@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

/* Page Title */
.page-title-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.title-left h2 { margin: 0 0 5px 0; font-size: 24px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 10px; }
.title-left h2 i { color: var(--primary); }
.title-left .subtitle { margin: 0; color: var(--text-muted); font-size: 14px; }

.btn-create-task {
    padding: 12px 24px; background: var(--primary); color: white; border: none; border-radius: 10px;
    font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px;
    transition: all 0.2s; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}
.btn-create-task:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4); }

/* Stats Grid Compact */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin-bottom: 15px; }
.stat-card { background: var(--surface); border-radius: 10px; padding: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 12px; border: 1px solid var(--header-border); transition: all 0.2s; }
.icon-bg { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; color: white; }
.icon-bg.blue { background: rgba(59, 130, 246, 0.1); color: var(--primary); }
.icon-bg.orange { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.icon-bg.purple { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
.icon-bg.green { background: rgba(16, 185, 129, 0.1); color: #10b981; }

.stat-info h4 { margin: 0 0 2px 0; font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
.stat-info span { font-size: 20px; font-weight: 800; color: var(--text-primary); }

/* Toolbar */
.toolbar-section { background: var(--surface); border-radius: 12px; padding: 15px 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05); flex-wrap: wrap; gap: 15px; border: 1px solid var(--header-border); }
.toolbar-left { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
.toolbar-right { display: flex; gap: 10px; }

.search-box { position: relative; }
.search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 14px; }
.search-box input { padding: 10px 12px 10px 38px; border: 1px solid var(--header-border); border-radius: 8px; width: 250px; font-size: 13px; background: var(--body-bg); color: var(--text-primary); }
.search-box input:focus { outline: none; border-color: var(--primary); background: var(--surface); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }

.toolbar-section select { padding: 10px 12px; border: 1px solid var(--header-border); border-radius: 8px; font-size: 13px; cursor: pointer; background: var(--body-bg); color: var(--text-primary); }
.toolbar-section select:focus { outline: none; border-color: var(--primary); background: var(--surface); }

.btn-export {
    padding: 10px 18px; background: #ef4444; color: white; border: none; border-radius: 8px;
    font-weight: 600; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 6px;
    transition: all 0.2s;
}
.btn-export:hover { background: #dc2626; transform: translateY(-1px); }
.btn-export.excel { background: #10b981; }
.btn-export.excel:hover { background: #059669; }

/* Table Container */
.glass-card { background: var(--surface); border: 1px solid var(--header-border); border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 20px; }
.table-container { overflow-x: auto; }

/* Tasks Table */
.tasks-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
.tasks-table thead th {
    text-align: left; padding: 12px; background: var(--body-bg); color: var(--text-muted);
    font-weight: 600; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;
    border-radius: 8px;
}
.tasks-table tbody tr { background: var(--surface); transition: all 0.2s; }
.tasks-table tbody tr:not(.empty-state):hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); transform: translateY(-1px); }
.tasks-table td { padding: 14px 12px; border-top: 1px solid var(--body-bg); border-bottom: 1px solid var(--body-bg); vertical-align: middle; color: var(--text-primary); }
.tasks-table td:first-child { border-left: 1px solid var(--body-bg); border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
.tasks-table td:last-child { border-right: 1px solid var(--body-bg); border-top-right-radius: 8px; border-bottom-right-radius: 8px; }

.text-center { text-align: center; }

.task-title-cell strong { font-size: 14px; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
.task-desc { margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted); }
.badge-auto {
    background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); color: white;
    font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 600;
    text-transform: uppercase; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 3px;
}

.employee-cell { display: flex; align-items: center; gap: 8px; }
.employee-avatar {
    width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;
}

.btn-assign {
    padding: 6px 12px; background: var(--body-bg); color: var(--primary); border: 1px solid var(--header-border); border-radius: 6px;
    font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 5px;
    transition: all 0.2s;
}
.btn-assign:hover { background: var(--primary); color: white; border-color: var(--primary); }

.customer-name { font-size: 13px; color: var(--text-primary); }

.date-cell { display: flex; flex-direction: column; gap: 2px; }
.date-cell i { color: var(--primary); font-size: 12px; }
.date-cell small { color: var(--text-muted); font-size: 11px; }

.priority-select {
    padding: 6px 10px; border: 1px solid var(--header-border); border-radius: 6px; font-size: 12px;
    font-weight: 600; cursor: pointer; background: var(--body-bg);
}
.priority-select option[value="low"] { color: #10b981; }
.priority-select option[value="medium"] { color: #f59e0b; }
.priority-select option[value="high"] { color: #f97316; }
.priority-select option[value="urgent"] { color: #ef4444; }

.status-badge {
    padding: 5px 12px; border-radius: 12px; font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.5px; display: inline-block;
}
.status-pending { background: rgba(245, 158, 11, 0.1); color: #b45309; }
.status-confirmed { background: rgba(59, 130, 246, 0.1); color: #1e3a8a; }
.status-completed { background: rgba(16, 185, 129, 0.1); color: #065f46; }
.status-cancelled { background: rgba(239, 68, 68, 0.1); color: #991b1b; }

/* Dark mode adjustments for badges text */
@media (prefers-color-scheme: dark) {
    .status-pending { color: #fbbf24; }
    .status-confirmed { color: #60a5fa; }
    .status-completed { color: #34d399; }
    .status-cancelled { color: #f87171; }
}

.progress-cell { display: flex; align-items: center; gap: 8px; }
.progress-bar { flex: 1; height: 6px; background: var(--body-bg); border-radius: 3px; overflow: hidden; }
.progress-fill { height: 100%; background: linear-gradient(90deg, #10b981 0%, #059669 100%); transition: width 0.3s; }
.progress-text { font-size: 11px; font-weight: 600; color: var(--text-muted); }

.action-buttons { display: flex; gap: 5px; }
.btn-action {
    width: 32px; height: 32px; border-radius: 6px; border: 1px solid var(--header-border); cursor: pointer;
    display: flex; align-items: center; justify-content: center; transition: all 0.2s; font-size: 13px; background: var(--body-bg);
}
.btn-action.view { color: var(--primary); }
.btn-action.view:hover { background: var(--primary); color: white; border-color: var(--primary); }
.btn-action.edit { color: #f59e0b; }
.btn-action.edit:hover { background: #f59e0b; color: white; border-color: #f59e0b; }
.btn-action.delete { color: #ef4444; }
.btn-action.delete:hover { background: #ef4444; color: white; border-color: #ef4444; }

.empty-state { text-align: center; padding: 60px 20px; }
.empty-state i { font-size: 64px; opacity: 0.2; display: block; margin-bottom: 15px; color: var(--text-muted); }
.empty-state p { margin: 0; font-size: 14px; color: var(--text-muted); }

/* Modal */
.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(5px); padding: 20px; }
.modal-glass { background: var(--surface); padding: 25px; border-radius: 16px; width: 500px; max-width: 90%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: zoomIn 0.2s ease; max-height: 90vh; overflow-y: auto; border: 1px solid var(--header-border); }
.modal-large { width: 800px; }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid var(--header-border); }
.modal-header h3 { margin: 0; font-size: 18px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
.btn-close { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-muted); transition: color 0.2s; }
.btn-close:hover { color: #ef4444; }
.hidden { display: none !important; }

@keyframes zoomIn { from{opacity:0; transform:scale(0.95);} to{opacity:1; transform:scale(1);} }

/* Form Elements */
.form-row { display: flex; gap: 15px; }
.form-group { margin-bottom: 15px; flex: 1; }
.form-group.flex-2 { flex: 2; }
.form-group label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; }
.req { color: #ef4444; }

input, select, textarea {
    width: 100%; box-sizing: border-box; padding: 10px 12px;
    border: 1px solid var(--header-border); border-radius: 8px; background: var(--body-bg);
    font-size: 13px; color: var(--text-primary); outline: none; transition: all 0.2s; font-family: inherit;
}
input:focus, select:focus, textarea:focus { border-color: var(--primary); background: var(--surface); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
textarea { resize: vertical; }

.btn-primary-block { width: 100%; padding: 12px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: background 0.2s; display: flex; justify-content: center; align-items: center; gap: 8px; font-size: 14px; }
.btn-primary-block:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3); }

/* Pagination */
.pagination-wrapper { margin-top: 20px; display: flex; justify-content: center; }
.pagination-wrapper nav { display: flex; gap: 5px; }
.pagination-wrapper .pagination { display: flex; gap: 5px; list-style: none; padding: 0; margin: 0; }
.pagination-wrapper .page-link {
    padding: 8px 14px; border-radius: 8px; background: var(--surface); border: 1px solid var(--header-border);
    color: var(--text-primary); font-weight: 600; font-size: 13px; text-decoration: none;
    transition: all 0.2s; display: flex; align-items: center; justify-content: center; min-width: 38px;
}
.pagination-wrapper .page-link:hover { background: var(--primary); color: white; border-color: var(--primary); transform: translateY(-2px); box-shadow: 0 4px 8px rgba(79, 70, 229, 0.2); }
.pagination-wrapper .page-item.active .page-link { background: var(--primary); color: white; border-color: var(--primary); }
.pagination-wrapper .page-item.disabled .page-link { opacity: 0.5; cursor: not-allowed; pointer-events: none; }

/* Responsive */
@media (max-width: 768px) {
    .page-wrapper { width: 100%; margin-left: 0; padding: 15px; }
    .page-title-section { flex-direction: column; align-items: flex-start; gap: 15px; }
    .toolbar-section { flex-direction: column; align-items: stretch; }
    .toolbar-left, .toolbar-right { width: 100%; flex-direction: column; }
    .search-box input { width: 100%; }
}
</style>
</style>

<script>
// Alert Helper
function showAlert(type, message) {
    const container = document.getElementById('alertContainer');
    const alert = document.createElement('div');
    alert.className = `glass-alert ${type}`;
    alert.innerHTML = `
        <div class="alert-content"><i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}</div>
        <button class="btn-close-alert" onclick="this.parentElement.remove()">&times;</button>
    `;
    container.appendChild(alert);
    setTimeout(() => alert.remove(), 5000);
}

// Create Task Modal
function openCreateTaskModal() {
    document.getElementById('modalTitle').textContent = 'Create New Task';
    document.getElementById('submitBtnText').textContent = 'Create Task';
    document.getElementById('taskForm').reset();
    document.getElementById('taskId').value = '';
    document.getElementById('taskModal').classList.remove('hidden');
}

function closeTaskModal() {
    document.getElementById('taskModal').classList.add('hidden');
}

// Task Form Submit
document.getElementById('taskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const taskId = document.getElementById('taskId').value;
    
    const formData = {
        title: form.title.value,
        description: form.description.value,
        employee_id: form.employee_id.value || null,
        user_id: form.user_id.value || null,
        scheduled_at: form.scheduled_date.value + ' ' + form.scheduled_time.value,
        status: form.status.value,
        priority: form.priority.value
    };
    
    const url = taskId ? `/admin/appointments/${taskId}` : '{{ route("admin.appointments.store") }}';
    const method = taskId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            showAlert('success', taskId ? 'Task updated successfully!' : 'Task created successfully!');
            closeTaskModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('error', d.message || 'Error saving task');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showAlert('error', 'Failed to save task');
    });
});



// Delete Task
function deleteTask(id) {
    if (!confirm('Are you sure you want to delete this task?')) return;
    
    fetch(`/admin/appointments/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(() => {
        showAlert('success', 'Task deleted successfully!');
        setTimeout(() => location.reload(), 1000);
    })
    .catch(err => {
        console.error('Error:', err);
        showAlert('error', 'Failed to delete task');
    });
}

// Assign Employee Modal
function openAssignModal(taskId) {
    document.getElementById('assignTaskId').value = taskId;
    document.getElementById('assignModal').classList.remove('hidden');
}

function closeAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
    document.getElementById('assignForm').reset();
}

document.getElementById('assignForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const taskId = document.getElementById('assignTaskId').value;
    const employeeId = document.getElementById('assignEmployee').value;
    
    fetch(`{{ url('admin/appointments') }}/${taskId}/assign`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ employee_id: employeeId })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            showAlert('success', 'Employee assigned successfully!');
            closeAssignModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('error', d.message || 'Error assigning employee');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showAlert('error', 'Failed to assign employee');
    });
});

// View Task
function viewTask(id) {
    fetch(`/admin/appointments/${id}/json`)
        .then(r => r.json())
        .then(task => {
            const content = `
                <div style="padding: 20px;">
                    <h3 style="margin: 0 0 20px 0; color: var(--primary);">${task.title}</h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px;">
                        <div>
                            <strong style="color: var(--text-light); font-size: 12px;">Status:</strong>
                            <p style="margin: 5px 0;"><span class="status-badge status-${task.status}">${task.status}</span></p>
                        </div>
                        <div>
                            <strong style="color: var(--text-light); font-size: 12px;">Priority:</strong>
                            <p style="margin: 5px 0; text-transform: capitalize;">${task.priority || 'Medium'}</p>
                        </div>
                        <div>
                            <strong style="color: var(--text-light); font-size: 12px;">Assigned To:</strong>
                            <p style="margin: 5px 0;">${task.employee ? task.employee.name : 'Unassigned'}</p>
                        </div>
                        <div>
                            <strong style="color: var(--text-light); font-size: 12px;">Customer:</strong>
                            <p style="margin: 5px 0;">${task.user ? task.user.name : 'No Customer'}</p>
                        </div>
                        <div>
                            <strong style="color: var(--text-light); font-size: 12px;">Due Date:</strong>
                            <p style="margin: 5px 0;">${new Date(task.scheduled_at).toLocaleString()}</p>
                        </div>
                    </div>
                    ${task.description ? `
                        <div>
                            <strong style="color: var(--text-light); font-size: 12px;">Description:</strong>
                            <p style="margin: 5px 0; line-height: 1.6;">${task.description}</p>
                        </div>
                    ` : ''}
                </div>
            `;
            document.getElementById('taskDetailsContent').innerHTML = content;
            document.getElementById('viewModal').classList.remove('hidden');
        })
        .catch(err => {
            console.error('Error:', err);
            showAlert('error', 'Failed to load task details');
        });
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
}

// Filter Tasks
function filterTasks() {
    const status = document.getElementById('statusFilter').value;
    const employee = document.getElementById('employeeFilter').value;
    
    document.querySelectorAll('#tasksTable tbody tr:not(.empty-state)').forEach(row => {
        const rowStatus = row.dataset.status;
        const rowEmployee = row.dataset.employee;
        
        const statusMatch = !status || rowStatus === status;
        const employeeMatch = !employee || rowEmployee === employee;
        
        row.style.display = (statusMatch && employeeMatch) ? '' : 'none';
    });
}

// Search Tasks
document.getElementById('taskSearch').addEventListener('keyup', function(e) {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('#tasksTable tbody tr:not(.empty-state)').forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
});

// Update Priority
function updatePriority(taskId, priority) {
    fetch(`/admin/appointments/${taskId}/priority`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ priority: priority })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            showAlert('success', 'Priority updated!');
        }
    })
    .catch(err => console.error('Error:', err));
}

// Report Modal
function openReportModal() {
    document.getElementById('reportModal').classList.remove('hidden');
}

function closeReportModal() {
    document.getElementById('reportModal').classList.add('hidden');
}

// Edit Task Function
function editTask(id) {
    fetch(`/admin/appointments/${id}`)
        .then(r => r.json())
        .then(task => {
            document.getElementById('modalTitle').textContent = 'Edit Task';
            document.getElementById('submitBtnText').textContent = 'Update Task';
            document.getElementById('taskId').value = task.id;
            document.getElementById('taskTitle').value = task.title;
            document.getElementById('taskDescription').value = task.description || '';
            document.getElementById('taskEmployee').value = task.employee_id || '';
            document.getElementById('taskCustomer').value = task.user_id || '';
            document.getElementById('taskPriority').value = task.priority || 'medium';
            document.getElementById('taskStatus').value = task.status;
            
            // Format Date Time for Input
            const date = new Date(task.scheduled_at);
            const dateStr = date.toISOString().split('T')[0];
            const timeStr = date.toTimeString().split(' ')[0].substring(0, 5);
            
            document.getElementById('taskDate').value = dateStr;
            document.getElementById('taskTime').value = timeStr;
            
            document.getElementById('taskModal').classList.remove('hidden');
        })
        .catch(err => {
            console.error('Error loading task:', err);
            showAlert('error', 'Failed to load task details');
        });
}

// Submit Report
function submitReport(type) {
    const form = document.getElementById('reportForm');
    if (type === 'pdf') {
        form.action = "{{ route('admin.appointments.generateReport') }}";
    } else {
        form.action = "{{ route('admin.appointments.exportExcel') }}";
    }
    form.submit();
    closeReportModal();
}

// Export Tasks
function exportTasks(type) {
    openReportModal();
}

// Close modals on outside click
window.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        closeTaskModal();
        closeAssignModal();
        closeViewModal();
        closeReportModal();
    }
});

function updateStatus(id, newStatus) {
    fetch(`/admin/appointments/${id}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            showAlert('success', 'Status updated successfully!');
            // Update the progress bar visually
            const select = document.querySelector(`select[onchange="updateStatus(${id}, this.value)"]`);
            if (select) {
                const row = select.closest('tr');
                const progressBar = row.querySelector('.progress-fill');
                const progressText = row.querySelector('.progress-text');
                
                let progress = 0;
                if (newStatus === 'completed') progress = 100;
                else if (newStatus === 'confirmed') progress = 50;
                
                if (progressBar) progressBar.style.width = progress + '%';
                if (progressText) progressText.textContent = progress + '%';
                
                // Update select class for color
                select.className = `status-select status-${newStatus}`;
            }
        } else {
            showAlert('error', d.message || 'Error updating status');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showAlert('error', 'Failed to update status');
    });
}
</script>

