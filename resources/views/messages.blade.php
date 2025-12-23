@include('layouts.header')
@include('layouts.sidebar')

<div class="messages-page-container">
    <div class="page-header glass-panel">
        <div class="header-left">
            <h1><i class="fas fa-envelope-open-text text-indigo"></i> Communication Hub</h1>
            <p>Your internal messages and official correspondence.</p>
        </div>
        <div class="header-actions">
            <!-- Optional actions -->
        </div>
    </div>

    <div class="messages-list glass-panel">
        @forelse($messages as $m)
        <div class="msg-card {{ !$m->read_at ? 'unread' : '' }}">
            <div class="msg-avatar">
                {{ strtoupper(substr($m->sender_name, 0, 1)) }}
            </div>
            <div class="msg-main-content">
                <div class="msg-header">
                    <div class="sender-info">
                        <h3>{{ $m->sender_name }}</h3>
                        @if(!$m->read_at)
                        <span class="unread-pill">New</span>
                        @endif
                    </div>
                    <span class="msg-date">{{ $m->created_at->diffForHumans() }}</span>
                </div>
                <h4 class="msg-subject">{{ $m->subject }}</h4>
                <p class="msg-body">{{ Str::limit($m->body, 120) }}</p>
                <div class="msg-actions">
                    <a href="{{ route('messages.read', $m->id) }}" class="glass-btn primary sm">
                        <i class="fas fa-eye"></i> View Message
                    </a>
                    @if($m->url)
                    <a href="{{ $m->url }}" class="glass-btn secondary sm">
                        <i class="fas fa-external-link-alt"></i> Follow Link
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-comment-slash"></i>
            <p>No messages available. Your hub is quiet.</p>
        </div>
        @endforelse
    </div>
</div>

<style>
.messages-page-container {
    margin-left: 250px;
    padding: 30px;
    background: var(--body-bg);
    min-height: 100vh;
    font-family: 'Outfit', 'Inter', sans-serif;
}

.glass-panel {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    box-shadow: 0 4px 15px -1px rgba(0,0,0,0.03);
    backdrop-filter: blur(10px);
}

.page-header {
    padding: 30px 40px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-left h1 { margin: 0; font-size: 26px; font-weight: 800; color: var(--slate); }
.header-left p { margin: 5px 0 0 0; color: var(--text-muted); font-size: 15px; }

.messages-list { padding: 0; overflow: hidden; }

.msg-card {
    padding: 25px 35px;
    display: flex;
    gap: 25px;
    border-bottom: 1px solid var(--header-border);
    transition: all 0.2s ease;
}

.msg-card:last-child { border-bottom: none; }
.msg-card:hover { background: var(--header-bg); }
.msg-card.unread { background: var(--surface); border-left: 4px solid var(--indigo); }

.msg-avatar {
    width: 54px; height: 54px;
    background: linear-gradient(135deg, #a5b4fc, #6366f1);
    color: #fff;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; font-weight: 800; flex-shrink: 0;
}

.msg-main-content { flex: 1; }

.msg-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }

.sender-info { display: flex; align-items: center; gap: 12px; }
.sender-info h3 { margin: 0; font-size: 17px; font-weight: 800; color: var(--slate); }

.unread-pill {
    background: var(--indigo); color: #fff; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;
}

.msg-date { font-size: 12px; color: var(--text-muted); font-weight: 500; }

.msg-subject { margin: 0 0 8px 0; font-size: 15px; font-weight: 700; color: var(--text-primary); }
.msg-body { margin: 0 0 20px 0; font-size: 14px; color: var(--text-muted); line-height: 1.6; }

.msg-actions { display: flex; gap: 12px; }

.glass-btn { 
    padding: 8px 18px; border-radius: 10px; font-weight: 700; font-size: 12px; 
    cursor: pointer; transition: all 0.2s; border: none; display: flex; align-items: center; gap: 8px; text-decoration: none;
}
.glass-btn.primary { background: var(--indigo); color: #fff; }
.glass-btn.secondary { background: var(--body-bg); color: var(--text-primary); border: 1px solid var(--header-border); }
.glass-btn:hover { transform: translateY(-2px); opacity: 0.9; }

.empty-state {
    padding: 80px;
    text-align: center;
    color: var(--text-muted);
}
.empty-state i { font-size: 50px; margin-bottom: 20px; opacity: 0.2; }
.empty-state p { font-size: 16px; font-weight: 500; }

@media (max-width: 768px) {
    .messages-page-container { margin-left: 0; padding: 15px; }
    .msg-card { padding: 15px; gap: 15px; }
    .msg-avatar { width: 44px; height: 44px; font-size: 16px; }
}
</style>
