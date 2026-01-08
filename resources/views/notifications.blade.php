@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Notifications Page Styles */
    .dashboard-wrapper { max-width: 800px; margin: 0 auto; padding: 20px; }
    
    .notif-tabs { display: flex; gap: 20px; margin-bottom: 25px; border-bottom: 1px solid var(--header-border); padding-bottom: 10px; }
    .notif-tab { background: none; border: none; color: var(--text-muted); font-weight: 600; font-size: 14px; cursor: pointer; padding: 5px 0; position: relative; }
    .notif-tab.active { color: var(--primary); }
    .notif-tab.active::after { content: ''; position: absolute; bottom: -11px; left: 0; width: 100%; height: 2px; background: var(--primary); border-radius: 2px; }
    .badge-count { background: var(--surface-hover); color: var(--text-primary); padding: 2px 8px; border-radius: 10px; font-size: 11px; margin-left: 5px; }
    .badge-count.unread-badge { background: #fee2e2; color: #ef4444; }

    .notif-group-header { display: flex; align-items: center; margin: 25px 0 15px; color: var(--text-muted); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .notif-group-header .line { flex: 1; height: 1px; background: var(--header-border); margin-left: 10px; }

    .notif-item { display: flex; gap: 15px; background: var(--surface); padding: 15px; border-radius: 12px; margin-bottom: 10px; border: 1px solid var(--header-border); transition: all 0.2s; }
    .notif-item:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .notif-item.unread { background: var(--surface-hover); border-left: 3px solid var(--primary); }
    
    .notif-icon-box { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: rgba(59, 130, 246, 0.1); color: var(--primary); flex-shrink: 0; }
    .notif-details { flex: 1; }
    .notif-header { display: flex; justify-content: space-between; margin-bottom: 5px; }
    .notif-header h4 { margin: 0; font-size: 14px; font-weight: 600; color: var(--text-primary); }
    .notif-header .time { font-size: 11px; color: var(--text-muted); }
    .notif-details p { margin: 0 0 10px; font-size: 13px; color: var(--text-secondary); line-height: 1.5; }
    
    .notif-actions { display: flex; gap: 15px; font-size: 12px; }
    .link-action { background: none; border: none; padding: 0; cursor: pointer; color: var(--text-muted); font-weight: 500; transition: color 0.2s; }
    .link-action:hover { color: var(--primary); text-decoration: underline; }
    .link-action.delete { color: #ef4444; margin-left: auto; }
    .link-action.check { color: var(--primary); }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
    .empty-state .icon-box { font-size: 40px; margin-bottom: 15px; opacity: 0.3; }

    .fade-out { opacity: 0; transform: translateX(20px); height: 0; margin: 0; padding: 0; overflow: hidden; pointer-events: none; }
    
    [data-theme="dark"] .badge-count.unread-badge { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }
</style>

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-bell" style="color: var(--primary);"></i> Intelligence Inbox</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Prioritized system alerts and corporate broadcasts.</p>
        </div>
        <div class="header-actions">
            @if($notifications->count() > 0)
            <button onclick="markAllRead()" class="action-btn secondary">
                <i class="fas fa-check-double"></i> Mark all read
            </button>
            <button onclick="clearAllNotifications()" class="action-btn secondary" style="color: #ef4444; border-color: rgba(239, 68, 68, 0.3);">
                <i class="fas fa-trash-alt"></i> Clear All
            </button>
            @endif
        </div>
    </div>

    <div class="notifications-container">
        <!-- Tabs -->
        <div class="notif-tabs">
            <button class="notif-tab active" onclick="filterNotifs('all')" id="tab-all">
                All Notifications <span class="badge-count">{{ $notifications->count() }}</span>
            </button>
            <button class="notif-tab" onclick="filterNotifs('unread')" id="tab-unread">
                Unread <span class="badge-count unread-badge">{{ $notifications->whereNull('read_at')->count() }}</span>
            </button>
        </div>

        <!-- Notifications List -->
        <div class="notif-list-wrapper">
            @php
                $grouped = $notifications->groupBy(function($item) {
                    if ($item->created_at->isToday()) return 'Today';
                    if ($item->created_at->isYesterday()) return 'Yesterday';
                    return 'Older';
                });
            @endphp

            @forelse(['Today', 'Yesterday', 'Older'] as $group)
                @if(isset($grouped[$group]) && $grouped[$group]->count() > 0)
                    <div class="notif-group-header">
                        <span>{{ $group }}</span>
                        <div class="line"></div>
                    </div>
                    
                    <div class="notif-group">
                        @foreach($grouped[$group] as $n)
                            <div class="notif-item {{ !$n->read_at ? 'unread' : '' }} {{ $n->read_at ? 'read-item' : '' }}" id="notif-{{ $n->id }}">
                                <div class="notif-icon-box {{ $n->data['type'] ?? 'info' }}">
                                    <i class="fas {{ $n->data['icon'] ?? 'fa-bell' }}"></i>
                                </div>
                                <div class="notif-details">
                                    <div class="notif-header">
                                        <h4>{{ $n->data['title'] ?? 'Notification' }}</h4>
                                        <span class="time">{{ $n->created_at->format('H:i') }}</span>
                                    </div>
                                    <p>{{ $n->data['message'] ?? '' }}</p>
                                    
                                    <div class="notif-actions">
                                        @if(isset($n->data['action_url']))
                                            <a href="{{ $n->data['action_url'] }}" class="link-action">View Details</a>
                                        @endif
                                        @if(!$n->read_at)
                                            <button onclick="markOneRead('{{ $n->id }}')" class="link-action check">Mark as Read</button>
                                        @endif
                                        <button onclick="deleteNotification('{{ $n->id }}')" class="link-action delete" title="Delete"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @empty
                <div class="empty-state">
                    <div class="icon-box">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3>All caught up!</h3>
                    <p>You have no notifications at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    // Helper to get theme colors
    function getSwalColors() {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        return {
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#e2e8f0' : '#334155'
        };
    }

    // Common fetch function
    async function fetchAction(url, method = 'POST') {
        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            return await response.json();
        } catch (error) {
            console.error('Error:', error);
            return { success: false };
        }
    }

    async function markAllRead() {
        const colors = getSwalColors();
        const res = await fetchAction("{{ route('notifications.markAllRead') }}");
        
        if (res.success) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'All marked as read',
                showConfirmButton: false,
                timer: 1500,
                background: colors.background,
                color: colors.color
            });
            // Update UI
            document.querySelectorAll('.notif-card.unread').forEach(card => {
                card.classList.remove('unread');
                // Remove the "Mark as Read" button from inside
                const btn = card.querySelector('.action-link');
                if(btn && btn.textContent.includes('Mark')) btn.remove();
            });
        }
    }

    function clearAllNotifications() {
        const colors = getSwalColors();
        Swal.fire({
            title: 'Clear Inbox?',
            text: "This will remove all system alerts permanently.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, clear all',
            background: colors.background,
            color: colors.color
        }).then(async (result) => {
            if (result.isConfirmed) {
                const res = await fetchAction("{{ route('notifications.clearAll') }}");
                if(res.success) {
                     Swal.fire({
                        title: 'Cleared!',
                        text: 'Your inbox is now empty.',
                        icon: 'success',
                        background: colors.background,
                        color: colors.color
                    });
                    
                    const list = document.getElementById('system-notifs-list');
                    list.innerHTML = `
                        <div class="empty-state glass-panel">
                            <div class="icon-placeholder"><i class="fas fa-moon"></i></div>
                            <p>Silence in the air. All systems are green.</p>
                        </div>
                    `;
                }
            }
        });
    }

    async function dismissAnnouncement(id) {
        const colors = getSwalColors();
        const res = await fetchAction("{{ url('notifications/dismiss') }}/" + id);
        
        if(res.success) {
            const card = document.getElementById('announcement-' + id);
            card.classList.add('fade-out');
            setTimeout(() => card.remove(), 300);
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Dismissed',
                showConfirmButton: false,
                timer: 1500,
                background: colors.background,
                color: colors.color
            });
        }
    }

    async function markOneRead(id) {
        const colors = getSwalColors();
        const res = await fetchAction("{{ url('notifications/read') }}/" + id, 'GET');
        
        if(res.success) {
            const card = document.getElementById('notif-' + id);
            card.classList.remove('unread');
            const btn = document.getElementById('btn-read-' + id);
            if(btn) btn.remove();
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Marked as read',
                showConfirmButton: false,
                timer: 1000,
                background: colors.background,
                color: colors.color
            });
        }
    }

    async function deleteNotification(id) {
        const colors = getSwalColors();
        // Ask for confirmation (optional, but safer)
        const result = await Swal.fire({
             title: 'Delete?',
             text: 'Remove this notification?',
             icon: 'warning',
             showCancelButton: true,
             confirmButtonColor: '#ef4444',
             confirmButtonText: 'Delete',
             background: colors.background,
             color: colors.color,
             width: 300
        });

        if(!result.isConfirmed) return;

        const res = await fetchAction("{{ url('notifications') }}/" + id, 'DELETE');
        
        if(res.success) {
            const card = document.getElementById('notif-' + id);
            card.classList.add('fade-out');
            setTimeout(() => card.remove(), 300);

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Deleted',
                showConfirmButton: false,
                timer: 1500,
                background: colors.background,
                color: colors.color
            });
        }
    }

    // --- Filter Logic ---
    function filterNotifs(type) {
        // Update Tabs
        document.querySelectorAll('.notif-tab').forEach(t => t.classList.remove('active'));
        document.getElementById('tab-' + type).classList.add('active');

        // Filter Items
        const items = document.querySelectorAll('.notif-item');
        const groups = document.querySelectorAll('.notif-group-header, .notif-group');
        let hasVisible = false;

        items.forEach(item => {
            if (type === 'all') {
                item.style.display = 'flex';
                hasVisible = true;
            } else if (type === 'unread') {
                if (item.classList.contains('unread')) {
                    item.style.display = 'flex';
                    hasVisible = true;
                } else {
                    item.style.display = 'none';
                }
            }
        });

        // Hide empty groups (visual polish)
        document.querySelectorAll('.notif-group').forEach(group => {
            // Check visible children in this group
            let visibleCount = 0;
            Array.from(group.children).forEach(child => {
                 if(child.style.display !== 'none') visibleCount++;
            });

            const header = group.previousElementSibling; 
            
            if (visibleCount === 0) {
                group.style.display = 'none';
                if(header && header.classList.contains('notif-group-header')) header.style.display = 'none';
            } else {
                group.style.display = 'block';
                if(header && header.classList.contains('notif-group-header')) header.style.display = 'flex';
            }
        });
    }
</script>


