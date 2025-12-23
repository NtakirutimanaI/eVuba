@include('layouts.header')
@include('layouts.sidebar')

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Theme Variables */
/* Rely on global variables from app layout */

body {
    background: var(--body-bg);
    font-family: 'Inter', sans-serif;
    color: var(--text-primary);
    margin: 0;
    min-height: 100vh;
}

.main-content {
    margin-left: 222px;
    width: 80%;
    margin-top: 20px;
    padding: 30px;
    transition: margin-left 0.3s ease;
}

@media (max-width: 1024px) {
    .main-content { margin-left: 0; width: 100%; padding: 15px; }
}

/* Header Section */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}
.page-title h2 { font-size: 26px; font-weight: 800; color: var(--text-primary); margin: 0; }
.page-title p { color: var(--text-muted); margin: 5px 0 0 0; font-size: 14px; }

.btn-primary {
    padding: 10px 20px;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex; align-items: center; gap: 8px;
    box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.3);
}
.btn-primary:hover { background: var(--primary-dark); transform: translateY(-2px); }

/* Stats Row */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}
.stat-card {
    background: var(--surface);
    border: 1px solid var(--header-border);
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    backdrop-filter: blur(10px);
}
.stat-icon {
    width: 54px; height: 54px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px;
}

/* Glass Card */
.glass-card {
    background: var(--surface);
    border: 1px solid var(--header-border);
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05);
    backdrop-filter: blur(12px);
}

/* Table Design */
.table-container { overflow-x: auto; }
.modern-table { width: 100%; border-collapse: separate; border-spacing: 0; }
.modern-table th {
    background: var(--surface);
    padding: 14px 18px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid var(--header-border);
}
.modern-table td {
    padding: 18px;
    font-size: 14px;
    border-bottom: 1px solid var(--header-border);
    color: var(--text-primary);
}
.modern-table tr:hover td { background: var(--body-bg); }

/* Modals */
.modal-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}
.modal-content {
    background: var(--surface);
    width: 90%;
    max-width: 600px;
    border-radius: 24px;
    padding: 40px;
    position: relative;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    border: 1px solid var(--header-border);
}
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
.modal-title { font-size: 20px; font-weight: 800; color: var(--text-primary); }
.close-modal { font-size: 24px; cursor: pointer; color: var(--text-muted); transition: color 0.2s; }
.close-modal:hover { color: #ef4444; }

.form-group { margin-bottom: 20px; }
.form-label { display: block; font-size: 13px; font-weight: 700; color: var(--text-muted); margin-bottom: 8px; }
.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--header-border);
    border-radius: 12px;
    font-size: 14px;
    background: var(--body-bg);
    color: var(--text-primary);
    transition: all 0.2s;
}
.form-control:focus {
    border-color: var(--primary);
    background: var(--surface);
    outline: none;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}

.action-btn {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    border: none; cursor: pointer;
    transition: all 0.2s;
    background: var(--surface);
    border: 1px solid var(--header-border);
    color: var(--text-muted);
}
.btn-view:hover { background: var(--primary); color: white; border-color: var(--primary); }
.btn-delete:hover { background: #dc2626; color: white; border-color: #dc2626; }

/* Alerts */
.alert {
    padding: 16px 20px;
    border-radius: 14px;
    margin-bottom: 25px;
    display: flex; align-items: center; gap: 12px;
    font-weight: 500;
}
.alert-success { background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }
.alert-error { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }

/* Theme-Aware Date Filter */
.date-filter-group {
    display: flex; align-items: center; gap: 10px;
    background: var(--surface); /* Theme aware */
    padding: 5px 15px; border-radius: 12px; 
    border: 1px solid var(--header-border); /* Theme aware */
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}

.date-label {
    font-size: 11px; font-weight: 700; 
    color: var(--text-muted); 
    text-transform: uppercase;
}

.mini-date-input {
    padding: 4px 8px; 
    font-size: 12px; width: 130px; height: auto; 
    border: none; border-radius: 6px;
    background: var(--body-bg); /* Theme aware */
    color: var(--text-primary); /* Theme aware */
    font-family: inherit;
    color-scheme: light dark; /* Browser native dark mode for calendar */
}

.mini-date-input:focus {
    outline: 2px solid var(--primary);
    background: var(--surface);
}

.date-separator {
    width: 1px; height: 20px; 
    background: var(--header-border); /* Theme aware */
}

/* Search & Bulk Actions Theme Support */
.search-icon { position: absolute; left: 15px; top: 14px; color: var(--text-muted); }

.search-control {
    width: 100%;
    padding: 12px 16px 12px 45px; /* Left padding for icon */
    height: 45px;
    border: 1px solid var(--header-border);
    border-radius: 12px;
    background: var(--surface); /* White in Light, Dark Slate in Dark */
    color: var(--text-primary);
    font-size: 14px;
    outline: none;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); /* Subtle shadow */
    transition: all 0.2s;
}
.search-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}

.bulk-actions-bar {
    display: none; align-items: center; gap: 12px;
    background: rgba(239, 68, 68, 0.1); /* Light red tint */
    padding: 6px 15px; border-radius: 12px;
    border: 1px solid rgba(239, 68, 68, 0.2);
}
.bulk-count { font-size: 13px; font-weight: 700; color: #ef4444; }

.btn-bulk-delete {
    padding: 6px 12px; border-radius: 8px; font-size: 12px; 
    display: flex; align-items: center; gap: 6px; 
    background: #dc2626; color: white; border: none; cursor: pointer;
    transition: background 0.2s;
}
.btn-bulk-delete:hover { background: #b91c1c; }
</style>

<div class="main-content">
    <div class="page-header">
        <div class="page-title">
            <h2>Audience & Subscribers</h2>
            <p>Manage your newsletter community and outbound updates</p>
        </div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <!-- Date Filter for Reports -->
            <div class="date-filter-group">
                <div style="display: flex; align-items: center; gap: 5px;">
                    <label class="date-label">From:</label>
                    <input type="date" id="report_start" class="mini-date-input">
                </div>
                <div class="date-separator"></div>
                <div style="display: flex; align-items: center; gap: 5px;">
                    <label class="date-label">To:</label>
                    <input type="date" id="report_end" class="mini-date-input">
                </div>
                <div class="date-separator"></div>
                <div style="display: flex; gap: 5px;">
                    <button onclick="generateFilteredReport('pdf')" class="action-btn" style="background: transparent; color: #ef4444;" title="Export PDF">
                        <i class="fas fa-file-pdf"></i>
                    </button>
                    <button onclick="generateFilteredReport('excel')" class="action-btn" style="background: transparent; color: #10b981;" title="Export Excel">
                        <i class="fas fa-file-excel"></i>
                    </button>
                </div>
            </div>

            <button class="btn-primary" id="openSendModal">
                <i class="fas fa-paper-plane"></i> Send Global Update
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon" style="background: #e0e7ff; color: #4338ca;"><i class="fas fa-users"></i></div>
            <div class="stat-content">
                <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Total Active</div>
                <div style="font-size: 28px; font-weight: 800; color: #0f172a;">{{ $subscribers->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #dcfce7; color: #15803d;"><i class="fas fa-user-plus"></i></div>
            <div class="stat-content">
                <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">New Today</div>
                <div style="font-size: 28px; font-weight: 800; color: #0f172a;">{{ $subscribers->where('created_at', '>=', now()->subDay())->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #fef3c7; color: #d97706;"><i class="fas fa-chart-line"></i></div>
            <div class="stat-content">
                <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Growth Rate</div>
                <div style="font-size: 28px; font-weight: 800; color: #0f172a;">+{{ round(($subscribers->where('created_at', '>=', now()->subWeek())->count() / max($subscribers->count(), 1)) * 100, 1) }}%</div>
            </div>
        </div>
    </div>

    <!-- SEARCH & BULK ACTIONS BAR -->
    <div class="action-bar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 15px;">
        <div style="flex: 1; position: relative;">
            <i class="fas fa-search search-icon"></i>
            <form action="{{ route('admin.subscribers.index') }}" method="GET">
                <input type="text" name="search" class="search-control" placeholder="Search by email address..." value="{{ request('search') }}">
            </form>
        </div>
        
        <div id="bulk-actions" class="bulk-actions-bar" style="display: none;">
            <span class="bulk-count"><span id="selected-count">0</span> Selected</span>
            <button onclick="bulkDelete()" class="btn-bulk-delete">
                <i class="fas fa-trash-alt"></i> Batch Delete
            </button>
        </div>
    </div>

    <div class="glass-card">
        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="select-all" style="width: 18px; height: 18px; cursor: pointer;">
                        </th>
                        <th>Subscriber ID</th>
                        <th>Email Address</th>
                        <th>Subscription Date</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscribers as $subscriber)
                    <tr>
                        <td>
                            <input type="checkbox" class="subscriber-checkbox" value="{{ $subscriber->id }}" style="width: 17px; height: 17px; cursor: pointer;">
                        </td>
                        <td style="font-weight: 700; color: var(--primary);">#SUB-{{ str_pad($subscriber->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td style="font-weight: 600;">{{ $subscriber->email }}</td>
                        <td>
                            <i class="far fa-calendar-alt" style="margin-right: 6px; opacity: 0.6; color: var(--secondary);"></i>
                            {{ $subscriber->created_at->format('M d, Y') }}
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <button class="action-btn btn-view" title="Quick View" 
                                    onclick="openViewModal({{ $subscriber->id }}, '{{ $subscriber->email }}', '{{ $subscriber->created_at->format('M d, Y H:i') }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <form action="{{ route('admin.subscribers.destroy', $subscriber->id) }}" method="POST" onsubmit="return confirm('Immediately remove this subscriber?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete" title="Remove Subscriber">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 60px; color: #94a3b8;">
                            <div style="font-size: 40px; margin-bottom: 15px; opacity: 0.2;"><i class="fas fa-search"></i></div>
                            No subscribers found matching your search criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- All your Modals and Scripts below -->
@include('admin.subscribers.modals')

<script>
// Bulk Selection Logic
const selectAll = document.getElementById('select-all');
const checkboxes = document.querySelectorAll('.subscriber-checkbox');
const bulkActions = document.getElementById('bulk-actions');
const selectedCount = document.getElementById('selected-count');

function generateFilteredReport(type) {
    const start = document.getElementById('report_start').value;
    const end = document.getElementById('report_end').value;

    if (!start || !end) {
        alert('Please select both Start and End dates to generate the filtered report.');
        return;
    }

    let url = type === 'pdf'
        ? "{{ route('admin.subscribers.export.pdf') }}"
        : "{{ route('admin.subscribers.export.excel') }}";

    const params = new URLSearchParams();
    params.append('start_date', start);
    params.append('end_date', end);

    window.open(url + '?' + params.toString(), '_blank');
}

function updateBulkUI() {
    const checkedCount = document.querySelectorAll('.subscriber-checkbox:checked').length;
    bulkActions.style.display = checkedCount > 0 ? 'flex' : 'none';
    selectedCount.innerText = checkedCount;
}

if(selectAll) {
    selectAll.onchange = (e) => {
        checkboxes.forEach(cb => cb.checked = e.target.checked);
        updateBulkUI();
    };
}

checkboxes.forEach(cb => {
    cb.onchange = updateBulkUI;
});

async function bulkDelete() {
    if (!confirm('Are you sure you want to delete all selected subscribers? This cannot be undone.')) return;
    
    const ids = Array.from(document.querySelectorAll('.subscriber-checkbox:checked')).map(cb => cb.value);
    
    try {
        const response = await fetch("{{ route('admin.subscribers.bulk-destroy') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids })
        });
        
        const result = await response.json();
        if (result.success) {
            location.reload();
        } else {
            alert('Something went wrong. Please try again.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to perform bulk delete.');
    }
}

// Modal and existing JS...
const sendModal = document.getElementById("sendModal");
const viewModal = document.getElementById("viewModal");

function openSendModal() { sendModal.style.display = "flex"; }
function closeSendModal() { sendModal.style.display = "none"; }
if(document.getElementById("openSendModal")) {
    document.getElementById("openSendModal").onclick = openSendModal;
}
if(document.getElementById("closeSendModal")) {
    document.getElementById("closeSendModal").onclick = closeSendModal;
}

function openViewModal(id, email, created) {
    document.getElementById("viewId").innerText = "#SUB-" + String(id).padStart(4, '0');
    document.getElementById("viewEmail").innerText = email;
    document.getElementById("viewCreated").innerText = created;
    viewModal.style.display = "flex";
}
function closeViewModal() { viewModal.style.display = "none"; }
if(document.getElementById("closeViewModal")) {
    document.getElementById("closeViewModal").onclick = closeViewModal;
}

window.onclick = (e) => {
    if (e.target == sendModal) closeSendModal();
    if (e.target == viewModal) closeViewModal();
}
</script>

