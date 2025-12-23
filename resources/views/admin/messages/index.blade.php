@extends('layouts.app')

@section('content')
@include('layouts.header')
@include('layouts.sidebar')
<div class="mail-app-wrapper">
    <!-- MAIL SIDEBAR (List) -->
    <aside class="mail-sidebar">
        <div class="mail-header">
            <h1>Inbox <span class="badge-count">{{ $stats['unread'] }}</span></h1>
            <div class="mail-actions">
                <a href="{{ route('admin.messages.index') }}" class="{{ !request('status') ? 'active' : '' }}">All</a>
                <a href="{{ route('admin.messages.index', ['status' => 'unread']) }}" class="{{ request('status') == 'unread' ? 'active' : '' }}">Unread</a>
            </div>
        </div>
        
        <div class="mail-search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search..." id="localSearch" autocomplete="off">
        </div>

        <div class="mail-list" id="mailList">
            @forelse($messages as $msg)
            <div class="mail-item {{ !$msg->read ? 'unread' : '' }}" 
                 onclick="openMail(this, {{ $msg->id }})"
                 data-id="{{ $msg->id }}">
                
                <div class="mail-avatar" style="background-color: {{ ['#f87171','#fb923c','#fbbf24','#a3e635','#22d3ee','#818cf8','#e879f9'][rand(0,6)] }}">
                    {{ strtoupper(substr($msg->first_name, 0, 1)) }}
                </div>
                
                <div class="mail-info">
                    <div class="mail-top">
                        <span class="sender-name">{{ $msg->first_name }} {{ $msg->last_name }}</span>
                        <span class="mail-time">{{ $msg->created_at->format('M d') }}</span>
                    </div>
                    <div class="mail-subject">{{ Str::limit($msg->message, 40) }}</div>
                    <div class="mail-snippet">
                        {{ $msg->email }} · {{ $msg->phone }}
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-placeholder">
                <i class="far fa-folder-open"></i>
                <p>No messages found.</p>
            </div>
            @endforelse
            
            <div class="pagination-container">
                {{ $messages->links('pagination::simple-bootstrap-4') }}
            </div>
        </div>
    </aside>

    <!-- MAIL CONTENT (Reading Pane) -->
    <main class="mail-content">
        <!-- Empty State -->
        <div id="mailEmptyState" class="mail-empty">
            <div class="icon-circle"><i class="far fa-paper-plane"></i></div>
            <h2>Welcome to your Inbox</h2>
            <p>Select a conversation from the left to view details.</p>
        </div>

        <!-- Loaded Content -->
        <div id="mailView" class="mail-view" style="display: none;">
            <div class="view-header">
                <div class="sender-profile-large">
                    <div class="avatar-large" id="viewAvatar">A</div>
                    <div>
                        <h2 id="viewName">Sender Name</h2>
                        <span id="viewEmail">sender@example.com</span>
                    </div>
                </div>
                <div class="view-actions">
                    <button class="action-icon success" onclick="markAsRead()" title="Mark Read"><i class="fas fa-check"></i></button>
                    <button class="action-icon danger" onclick="deleteMail()" title="Delete"><i class="fas fa-trash"></i></button>
                </div>
            </div>

            <div class="view-body" id="viewBody">
                <!-- Ajax Content Injected Here -->
            </div>

            <div class="view-footer">
                <form id="replyForm" onsubmit="sendReply(event)">
                    <textarea id="replyText" placeholder="Type your reply here..." required></textarea>
                    <div class="footer-actions">
                        <span class="secure-badge"><i class="fas fa-lock"></i> Secure TLS</span>
                        <button type="submit" class="btn-send">Send Reply <i class="fas fa-paper-plane"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<style>
    /* CSS Variables & Reset */
    /* CSS Variables & Reset */
    :root {
        --mail-sidebar-w: 360px;
        /* Light Mode Defaults */
        --mail-bg: #f1f5f9;
        --mail-white: #ffffff;
        --mail-border: #e2e8f0;
        --mail-text: #1e293b;
        --mail-muted: #64748b;
        --mail-primary: #4f46e5;
        --mail-hover: #f8fafc;
        --mail-active: #eff6ff;
    }

    /* Dark Mode Overrides */
    html[data-theme='dark'] {
        --mail-bg: #0f172a; /* Main Dark Bg */
        --mail-white: #1e293b; /* Panel Dark Bg */
        --mail-border: #334155; /* Dark Border */
        --mail-text: #f8fafc; /* White Text */
        --mail-muted: #94a3b8; /* Muted Text */
        --mail-hover: #334155;
        --mail-active: rgba(79, 70, 229, 0.2);
    }

    .mail-app-wrapper {
        display: flex;
        /* Gap from Sidebar (222px + 20px gap) */
        margin-left: 242px; 
        width: calc(100% - 262px); /* Responsive width: 100% - (margin-left + 20px right margin) */
        margin-top: 20px;
        height: calc(100vh - 100px);
        background: var(--surface, #ffffff); /* Use surface instead of mail-bg for better contrast */
        border: 1px solid var(--header-border, #e5e7eb);
        border-radius: 12px;
        overflow: hidden;
        color: var(--text-primary, #1f2937);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    
    /* Force Dark Mode Support explicitly if variables behave oddly */
    [data-theme='dark'] .mail-app-wrapper {
        background: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }
    [data-theme='dark'] .mail-sidebar, 
    [data-theme='dark'] .mail-search-bar input,
    [data-theme='dark'] .mail-item:hover,
    [data-theme='dark'] .view-header,
    [data-theme='dark'] .view-footer,
    [data-theme='dark'] #replyText {
        background: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }
    [data-theme='dark'] .chat-in {
        background: #0f172a;
        border-color: #334155;
        color: #e2e8f0;
    }

    /* SIDEBAR */
    .mail-sidebar {
        width: var(--mail-sidebar-w);
        background: var(--mail-white);
        border-right: 1px solid var(--mail-border);
        display: flex;
        flex-direction: column;
    }

    .mail-header {
        padding: 20px;
        border-bottom: 1px solid var(--mail-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .mail-header h1 { font-size: 20px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px; color: var(--mail-text); }
    .badge-count { background: #fee2e2; color: #ef4444; font-size: 11px; padding: 2px 8px; border-radius: 10px; }

    .mail-actions a {
        font-size: 13px; font-weight: 600; color: var(--mail-muted); margin-left: 10px; text-decoration: none; padding: 4px 10px; border-radius: 6px;
    }
    .mail-actions a.active { background: #eff6ff; color: var(--mail-primary); }

    .mail-search-bar {
        padding: 15px 20px;
        position: relative;
    }
    .mail-search-bar input {
        width: 100%; padding: 10px 10px 10px 35px;
        border: 1px solid var(--mail-border); border-radius: 8px;
        background: var(--mail-bg); outline: none; transition: all 0.2s;
        font-family: inherit; font-size: 14px; color: var(--mail-text);
    }
    .mail-search-bar input:focus { border-color: var(--mail-primary); background: var(--mail-white); }
    .mail-search-bar i { position: absolute; left: 32px; top: 26px; color: var(--mail-muted); }

    .mail-list { flex: 1; overflow-y: auto; }
    
    .mail-item {
        padding: 15px 20px;
        border-bottom: 1px solid var(--mail-border);
        cursor: pointer;
        display: flex; gap: 15px;
        transition: background 0.15s;
        background: var(--mail-white);
        color: var(--mail-text);
    }
    .mail-item:hover { background: var(--mail-hover); }
    .mail-item.selected { background: var(--mail-active); border-left: 3px solid var(--mail-primary); }
    
    .mail-item.unread .sender-name { font-weight: 800; color: var(--mail-text); }
    .mail-item.unread .mail-subject { font-weight: 600; color: var(--mail-text); }

    .mail-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        color: white; font-weight: 700; display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: 16px;
    }

    .mail-info { flex: 1; min-width: 0; }
    .mail-top { display: flex; justify-content: space-between; margin-bottom: 4px; }
    .sender-name { font-size: 14px; font-weight: 600; color: var(--mail-text); }
    .mail-time { font-size: 11px; color: var(--mail-muted); }
    
    .mail-subject { font-size: 13px; color: var(--mail-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .mail-snippet { font-size: 12px; color: var(--mail-muted); margin-top: 2px; }

    /* CONTENT PANE */
    .mail-content { flex: 1; display: flex; flex-direction: column; background: var(--mail-active); /* Subtle difference */ }

    .mail-empty {
        margin: auto; text-align: center; color: var(--mail-muted);
    }
    .icon-circle {
        width: 80px; height: 80px; background: var(--mail-bg); border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 32px; color: var(--mail-muted); margin: 0 auto 20px;
    }

    .mail-view { display: flex; flex-direction: column; height: 100%; animation: fadeIn 0.3s ease; background: var(--mail-white); }
    
    .view-header {
        padding: 20px 30px;
        border-bottom: 1px solid var(--mail-border);
        display: flex; justify-content: space-between; align-items: center;
        background: var(--mail-white);
    }
    
    .sender-profile-large { display: flex; align-items: center; gap: 15px; }
    .avatar-large {
        width: 48px; height: 48px; background: var(--mail-text); color: var(--mail-bg);
        border-radius: 12px; display: flex; align-items: center; justify-content: center;
        font-size: 20px; font-weight: 700;
    }
    .view-header h2 { margin: 0; font-size: 18px; color: var(--mail-text); }
    .view-header span { font-size: 13px; color: var(--mail-muted); }

    .action-icon {
        width: 36px; height: 36px; border-radius: 8px; border: 1px solid var(--mail-border);
        background: transparent; color: var(--mail-muted); cursor: pointer; transition: all 0.2s;
        display: flex; align-items: center; justify-content: center;
    }
    .action-icon:hover { background: var(--mail-hover); }
    .action-icon.success:hover { color: #10b981; border-color: #10b981; }
    .action-icon.danger:hover { color: #ef4444; border-color: #ef4444; }

    .view-body { flex: 1; overflow-y: auto; padding: 30px; background: var(--mail-bg); display: flex; flex-direction: column; gap: 20px; }

    /* Bubbles */
    .chat-bubble {
        max-width: 70%; padding: 15px 20px; border-radius: 12px;
        position: relative; line-height: 1.5; font-size: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .chat-in {
        align-self: flex-start; background: var(--mail-white); border: 1px solid var(--mail-border); color: var(--mail-text);
        border-bottom-left-radius: 2px;
    }
    .chat-out {
        align-self: flex-end; background: var(--mail-primary); color: #fff;
        border-bottom-right-radius: 2px;
    }
    .chat-meta {
        font-size: 11px; margin-top: 6px; display: block; opacity: 0.7; color: var(--mail-text);
    }

    .view-footer {
        padding: 20px 30px;
        background: var(--mail-white);
        border-top: 1px solid var(--mail-border);
    }
    
    #replyText {
        width: 100%; border: 1px solid var(--mail-border); border-radius: 8px;
        padding: 12px; font-family: inherit; resize: none; height: 80px;
        outline: none; margin-bottom: 10px; background: var(--mail-bg); color: var(--mail-text);
    }
    #replyText:focus { border-color: var(--mail-primary); background: var(--mail-white); }

    .footer-actions { display: flex; justify-content: space-between; align-items: center; }
    .btn-send {
        background: var(--mail-primary); color: white; border: none;
        padding: 10px 24px; border-radius: 30px; font-weight: 600; cursor: pointer;
        display: flex; align-items: center; gap: 8px; transition: transform 0.1s;
    }
    .btn-send:hover { opacity: 0.9; }
    .btn-send:active { transform: scale(0.98); }
    
    .secure-badge { font-size: 12px; color: #10b981; display: flex; align-items: center; gap: 5px; font-weight: 500; }

    /* Pagination Clean */
    .pagination-container { padding: 10px 20px; text-align: center; border-top: 1px solid var(--mail-border); }
    .pagination { margin: 0; }
    .page-link { border: none; color: var(--mail-muted); }
    .page-item.active .page-link { background: var(--mail-primary); border-radius: 5px; }

    @keyframes fadeIn { from{opacity:0;}to{opacity:1;} }
</style>

<script>
    let currentMailId = null;
    const csrfToken = "{{ csrf_token() }}";

    function openMail(el, id) {
        currentMailId = id;
        
        // UI Selection
        document.querySelectorAll('.mail-item').forEach(i => i.classList.remove('selected'));
        el.classList.add('selected');
        el.classList.remove('unread'); // Optimistic UI

        // Switch View
        document.getElementById('mailEmptyState').style.display = 'none';
        document.getElementById('mailView').style.display = 'flex';
        
        // Loader
        const body = document.getElementById('viewBody');
        body.innerHTML = '<div style="margin:auto;color:#9ca3af;"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';

        // Fetch
        fetch(`/admin/messages/ajax/${id}`)
            .then(res => res.json())
            .then(data => {
                // Populate Header
                document.getElementById('viewName').innerText = data.first_name + ' ' + data.last_name;
                document.getElementById('viewEmail').innerText = data.email;
                document.getElementById('viewAvatar').innerText = data.first_name[0];
                
                // Build Chat
                let html = `
                    <div class="chat-bubble chat-in">
                        <strong>Original Inquiry</strong><br>
                        ${data.message}
                        <span class="chat-meta">${new Date(data.created_at).toLocaleString()}</span>
                    </div>
                `;

                if(data.replies && data.replies.length > 0) {
                    data.replies.forEach(r => {
                        html += `
                            <div class="chat-bubble chat-out">
                                ${r.reply_content}
                                <span class="chat-meta">${new Date(r.created_at).toLocaleString()}</span>
                            </div>
                        `;
                    });
                }
                
                body.innerHTML = html;
                body.scrollTop = body.scrollHeight;
                
                // Mark Read API
                fetch(`/admin/messages/${id}/mark-read`, {
                    method: 'POST', 
                    headers: {'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json'}
                });
            });
    }

    function sendReply(e) {
        e.preventDefault();
        const txt = document.getElementById('replyText');
        if(!txt.value.trim()) return;

        const btn = document.querySelector('.btn-send');
        const oldHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        fetch(`/admin/messages/${currentMailId}/reply`, {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json'},
            body: JSON.stringify({ reply_content: txt.value })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Append Bubble
                const body = document.getElementById('viewBody');
                body.innerHTML += `
                    <div class="chat-bubble chat-out" style="animation:fadeIn 0.3s">
                        ${data.reply.content}
                        <span class="chat-meta">Just now</span>
                    </div>
                `;
                body.scrollTop = body.scrollHeight;
                txt.value = '';
            }
        })
        .finally(() => {
            btn.innerHTML = oldHtml;
            btn.disabled = false;
        });
    }

    function deleteMail() {
        if(confirm('Delete conversation?')) {
            fetch(`/admin/messages/${currentMailId}`, {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': csrfToken}
            }).then(() => {
                location.reload(); // Simple reload for delete to refresh list state accurately
            });
        }
    }
</script>
@endsection
