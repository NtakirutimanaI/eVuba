@include('layouts.header')
@include('layouts.sidebar')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard-wrapper" style="padding: 1.5rem;">
    <!-- Premium Header -->
    <div class="pro-header" style="margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.75rem; letter-spacing: -1px;">Support Intelligence Hub</h1>
            <p style="color: var(--secondary); font-size: 0.9rem; margin-top: 0.25rem;">Monitor customer inquiries and
                manage resolution cycles</p>
        </div>
        <div class="header-actions">
            <div
                style="font-size: 0.75rem; color: var(--secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                <i class="fas fa-signal" style="color: var(--success); margin-right: 0.5rem;"></i> System Live
            </div>
        </div>
    </div>

    <!-- Intelligence Widgets -->
    <div class="dashboard-grid"
        style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <div class="pro-card" style="padding: 1.25rem;">
            <div class="stat-widget" style="gap: 1rem;">
                <div class="stat-icon"
                    style="width: 45px; height: 45px; font-size: 1.15rem; background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="stat-info">
                    <div class="label" style="font-size: 0.75rem;">All Tickets</div>
                    <div class="value" style="font-size: 1.4rem;">{{ number_format($stats['total']) }}</div>
                </div>
            </div>
        </div>

        <div class="pro-card" style="padding: 1.25rem;">
            <div class="stat-widget" style="gap: 1rem;">
                <div class="stat-icon"
                    style="width: 45px; height: 45px; font-size: 1.15rem; background: linear-gradient(135deg, #ef4444, #f87171);">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-info">
                    <div class="label" style="font-size: 0.75rem;">Open Issues</div>
                    <div class="value" style="font-size: 1.4rem; color: var(--danger);">
                        {{ number_format($stats['open']) }}</div>
                </div>
            </div>
        </div>

        <div class="pro-card" style="padding: 1.25rem;">
            <div class="stat-widget" style="gap: 1rem;">
                <div class="stat-icon"
                    style="width: 45px; height: 45px; font-size: 1.15rem; background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="fas fa-sync"></i>
                </div>
                <div class="stat-info">
                    <div class="label" style="font-size: 0.75rem;">Processing</div>
                    <div class="value" style="font-size: 1.4rem; color: var(--warning);">
                        {{ number_format($stats['processing']) }}</div>
                </div>
            </div>
        </div>

        <div class="pro-card" style="padding: 1.25rem;">
            <div class="stat-widget" style="gap: 1rem;">
                <div class="stat-icon"
                    style="width: 45px; height: 45px; font-size: 1.15rem; background: linear-gradient(135deg, #10b981, #34d399);">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="stat-info">
                    <div class="label" style="font-size: 0.75rem;">Resolved</div>
                    <div class="value" style="font-size: 1.4rem; color: var(--success);">
                        {{ number_format($stats['resolved']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Ledger -->
    <div class="pro-card"
        style="padding: 0; overflow: hidden; border: 1px solid var(--glass-border); background: var(--white);">
        <div
            style="padding: 1.5rem; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; flex-wrap: wrap; background: var(--glass);">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div
                    style="width: 35px; height: 35px; border-radius: 10px; background: rgba(99, 102, 241, 0.1); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-list-ul" style="color: var(--primary);"></i>
                </div>
                <h3 style="font-size: 1.1rem; margin: 0; font-weight: 700;">Inquiry Registry</h3>
            </div>

            <form action="{{ route('manager.support.index') }}" method="GET"
                style="display: flex; gap: 1rem; flex: 1; max-width: 700px;">
                <div class="pro-search"
                    style="flex: 1; background: var(--bg-main); border: 1px solid var(--glass-border); border-radius: 0.75rem; padding: 0.5rem 1rem; display: flex; align-items: center; transition: all 0.3s ease;">
                    <i class="fas fa-search" style="color: var(--secondary); font-size: 0.9rem;"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search ticket #, client, or subject..."
                        style="border: none; background: none; width: 100%; outline: none; font-size: 0.9rem; margin-left: 0.75rem; color: var(--dark);">
                </div>
                <select name="status" class="glass-select"
                    style="min-width: 140px; font-size: 0.85rem; padding: 0.5rem 1rem; border-radius: 0.75rem;">
                    <option value="all">All Cycles</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress
                    </option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <button type="submit" class="action-btn btn-primary"
                    style="padding: 0.5rem 1.5rem; font-size: 0.85rem; border-radius: 0.75rem; letter-spacing: 0.5px;">
                    <i class="fas fa-sync-alt"></i> REFRESH
                </button>
            </form>
        </div>

        <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">
            <table class="pro-table" style="width: 100%; border-spacing: 0;">
                <thead style="position: sticky; top: 0; z-index: 10; background: var(--white);">
                    <tr>
                        <th style="padding: 1.25rem 1.5rem; background: var(--glass);">Ticket Ref</th>
                        <th style="background: var(--glass);">Client Entity</th>
                        <th style="background: var(--glass);">Subject / Inquiry</th>
                        <th style="background: var(--glass);">Classification</th>
                        <th style="background: var(--glass);">Status</th>
                        <th style="background: var(--glass);">Timeline</th>
                        <th style="text-align: right; padding-right: 1.5rem; background: var(--glass);">Orchestrate</th>
                    </tr>
                </thead>
                <tbody style="background: var(--white);">
                    @forelse($tickets as $ticket)
                        <tr style="transition: all 0.2s; border-bottom: 1px solid var(--glass-border);">
                            <td style="padding: 1.25rem 1.5rem;">
                                <span style="font-weight: 700; color: var(--primary);">#{{ $ticket->ticket_no }}</span>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: var(--dark);">
                                    {{ optional($ticket->customer)->name ?? optional($ticket->submitter)->name ?? 'Guest User' }}</div>
                                <div style="font-size: 0.75rem; color: var(--secondary);">
                                    {{ optional($ticket->customer)->email ?? 'no-digital-trace' }}</div>
                            </td>
                            <td
                                style="font-weight: 600; color: var(--dark); max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $ticket->subject }}
                            </td>
                            <td>
                                <span
                                    style="font-size: 0.75rem; font-weight: 600; color: var(--secondary); background: var(--bg-main); padding: 4px 10px; border-radius: 6px;">
                                    {{ optional($ticket->category)->name ?? 'General' }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ str_replace(' ', '-', $ticket->status) }}"
                                    style="font-size: 0.65rem; font-weight: 800; padding: 0.4rem 0.8rem;">
                                    {{ strtoupper($ticket->status) }}
                                </span>
                            </td>
                            <td style="font-weight: 600; font-size: 0.85rem; color: var(--secondary);">
                                {{ $ticket->created_at->format('M d, Y') }}</td>
                            <td style="padding-right: 1.5rem;">
                                <div style="display: flex; gap: 0.75rem; justify-content: flex-end; align-items: center;">
                                    <button class="action-btn" onclick="openTicketModal({{ $ticket->id }})"
                                        style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: rgba(99, 102, 241, 0.08); color: var(--primary); border: 1px solid rgba(99, 102, 241, 0.15);"
                                        title="View Dossier">
                                        <i class="fas fa-eye" style="font-size: 0.85rem;"></i>
                                    </button>
                                    <select onchange="managerChangeStatus({{ $ticket->id }}, this.value)"
                                        class="glass-select"
                                        style="font-size: 0.75rem; padding: 0.35rem 0.6rem; font-weight: 700; border-radius: 6px;">
                                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>OPEN</option>
                                        <option value="in-progress" {{ $ticket->status == 'in-progress' ? 'selected' : '' }}>
                                            IN-PROGRESS</option>
                                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>CLOSED
                                        </option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 5rem; color: var(--secondary);">
                                <i class="fas fa-inbox"
                                    style="font-size: 4rem; opacity: 0.05; margin-bottom: 1.5rem; display: block;"></i>
                                <h3 style="opacity: 0.5;">Registry Silent</h3>
                                <p style="font-size: 0.85rem;">No active support tickets detected in this cycle.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper"
            style="padding: 1.5rem; border-top: 1px solid var(--glass-border); display: flex; justify-content: center; background: var(--glass);">
            {{ $tickets->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
</div>

<!-- Premium Support Modal -->
<div id="ticketModal" class="modal-overlay" style="display: none;">
    <div class="modal-container"
        style="width: 90%; max-width: 1000px; height: 90vh; display: flex; flex-direction: column;">
        <div class="modal-header"
            style="background: var(--glass); border-bottom: 1px solid var(--glass-border); padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div id="modal-title-icon"
                    style="width: 40px; height: 40px; background: var(--primary); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div>
                    <h2 id="modal-subject" style="margin: 0; font-size: 1.25rem; font-weight: 800; color: var(--dark);">
                        ...</h2>
                    <div id="modal-ref-data" style="font-size: 0.8rem; color: var(--secondary); margin-top: 0.2rem;">
                        REF: #...</div>
                </div>
            </div>
            <button class="close-modal" onclick="closeTicketModal()"
                style="background: none; border: none; font-size: 1.5rem; color: var(--secondary); cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body"
            style="flex: 1; overflow-y: auto; padding: 2rem; display: grid; grid-template-columns: 1fr 320px; gap: 2rem; background: var(--white);">
            <!-- Left: Conversation & Details -->
            <div class="interaction-zone">
                <div class="pro-card" style="padding: 1.5rem; background: var(--bg-main); margin-bottom: 2rem;">
                    <h4
                        style="margin-top: 0; margin-bottom: 1rem; font-size: 0.9rem; text-transform: uppercase; color: var(--secondary);">
                        Original Directive</h4>
                    <div id="modal-description"
                        style="font-size: 1rem; line-height: 1.6; color: var(--dark); white-space: pre-wrap;"></div>
                    <div id="modal-attachment" style="margin-top: 1.5rem;"></div>
                </div>

                <h3 style="font-size: 1.1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-comments" style="color: var(--primary);"></i>
                    Resolution Thread
                </h3>

                <div id="replies-list" style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem;">
                    <!-- Replies injected here -->
                </div>

                <!-- Reply Composer -->
                <div class="reply-composer"
                    style="background: var(--bg-main); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem;">
                    <form id="replyFormManager" onsubmit="postReplyManager(event)">
                        @csrf
                        <input type="hidden" id="mgr_reply_ticket_id" name="ticket_id">
                        <textarea id="mgr_reply_message" name="message" rows="4"
                            placeholder="Draft your response here..." required
                            style="width: 100%; padding: 1rem; border: 1px solid var(--glass-border); border-radius: 0.75rem; font-size: 0.9rem; margin-bottom: 1rem; resize: none; background: var(--white); color: var(--dark);"></textarea>

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <label for="mgr_reply_attachment"
                                    style="cursor: pointer; display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: var(--secondary); font-weight: 700;">
                                    <i class="fas fa-paperclip" style="font-size: 1rem; color: var(--primary);"></i>
                                    ATTACH LOGS
                                </label>
                                <input type="file" id="mgr_reply_attachment" name="attachment" style="display: none;"
                                    onchange="updateFileName(this)">
                                <span id="file-name" style="font-size: 0.75rem; color: var(--secondary);"></span>
                            </div>
                            <button type="submit" class="action-btn btn-primary"
                                style="padding: 0.75rem 2rem; font-weight: 700;">
                                DISPATCH REPLY
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: Meta Data & Logs -->
            <div class="meta-zone">
                <div class="pro-card" style="padding: 1.25rem; margin-bottom: 1.5rem;">
                    <h4 style="margin-top: 0; margin-bottom: 1rem; font-size: 0.8rem; text-transform: uppercase;">
                        Engagement Dossier</h4>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <div
                                style="font-size: 0.7rem; color: var(--secondary); font-weight: 700; text-transform: uppercase;">
                                Client</div>
                            <div id="modal-customer" style="font-weight: 700; margin-top:0.25rem;">...</div>
                        </div>
                        <div>
                            <div
                                style="font-size: 0.7rem; color: var(--secondary); font-weight: 700; text-transform: uppercase;">
                                Inquiry Focus</div>
                            <div id="modal-category" style="font-weight: 700; margin-top:0.25rem;">...</div>
                        </div>
                        <div>
                            <div
                                style="font-size: 0.7rem; color: var(--secondary); font-weight: 700; text-transform: uppercase;">
                                Current State</div>
                            <div id="modal-status" style="margin-top:0.5rem;">...</div>
                        </div>
                    </div>
                </div>

                <div class="pro-card" style="padding: 1.25rem;">
                    <h4 style="margin-top: 0; margin-bottom: 1rem; font-size: 0.8rem; text-transform: uppercase;">Audit
                        Logs</h4>
                    <div id="logs-list"
                        style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 300px; overflow-y: auto;">
                        <!-- Logs injected here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(8px);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }

    .modal-container {
        background: var(--white);
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        animation: scaleUp 0.3s ease;
        border: 1px solid var(--glass-border);
    }

    .status-badge.status-open {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .status-badge.status-in-progress {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .status-badge.status-closed {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .reply-item {
        padding: 1.25rem;
        border-radius: 1rem;
        border: 1px solid var(--glass-border);
    }

    .reply-mgr {
        background: rgba(99, 102, 241, 0.05);
        align-self: flex-end;
        width: 85%;
    }

    .reply-client {
        background: var(--white);
        align-self: flex-start;
        width: 85%;
    }

    .glass-select {
        background: var(--bg-main);
        border: 1px solid var(--glass-border);
        border-radius: 0.5rem;
        color: var(--dark);
        outline: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .glass-select:hover {
        border-color: var(--primary);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes scaleUp {
        from {
            transform: scale(0.95);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Custom Scrollbar */
    .table-responsive::-webkit-scrollbar,
    .modal-body::-webkit-scrollbar,
    #logs-list::-webkit-scrollbar {
        width: 6px;
    }

    .table-responsive::-webkit-scrollbar-track,
    .modal-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .table-responsive::-webkit-scrollbar-thumb, .modal-body::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.05); border-radius: 10px; }

    /* Pagination Styling */
    .pagination-wrapper nav { display: flex; justify-content: center; align-items: center; width: 100%; }
    .pagination { display: flex; gap: 6px; list-style: none; padding: 0; margin: 0; flex-wrap: wrap; justify-content: center; }
    .pagination li { display: inline-block; }
    .pagination a, .pagination span { 
        display: flex; 
        align-items: center; 
        justify-content: center;
        min-width: 36px; 
        height: 36px; 
        padding: 0 12px;
        border-radius: 10px; 
        font-weight: 600; 
        font-size: 0.85rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        border: 1px solid var(--glass-border);
        background: var(--bg-main);
        color: var(--dark);
    }
    .pagination a:hover { 
        background: var(--primary); 
        color: white; 
        border-color: var(--primary);
        transform: translateY(-2px);
    }
    .pagination .active span { 
        background: var(--primary); 
        color: white; 
        border-color: var(--primary);
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
    }
    .pagination .disabled span { 
        opacity: 0.4; 
        cursor: not-allowed; 
        background: var(--bg-main);
    }
    /* Hide default laravel/bootstrap text summary "Showing x to y of z results" if it breaks layout */
    .pagination-wrapper nav > div.d-none.flex-sm-fill { display: none !important; }
    .pagination-wrapper nav > div:first-child { display: none !important; }
</style>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function updateFileName(input) {
        const fileName = input.files[0] ? input.files[0].name : '';
        document.getElementById('file-name').innerText = fileName;
    }

    async function openTicketModal(id) {
        const modal = document.getElementById('ticketModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // Clear previous
        document.getElementById('modal-subject').innerText = 'Syncing...';
        document.getElementById('replies-list').innerHTML = '<div style="text-align:center; padding: 2rem; opacity: 0.5;"><i class="fas fa-circle-notch fa-spin"></i> Loading Registry...</div>';

        try {
            const res = await fetch(`/manager/support/ajax/ticket/${id}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();

            document.getElementById('modal-subject').innerText = data.subject;
            document.getElementById('modal-ref-data').innerText = `REF: #${data.ticket_no} • Created ${data.created_at}`;
            document.getElementById('modal-description').innerText = data.description;
            // Updated to use requester fields from server
            document.getElementById('modal-customer').innerHTML = `<strong>${data.requester_name}</strong><br><span style="font-size:0.75rem; color:var(--secondary);">${data.requester_email}</span>`;
            document.getElementById('modal-category').innerText = data.category?.name || 'General Inquiries';
            document.getElementById('modal-status').innerHTML = `<span class="status-badge status-${data.status.replace(' ', '-')}" style="font-size:0.7rem;">${data.status.toUpperCase()}</span>`;
            document.getElementById('mgr_reply_ticket_id').value = data.id;

            if (data.attachment) {
                document.getElementById('modal-attachment').innerHTML = `<a href="/storage/${data.attachment}" target="_blank" class="action-btn" style="background:#f1f5f9; color:var(--primary); font-size:0.75rem; font-weight:700;"><i class="fas fa-file-download"></i> DOWNLOAD ATTACHMENT</a>`;
            } else {
                document.getElementById('modal-attachment').innerHTML = '';
            }

            // Replies
            const repliesList = document.getElementById('replies-list');
            repliesList.innerHTML = '';
            if (data.replies.length > 0) {
                data.replies.forEach(r => {
                    const isStaff = r.is_staff_reply;
                    const avatar = isStaff ? 'fa-user-shield' : 'fa-user';
                    const color = isStaff ? '#6366f1' : '#64748b';

                    const div = document.createElement('div');
                    div.className = `reply-item ${isStaff ? 'reply-mgr' : 'reply-client'}`;
                    div.innerHTML = `
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.75rem;">
                        <span style="font-weight:800; font-size:0.75rem; color:${color}; text-transform:uppercase;">
                            <i class="fas ${avatar}"></i> ${isStaff ? 'Manager Post' : 'Client Input'}
                        </span>
                        <span style="font-size:0.7rem; color:var(--secondary);">${r.created_at}</span>
                    </div>
                    <div style="font-size:0.95rem; line-height:1.5;">${r.message}</div>
                    ${r.attachment ? `<div style="margin-top:0.75rem; font-size:0.75rem;"><a href="${r.attachment}" target="_blank" style="color:var(--primary); font-weight:700;"><i class="fas fa-paperclip"></i> View Attachment</a></div>` : ''}
                `;
                    repliesList.appendChild(div);
                });
            } else {
                repliesList.innerHTML = '<div style="text-align:center; padding:3rem; color:var(--secondary); opacity:0.5;"><i class="fas fa-comment-slash" style="font-size:2rem; margin-bottom:1rem; display:block;"></i>No communications recorded yet.</div>';
            }

            // Logs
            await loadLogs(id);

        } catch (e) {
            console.error(e);
            document.getElementById('modal-subject').innerText = 'Registry Sync Error';
        }
    }

    async function loadLogs(id) {
        const list = document.getElementById('logs-list');
        list.innerHTML = '';
        const res = await fetch(`/manager/support/ajax/logs/${id}`);
        const logs = await res.json();

        if (logs.length > 0) {
            logs.forEach(l => {
                const div = document.createElement('div');
                div.style.paddingLeft = '1rem';
                div.style.borderLeft = '2px solid var(--glass-border)';
                div.style.paddingBottom = '0.5rem';
                div.innerHTML = `
                <div style="font-size:0.7rem; color:var(--secondary); font-weight:800;">${new Date(l.created_at).toLocaleDateString()}</div>
                <div style="font-size:0.8rem; font-weight:600;">${l.old_status} → <span style="color:var(--primary)">${l.new_status}</span></div>
                <div style="font-size:0.65rem; color:var(--secondary);">Authored by ${l.changed_by}</div>
            `;
                list.appendChild(div);
            });
        } else {
            list.innerHTML = '<div style="font-size:0.75rem; color:var(--secondary); opacity:0.5;">No status history.</div>';
        }
    }

    function closeTicketModal() {
        document.getElementById('ticketModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    async function postReplyManager(e) {
        e.preventDefault();
        const fd = new FormData(e.target);
        const id = document.getElementById('mgr_reply_ticket_id').value;

        try {
            const res = await fetch(`/manager/support/reply/${id}`, {
                method: 'POST',
                body: fd,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                // Append new reply
                const list = document.getElementById('replies-list');
                if (list.innerText.includes('No communications')) list.innerHTML = '';

                const div = document.createElement('div');
                div.className = 'reply-item reply-mgr';
                div.innerHTML = `
                <div style="display:flex; justify-content:space-between; margin-bottom:0.75rem;">
                    <span style="font-weight:800; font-size:0.75rem; color:#6366f1; text-transform:uppercase;"><i class="fas fa-user-shield"></i> Manager Post</span>
                    <span style="font-size:0.7rem; color:var(--secondary);">${data.reply.created_at}</span>
                </div>
                <div style="font-size:0.95rem; line-height:1.5;">${data.reply.message}</div>
                ${data.reply.attachment ? `<div style="margin-top:0.75rem; font-size:0.75rem;"><a href="${data.reply.attachment}" target="_blank" style="color:var(--primary); font-weight:700;"><i class="fas fa-paperclip"></i> View Attachment</a></div>` : ''}
            `;
                list.appendChild(div);
                e.target.reset();
                document.getElementById('file-name').innerText = '';
            }
        } catch (e) {
            alert('Could not dispatch reply to registry.');
        }
    }

    async function managerChangeStatus(id, status) {
        try {
            const fd = new FormData();
            fd.append('status', status);
            const res = await fetch(`/manager/support/status/${id}`, {
                method: 'POST',
                body: fd,
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await res.json();
            if (data.success) {
                location.reload(); // Quick refresh to update all visuals
            }
        } catch (e) {
            alert('Failed to update status in distribution registry.');
        }
    }
</script>