@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-file-invoice" style="color: var(--primary);"></i> Employee Reports</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Review and manage operational reports submitted by
                employees.</p>
        </div>
    </div>

    <div style="margin-top: 2rem;">
        <div class="table-container">
            <table class="pro-table">
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Employee</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>#REP-{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="primary-text font-weight-bold">{{ $report->user->name ?? 'Unknown' }}</td>
                            <td>{{ $report->title }}</td>
                            <td>{{ $report->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="status-chip status-{{ $report->status }}">{{ ucfirst($report->status) }}</span>
                            </td>
                            <td>
                                <button class="icon-btn" onclick="viewReportDetails({{ $report->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding: 40px; color: var(--secondary);">
                                <i class="fas fa-folder-open"
                                    style="font-size: 2rem; display:block; margin-bottom: 10px; opacity:0.5;"></i>
                                No reports found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 25px;">
            {{ $reports->links() }}
        </div>
    </div>
</div>

{{-- Report Details Modal --}}
<div id="reportModal" class="modal-overlay">
    <div class="pro-modal" style="max-width: 600px;">
        <div class="modal-header">
            <h3><i class="fas fa-clipboard-list" style="color: var(--primary);"></i> Report Details</h3>
            <button class="close-modal" onclick="closeReport()">&times;</button>
        </div>
        <div class="modal-body" id="reportContent">
            <div style="text-align:center; padding: 2rem;"><i class="fas fa-spinner fa-spin"></i> Loading...</div>
        </div>
        <div class="modal-footer" id="modalFooter">
            <button class="btn-cancel" onclick="closeReport()">Close</button>
        </div>
    </div>
</div>

<style>
    /* Status Colors */
    .status-chip {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .status-reviewed {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .status-approved {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .status-rejected {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    /* Modal Overlay */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
        z-index: 10001;
        align-items: center;
        justify-content: center;
    }

    .pro-modal {
        background: var(--white);
        width: 90%;
        border-radius: 20px;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        animation: popIn 0.3s ease-out;
    }

    @keyframes popIn {
        from {
            transform: scale(0.95);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 20px 30px;
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-body {
        padding: 30px;
        line-height: 1.6;
        color: var(--dark);
        max-height: 70vh;
        overflow-y: auto;
    }

    .modal-footer {
        padding: 20px 30px;
        background: var(--light);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-cancel {
        padding: 10px 20px;
        border-radius: 8px;
        border: 1px solid var(--glass-border);
        background: var(--white);
        color: var(--secondary);
        cursor: pointer;
        font-weight: 600;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--secondary);
    }

    .action-badge {
        margin-top: 10px;
        display: inline-block;
        padding: 5px 10px;
        background: var(--light);
        border-radius: 5px;
        font-size: 0.8rem;
        font-weight: 600;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    async function viewReportDetails(id) {
        const modal = document.getElementById('reportModal');
        const content = document.getElementById('reportContent');
        const footer = document.getElementById('modalFooter');

        modal.style.display = 'flex';
        content.innerHTML = '<div style="text-align:center; padding: 2rem;"><i class="fas fa-spinner fa-spin fa-2x" style="color:var(--primary);"></i></div>';

        // Reset Footer
        footer.innerHTML = '<button class="btn-cancel" onclick="closeReport()">Close</button>';

        try {
            const res = await fetch(`/admin/employee/reports/${id}/details`);
            const json = await res.json();
            if (json.success) {
                const r = json.data;

                content.innerHTML = `
                    <div style="margin-bottom: 20px;">
                        <h2 style="font-size:1.3rem; margin:0 0 5px 0; color:var(--primary);">${r.title}</h2>
                        <div style="font-size:0.85rem; color:var(--secondary);">
                            Submitted by <strong>${r.employee}</strong> on ${r.created_at}
                            <span class="status-chip status-${r.status}" style="margin-left:10px;">${r.status}</span>
                        </div>
                    </div>
                    <div style="background:var(--light); padding:20px; border-radius:12px; border:1px solid var(--glass-border);">
                        ${r.description}
                    </div>
                `;

                // Add Status Actions
                footer.innerHTML = `
                    <div style="margin-right:auto;">
                        <select onchange="updateStatus(${r.id}, this.value)" style="padding:8px; border-radius:8px; border:1px solid #ddd;">
                            <option value="" disabled selected>Mark Status As...</option>
                            <option value="reviewed">Reviewed</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <button class="btn-cancel" onclick="closeReport()">Close</button>
                `;
            }
        } catch (e) {
            content.innerHTML = '<p style="color:red; text-align:center;">Failed to load report details.</p>';
        }
    }

    async function updateStatus(id, newStatus) {
        if (!newStatus) return;

        try {
            const response = await fetch(`/admin/employee/reports/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            });

            const res = await response.json();
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated',
                    text: res.message,
                    timer: 1000,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', 'Failed to update status', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Network error occurred', 'error');
        }
    }

    function closeReport() {
        document.getElementById('reportModal').style.display = 'none';
    }

    window.onclick = function (event) {
        if (event.target === document.getElementById('reportModal')) closeReport();
    }
</script>