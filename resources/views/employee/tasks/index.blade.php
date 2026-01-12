@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-list-check" style="color: var(--primary);"></i> Tasks Catalog</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Prioritize and manage your assigned operational tasks.
            </p>
        </div>
        <div class="header-actions">
            <div class="glass-panel" style="padding: 10px 20px; text-align: center; border-radius: 12px;">
                <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--secondary);">Backlog
                    Tasks</span>
                <strong
                    style="display: block; font-size: 1.25rem; color: var(--primary);">{{ $tasks->total() }}</strong>
            </div>
        </div>
    </div>

    {{-- Kanban Style Dashboard --}}
    <div
        style="margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px;">

        {{-- Column 1: ACTIVE OBJECTIVES --}}
        <div class="kanban-column">
            <div class="column-header">
                <div class="indicator pulse-blue"></div>
                <h3>Active Objectives</h3>
                <span class="count-badge">{{ $tasks->where('status', '!=', 'completed')->count() }}</span>
            </div>

            <div class="task-grid" id="activeTasks">
                @forelse($tasks->where('status', '!=', 'completed') as $task)
                    <div class="task-card task-row" data-id="{{ $task->id }}">
                        <div class="card-glow"></div>
                        <div class="card-content">
                            <div
                                style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                <span class="priority-tag">Priority: High</span>
                                <div class="task-timer"><i class="far fa-clock"></i>
                                    {{ $task->created_at->diffForHumans() }}</div>
                            </div>
                            <h4 class="task-title">{{ $task->title }}</h4>
                            <p class="task-excerpt">{{ Str::limit($task->description, 100) }}</p>

                            <div class="card-footer">
                                <div class="assigned-user">
                                    <div class="mini-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                                    <span>Assigned to You</span>
                                </div>
                                <div class="card-actions">
                                    <button class="icon-btn" onclick="viewTaskDetails({{ $task->id }})"
                                        title="View Details">
                                        <i class="fas fa-expand-alt"></i>
                                    </button>
                                    <button class="icon-btn success"
                                        onclick="updateTaskStatus({{ $task->id }}, 'completed')" title="Complete">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-column-state">
                        <i class="fas fa-check-circle"></i>
                        <p>No active objectives</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Column 2: FINALIZED MISSIONS --}}
        <div class="kanban-column">
            <div class="column-header">
                <div class="indicator pulse-green"></div>
                <h3>Finalized Missions</h3>
                <span class="count-badge">{{ $tasks->where('status', 'completed')->count() }}</span>
            </div>

            <div class="task-grid" id="completedTasks">
                @forelse($tasks->where('status', 'completed') as $task)
                    <div class="task-card completed task-row" data-id="{{ $task->id }}">
                        <div class="card-content">
                            <div
                                style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                <span class="status-chip"><i class="fas fa-medal"></i> Quality Tested</span>
                                <div class="task-timer">{{ $task->created_at->format('M d') }}</div>
                            </div>
                            <h4 class="task-title">{{ $task->title }}</h4>
                            <div class="card-footer" style="margin-top: 20px;">
                                <div class="assigned-user">
                                    <div class="mini-avatar green">{{ substr(Auth::user()->name, 0, 1) }}</div>
                                    <span>Archived</span>
                                </div>
                                <button class="icon-btn" onclick="viewTaskDetails({{ $task->id }})">
                                    <i class="fas fa-history"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-column-state">
                        <i class="fas fa-box-open"></i>
                        <p>Archive is currently empty</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Column 3: SERVICE CATALOG (Info Retrieval) --}}
        <div class="kanban-column">
            <div class="column-header">
                <div class="indicator pulse-purple"
                    style="background: var(--primary); box-shadow: 0 0 0 rgba(79, 70, 229, 0.4);"></div>
                <h3>Service Catalog</h3>
                <span class="count-badge">{{ $services->count() }}</span>
            </div>

            <div class="task-grid" id="serviceCatalog">
                @forelse($services as $service)
                    <div class="task-card task-row" style="border-left: 4px solid var(--primary);">
                        <div class="card-content">
                            <div
                                style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                <span class="status-chip"
                                    style="color: var(--primary); background: rgba(79, 70, 229, 0.1);"><i
                                        class="fas fa-cube"></i> Service</span>
                                <div class="task-timer">{{ number_format($service->price) }} FRW</div>
                            </div>
                            <h4 class="task-title">{{ $service->name }}</h4>
                            <p class="task-excerpt">{{ Str::limit($service->description, 80) }}</p>
                            <div class="card-footer" style="margin-top: 20px;">
                                <div class="assigned-user">
                                    <i class="fas fa-info-circle"></i> <span>Info Only</span>
                                </div>
                                <button class="icon-btn"
                                    onclick="Swal.fire({ title: '{{ addslashes($service->name) }}', html: '<p>{{ addslashes($service->description) }}</p><p><b>Price:</b> {{ number_format($service->price) }} FRW</p>', confirmButtonText: 'Close' })">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-column-state">
                        <i class="fas fa-box-open"></i>
                        <p>Catalog is empty</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div style="margin-top: 35px; border-top: 1px solid var(--glass-border); padding-top: 25px;">
        {{ $tasks->links() }}
    </div>
</div>

{{-- Task Details Modal --}}
<div id="taskDetailsModal" class="modal-overlay">
    <div class="pro-modal" style="max-width: 550px;">
        <div class="modal-header">
            <h3><i class="fas fa-clipboard-list" style="color: var(--primary);"></i> Task Requirement Detail</h3>
            <button class="close-modal" onclick="closeTaskModal()">&times;</button>
        </div>
        <div class="modal-body" id="taskDetailsContent">
            <div style="text-align:center; padding: 2rem;">
                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary);"></i>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeTaskModal()">Return to Catalog</button>
        </div>
    </div>
</div>

<style>
    /* Kanban Layout */
    .kanban-column {
        background: rgba(0, 0, 0, 0.02);
        border-radius: 24px;
        padding: 25px;
        min-height: 600px;
        border: 1px dashed var(--glass-border);
    }

    .column-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
        padding: 0 10px;
    }

    .column-header h3 {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--dark);
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .count-badge {
        background: var(--white);
        color: var(--secondary);
        font-size: 0.8rem;
        font-weight: 700;
        padding: 2px 10px;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .pulse-blue {
        background: #6366f1;
        box-shadow: 0 0 0 rgba(99, 102, 241, 0.4);
        animation: pulse-blue 2s infinite;
    }

    .pulse-green {
        background: #10b981;
    }

    @keyframes pulse-blue {
        0% {
            box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(99, 102, 241, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(99, 102, 241, 0);
        }
    }

    /* Task Cards */
    .task-grid {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .task-card {
        background: var(--white);
        border-radius: 20px;
        padding: 24px;
        position: relative;
        border: 1px solid var(--glass-border);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: default;
        overflow: hidden;
    }

    .task-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border-color: var(--primary);
    }

    .task-card.completed {
        opacity: 0.8;
        grayscale: 0.5;
    }

    .task-card.completed:hover {
        opacity: 1;
        grayscale: 0;
    }

    .priority-tag {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #ef4444;
        background: rgba(239, 68, 68, 0.1);
        padding: 4px 10px;
        border-radius: 8px;
    }

    .status-chip {
        font-size: 0.7rem;
        font-weight: 700;
        color: #10b981;
        background: rgba(16, 185, 129, 0.1);
        padding: 4px 10px;
        border-radius: 8px;
    }

    .task-timer {
        font-size: 0.75rem;
        color: var(--secondary);
        font-weight: 600;
    }

    .task-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0 0 8px;
    }

    .task-excerpt {
        font-size: 0.85rem;
        color: var(--secondary);
        line-height: 1.5;
        margin: 0;
    }

    .card-footer {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(0, 0, 0, 0.03);
        padding-top: 15px;
    }

    .assigned-user {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--secondary);
    }

    .mini-avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
    }

    .mini-avatar.green {
        background: #10b981;
    }

    .card-actions {
        display: flex;
        gap: 8px;
    }

    .icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: var(--light);
        color: var(--secondary);
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .icon-btn:hover {
        background: var(--dark);
        color: white;
    }

    .icon-btn.success:hover {
        background: #10b981;
    }

    .empty-column-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--secondary);
        opacity: 0.5;
    }

    .empty-column-state i {
        font-size: 2rem;
        margin-bottom: 15px;
        display: block;
    }

    .card-glow {
        position: absolute;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
        top: -50px;
        right: -50px;
        z-index: 0;
        pointer-events: none;
    }

    /* Modal Overlay */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(10px);
        z-index: 10001;
        align-items: center;
        justify-content: center;
    }

    .pro-modal {
        background: var(--white);
        width: 90%;
        border-radius: 30px;
        padding: 0;
        box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.3);
        animation: modalEntrance 0.5s cubic-bezier(0.18, 0.89, 0.32, 1.28);
    }

    @keyframes modalEntrance {
        from {
            opacity: 0;
            transform: translateY(60px) scale(0.9);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-header {
        padding: 30px;
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--secondary);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .close-modal:hover {
        background: var(--light);
    }

    .modal-body {
        padding: 40px;
    }

    .modal-footer {
        padding: 20px 40px;
        background: var(--light);
        display: flex;
        justify-content: flex-end;
    }

    .btn-cancel {
        padding: 12px 24px;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        background: var(--white);
        color: var(--secondary);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('taskSearch').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.task-row');
        rows.forEach(row => {
            let title = row.querySelector('td div').textContent.toLowerCase();
            row.style.display = title.includes(filter) ? '' : 'none';
        });
    });

    async function viewTaskDetails(id) {
        const modal = document.getElementById('taskDetailsModal');
        const content = document.getElementById('taskDetailsContent');
        const footer = modal.querySelector('.modal-footer');
        modal.style.display = 'flex';
        content.innerHTML = '<div style="text-align:center; padding: 2rem;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary);"></i></div>';
        footer.innerHTML = '<button class="btn-cancel" onclick="closeTaskModal()">Dismiss Focus</button>';

        try {
            const response = await fetch(`/employee/tasks/${id}/details`);
            const res = await response.json();
            if (res.success) {
                const t = res.data;
                content.innerHTML = `
                    <div style="margin-bottom: 30px;">
                        <h2 style="margin: 0; font-size: 1.4rem; color: var(--dark);">${t.title}</h2>
                        <div style="margin-top: 10px; display: flex; gap: 15px; align-items: center;">
                            <span class="status-pill status-${t.status == 'completed' ? 'active' : 'pending'}">${t.status}</span>
                            <span style="font-size: 0.8rem; color: var(--secondary);"><i class="far fa-clock"></i> Assigned on ${t.created_at}</span>
                        </div>
                    </div>
                    <div>
                        <label style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary); font-weight: 800;">Requirement Narrative</label>
                        <div style="margin-top: 15px; background: var(--light); padding: 25px; border-radius: 20px; line-height: 1.7; color: var(--dark); font-size: 1rem;">
                            ${t.description || 'No detailed instructions provided.'}
                        </div>
                    </div>
                `;

                footer.innerHTML = `
                    <button class="btn-cancel" onclick="deleteTask(${t.id})" style="color: #ef4444; margin-right: auto;"><i class="far fa-trash-alt"></i> Purge Task</button>
                    <button class="btn-cancel" onclick="closeTaskModal()">Dismiss Focus</button>
                    <button class="action-btn btn-primary" onclick="editTask(${t.id}, '${t.title.replace(/'/g, "\\'")}', \`${(t.description || '').replace(/`/g, "\\`")}\`)" style="width: auto; padding: 0 20px; border-radius: 12px; height: 42px;">
                        <i class="fas fa-edit"></i> Edit Objective
                    </button>
                `;
            }
        } catch (err) {
            content.innerHTML = '<p style="color:red; text-align:center;">Failed to resolve task data.</p>';
        }
    }

    async function deleteTask(id) {
        const result = await Swal.fire({
            title: 'Purge Task?',
            text: "Removing this objective is permanent and will clear all associated logs.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: 'var(--secondary)',
            confirmButtonText: 'Yes, Purge!'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/employee/tasks/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const res = await response.json();
                if (res.success) {
                    Swal.fire('Purged!', res.message, 'success').then(() => location.reload());
                }
            } catch (e) {
                Swal.fire('Error', 'Purge failed.', 'error');
            }
        }
    }

    async function editTask(id, currentTitle, currentDesc) {
        const { value: formValues } = await Swal.fire({
            title: 'Adjust Objective Parameters',
            html:
                `<input id="swal-title" class="swal2-input" placeholder="Task Title" value="${currentTitle}">` +
                `<textarea id="swal-desc" class="swal2-textarea" placeholder="Requirement Narrative" style="height: 150px;">${currentDesc}</textarea>`,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Sync Updates',
            preConfirm: () => {
                return {
                    title: document.getElementById('swal-title').value,
                    description: document.getElementById('swal-desc').value
                }
            }
        });

        if (formValues) {
            try {
                const response = await fetch(`/employee/tasks/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formValues)
                });
                const res = await response.json();
                if (res.success) {
                    Swal.fire('Synchronized!', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Failed', res.message, 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Synchronization process failed.', 'error');
            }
        }
    }

    function closeTaskModal() {
        document.getElementById('taskDetailsModal').style.display = 'none';
    }

    async function updateTaskStatus(id, status) {
        const result = await Swal.fire({
            title: 'Verify Completion',
            text: "Are you sure you have finalized all requirements for this task?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'var(--primary)',
            cancelButtonColor: 'var(--secondary)',
            confirmButtonText: 'Yes, Finalize!'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/employee/tasks/${id}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ status: status })
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Task Resolved',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Update Failed', data.message || 'Operation could not be completed.', 'error');
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Communication with orchestration layer failed. Please try again later.', 'error');
            }
        }
    }

    window.onclick = function (event) {
        if (event.target === document.getElementById('taskDetailsModal')) closeTaskModal();
    }
</script>