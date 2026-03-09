@include('layouts.header')
@include('layouts.sidebar')

<div class="support-dashboard-wrapper">
    <!-- DASHBOARD HEADER -->
    <div class="dashboard-header">
        <div class="header-left">
            <h1><i class="fas fa-headset"></i>Support Command Center</h1>
            <p>Monitor, manage, and resolve customer inquiries in real-time.</p>
        </div>
        <div class="header-actions">
            <button class="glass-btn primary" onclick="openModal('categoryModal')">
                <i class="fas fa-plus"></i> New Category
            </button>
        </div>
    </div>

    <!-- REPORT FILTER BAR -->
    <div class="report-filter-bar glass">
        <form action="{{ route('admin.support.report.pdf') }}" method="GET" id="exportForm" class="filter-flex">
            <div class="filter-item">
                <label>Date From</label>
                <input type="date" name="start_date">
            </div>
            <div class="filter-item">
                <label>Date To</label>
                <input type="date" name="end_date">
            </div>
            <div class="filter-item">
                <label>Staff Role</label>
                <select name="role">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="employee">Employee</option>
                </select>
            </div>
            <div class="filter-item">
                <label>Category</label>
                <select name="category_id">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button type="button" class="glass-btn export-pdf" onclick="runExport('pdf')">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
                <button type="button" class="glass-btn export-excel" onclick="runExport('excel')">
                    <i class="fas fa-file-excel"></i> EXCEL
                </button>
            </div>
        </form>
    </div>

    <!-- METRICS GRID -->
    <div class="metrics-grid">
        <div class="metric-card glass gradient-blue">
            <div class="m-icon"><i class="fas fa-ticket-alt"></i></div>
            <div class="m-data">
                <span class="m-label">Total Tickets</span>
                <span class="m-value">{{ $stats['total'] }}</span>
            </div>
        </div>
        <div class="metric-card glass gradient-orange">
            <div class="m-icon"><i class="fas fa-clock"></i></div>
            <div class="m-data">
                <span class="m-label">Open / Pending</span>
                <span class="m-value">{{ $stats['open'] }}</span>
            </div>
        </div>
        <div class="metric-card glass gradient-purple">
            <div class="m-icon"><i class="fas fa-spinner"></i></div>
            <div class="m-data">
                <span class="m-label">In Progress</span>
                <span class="m-value">{{ $stats['in_progress'] }}</span>
            </div>
        </div>
        <div class="metric-card glass gradient-green">
            <div class="m-icon"><i class="fas fa-check-circle"></i></div>
            <div class="m-data">
                <span class="m-label">Resolved</span>
                <span class="m-value">{{ $stats['closed'] }}</span>
            </div>
        </div>
    </div>

    <!-- MAIN DASHBOARD CONTENT -->
    <div class="dashboard-content">
        <!-- TICKET HUB -->
        <div class="hub-container">
            <div class="hub-card glass">
                <div class="card-header">
                    <h3><i class="fas fa-list"></i> Ticket Hub</h3>
                    <div class="filter-pills">
                        <button class="f-pill active" data-filter="all">All</button>
                        <button class="f-pill" data-filter="open">Open</button>
                        <button class="f-pill" data-filter="in_progress">In Progress</button>
                        <button class="f-pill" data-filter="closed">Resolved</button>
                    </div>
                </div>

                <div class="table-wrapper text-small">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th># Ticket</th>
                                <th>Client / Subject</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>CASE WORKER</th>
                                <th>Quick Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr class="ticket-row" data-status="{{ $ticket->status }}" data-id="{{ $ticket->id }}">
                                    <td class="ticket-no">#{{ $ticket->ticket_no }}</td>
                                    <td class="client-info">
                                        <strong>{{ optional($ticket->customer)->name ?? optional($ticket->submitter)->name ?? 'Guest User' }}</strong>
                                        <span>{{ $ticket->subject }}</span>
                                    </td>
                                    <td>
                                        <span class="prio-tag {{ $ticket->priority ?? 'normal' }}">
                                            {{ ucfirst($ticket->priority ?? 'normal') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $ticket->status }}">
                                            @if($ticket->status === 'closed')
                                                Resolved
                                            @elseif($ticket->status === 'in_progress')
                                                In Progress
                                            @else
                                                {{ ucfirst($ticket->status) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="assigned-col">
                                        {{ optional($ticket->assignedUser)->name ?? 'Unassigned' }}
                                    </td>
                                    <td class="actions-col">
                                        <button class="btn-icon" onclick="viewTicket({{ $ticket->id }})"><i
                                                class="fas fa-eye"></i></button>
                                        <button class="btn-icon" onclick="openReplyModal({{ $ticket->id }})"><i
                                                class="fas fa-reply"></i></button>
                                        <button class="btn-icon delete" onclick="openAssignModal({{ $ticket->id }})"><i
                                                class="fas fa-user-plus"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-state">No tickets matched your criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>

        <!-- INTERACTION PANEL (Appears on selection) -->
        <div id="inspectorPanel" class="inspector-panel glass">
            <div class="inspector-header">
                <h3>Ticket Insight</h3>
                <button class="close-btn" onclick="closeInspector()"><i class="fas fa-times"></i></button>
            </div>
            <div id="inspectorBody" class="inspector-body">
                <div class="empty-inspector">
                    <i class="fas fa-mouse-pointer"></i>
                    <p>Select a ticket to view details and timeline.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CATEGORY MODAL -->
<div id="categoryModal" class="modal">
    <div class="modal-content wide">
        <div class="modal-flex">
            <!-- CREATE SECTION -->
            <div class="modal-side entry">
                <div class="modal-header">
                    <h3><i class="fas fa-plus-circle"></i> New Category</h3>
                    <p class="modal-desc">Organize and route tickets (e.g., "Billing", "Tech Support").</p>
                </div>
                <form action="{{ route('admin.support.category.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Category Label</label>
                        <div class="input-with-icon">
                            <i class="fas fa-tag"></i>
                            <input type="text" name="name" placeholder="Technical, Billing, etc." required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description (Optional)</label>
                        <textarea name="description" placeholder="What kind of tickets fall here?"></textarea>
                    </div>
                    <div class="modal-footer no-padding">
                        <button type="submit" class="glass-btn primary full-width">Create & Save Category</button>
                    </div>
                </form>
            </div>

            <!-- LIST SECTION -->
            <div class="modal-side list">
                <div class="modal-header">
                    <h3><i class="fas fa-tags"></i> Active Categories</h3>
                    <p class="modal-desc">Manage existing ticket classifications.</p>
                </div>
                <div class="category-scroll">
                    @forelse($categories as $cat)
                        <div class="cat-item">
                            <div class="cat-info">
                                <strong>{{ $cat->name }}</strong>
                                <span>{{ $cat->description ?? 'No description' }}</span>
                            </div>
                            <div class="cat-actions">
                                <form action="{{ route('admin.support.category.destroy', $cat->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this category?')">
                                    @csrf @method('DELETE')
                                    <button class="btn-icon mini delete"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-mini">
                            <i class="fas fa-folder-open"></i>
                            <p>No categories created yet.</p>
                        </div>
                    @endforelse
                </div>
                <div class="modal-footer no-padding">
                    <button type="button" class="glass-btn secondary full-width"
                        onclick="closeModal('categoryModal')">Close Panel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ASSIGN MODAL -->
<div id="assignModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Delegate Ticket</h3>
            <p class="modal-desc">Select a staff member or manager to handle this inquiry.</p>
        </div>
        <form id="assignForm">
            @csrf
            <div class="form-group">
                <label>Select Staff Agent</label>
                <div class="input-with-icon">
                    <i class="fas fa-user-tie"></i>
                    <select name="assigned_to" required>
                        <option value="">Choose an agent...</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}
                                ({{ ucfirst($agent->role) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="glass-btn secondary" onclick="closeModal('assignModal')">Cancel</button>
                <button type="submit" class="glass-btn primary">Assign Agent</button>
            </div>
        </form>
    </div>
</div>

<!-- REPLY MODAL -->
<div id="replyModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3><i class="fas fa-reply-all"></i> Post Official Response</h3>
            <p class="modal-desc">Transmit a formal resolution or inquiry to the client.</p>
        </div>
        <form id="replyForm" enctype="multipart/form-data" style="padding: 25px;">
            @csrf
            <div class="form-group">
                <label>Resolution Message</label>
                <textarea name="message" id="replyMessage" placeholder="Provide a detailed response for the client..."
                    required style="min-height: 180px;"></textarea>
            </div>

            <div class="form-group">
                <label>Support Documents</label>
                <div class="file-upload-zone" style="padding: 15px;">
                    <i class="fas fa-paperclip" style="font-size: 18px;"></i>
                    <p style="font-size: 11px;">Drop PDF or Images here</p>
                    <input type="file" name="attachment" class="file-input">
                </div>
            </div>

            <div class="modal-footer no-padding" style="margin-top: 20px; gap: 10px;">
                <button type="submit" class="glass-btn primary" style="flex: 1; justify-content: center;">
                    <i class="fas fa-paper-plane"></i> Send Official Reply
                </button>
                <button type="button" class="glass-btn secondary" onclick="closeModal('replyModal')"
                    style="padding: 10px 20px;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Theme Variables */
    /* Rely on global variables from app layout */

    body {
        background: var(--body-bg);
        font-family: 'Outfit', 'Inter', sans-serif;
        color: var(--text-primary);
        margin: 0;
        min-height: 100vh;
    }

    .support-dashboard-wrapper {
        margin-left: 242px;
        padding: 30px;
        background: var(--body-bg);
        min-height: 100vh;
    }

    /* REPORT FILTER BAR */
    .report-filter-bar {
        padding: 15px 25px;
        border-radius: 20px;
        margin-bottom: 25px;
        border: 1px solid var(--header-border);
        background: var(--surface);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .filter-flex {
        display: flex;
        align-items: flex-end;
        gap: 20px;
        flex-wrap: wrap;
    }

    .filter-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .filter-item label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
    }

    .filter-item input,
    .filter-item select {
        padding: 8px 12px;
        border-radius: 10px;
        border: 1px solid var(--header-border);
        background: var(--body-bg);
        font-size: 13px;
        color: var(--text-primary);
        font-family: inherit;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
    }

    .glass-btn {
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .glass-btn.primary {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .glass-btn.primary:hover {
        background: var(--primary-dark);
    }

    .glass-btn.secondary {
        background: var(--body-bg);
        color: var(--text-muted);
        border-color: var(--header-border);
    }

    .glass-btn.secondary:hover {
        border-color: var(--text-muted);
        color: var(--text-primary);
    }

    .glass-btn.export-pdf {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border-color: transparent;
    }

    .glass-btn.export-pdf:hover {
        background: rgba(239, 68, 68, 0.2);
    }

    .glass-btn.export-excel {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border-color: transparent;
    }

    .glass-btn.export-excel:hover {
        background: rgba(16, 185, 129, 0.2);
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .dashboard-header h1 {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dashboard-header p {
        color: var(--text-muted);
        margin-top: 5px;
        font-size: 15px;
    }

    /* METRICS */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 35px;
    }

    .metric-card {
        padding: 24px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        gap: 18px;
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }

    .metric-card.gradient-blue {
        background: linear-gradient(135deg, #6366f1, #3b82f6);
    }

    .metric-card.gradient-orange {
        background: linear-gradient(135deg, #f59e0b, #ef4444);
    }

    .metric-card.gradient-purple {
        background: linear-gradient(135deg, #8b5cf6, #d946ef);
    }

    .metric-card.gradient-green {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .m-icon {
        width: 52px;
        height: 52px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .m-data .m-label {
        font-size: 13px;
        font-weight: 600;
        opacity: 0.85;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .m-value {
        font-size: 28px;
        font-weight: 800;
    }

    /* DASHBOARD CONTENT */
    .dashboard-content {
        display: flex;
        gap: 25px;
        align-items: flex-start;
    }

    .hub-container {
        flex: 1;
        min-width: 0;
    }

    .hub-card {
        border-radius: 28px;
        padding: 30px;
        border: 1px solid var(--header-border);
        background: var(--surface);
        box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.05);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        border-bottom: 1px solid var(--header-border);
        padding-bottom: 20px;
    }

    .card-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 800;
        color: var(--text-primary);
    }

    .filter-pills {
        display: flex;
        gap: 10px;
    }

    .f-pill {
        padding: 8px 18px;
        border-radius: 12px;
        border: 1px solid var(--header-border);
        background: var(--body-bg);
        font-size: 13px;
        font-weight: 700;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
    }

    .f-pill.active {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    /* MODERN TABLE OVERHAUL */
    .table-wrapper {
        overflow-x: auto;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
    }

    .modern-table th {
        padding: 12px 20px;
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
        color: var(--text-muted);
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .ticket-row {
        background: var(--body-bg);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        border-radius: 16px;
        position: relative;
    }

    .ticket-row:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.08);
        z-index: 2;
    }

    .ticket-row td {
        padding: 20px;
        border-top: 1px solid var(--header-border);
        border-bottom: 1px solid var(--header-border);
        color: var(--text-primary);
    }

    .ticket-row td:first-child {
        border-left: 1px solid var(--header-border);
        border-radius: 16px 0 0 16px;
    }

    .ticket-row td:last-child {
        border-right: 1px solid var(--header-border);
        border-radius: 0 16px 16px 0;
    }

    .ticket-no {
        font-family: 'JetBrains Mono', monospace;
        font-weight: 700;
        color: var(--primary);
        font-size: 13px;
    }

    .client-info strong {
        display: block;
        font-size: 15px;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .client-info span {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 500;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.open {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .status-badge.in_progress {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .status-badge.closed {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .prio-tag {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        color: #fff;
        padding: 4px 10px;
        border-radius: 8px;
    }

    .prio-tag.high,
    .prio-tag.urgent {
        background: #ef4444;
    }

    .prio-tag.medium,
    .prio-tag.normal {
        background: var(--primary);
    }

    .prio-tag.low {
        background: #10b981;
    }

    .btn-icon {
        background: var(--surface);
        border: 1px solid var(--header-border);
        color: var(--text-muted);
        width: 36px;
        height: 36px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        margin-right: 5px;
        font-size: 14px;
    }

    .btn-icon:hover {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
        transform: scale(1.1);
    }

    .btn-icon.delete:hover {
        background: #ef4444;
        border-color: #ef4444;
    }

    /* PAGINATION - COMPACT & MINI */
    .pagination-container {
        margin-top: 30px;
        display: flex;
        justify-content: center;
        width: 100%;
    }

    .pagination-container nav {
        background: transparent !important;
        box-shadow: none !important;
    }

    /* Hide redundant parts */
    .pagination-container nav>div:first-child,
    .pagination-container .hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between>div:first-child,
    .pagination-container p {
        display: none !important;
    }

    .pagination-container nav>div:last-child {
        display: flex !important;
        justify-content: center !important;
    }

    .pagination-container ul.pagination,
    .pagination-container span.relative.z-0 {
        display: inline-flex !important;
        gap: 6px !important;
        background: transparent !important;
        padding: 0 !important;
        border: none !important;
    }

    .pagination-container .page-link,
    .pagination-container span.relative.z-0 a,
    .pagination-container span.relative.z-0 span {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 34px !important;
        height: 34px !important;
        background: var(--surface) !important;
        border: 1px solid var(--header-border) !important;
        border-radius: 8px !important;
        color: var(--text-primary) !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        transition: all 0.2s !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03) !important;
        margin: 0 !important;
    }

    .pagination-container .page-link:hover,
    .pagination-container a:hover {
        border-color: var(--primary) !important;
        color: var(--primary) !important;
        background: var(--body-bg) !important;
        transform: translateY(-1px) !important;
    }

    .pagination-container .page-item.active .page-link,
    .pagination-container span[aria-current="page"] {
        background: var(--primary) !important;
        color: white !important;
        border-color: var(--primary) !important;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3) !important;
        position: relative;
    }

    /* INSPECTOR PANEL REDESIGN */
    .inspector-panel {
        width: 420px;
        background: var(--surface);
        border-radius: 28px;
        border: 1px solid var(--header-border);
        display: none;
        flex-direction: column;
        box-shadow: -10px 0 30px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 100px;
        max-height: calc(100vh - 120px);
    }

    .inspector-header {
        padding: 25px;
        border-bottom: 1px solid var(--header-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .inspector-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: var(--text-primary);
    }

    .close-btn {
        background: var(--body-bg);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 10px;
        cursor: pointer;
        color: var(--text-muted);
    }

    .inspector-body {
        padding: 25px;
        overflow-y: auto;
    }

    .insight-section {
        margin-bottom: 25px;
    }

    .insight-section label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .insight-value {
        background: var(--body-bg);
        border: 1px solid var(--header-border);
        padding: 15px;
        border-radius: 16px;
        color: var(--text-primary);
        font-size: 14px;
        line-height: 1.6;
    }

    .thread-item {
        margin-bottom: 15px;
    }

    .thread-bubble {
        padding: 15px;
        border-radius: 18px;
        font-size: 13px;
        line-height: 1.5;
    }

    .thread-item.admin .thread-bubble {
        background: rgba(59, 130, 246, 0.1);
        color: var(--text-primary);
        border: 1px solid rgba(59, 130, 246, 0.2);
        margin-left: 20px;
    }

    .thread-item.customer .thread-bubble {
        background: var(--body-bg);
        color: var(--text-primary);
        border: 1px solid var(--header-border);
        margin-right: 20px;
    }

    .thread-meta {
        display: flex;
        justify-content: space-between;
        font-size: 10px;
        font-weight: 700;
        margin-bottom: 5px;
        opacity: 0.7;
    }

    /* MODALS - COMPACT & LIGHT */
    .modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(6px);
    }

    .modal-content {
        background: var(--surface);
        width: 95%;
        max-width: 480px;
        margin: 60px auto;
        padding: 0;
        border-radius: 24px;
        box-shadow: 0 40px 80px -15px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        border: 1px solid var(--header-border);
    }

    .modal-content.wide {
        max-width: 760px;
    }

    .modal-header {
        padding: 25px 30px;
        border-bottom: 1px solid var(--header-border);
        background: var(--surface);
    }

    .modal-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 800;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .modal-header h3 i {
        color: var(--primary);
        font-size: 19px;
    }

    .modal-desc {
        font-size: 13px;
        color: var(--text-muted);
        margin: 6px 0 0 0;
        font-weight: 500;
    }

    .modal-flex {
        display: flex;
        height: 500px;
    }

    .modal-side {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .modal-side.entry {
        padding: 25px;
    }

    .modal-side.list {
        background: var(--body-bg);
        border-left: 1px solid var(--header-border);
        padding: 25px;
    }

    .modal-footer {
        padding: 20px 30px;
        background: var(--surface);
        border-top: 1px solid var(--header-border);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    .modal-footer.no-padding {
        padding: 0;
        border-top: none;
        background: transparent;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
        font-size: 13px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 14px;
    }

    .input-with-icon input,
    .input-with-icon select {
        padding-left: 45px !important;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1.5px solid var(--header-border);
        background: var(--body-bg);
        font-family: inherit;
        font-size: 14px;
        color: var(--text-primary);
        transition: all 0.2s;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .category-scroll {
        flex: 1;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
        padding-right: 5px;
    }

    .cat-item {
        padding: 18px;
        border-radius: 18px;
        background: var(--surface);
        border: 1px solid var(--header-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .cat-info strong {
        display: block;
        font-size: 15px;
        color: var(--text-primary);
    }

    .cat-info span {
        font-size: 12px;
        color: var(--text-muted);
    }

    .reply-layout {
        display: flex;
        gap: 30px;
        padding: 30px;
    }

    .reply-main {
        flex: 2;
    }

    .reply-sidebar {
        flex: 1;
        background: var(--body-bg);
        padding: 25px;
        border-radius: 20px;
        border: 1px solid var(--header-border);
        display: flex;
        flex-direction: column;
    }

    .file-upload-zone {
        border: 2px dashed var(--header-border);
        border-radius: 16px;
        padding: 30px 20px;
        text-align: center;
        background: var(--body-bg);
        position: relative;
        cursor: pointer;
        transition: all 0.2s;
    }

    .file-upload-zone:hover {
        border-color: var(--primary);
        background: var(--surface);
    }

    .file-upload-zone i {
        font-size: 32px;
        color: var(--primary);
        margin-bottom: 12px;
        opacity: 0.6;
    }

    .file-upload-zone p {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        margin: 0;
    }

    .file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .mb-3 {
        margin-bottom: 12px;
    }

    .mt-auto {
        margin-top: auto;
    }

    .full-width {
        width: 100%;
        justify-content: center;
    }

    .empty-state-mini {
        text-align: center;
        padding: 40px 0;
        color: var(--text-muted);
    }

    .empty-state-mini i {
        font-size: 40px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .empty-state-mini p {
        font-size: 14px;
        font-weight: 500;
    }

    @media (max-width: 1280px) {
        .support-dashboard-wrapper {
            margin-left: 0;
            padding: 20px;
        }

        .dashboard-content {
            flex-direction: column;
        }

        .inspector-panel {
            width: 100%;
            position: static;
            margin-top: 30px;
        }

        .reply-layout {
            flex-direction: column;
        }

        .modal-flex {
            flex-direction: column;
            height: auto;
        }

        .modal-side.list {
            border-left: none;
            border-top: 1px solid var(--header-border);
        }
    }

    /* FIX: Z-Index to prevent Header from blocking Modal */
    .modal {
        z-index: 10001 !important;
    }

    /* FIX: Dark Mode Select Options */
    html[data-theme="dark"] option {
        background: #1a1f2e;
        color: #f8fafc;
    }

    html[data-theme="dark"] select {
        background-color: #1a1f2e !important;
        color: #f8fafc !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = '{{ csrf_token() }}';

        window.runExport = function (type) {
            const form = document.getElementById('exportForm');
            const formData = new FormData(form);

            // Validation: Ensure all filters are selected
            const start = formData.get('start_date');
            const end = formData.get('end_date');
            const role = formData.get('role');
            const cat = formData.get('category_id');

            if (!start || !end || !role || !cat) {
                showToast('Please select Date Range, Staff Role, and Category to generate report.', 'error');
                return;
            }

            const baseUrl = type === 'pdf' ? '{{ route("admin.support.report.pdf") }}' : '{{ route("admin.support.report.excel") }}';
            const params = new URLSearchParams(formData).toString();
            window.location.href = `${baseUrl}?${params}`;
        };

        // TAB FILTERING (Updated to Server-side)
        document.querySelectorAll('.f-pill').forEach(pill => {
            // Set active state based on URL
            const urlParams = new URLSearchParams(window.location.search);
            const currentStatus = urlParams.get('status') || 'all';
            if (pill.dataset.filter === currentStatus) pill.classList.add('active');
            else pill.classList.remove('active');

            pill.addEventListener('click', () => {
                const filter = pill.dataset.filter;
                window.location.href = `/admin/support?status=${filter}`;
            });
        });

        // VIEW TICKET INSIGHT
        window.viewTicket = function (id) {
            const panel = document.getElementById('inspectorPanel');
            const body = document.getElementById('inspectorBody');
            panel.style.display = 'flex';
            panel.style.animation = 'slideIn 0.3s ease-out';

            body.innerHTML = '<div class="empty-inspector"><i class="fas fa-spinner fa-spin"></i><p>Fetching intelligence...</p></div>';

            fetch(`/admin/support/ticket/${id}`)
                .then(res => res.json())
                .then(t => {
                    // Status Badge Logic for Dropdown
                    const isSelected = (s) => t.status === s ? 'selected' : '';

                    body.innerHTML = `
                    <div class="insight-section">
                        <label>Ticket Identity</label>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="insight-value" style="font-family: monospace; font-weight: 800; color: var(--indigo); font-size: 16px;">
                                #${t.ticket_no}
                            </div>
                            <!-- Status Dropdown -->
                            <select onchange="updateTicketStatus(${t.id}, this.value)" 
                                    style="padding: 8px 12px; border-radius: 10px; border: 1px solid var(--header-border); background: var(--body-bg); color: var(--text-primary); font-weight: 700; font-size: 12px; cursor: pointer;">
                                <option value="open" ${isSelected('open')}>Open</option>
                                <option value="in_progress" ${isSelected('in_progress')}>In Progress</option>
                                <option value="closed" ${isSelected('closed')}>Resolved</option>
                            </select>
                        </div>
                    </div>

                    <div class="insight-section">
                        <label>Requester Details</label>
                        <div class="insight-value">
                            <div style="font-weight: 800; font-size: 15px;">${t.requester_name}</div>
                            <div style="color: #64748b; font-size: 13px;">${t.requester_email}</div>
                        </div>
                    </div>

                    <div class="insight-section">
                        <label>Issue Context</label>
                        <div class="insight-value" style="background: white; border-left: 4px solid var(--indigo);">
                            <div style="font-weight: 700; margin-bottom: 8px;">Subject: ${t.subject}</div>
                            <p style="margin: 0;">${t.description}</p>
                        </div>
                    </div>

                    ${t.attachment ? `
                        <div class="insight-section">
                            <label>Attachments</label>
                            <a href="/storage/${t.attachment}" target="_blank" class="glass-btn primary" style="width: 100%; justify-content: center;">
                                <i class="fas fa-file-alt"></i> View Reference Doc
                            </a>
                        </div>
                    ` : ''}

                    <div class="insight-section">
                        <label>Dialogue History</label>
                        <div class="timeline-thread">
                            ${t.replies.length ? t.replies.map(r => `
                                <div class="thread-item ${r.is_staff_reply ? 'admin' : 'customer'}">
                                    <div class="thread-bubble">
                                        <div class="thread-meta">
                                            <span>${r.is_staff_reply ? 'Support Agent' : 'Client Representative'}</span>
                                            <span>${new Date(r.created_at).toLocaleDateString()}</span>
                                        </div>
                                        <div style="white-space: pre-wrap;">${r.message}</div>
                                        ${r.attachment ? `<a href="${r.attachment}" target="_blank" style="display:inline-block; margin-top:10px; font-size:11px; color:var(--indigo); font-weight:700;"><i class="fas fa-link"></i> Download Attachment</a>` : ''}
                                    </div>
                                </div>
                            `).join('') : '<div style="text-align:center; padding: 20px; color: #94a3b8; font-style: italic; font-size: 13px;">No replies yet. Use "Quick Actions" to respond.</div>'}
                        </div>
                    </div>
                `;
                });
        };

        // NEW: Status Update Logic
        window.updateTicketStatus = function (id, newStatus) {
            const formData = new FormData();
            formData.append('status', newStatus);

            fetch(`/admin/support/status/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        let displayStatus = newStatus === 'closed' ? 'RESOLVED' : newStatus.replace('_', ' ').toUpperCase();
                        showToast(`Status updated to ${displayStatus}`, 'success');

                        // Reload page to update stats and table (User Request)
                        setTimeout(() => location.reload(), 800);
                    } else {
                        showToast('Failed to update status', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Error updating status', 'error');
                });
        };

        window.closeInspector = () => document.getElementById('inspectorPanel').style.display = 'none';

        // ASSIGN LOGIC
        const assignForm = document.getElementById('assignForm');
        assignForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const tid = assignForm.dataset.tid;
            fetch(`/admin/support/assign/${tid}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(assignForm)
            })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        closeModal('assignModal');
                    } else {
                        showToast(data.message || 'Failed to assign agent.', 'error');
                    }
                    // Always reload so the Case Worker column reflects the change
                    setTimeout(() => location.reload(), 900);
                })
                .catch(err => {
                    console.error(err);
                    showToast('An error occurred. Check console for details.', 'error');
                });
        });

        window.openAssignModal = (id) => {
            document.getElementById('assignModal').style.display = 'block';
            assignForm.dataset.tid = id;
        };

        // REPLY LOGIC
        const replyForm = document.getElementById('replyForm');
        replyForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const tid = replyForm.dataset.tid;
            fetch(`/admin/support/reply/${tid}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: new FormData(replyForm)
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        closeModal('replyModal');
                        if (document.getElementById('inspectorPanel').style.display === 'flex') viewTicket(tid);
                    }
                });
        });

        window.openReplyModal = (id) => {
            document.getElementById('replyModal').style.display = 'block';
            replyForm.dataset.tid = id;
        };

        window.openModal = (id) => document.getElementById(id).style.display = 'block';
        window.closeModal = (id) => document.getElementById(id).style.display = 'none';

        function showToast(msg, type) {
            const toast = document.createElement('div');
            Object.assign(toast.style, {
                position: 'fixed', bottom: '20px', right: '20px',
                padding: '12px 24px', borderRadius: '12px', color: '#fff',
                zIndex: '9999', fontWeight: '600', boxShadow: '0 10px 15px rgba(0,0,0,0.1)',
                backgroundColor: type === 'success' ? '#22c55e' : '#ef4444'
            });
            toast.innerText = msg;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    });
</script>

<style>
    /* Additional Inline Styles for Timeline & Inspector */
    .insight-meta {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .meta-section label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #94a3b8;
        display: block;
        margin-bottom: 5px;
    }

    .meta-section h4 {
        margin: 0;
        color: var(--slate);
        font-size: 18px;
    }

    .meta-section p {
        margin: 0;
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
    }

    .desc-box {
        padding: 12px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        font-size: 13px;
        color: #475569;
        background: #fff;
    }

    .timeline h4 {
        font-size: 14px;
        margin: 20px 0 15px 0;
        color: var(--slate);
    }

    .timeline-thread {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .thread-item {
        display: flex;
        flex-direction: column;
    }

    .thread-bubble {
        padding: 12px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #fff;
        position: relative;
    }

    .thread-item.admin .thread-bubble {
        background: #eef2ff;
        border-color: #c7d2fe;
    }

    .thread-meta {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
    }

    .u-type {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .u-date {
        font-size: 10px;
        color: #94a3b8;
    }

    .thread-bubble p {
        margin: 0;
        font-size: 12px;
        line-height: 1.4;
        color: #334155;
    }

    .mini-attach {
        display: inline-block;
        font-size: 10px;
        font-weight: 700;
        color: var(--indigo);
        text-decoration: none;
        margin-top: 5px;
    }

    .glass-hr {
        border: none;
        height: 1px;
        background: rgba(0, 0, 0, 0.05);
        margin: 20px 0;
    }

    .empty-thread {
        font-size: 12px;
        color: #94a3b8;
        text-align: center;
        font-style: italic;
    }
</style>