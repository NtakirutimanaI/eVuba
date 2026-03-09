@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-file-invoice" style="color: var(--primary);"></i> Report Center</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Draft, submit, and manage your operational activity
                reports.</p>
        </div>
        <div class="header-actions">
            <button class="action-btn btn-primary" onclick="createNewReport()"
                style="width: auto; padding: 0 25px; gap: 10px; border-radius: 12px; height: 48px;">
                <i class="fas fa-plus"></i> Submit Log
            </button>
        </div>
    </div>

    {{-- Document Gallery --}}
    <div style="margin-top: 3rem;">
        <div class="gallery-header"
            style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: var(--dark);">Document Repository</h3>
            <div class="mega-search"
                style="flex: 1; max-width: 400px; min-width: 250px; background: var(--light); display: flex; align-items: center; border-radius: 12px; padding: 0 15px;">
                <i class="fas fa-search" style="color: var(--secondary);"></i>
                <input type="text" id="reportSearch" placeholder="Find reports..." class="search-input"
                    style="flex: 1; background: transparent; border: none; padding: 12px 10px; font-size: 0.95rem; color: var(--text-main); outline: none;">
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
                            <span class="doc-date"><i class="far fa-clock"></i>
                                {{ $report->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="doc-action-hint">Open Document <i class="fas fa-arrow-right"></i></div>
                    </div>
                </div>
            @empty
                <div class="empty-repo">
                    <div class="empty-box">
                        <i class="fas fa-archive"></i>
                        <p>Repository is currently empty.</p>
                        <button class="btn-primary" onclick="createNewReport()" style="margin-top: 20px;">Initialize First
                            Report</button>
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
    .status-pill {
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .status-active {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    /* Document Gallery Engine */
    .document-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .doc-card {
        background: var(--white);
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        border: 1px solid var(--glass-border);
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
    }

    .doc-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border-color: var(--primary);
    }

    .doc-header {
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--glass-border);
    }

    .doc-type-icon {
        width: 45px;
        height: 45px;
        border-radius: 14px;
        background: rgba(99, 102, 241, 0.08);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        transition: 0.3s;
    }

    .doc-card:hover .doc-type-icon {
        background: var(--primary);
        color: white;
        transform: rotate(-5deg);
    }

    .doc-status-ribbon {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }

    .doc-status-ribbon.status-approved {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
    }

    .doc-status-ribbon.status-pending {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
    }

    .doc-body {
        padding: 25px;
        flex: 1;
    }

    .doc-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--text-main);
        margin: 0 0 12px;
        line-height: 1.4;
    }

    .doc-excerpt {
        font-size: 0.95rem;
        color: var(--text-muted);
        line-height: 1.6;
        margin: 0;
    }

    .doc-footer {
        padding: 20px 25px;
        background: var(--bg-main);
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid var(--glass-border);
    }

    .doc-meta {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .doc-id {
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--primary);
        font-family: 'Courier New', Courier, monospace;
        letter-spacing: 1px;
    }

    .doc-date {
        font-size: 0.8rem;
        color: var(--secondary);
        font-weight: 600;
    }

    .doc-action-hint {
        font-size: 0.8rem;
        font-weight: 800;
        color: var(--text-main);
        opacity: 0;
        transform: translateX(-15px);
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .doc-card:hover .doc-action-hint {
        opacity: 1;
        transform: translateX(0);
    }

    .empty-repo {
        grid-column: 1 / -1;
        padding: 80px 20px;
        display: flex;
        justify-content: center;
    }

    .empty-box {
        text-align: center;
        max-width: 400px;
        padding: 40px;
        background: var(--white);
        border-radius: 24px;
        border: 1px dashed var(--glass-border);
    }

    .empty-box i {
        font-size: 3.5rem;
        color: var(--secondary);
        opacity: 0.15;
        margin-bottom: 20px;
        display: block;
    }

    .empty-box p {
        color: var(--text-muted);
        font-weight: 600;
        font-size: 1.05rem;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(8px);
        z-index: 10001;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .pro-modal {
        background: var(--white);
        border-radius: 24px;
        overflow: hidden;
        animation: popIn 0.3s cubic-bezier(0.18, 0.89, 0.32, 1.28) forwards;
        width: 100%;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.2);
    }

    @keyframes popIn {
        from {
            transform: scale(0.95) translateY(20px);
            opacity: 0;
        }

        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 25px 30px;
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--bg-main);
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.3rem;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 800;
    }

    .close-modal {
        background: var(--white);
        border: 1px solid var(--glass-border);
        font-size: 1.5rem;
        color: var(--secondary);
        cursor: pointer;
        height: 38px;
        width: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .close-modal:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
        transform: scale(1.05);
    }

    .modal-body {
        padding: 30px;
        overflow-y: auto;
        flex: 1;
    }

    .modal-footer {
        padding: 20px 30px;
        background: var(--bg-main);
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: flex-end;
        border-top: 1px solid var(--glass-border);
    }

    .btn-cancel {
        padding: 12px 24px;
        border-radius: 12px;
        background: var(--white);
        border: 1px solid var(--glass-border);
        color: var(--text-main);
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-cancel:hover {
        background: var(--light);
        border-color: var(--secondary);
    }

    @media (max-width: 768px) {
        .pro-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .gallery-header {
            flex-direction: column;
            align-items: stretch;
        }

        .mega-search {
            max-width: 100%;
        }

        .modal-footer {
            justify-content: stretch;
            flex-direction: column-reverse;
        }

        .modal-footer button {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    async function createNewReport() {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const bgColor = isDark ? '#1e293b' : '#ffffff';
        const textColor = isDark ? '#f8fafc' : '#0f172a';
        const inputBg = isDark ? '#0f172a' : '#f8fafc';

        const { value: formValues } = await Swal.fire({
            title: 'New Activity Log',
            html:
                '<div style="text-align:left;">' +
                '<label style="font-size:0.85rem; font-weight:700; color:var(--text-muted);">Report Subject</label>' +
                `<input id="swal-title" class="swal2-input" placeholder="e.g. End of Day Pipeline Update" style="margin:8px 0 20px 0; width:100%; border-radius: 12px; background:${inputBg}; color:${textColor};">` +
                '<label style="font-size:0.85rem; font-weight:700; color:var(--text-muted); display:block;">Detailed Description</label>' +
                `<textarea id="swal-desc" class="swal2-textarea" placeholder="Describe the core outcomes and challenges..." style="margin:8px 0 0 0; width:100%; height:120px; border-radius:12px; background:${inputBg}; color:${textColor}; padding:15px;"></textarea>` +
                '</div>',
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Submit Manifest',
            confirmButtonColor: 'var(--primary)',
            background: bgColor,
            color: textColor,
            didOpen: () => {
                Swal.getPopup().style.borderRadius = '24px';
            },
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Manifest Archived',
                        text: 'Report has been successfully submitted.',
                        timer: 1500,
                        showConfirmButton: false,
                        background: bgColor,
                        color: textColor
                    }).then(() => location.reload());
                }
            } catch (e) {
                Swal.fire({ title: 'Error', text: 'Archive process failed.', icon: 'error', background: bgColor, color: textColor });
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
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const bgColor = isDark ? '#1e293b' : '#ffffff';
        const textColor = isDark ? '#f8fafc' : '#0f172a';
        const inputBg = isDark ? '#0f172a' : '#f8fafc';

        const { value: formValues } = await Swal.fire({
            title: 'Refine Report Data',
            html:
                '<div style="text-align:left;">' +
                '<label style="font-size:0.85rem; font-weight:700; color:var(--text-muted);">Report Subject</label>' +
                `<input id="swal-title" class="swal2-input" placeholder="Report Title" value="${currentTitle}" style="margin:8px 0 20px 0; width:100%; border-radius: 12px; background:${inputBg}; color:${textColor};">` +
                '<label style="font-size:0.85rem; font-weight:700; color:var(--text-muted); display:block;">Detailed Description</label>' +
                `<textarea id="swal-desc" class="swal2-textarea" placeholder="Detailed Description" style="height: 150px; margin:8px 0 0 0; width:100%; border-radius:12px; background:${inputBg}; color:${textColor}; padding:15px;">${currentDesc}</textarea>` +
                '</div>',
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Update Record',
            confirmButtonColor: 'var(--primary)',
            background: bgColor,
            color: textColor,
            didOpen: () => {
                Swal.getPopup().style.borderRadius = '24px';
            },
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

    document.getElementById('reportSearch').addEventListener('keyup', function () {
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