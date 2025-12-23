@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-headset" style="color: var(--primary);"></i> Support & Advocacy</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Dedicated resolution channels for your operational inquiries.</p>
        </div>
        <div class="header-actions">
            <button class="action-btn btn-primary" onclick="toggleSupportForm()" style="text-decoration:none; padding: 0 25px; border-radius: 12px; height: 46px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-plus-circle"></i> New Support Request
            </a>
        </div>
    </div>

    {{-- Ticket Submission Panel (Hidden by default) --}}
    <div id="supportFormPanel" class="glass-panel" style="display: none; margin-top: 2rem; padding: 30px; border-radius: 20px; border: 1px solid var(--primary);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h3 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: var(--dark);"><i class="fas fa-pen-nib"></i> Initialize Resolution Request</h3>
            <button class="icon-btn" onclick="toggleSupportForm()"><i class="fas fa-times"></i></button>
        </div>
        
        <form id="customer-ticket-form" action="{{ route('customer.support.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr 200px; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label>Identity Manifest</label>
                    <input type="text" name="name" class="pro-input" value="{{ auth()->user()->name }}" readonly style="background: var(--light);">
                </div>
                <div class="form-group">
                    <label>Communication Channel</label>
                    <input type="email" name="email" class="pro-input" value="{{ auth()->user()->email }}" readonly style="background: var(--light);">
                </div>
                <div class="form-group">
                    <label>Internal Track ID</label>
                    <input type="text" id="ticket_no_display" class="pro-input" readonly style="background: var(--light); font-family: monospace; font-weight: 700;">
                </div>
            </div>

            <div class="form-row" style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label>Inquiry Classification</label>
                    <select name="category_id" class="pro-input" required>
                        <option value="">-- Classification --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Inquiry Subject</label>
                    <input type="text" name="subject" class="pro-input" placeholder="Summarize your technical requirement..." required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label>Operational Description</label>
                <textarea name="description" class="pro-input" rows="5" placeholder="Provide detailed technical context..." required></textarea>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="file-manifest">
                    <label class="file-label">
                        <i class="fas fa-paperclip"></i> Attach Technical Evidence
                        <input type="file" name="attachment" style="display: none;">
                    </label>
                    <span id="fileName" style="font-size: 0.75rem; color: var(--secondary); margin-left: 10px;"></span>
                </div>
                <button type="submit" class="action-btn btn-primary" style="padding: 0 40px; border-radius: 14px; height: 50px; font-weight: 800;">
                    Transmit Request <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>

    {{-- Resolution Pipeline --}}
    <div style="margin-top: 2rem;">
        <div class="glass-panel" style="padding: 0; overflow: hidden; border-radius: 20px;">
            <div style="padding: 20px 25px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1rem; font-weight: 800; color: var(--dark);"><i class="fas fa-history"></i> Resolution Pipeline</h3>
                <div class="mega-search" style="max-width: 300px; margin: 0;">
                    <i class="fas fa-search"></i>
                    <input type="text" id="ticketSearch" placeholder="Search track IDs or subjects..." class="search-input">
                </div>
            </div>
            
            <table class="pro-table">
                <thead>
                    <tr>
                        <th style="padding-left: 25px;">Track ID</th>
                        <th>Subject & Classification</th>
                        <th>Status</th>
                        <th>Deployment Date</th>
                        <th style="text-align: right; padding-right: 25px;">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $t)
                        <tr class="ticket-row" data-search="{{ strtolower($t->ticket_no . ' ' . $t->subject) }}">
                            <td style="padding-left: 25px;">
                                <span style="font-family: monospace; font-size: 0.8rem; color: var(--secondary); font-weight: 700;">#{{ $t->ticket_no }}</span>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: var(--dark);">{{ $t->subject }}</div>
                                <div style="font-size: 0.7rem; color: var(--secondary); display: flex; align-items: center; gap: 5px;">
                                    <i class="fas fa-tag"></i> {{ optional($t->category)->name }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower($t->status) }}">
                                    {{ ucfirst($t->status) }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 0.85rem; font-weight: 600;">{{ $t->created_at->format('M d, Y') }}</div>
                                <div style="font-size: 0.7rem; color: var(--secondary);">{{ $t->created_at->diffForHumans() }}</div>
                            </td>
                            <td style="text-align: right; padding-right: 25px;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <button class="icon-btn" onclick="viewTicketIntel({{ $t->id }})" title="View Resolution Intel"><i class="fas fa-eye"></i></button>
                                    <form action="{{ route('customer.support.destroy', $t->id) }}" method="POST" style="display:inline-block;" class="delete-ticket-form">
                                        @csrf @method('DELETE')
                                        <button type="button" class="icon-btn delete" onclick="confirmDelete(this)" title="Terminate Request"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 80px; text-align: center;">
                                <div style="opacity: 0.2; transform: scale(1.5); margin-bottom: 20px;"><i class="fas fa-clipboard-check" style="font-size: 3rem;"></i></div>
                                <p style="color: var(--secondary); font-weight: 600;">No resolution requests detected in your history.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Resolution Intel Modal --}}
<div id="intelModal" class="modal-overlay">
    <div class="pro-modal chat-modal" style="max-width: 900px; display: flex; flex-direction: row; height: 80vh;">
        {{-- Sidebar for details --}}
        <div style="width: 300px; background: var(--light); padding: 30px; border-right: 1px solid var(--glass-border); display: flex; flex-direction: column;">
            <div style="flex: 1;">
                <h4 style="margin: 0 0 5px; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary);">Track Identifier</h4>
                <div id="intel-ticket-no" style="font-family: monospace; font-weight: 900; font-size: 1.1rem; color: var(--primary); margin-bottom: 25px;">#TCK-0000</div>
                
                <h4 style="margin: 0 0 5px; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary);">Resolution Status</h4>
                <div id="intel-status" style="margin-bottom: 25px;"></div>

                <h4 style="margin: 0 0 5px; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary);">Classification</h4>
                <div id="intel-category" style="font-weight: 700; color: var(--dark); margin-bottom: 25px;">General</div>

                <h4 style="margin: 0 0 5px; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary);">Assigned Advocate</h4>
                <div id="intel-advocate" style="font-weight: 700; color: var(--dark); margin-bottom: 25px;">Unassigned</div>
            </div>
            
            <div id="intel-attachment" style="background: var(--white); padding: 15px; border-radius: 12px; border: 1px solid var(--glass-border);">
                <div style="font-size: 0.7rem; font-weight: 800; color: var(--secondary); margin-bottom: 8px;">ORIGINAL ATTACHMENT</div>
                <div id="attachment-link">None</div>
            </div>
        </div>

        {{-- Main Area for Chat --}}
        <div style="flex: 1; display: flex; flex-direction: column; background: var(--white);">
            <div class="modal-header" style="padding: 20px 30px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
                <h3 id="intel-subject" style="margin: 0; font-size: 1.1rem; font-weight: 900; color: var(--dark);">Resolution Inquiry Subject</h3>
                <button class="close-modal" onclick="closeIntelModal()">&times;</button>
            </div>
            
            <div id="intel-chat-window" style="flex: 1; padding: 30px; overflow-y: auto; background: #f8fafc; display: flex; flex-direction: column; gap: 20px;">
                {{-- Messages will be injected here --}}
            </div>

            <div class="chat-input-area" style="padding: 20px 30px; border-top: 1px solid var(--glass-border);">
                <form id="replyForm" onsubmit="submitReply(event)">
                    @csrf
                    <div style="display: flex; gap: 15px; align-items: flex-end;">
                        <div style="flex: 1;">
                            <textarea id="replyMessage" class="pro-input" placeholder="Transmit further context..." required style="height: 60px; resize: none;"></textarea>
                        </div>
                        <button type="submit" class="action-btn btn-primary" style="width: 50px; height: 50px; border-radius: 14px; padding: 0;">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let activeTicketId = null;

    // Auto-generate track ID on load
    window.addEventListener('DOMContentLoaded', () => {
        const prefix='TCK', year=new Date().getFullYear(), rand=Math.floor(Math.random()*(99999-10000+1))+10000;
        const ticketNo=`${prefix}-${year}-${rand}`;
        document.getElementById('ticket_no_display').value = ticketNo;
        const input = document.createElement('input'); 
        input.type='hidden'; input.name='ticket_no'; input.value=ticketNo;
        document.getElementById('customer-ticket-form').appendChild(input);
    });

    function toggleSupportForm() {
        const panel = document.getElementById('supportFormPanel');
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        if(panel.style.display === 'block') panel.scrollIntoView({ behavior: 'smooth' });
    }

    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : '';
            document.getElementById('fileName').textContent = fileName;
        });
    });

    async function viewTicketIntel(id) {
        activeTicketId = id;
        document.getElementById('intelModal').style.display = 'flex';
        const chatWindow = document.getElementById('intel-chat-window');
        chatWindow.innerHTML = '<div style="text-align:center; padding-top:100px; color:var(--secondary);"><i class="fas fa-spinner fa-spin fa-2x"></i><br><br>Synchronizing data with resolution servers...</div>';

        try {
            const res = await fetch(`/customer/support/ajax/ticket/${id}`);
            const data = await res.json();
            
            document.getElementById('intel-ticket-no').textContent = '#' + data.ticket_no;
            document.getElementById('intel-subject').textContent = data.subject;
            document.getElementById('intel-category').textContent = data.category?.name || 'General';
            document.getElementById('intel-advocate').textContent = data.assigned_to || 'Pending Assignment';
            document.getElementById('intel-status').innerHTML = `<span class="status-badge status-${data.status.toLowerCase()}">${data.status}</span>`;
            
            if(data.attachment) {
                document.getElementById('attachment-link').innerHTML = `<a href="${data.attachment}" target="_blank" style="text-decoration:none; color:var(--primary); font-weight:700; font-size:0.75rem;"><i class="fas fa-download"></i> Manifest Evidence</a>`;
            } else {
                document.getElementById('attachment-link').textContent = 'None Provided';
            }

            // Build chat
            chatWindow.innerHTML = '';
            
            // Add initial description as first message
            const descMsg = document.createElement('div');
            descMsg.className = 'message customer';
            descMsg.innerHTML = `<span class="msg-meta">System Initiation</span>${data.description}`;
            chatWindow.appendChild(descMsg);

            data.replies.forEach(r => {
                const msg = document.createElement('div');
                msg.className = r.is_staff_reply ? 'message staff' : 'message customer';
                const time = new Date(r.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                msg.innerHTML = `<span class="msg-meta">${r.is_staff_reply ? 'Advocate' : 'You'} • ${time}</span>${r.message}`;
                chatWindow.appendChild(msg);
            });

            chatWindow.scrollTop = chatWindow.scrollHeight;

        } catch (err) {
            chatWindow.innerHTML = '<div style="padding: 20px; color: #ef4444; border: 1px solid #ef4444; border-radius: 12px; background: rgba(239, 68, 68, 0.05);">Communication failure. Unable to synchronize track ID manifest.</div>';
        }
    }

    async function submitReply(e) {
        e.preventDefault();
        const message = document.getElementById('replyMessage').value;
        const btn = e.target.querySelector('button');
        btn.disabled = true;

        try {
            const res = await fetch(`/customer/support/ajax/reply/${activeTicketId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message })
            });
            
            if(res.ok) {
                document.getElementById('replyMessage').value = '';
                viewTicketIntel(activeTicketId); // Refresh
            }
        } finally {
            btn.disabled = false;
        }
    }

    function closeIntelModal() {
        document.getElementById('intelModal').style.display = 'none';
    }

    function confirmDelete(btn) {
        Swal.fire({
            title: 'Terminate Request?',
            text: "This operation will permanently purge the resolution manifest from historical archives.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Terminate',
            cancelButtonText: 'Abort Operation'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        });
    }

    document.getElementById('ticketSearch').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        document.querySelectorAll('.ticket-row').forEach(row => {
            row.style.display = row.dataset.search.includes(filter) ? '' : 'none';
        });
    });

    window.onclick = (e) => {
        if (e.target === document.getElementById('intelModal')) closeIntelModal();
    }
</script>

