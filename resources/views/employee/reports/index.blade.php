@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-file-invoice" style="color: var(--primary);"></i> Report Center</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Draft, submit, and manage your operational activity reports.</p>
        </div>
        <div class="header-actions">
            <button class="action-btn btn-primary" onclick="createNewReport()" style="width: auto; padding: 0 25px; gap: 10px; border-radius: 12px; height: 48px;">
                <i class="fas fa-plus"></i> Submit Log
            </button>
        </div>
    </div>

    {{-- Document Gallery --}}
    <div style="margin-top: 3rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: var(--dark);">Document Repository</h3>
            <div class="mega-search" style="max-width: 300px; flex: 1; background: var(--light);">
                <i class="fas fa-search" style="color: var(--secondary);"></i>
                <input type="text" id="reportSearch" placeholder="Find reports..." class="search-input" style="background: transparent; border: none; padding: 10px;">
            </div>
        </div>

        <div class="document-grid">
            @forelse($reports as $report)
                <div class="doc-card report-row" onclick="viewReportDetails({{ $report->id }})">
                    <div class="doc-header">
                        <div class="doc-type-icon"><i class="fas fa-file-signature"></i></div>
                        <div class="doc-status-ribbon status-{{ $report->status }}">{{ ucfirst($report->status) }}</div>
                    </div>
                    <div class="doc-body">
                        <h4 class="doc-title">{{ $report->title }}</h4>
                        <p class="doc-excerpt">{{ Str::limit($report->description, 120) }}</p>
                    </div>
                    <div class="doc-footer">
                        <div class="doc-meta">
                            <span class="doc-id">#REP-{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
                            <span class="doc-date"><i class="far fa-clock"></i> {{ $report->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="doc-action-hint">Open Document <i class="fas fa-arrow-right"></i></div>
                    </div>
                </div>
            @empty
                <div class="empty-repo">
                    <div class="empty-box">
                        <i class="fas fa-archive"></i>
                        <p>Repository is currently empty.</p>
                        <button class="btn-primary" onclick="createNewReport()" style="margin-top: 20px;">Initialize First Report</button>
                    </div>
                </div>
            @endforelse
        </div>

        <div style="margin-top: 40px; border-top: 1px solid var(--glass-border); padding-top: 25px;">
            {{ $reports->links() }}
        </div>
    </div>
</div>

{{-- Report Details Modal --}}
<div id="reportModal" class="modal-overlay">
    <div class="pro-modal" style="max-width: 650px;">
        <div class="modal-header">
            <h3><i class="fas fa-file-contract" style="color: var(--primary);"></i> Activity Report Brief</h3>
            <button class="close-modal" onclick="closeReport()">&times;</button>
        </div>
        <div class="modal-body" id="reportContent">
             <div style="text-align:center; padding: 2rem;"><i class="fas fa-spinner fa-spin"></i> Loading...</div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeReport()">Dismiss View</button>
        </div>
    </div>
</div>

<style>
    .status-pill { padding: 6px 14px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; }
    .status-active { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-pending { background: rgba(99, 102, 241, 0.1); color: #6366f1; }

    /* Document Gallery Engine */
    .document-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; }
    .doc-card { background: var(--white); border-radius: 20px; overflow: hidden; position: relative; border: 1px solid var(--glass-border); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); cursor: pointer; display: flex; flex-direction: column; }
    .doc-card:hover { transform: translateY(-10px) scale(1.02); box-shadow: 0 30px 60px rgba(0,0,0,0.1); border-color: var(--primary); }
    
    .doc-header { padding: 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(0,0,0,0.03); }
    .doc-type-icon { width: 40px; height: 40px; border-radius: 12px; background: rgba(99, 102, 241, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .doc-status-ribbon { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; padding: 4px 12px; border-radius: 20px; }
    .doc-status-ribbon.status-approved { background: #10b981; color: white; }
    .doc-status-ribbon.status-pending { background: #f59e0b; color: white; }

    .doc-body { padding: 25px; flex: 1; }
    .doc-title { font-size: 1.1rem; font-weight: 800; color: var(--dark); margin: 0 0 10px; line-height: 1.3; }
    .doc-excerpt { font-size: 0.9rem; color: var(--secondary); line-height: 1.6; margin: 0; }

    .doc-footer { padding: 20px 25px; background: var(--light); display: flex; justify-content: space-between; align-items: center; }
    .doc-meta { display: flex; flex-direction: column; gap: 2px; }
    .doc-id { font-size: 0.7rem; font-weight: 700; color: var(--primary); font-family: monospace; }
    .doc-date { font-size: 0.75rem; color: var(--secondary); font-weight: 600; }
    .doc-action-hint { font-size: 0.75rem; font-weight: 800; color: var(--dark); opacity: 0; transform: translateX(-10px); transition: 0.3s; }
    .doc-card:hover .doc-action-hint { opacity: 1; transform: translateX(0); }

    .empty-repo { grid-column: 1 / -1; padding: 100px 0; display: flex; justify-content: center; }
    .empty-box { text-align: center; max-width: 400px; }
    .empty-box i { font-size: 4rem; color: var(--secondary); opacity: 0.1; margin-bottom: 20px; display: block; }
    .empty-box p { color: var(--secondary); font-weight: 600; font-size: 1.1rem; }

    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(10px); z-index: 10001; align-items: center; justify-content: center; }
    .pro-modal { background: var(--white); border-radius: 30px; overflow: hidden; animation: popIn 0.4s cubic-bezier(0.18, 0.89, 0.32, 1.28); width: 100%; box-shadow: 0 50px 100px rgba(0,0,0,0.3); }
    @keyframes popIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .modal-header { padding: 30px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center; }
    .close-modal { background: none; border: none; font-size: 2rem; color: var(--secondary); cursor: pointer; height: 40px; width: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .close-modal:hover { background: var(--light); }
    .modal-body { padding: 40px; max-height: 60vh; overflow-y: auto; }
    .modal-footer { padding: 25px 40px; background: var(--light); display: flex; justify-content: flex-end; }
    .btn-cancel { padding: 12px 24px; border-radius: 12px; background: var(--white); border: 1px solid var(--glass-border); color: var(--secondary); font-weight: 700; cursor: pointer; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    async function createNewReport() {
        const { value: formValues } = await Swal.fire({
            title: 'New Activity Log',
            html:
                '<div style="text-align:left;">' +
                '<label style="font-size:0.8rem; font-weight:700; color:var(--secondary);">Report Subject</label>' +
                '<input id="swal-title" class="swal2-input" placeholder="e.g. End of Day Pipeline Update" style="margin-top:5px; width:100%;">' +
                '<label style="font-size:0.8rem; font-weight:700; color:var(--secondary); margin-top:20px; display:block;">Detailed Description</label>' +
                '<textarea id="swal-desc" class="swal2-textarea" placeholder="Describe the core outcomes and challenges..." style="margin-top:5px; width:100%; border-radius:12px;"></textarea>' +
                '</div>',
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Submit Manifest',
            confirmButtonColor: 'var(--primary)',
            preConfirm: () => {
                const title = document.getElementById('swal-title').value;
                const description = document.getElementById('swal-desc').value;
                if (!title || !description) {
                    Swal.showValidationMessage('Both title and description are critical.');
                }
                return { title: title, description: description }
            }
        });

        if (formValues) {
            try {
                const response = await fetch("{{ route('employee.reports.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formValues)
                });
                if (response.ok) {
                    Swal.fire({ icon: 'success', title: 'Manifest Archived', text: 'Report has been successfully submitted.', timer: 1500, showConfirmButton: false })
                    .then(() => location.reload());
                }
            } catch (e) {
                Swal.fire('Error', 'Archive process failed.', 'error');
            }
        }
    }

    async function viewReportDetails(id) {
        const modal = document.getElementById('reportModal');
        const content = document.getElementById('reportContent');
        const footer = modal.querySelector('.modal-footer');
        modal.style.display = 'flex';
        content.innerHTML = '<div style="text-align:center; padding: 2rem;"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
        footer.innerHTML = '<button class="btn-cancel" onclick="closeReport()">Dismiss View</button>';

        try {
            const res = await fetch(`/employee/reports/${id}/details`);
            const json = await res.json();
            if (json.success) {
                const r = json.data;
                content.innerHTML = `
                    <div style="margin-bottom:25px;">
                        <h2 style="margin:0; font-size:1.5rem; color:var(--dark);">${r.title}</h2>
                        <div style="margin-top:8px; display:flex; gap:12px; align-items:center;">
                            <span class="status-pill status-${r.status == 'approved' ? 'active' : 'pending'}">${r.status}</span>
                            <span style="font-size:0.8rem; color:var(--secondary); font-weight:600;"><i class="far fa-calendar-alt"></i> ${r.created_at}</span>
                        </div>
                    </div>
                    <div>
                        <label style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary); font-weight: 800;">Report Content</label>
                        <div style="margin-top: 15px; background: var(--light); padding: 30px; border-radius: 24px; line-height: 1.8; color: var(--dark); white-space: pre-wrap; border: 1px solid var(--glass-border);">
                            ${r.description}
                        </div>
                    </div>
                `;
                
                // Add Edit/Delete buttons to footer
                footer.innerHTML = `
                    <button class="btn-cancel" onclick="deleteReport(${r.id})" style="color: #ef4444; margin-right: auto;"><i class="far fa-trash-alt"></i> Discard Report</button>
                    <button class="btn-cancel" onclick="closeReport()">Dismiss View</button>
                    <button class="action-btn btn-primary" onclick="editReport(${r.id}, '${r.title.replace(/'/g, "\\'")}', \`${r.description.replace(/`/g, "\\`")}\`)" style="width: auto; padding: 0 20px; border-radius: 10px;">
                        <i class="fas fa-edit"></i> Edit Record
                    </button>
                `;
            }
        } catch (e) {
            content.innerHTML = '<p style="color:red; text-align:center;">Failed to resolve document.</p>';
        }
    }

    async function deleteReport(id) {
        const result = await Swal.fire({
            title: 'Discard Report?',
            text: "This action cannot be undone and will remove the record from repository.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: 'var(--secondary)',
            confirmButtonText: 'Yes, Delete!'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/employee/reports/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const res = await response.json();
                if (res.success) {
                    Swal.fire('Deleted!', res.message, 'success').then(() => location.reload());
                }
            } catch (e) {
                Swal.fire('Error', 'Deletion failed.', 'error');
            }
        }
    }

    async function editReport(id, currentTitle, currentDesc) {
        const { value: formValues } = await Swal.fire({
            title: 'Refine Report Data',
            html:
                `<input id="swal-title" class="swal2-input" placeholder="Report Title" value="${currentTitle}">` +
                `<textarea id="swal-desc" class="swal2-textarea" placeholder="Detailed Description" style="height: 150px;">${currentDesc}</textarea>`,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Update Record',
            preConfirm: () => {
                return {
                    title: document.getElementById('swal-title').value,
                    description: document.getElementById('swal-desc').value
                }
            }
        });

        if (formValues) {
            try {
                const response = await fetch(`/employee/reports/${id}`, {
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
                    Swal.fire('Updated!', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Failed', res.message, 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Update process failed.', 'error');
            }
        }
    }

    function closeReport() {
        document.getElementById('reportModal').style.display = 'none';
    }

    document.getElementById('reportSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.report-row');
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    window.onclick = (e) => {
        if (e.target === document.getElementById('reportModal')) closeReport();
    }
</script>
