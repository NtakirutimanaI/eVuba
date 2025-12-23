@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-headset" style="color: var(--primary);"></i> Support Hub</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Resolve customer inquiries and technical support tickets.</p>
        </div>
        <div class="header-actions">
            <div class="glass-panel" style="padding: 10px 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px;">
                <div class="stat-item">
                    <span style="font-size: 0.75rem; color: var(--secondary);">Active Inquiries</span>
                    <strong style="display: block; font-size: 1.1rem; color: var(--primary);">{{ $tickets->total() }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Communication Grid --}}
    <div style="margin-top: 3rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: var(--dark);">Communication Grid</h3>
            <div class="mega-search" style="max-width: 300px; flex: 1; background: var(--light);">
                <i class="fas fa-search" style="color: var(--secondary);"></i>
                <input type="text" id="supportSearch" placeholder="Find inquiry..." class="search-input" style="background: transparent; border: none; padding: 10px;">
            </div>
        </div>

        <div class="support-grid">
            @forelse($tickets as $ticket)
                <div class="support-card support-row">
                    <div class="card-top">
                        <span class="ticket-id">#TCK-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</span>
                        <span class="urgency-label {{ $ticket->priority }}">{{ strtoupper($ticket->priority ?? 'Low') }}</span>
                    </div>
                    
                    <div class="card-main">
                        <h4 class="ticket-subject">{{ $ticket->subject }}</h4>
                        <div class="client-mini-profile">
                            <div class="mini-avatar">{{ strtoupper(substr($ticket->customer->name ?? 'G', 0, 1)) }}</div>
                            <div>
                                <div class="client-name">{{ $ticket->customer->name ?? 'Guest Client' }}</div>
                                <div class="client-email">{{ $ticket->customer->email ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-bottom">
                        <div class="category-tag"><i class="fas fa-tag"></i> {{ $ticket->category->name ?? 'General' }}</div>
                        <button class="engage-btn" onclick="openTicket('{{ $ticket->id }}')">
                            Engage <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 100px 0; background: var(--white); border-radius: 30px; border: 1px dashed var(--glass-border);">
                    <i class="fas fa-inbox" style="font-size: 4rem; color: var(--secondary); opacity: 0.1; margin-bottom: 20px; display: block;"></i>
                    <p style="color: var(--secondary); font-weight: 600;">No active inquiries detected.</p>
                </div>
            @endforelse
        </div>

        <div style="margin-top: 40px; border-top: 1px solid var(--glass-border); padding-top: 25px;">
            {{ $tickets->links() }}
        </div>
    </div>
</div>

{{-- Ticket Modal --}}
<div id="ticketModal" class="modal-overlay">
    <div class="pro-modal" style="max-width: 800px; display: flex; flex-direction: column; height: 90vh;">
        <div class="modal-header">
            <div>
                <h3 id="modalSubject" style="margin: 0; font-size: 1.2rem;">Ticket Subject</h3>
                <span id="modalMeta" style="font-size: 0.8rem; color: var(--secondary);">#TCK-00000 | From: Client Name</span>
            </div>
            <button class="close-modal" onclick="closeTicket()">&times;</button>
        </div>
        
        <div class="modal-body chat-container" id="chatStream" style="flex: 1; overflow-y: auto; background: var(--light); padding: 25px; display: flex; flex-direction: column; gap: 20px;">
            {{-- Original Message --}}
            <div id="originalMessage" class="chat-bubble client">
                <p id="modalDescription">Loading ticket description...</p>
            </div>
            
            {{-- History Stream --}}
            <div id="historyStream" style="display: flex; flex-direction: column; gap: 20px;"></div>
        </div>

        <div class="modal-footer" style="padding: 25px; background: var(--white); border-top: 1px solid var(--glass-border);">
            <form id="replyForm" style="width: 100%; display: flex; gap: 15px; align-items: flex-end;">
                <input type="hidden" id="replyTicketId">
                <div style="flex: 1; display: flex; align-items: center; background: var(--light); border-radius: 20px; border: 1px solid var(--glass-border); padding: 5px 15px; transition: 0.3s;" id="inputContainer">
                    <i class="far fa-comment-dots" style="color: var(--secondary); margin-right: 10px;"></i>
                    <textarea id="replyMessage" placeholder="Compose your response..." style="flex: 1; padding: 12px 0; border: none; background: transparent; font-family: inherit; resize: none; min-height: 48px; max-height: 150px; outline: none; font-size: 0.95rem; color: var(--dark);" rows="1" required></textarea>
                </div>
                <button type="submit" class="send-btn" style="width: 54px; height: 54px; border-radius: 20px; background: var(--primary); color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);">
                    <i class="fas fa-paper-plane" style="font-size: 1.1rem;"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    /* Communication Grid Engine */
    .support-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px; }
    .support-card { background: var(--white); border-radius: 24px; padding: 30px; border: 1px solid var(--glass-border); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; gap: 20px; position: relative; overflow: hidden; }
    .support-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); border-color: var(--primary); }
    
    .card-top { display: flex; justify-content: space-between; align-items: center; }
    .ticket-id { font-size: 0.75rem; font-weight: 800; color: var(--secondary); font-family: monospace; }
    .urgency-label { font-size: 0.65rem; font-weight: 900; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; }
    .urgency-label.high { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .urgency-label.medium { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .urgency-label.low { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }

    .card-main { flex: 1; }
    .ticket-subject { font-size: 1.1rem; font-weight: 800; color: var(--dark); margin: 0 0 15px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .client-mini-profile { display: flex; align-items: center; gap: 12px; background: var(--light); padding: 12px; border-radius: 16px; }
    .mini-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--dark); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; }
    .client-name { font-size: 0.85rem; font-weight: 700; color: var(--dark); }
    .client-email { font-size: 0.7rem; color: var(--secondary); }

    .card-bottom { display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid rgba(0,0,0,0.03); }
    .category-tag { font-size: 0.75rem; font-weight: 600; color: var(--secondary); }
    .engage-btn { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 0.8rem; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 8px; }
    .engage-btn:hover { background: var(--dark); transform: scale(1.05); }

    /* Chat Styling - Premium Engine */
    .chat-container { scroll-behavior: smooth; }
    .chat-container::-webkit-scrollbar { width: 6px; }
    .chat-container::-webkit-scrollbar-track { background: transparent; }
    .chat-container::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.05); border-radius: 10px; }

    .chat-message-wrapper { display: flex; gap: 15px; max-width: 85%; }
    .chat-message-wrapper.client { align-self: flex-start; }
    .chat-message-wrapper.staff { align-self: flex-end; flex-direction: row-reverse; }

    .chat-avatar { width: 40px; height: 40px; border-radius: 14px; background: var(--white); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid var(--glass-border); flex-shrink: 0; }
    .staff .chat-avatar { background: var(--dark); color: white; border-color: transparent; }

    .chat-bubble { padding: 18px 24px; border-radius: 20px; font-size: 0.95rem; line-height: 1.6; position: relative; }
    .client .chat-bubble { background: var(--white); border-top-left-radius: 4px; box-shadow: 0 8px 16px rgba(0,0,0,0.03); border: 1px solid var(--glass-border); }
    .staff .chat-bubble { background: var(--primary); color: white; border-top-right-radius: 4px; box-shadow: 0 12px 24px rgba(99, 102, 241, 0.15); }

    .origin-manifest { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 24px; padding: 25px; margin-bottom: 20px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02); }
    .origin-label { font-size: 0.6rem; font-weight: 900; text-transform: uppercase; color: var(--secondary); letter-spacing: 1px; margin-bottom: 12px; display: block; }
    .origin-content { font-size: 1rem; color: var(--dark); font-weight: 500; }

    .chat-meta { font-size: 0.75rem; margin-top: 10px; display: flex; align-items: center; gap: 8px; font-weight: 600; }
    .client .chat-meta { color: var(--secondary); }
    .staff .chat-meta { color: rgba(255,255,255,0.85); justify-content: flex-end; }
    
    .role-badge { font-size: 0.6rem; font-weight: 800; text-transform: uppercase; padding: 2px 8px; border-radius: 6px; }
    .client .role-badge { background: rgba(0,0,0,0.05); color: var(--secondary); }
    .staff .role-badge { background: rgba(255,255,255,0.2); color: white; }

    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(15px); z-index: 10001; align-items: center; justify-content: center; }
    .pro-modal { border: 1px solid rgba(255,255,255,0.1); background: var(--white); width: 95%; border-radius: 35px; overflow: hidden; animation: slideIn 0.4s cubic-bezier(0.19, 1, 0.22, 1); box-shadow: 0 50px 100px -20px rgba(0,0,0,0.4); }
    @keyframes slideIn { from { transform: translateY(80px) scale(0.95); opacity: 0; } to { transform: translateY(0) scale(1); opacity: 1; } }

    .send-btn:hover { transform: scale(1.1) rotate(-10deg); background: var(--dark); }
    #replyMessage:focus + #inputContainer { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(99,102,241,0.1); }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    async function openTicket(id) {
        const modal = document.getElementById('ticketModal');
        modal.style.display = 'flex';
        document.getElementById('replyTicketId').value = id;
        
        try {
            const detRes = await fetch(`/employee/support/details/${id}`);
            const ticket = await detRes.json();
            
            document.getElementById('modalSubject').textContent = ticket.subject;
            document.getElementById('modalMeta').textContent = `#TCK-${id.toString().padStart(5, '0')} | From: ${ticket.customer}`;
            
            const stream = document.getElementById('chatStream');
            stream.innerHTML = `
                <div class="origin-manifest">
                    <span class="origin-label">Origin Inquiry Manifest</span>
                    <div class="origin-content">${ticket.description}</div>
                </div>
                <div id="historyStream" style="display: flex; flex-direction: column; gap: 25px;"></div>
            `;
            
            loadHistory(id);
        } catch (e) {
            Swal.fire('Error', 'Failed to synchronize ticket data.', 'error');
        }
    }

    async function loadHistory(id) {
        const stream = document.getElementById('historyStream');
        if (!stream) return;
        
        try {
            const histRes = await fetch(`/employee/support/history/${id}`);
            const history = await histRes.json();
            
            stream.innerHTML = '';
            history.forEach(h => {
                const type = h.is_staff ? 'staff' : 'client';
                const avatarChar = (h.user_name || 'G').charAt(0).toUpperCase();
                
                const div = document.createElement('div');
                div.className = `chat-message-wrapper ${type}`;
                div.innerHTML = `
                    <div class="chat-avatar">${avatarChar}</div>
                    <div style="flex:1">
                        <div class="chat-bubble">
                            <p style="margin:0;">${h.message}</p>
                            <div class="chat-meta">
                                <span class="role-badge">${h.is_staff ? 'Operator' : 'Client'}</span>
                                <span>${h.user_name} • ${h.date}</span>
                            </div>
                        </div>
                    </div>
                `;
                stream.appendChild(div);
            });
            
            const chatBody = document.getElementById('chatStream');
            chatBody.scrollTop = chatBody.scrollHeight;
        } catch (e) {
            stream.innerHTML = '<p style="color:red; font-size:0.8rem; text-align:center;">History synchronization failed.</p>';
        }
    }

    document.getElementById('replyForm').onsubmit = async (e) => {
        e.preventDefault();
        const id = document.getElementById('replyTicketId').value;
        const message = document.getElementById('replyMessage').value;
        
        const btn = e.target.querySelector('button');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            const formData = new FormData();
            formData.append('ticket_id', id);
            formData.append('message', message);
            formData.append('_token', '{{ csrf_token() }}');

            const res = await fetch("{{ route('employee.support.reply') }}", {
                method: 'POST',
                body: formData
            });

            if (res.ok) {
                document.getElementById('replyMessage').value = '';
                loadHistory(id);
            }
        } catch (err) {
            Swal.fire('Error', 'Dispatch failed.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i>';
        }
    };

    function closeTicket() {
        document.getElementById('ticketModal').style.display = 'none';
    }

    window.onclick = (e) => {
        if (e.target === document.getElementById('ticketModal')) closeTicket();
    }
</script>
