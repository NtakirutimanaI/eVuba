@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-bell" style="color: var(--primary);"></i> Intelligence Inbox</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Prioritized system alerts and corporate broadcasts.</p>
        </div>
        <div class="header-actions">
            <form action="{{ route('notifications.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="action-btn secondary">
                    <i class="fas fa-check-double"></i> Mark all read
                </button>
            </form>
            <form action="{{ route('notifications.clearAll') }}" method="POST" onsubmit="return confirm('Clear all notifications permanently?')">
                @csrf
                <button type="submit" class="action-btn secondary" style="color: #ef4444; border-color: rgba(239, 68, 68, 0.3);">
                    <i class="fas fa-trash-alt"></i> Clear All
                </button>
            </form>
        </div>
    </div>

    <div class="notifications-grid">
        <!-- ANNOUNCEMENTS SECTION -->
        <div class="notif-section">
            <h2 class="section-title"><i class="fas fa-bullhorn"></i> Global Broadcasts</h2>
            <div class="notif-list announcements">
                @forelse($announcements as $a)
                <div class="notif-card announcement glass-panel">
                    <div class="notif-icon"><i class="fas fa-broadcast-tower"></i></div>
                    <div class="notif-content">
                        <div class="notif-top">
                            <h3>{{ $a->title }}</h3>
                            <span class="notif-time">{{ $a->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="notif-msg">{{ $a->message }}</p>
                        <div class="notif-footer">
                            <form action="{{ route('notifications.dismiss', $a->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="dismiss-btn">Dismiss Announcement</button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state glass-panel">
                    <div class="icon-placeholder"><i class="fas fa-check-circle"></i></div>
                    <p>No new global broadcasts at this time.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- NOTIFICATIONS SECTION -->
        <div class="notif-section">
            <h2 class="section-title"><i class="fas fa-bolt"></i> System Alerts</h2>
            <div class="notif-list system">
                @forelse($notifications as $n)
                <div class="notif-card alert {{ !$n->read_at ? 'unread' : '' }} glass-panel">
                    <div class="notif-icon">
                        <i class="fas {{ $n->data['icon'] ?? 'fa-info-circle' }}"></i>
                    </div>
                    <div class="notif-content">
                        <div class="notif-top">
                            <h3>{{ $n->data['title'] ?? 'System Update' }}</h3>
                            <span class="notif-time">{{ $n->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="notif-msg">{{ $n->data['message'] ?? 'You have a new update.' }}</p>
                        <div class="notif-footer">
                            @if(!$n->read_at)
                            <a href="{{ route('notifications.read', $n->id) }}" class="action-link">Mark as Read</a>
                            @endif
                            @if(isset($n->data['action_url']))
                            <a href="{{ $n->data['action_url'] }}" class="action-btn-sm">View Details</a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state glass-panel">
                    <div class="icon-placeholder"><i class="fas fa-moon"></i></div>
                    <p>Silence in the air. All systems are green.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>


