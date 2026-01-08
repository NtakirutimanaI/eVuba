@include('layouts.header')
@include('layouts.sidebar')

    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --light: #f1f5f9;
            --white: #ffffff;
            --glass-border: rgba(0,0,0,0.08); /* Light mode border */
        }
        [data-theme="dark"] {
            --dark: #f1f5f9;       /* Text becomes light */
            --light: #1e293b;      /* Backgrounds become dark */
            --white: #0f172a;      /* Cards become darker */
            --glass-border: rgba(255,255,255,0.1);
            --secondary: #94a3b8;
        }

        .pro-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 25px;
            margin-top: 20px;
            align-items: start;
        }
        @media (max-width: 1024px) {
            .pro-layout { grid-template-columns: 1fr; }
        }

        .pipeline-card {
            background: var(--white);
            border-radius: 16px;
            border: 1px solid var(--glass-border);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* --- Sidebar & Cards --- */
        .sidebar-card {
            background: var(--white);
            border-radius: 16px;
            padding: 20px;
            border: 1px solid var(--glass-border);
            margin-bottom: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .action-tile {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            border-radius: 12px;
            background: var(--light);
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            color: var(--dark);
            border: 1px solid transparent;
        }
        .action-tile:hover {
            transform: translateY(-2px);
            background: var(--white);
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
        }
        .action-tile.primary {
            background: linear-gradient(135deg, var(--primary), #6366f1);
            color: white;
        }
        .action-tile.primary .tile-icon { color: white; background: rgba(255,255,255,0.2); }
        .action-tile.primary .tile-desc { color: rgba(255,255,255,0.9); }

        .tile-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: var(--primary);
        }
        .tile-info { flex: 1; }
        .tile-title { font-weight: 700; font-size: 0.95rem; line-height: 1.2; }
        .tile-desc { font-size: 0.75rem; color: var(--secondary); margin-top: 2px; }

        .stat-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--glass-border);
        }
        .stat-row:last-child { border-bottom: none; }
        .stat-val { font-weight: 800; color: var(--dark); }
        .stat-key { color: var(--secondary); font-size: 0.85rem; }

        /* --- Tables --- */
        .pro-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        .pro-table thead th {
            text-align: left;
            padding: 15px;
            background: var(--light);
            color: var(--secondary);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--glass-border);
        }
        .pro-table tbody td {
            padding: 15px;
            border-bottom: 1px solid var(--glass-border);
            vertical-align: middle;
            color: var(--dark);
        }
        .pro-table tbody tr:hover {
            background: rgba(0,0,0,0.01);
        }
        .pro-table tbody tr:last-child td { border-bottom: none; }

        /* --- Modals --- */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
        }
        .pro-modal {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            width: 95%; /* Responsive width */
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h3 { margin: 0; font-size: 1.1rem; color: var(--dark); font-weight: 700; }
        .close-modal {
            background: none; border: none; font-size: 1.5rem; color: var(--secondary); cursor: pointer;
        }

        /* --- Form Elements --- */
        .pro-input {
            width: 100%;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: var(--white);
            color: var(--dark);
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .pro-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .search-input {
            width: 100%;
            padding: 8px 12px 8px 35px;
            border-radius: 8px;
            border: 1px solid transparent;
            background: var(--light);
            font-size: 0.9rem;
        }
        .search-input:focus { border-color: var(--primary); background: var(--white); outline: none; }
        .mega-search { position: relative; }
        .mega-search i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--secondary); opacity: 0.7; }

        .action-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .action-btn:hover { opacity: 0.9; }
        .action-btn:disabled { opacity: 0.7; cursor: not-allowed; }

        .icon-btn {
            width: 32px; height: 32px;
            border-radius: 8px;
            border: none;
            background: transparent;
            color: var(--secondary);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .icon-btn:hover { background: var(--light); color: var(--primary); }
        .icon-btn.delete:hover { background: #fee2e2; color: #ef4444; }

        .form-group label {
            display: block; margin-bottom: 6px; font-size: 0.85rem; font-weight: 600; color: var(--secondary);
        }

        /* --- Misc --- */
        .status-badge { padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .status-open { background: #fee2e2; color: #991b1b; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-resolved { background: #ecfdf5; color: #065f46; }

        .file-label {
            cursor: pointer; color: var(--primary); font-size: 0.9rem; font-weight: 600;
        }
    </style>

    <div style="width: 80%; margin-left: 222px; padding-top: 30px;">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-headset" style="color: var(--primary);"></i> Support Operations Center</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Centralized resolution protocols & live advocacy channels.</p>
        </div>
    </div>

    <div class="pro-layout">
        {{-- MAIN COLUMN: TICKET LIST --}}
        <div class="pipeline-card">
            <div style="padding: 25px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--dark);"><i class="fas fa-list-alt"></i> Resolution Pipeline</h3>
                    <p style="margin: 5px 0 0; font-size: 0.8rem; color: var(--secondary);">Track status of active resolution protocols.</p>
                </div>
                <div class="mega-search" style="max-width: 250px; margin: 0;">
                    <i class="fas fa-search"></i>
                    <input type="text" id="ticketSearch" placeholder="Search pipeline..." class="search-input">
                </div>
            </div>
            
            <div style="flex: 1; overflow-y: auto;">
                <table class="pro-table">
                    <thead>
                        <tr>
                            <th style="padding-left: 25px;">Track ID</th>
                            <th>Subject & Classification</th>
                            <th>Status</th>
                            <th>Deployment</th>
                            <th style="text-align: right; padding-right: 25px;">Ops</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $t)
                            <tr class="ticket-row" data-search="{{ strtolower($t->ticket_no . ' ' . $t->subject) }}">
                                <td style="padding-left: 25px;">
                                    <span style="font-family: monospace; font-size: 0.8rem; color: var(--primary); font-weight: 700; background: rgba(99,102,241,0.1); padding: 3px 8px; border-radius: 6px;">
                                        #{{ $t->ticket_no }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: var(--dark);">{{ $t->subject }}</div>
                                    <div style="font-size: 0.7rem; color: var(--secondary);">
                                        <i class="fas fa-tag"></i> {{ optional($t->category)->name }}
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($t->status) }}">
                                        {{ ucfirst($t->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 0.8rem; font-weight: 600;">{{ $t->created_at->format('M d') }}</div>
                                </td>
                                <td style="text-align: right; padding-right: 25px;">
                                    <div style="display: flex; gap: 5px; justify-content: flex-end;">
                                        <button class="icon-btn" onclick="viewTicketIntel({{ $t->id }})" title="View Resolution Intel"><i class="fas fa-eye"></i></button>
                                        <button type="button" class="icon-btn delete" onclick="confirmDelete('{{ $t->id }}')" title="Terminate Request"><i class="fas fa-trash-alt"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 60px; text-align: center;">
                                    <div style="opacity: 0.1; font-size: 3rem; margin-bottom: 15px;"><i class="fas fa-folder-open"></i></div>
                                    <p style="color: var(--secondary);">No active protocols found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SIDEBAR COLUMN: ACTIONS --}}
        <div>
            <!-- Actions Card -->
            <div class="sidebar-card">
                <h4 style="margin: 0 0 20px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary); font-weight: 800;">Operations</h4>
                
                <div class="action-tile primary" onclick="openNewTicketModal()">
                    <div class="tile-icon"><i class="fas fa-plus"></i></div>
                    <div class="tile-info">
                        <div class="tile-title">New Request</div>
                        <div class="tile-desc">Initialize resolution protocol</div>
                    </div>
                </div>

                <a href="https://wa.me/250786325291" target="_blank" class="action-tile" style="background: linear-gradient(135deg, #dcfce7, #f0fdf4); border: 1px solid #bbf7d0;">
                    <div class="tile-icon" style="background: #25D366; color: white;"><i class="fab fa-whatsapp"></i></div>
                    <div class="tile-info">
                        <div class="tile-title" style="color: #166534;">Live Chat</div>
                        <div class="tile-desc" style="color: #15803d;">Connect via WhatsApp</div>
                    </div>
                </a>
            </div>

            <!-- Intelligence Card -->
            <div class="sidebar-card">
                 <h4 style="margin: 0 0 20px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary); font-weight: 800;">Intelligence</h4>
                 
                 <div class="stat-row">
                     <span class="stat-key">Active Protocols</span>
                     <span class="stat-val" style="color: #f59e0b;">{{ $tickets->whereIn('status', ['open', 'pending'])->count() }}</span>
                 </div>
                 <div class="stat-row">
                     <span class="stat-key">Resolved</span>
                     <span class="stat-val" style="color: #10b981;">{{ $tickets->where('status', 'resolved')->count() }}</span>
                 </div>
                 <div class="stat-row">
                     <span class="stat-key">Total Archives</span>
                     <span class="stat-val">{{ $tickets->count() }}</span>
                 </div>
            </div>
            
             <!-- Help Card -->
            <div class="sidebar-card" style="background: transparent; border: 2px dashed var(--glass-border); text-align: center;">
                 <p style="color: var(--secondary); font-size: 0.9rem; margin-bottom: 0;">Need immediate verbal assistance?</p>
                 <h3 style="margin: 5px 0; color: var(--primary);">+250 786 325 291</h3>
            </div>
        </div>
    </div>
    </div>

    {{-- New Ticket Modal (Replaces old hidden panel) --}}
    <div id="newTicketModal" class="modal-overlay">
        <div class="pro-modal" style="max-width: 700px;">
             <div class="modal-header">
                <h3><i class="fas fa-pen-nib"></i> Initialize Resolution Request</h3>
                <button class="close-modal" onclick="closeNewTicketModal()">&times;</button>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <form id="customer-ticket-form" onsubmit="submitTicket(event)" enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label>Identity</label>
                            <input type="text" name="name" class="pro-input" value="{{ auth()->user()->name }}" readonly style="background: var(--light);">
                        </div>
                        <div class="form-group">
                            <label>Internal Track ID</label>
                            <input type="text" id="ticket_no_display" class="pro-input" readonly style="background: var(--light); font-family: monospace; font-weight: 700;">
                        </div>
                    </div>

                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 20px;">
                         <div class="form-group">
                            <label>Classification</label>
                            <select name="category_id" class="pro-input" required>
                                <option value="">-- Select --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" name="subject" class="pro-input" placeholder="Summarize issue..." required>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Operational Context</label>
                        <textarea name="description" class="pro-input" rows="5" placeholder="Detailed description..." required></textarea>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="file-manifest">
                            <label class="file-label">
                                <i class="fas fa-paperclip"></i> Upload Evidence
                                <input type="file" name="attachment" style="display: none;">
                            </label>
                            <span id="fileName" style="font-size: 0.75rem; color: var(--secondary); margin-left: 10px;"></span>
                        </div>
                        <button type="submit" class="action-btn btn-primary">
                            Transmit Request <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Resolution Intel Modal (Chat) --}}
    <div id="intelModal" class="modal-overlay">
        <div class="pro-modal chat-modal" style="max-width: 900px; display: flex; flex-direction: row; height: 80vh;">
            {{-- Sidebar --}}
             <div style="width: 250px; background: var(--light); padding: 25px; border-right: 1px solid var(--glass-border); display: flex; flex-direction: column;">
                <div style="flex: 1;">
                    <div style="font-size: 0.75rem; text-transform: uppercase; color: var(--secondary); font-weight: 700;">Ticket #</div>
                    <div id="intel-ticket-no" style="font-family: monospace; font-weight: 900; font-size: 1rem; color: var(--primary); margin-bottom: 20px;">...</div>
                    
                    <div style="font-size: 0.75rem; text-transform: uppercase; color: var(--secondary); font-weight: 700;">Status</div>
                    <div id="intel-status" style="margin-bottom: 20px;"></div>

                    <div style="font-size: 0.75rem; text-transform: uppercase; color: var(--secondary); font-weight: 700;">Category</div>
                    <div id="intel-category" style="font-weight: 700; color: var(--dark); margin-bottom: 20px;">...</div>
                 </div>
                 <div id="intel-attachment">
                     <span id="attachment-link"></span>
                 </div>
            </div>

            {{-- Main Chat --}}
            <div style="flex: 1; display: flex; flex-direction: column; background: var(--white);">
                <div class="modal-header" style="padding: 15px 25px; border-bottom: 1px solid var(--glass-border);">
                     <h3 id="intel-subject" style="margin: 0; font-size: 1rem; font-weight: 800;">...</h3>
                     <button class="close-modal" onclick="closeIntelModal()">&times;</button>
                </div>
                <div id="intel-chat-window" style="flex: 1; padding: 25px; overflow-y: auto; background: #f8fafc; display: flex; flex-direction: column; gap: 15px;"></div>
                <div class="chat-input-area" style="padding: 15px 25px; border-top: 1px solid var(--glass-border);">
                    <form id="replyForm" onsubmit="submitReply(event)">
                        @csrf
                        <div style="display: flex; gap: 10px;">
                            <textarea id="replyMessage" class="pro-input" placeholder="Reply..." required style="height: 50px; resize: none;"></textarea>
                            <button type="submit" class="action-btn btn-primary" style="width: 50px; height: 50px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:12px;"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let activeTicketId = null;

        function getSwalColors() {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            return {
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#e2e8f0' : '#334155'
            };
        }

        // --- Layout Actions ---
        function openNewTicketModal() {
             document.getElementById('newTicketModal').style.display = 'flex';
        }
        function closeNewTicketModal() {
             document.getElementById('newTicketModal').style.display = 'none';
        }

        function closeIntelModal() {
            document.getElementById('intelModal').style.display = 'none';
            activeTicketId = null;
        }

        // --- Ticket ID Generation ---
        window.addEventListener('DOMContentLoaded', () => {
            const prefix='TCK', year=new Date().getFullYear(), rand=Math.floor(Math.random()*(99999-10000+1))+10000;
            const ticketNo=`${prefix}-${year}-${rand}`;
            try {
                if(document.getElementById('ticket_no_display')) {
                    document.getElementById('ticket_no_display').value = ticketNo;
                    const input = document.createElement('input'); 
                    input.type='hidden'; input.name='ticket_no'; input.value=ticketNo;
                    document.getElementById('customer-ticket-form').appendChild(input);
                }
            } catch(e){}
        });

        // --- Form Submissions ---
        async function submitTicket(e) {
            e.preventDefault();
            console.log("Submitting ticket...");
            
            const form = e.target;
            const formData = new FormData(form);
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            
            // Explicit Validation to handle 'novalidate'
            const category = formData.get('category_id');
            if (!category || category === "") {
                 Swal.fire({ icon: 'warning', title: 'Missing Classification', text: 'Please select a classification category for this request.', background: getSwalColors().background, color: getSwalColors().color });
                 return;
            }

            const subject = formData.get('subject');
            if (!subject || !subject.trim()) {
                 Swal.fire({ icon: 'warning', title: 'Subject Required', text: 'Please provide a subject line for your request.', background: getSwalColors().background, color: getSwalColors().color });
                 return;
            }

            const description = formData.get('description');
            if (!description || !description.trim()) {
                 Swal.fire({ icon: 'warning', title: 'Description Required', text: 'Please provide the operational context/description.', background: getSwalColors().background, color: getSwalColors().color });
                 return;
            }
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Transmitting...'; 
            btn.disabled = true;

            // Frontend Validation: File size
            const fileInput = form.querySelector('input[name="attachment"]');
            if (fileInput && fileInput.files[0]) {
                if (fileInput.files[0].size > 10 * 1024 * 1024) { // 10MB restriction
                     const msg = "The attached file exceeds the maximum allowance of 10MB. Please compress or select a smaller file.";
                     if(typeof Swal !== 'undefined') {
                         Swal.fire({ icon: 'warning', title: 'File Too Large', text: msg, background: getSwalColors().background, color: getSwalColors().color });
                     } else {
                         alert(msg);
                     }
                     btn.innerHTML = originalText;
                     btn.disabled = false;
                     return;
                }
            }

            // Safety check: Ensure ticket_no is in formData
            if (!formData.has('ticket_no')) {
                const prefix='TCK', year=new Date().getFullYear(), rand=Math.floor(Math.random()*(99999-10000+1))+10000;
                formData.append('ticket_no', `${prefix}-${year}-${rand}`);
            }

            try {
                const response = await fetch("{{ route('customer.support.store') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                });

                console.log("Response status:", response.status);
                const data = await response.json().catch(err => {
                    console.error("JSON parse error:", err);
                    return {};
                });

                if (response.ok) {
                    closeNewTicketModal();
                    if(typeof Swal !== 'undefined') {
                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Request Logged', 
                            text: data.message || 'Protocol initialized successfully.',
                            background: getSwalColors().background, 
                            color: getSwalColors().color 
                        }).then(() => location.reload());
                    } else {
                        alert("Success: " + (data.message || 'Protocol initialized.'));
                        location.reload();
                    }
                } else {
                     let errorMsg = 'Submission failed.';
                     if (data.errors) {
                         errorMsg = Object.values(data.errors).flat().join('\n');
                     } else if (data.message) {
                         errorMsg = data.message;
                     }
                     throw new Error(errorMsg);
                }
            } catch (err) {
                 console.error("Submit error:", err);
                 if(typeof Swal !== 'undefined') {
                     Swal.fire({ 
                         icon: 'error', 
                         title: 'Transmission Error', 
                         text: err.message || 'Unable to secure connection.',
                         background: getSwalColors().background, 
                         color: getSwalColors().color 
                     });
                 } else {
                     alert("Error: " + (err.message || 'Unable to secure connection.'));
                 }
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        // --- Ticket Views / Chat ---
        async function viewTicketIntel(id) {
            activeTicketId = id;
            document.getElementById('intelModal').style.display = 'flex';
            const win = document.getElementById('intel-chat-window');
            win.innerHTML = '<div style="text-align:center; padding-top:50px; opacity:0.5;"><i class="fas fa-spinner fa-spin"></i> Loading intel...</div>';
            
            try {
                const res = await fetch(`/customer/support/ajax/ticket/${id}`);
                const data = await res.json();
                
                document.getElementById('intel-ticket-no').textContent = '#' + data.ticket_no;
                document.getElementById('intel-subject').textContent = data.subject;
                document.getElementById('intel-category').textContent = data.category?.name || 'General';
                document.getElementById('intel-status').innerHTML = `<span class="status-badge status-${data.status.toLowerCase()}">${data.status}</span>`;
                
                if(data.attachment) document.getElementById('attachment-link').innerHTML = `<a href="${data.attachment}" target="_blank" style="color:var(--primary); font-size:0.8rem;"><i class="fas fa-paperclip"></i> View Evidence</a>`;
                else document.getElementById('attachment-link').innerHTML = '<span style="color:var(--secondary); font-size:0.8rem;">No evidence</span>';

                // Chat
                win.innerHTML = '';
                // Description
                const d = document.createElement('div'); d.className='message customer';
                d.innerHTML = `<span class="msg-meta">Initial Request</span>${data.description}`;
                win.appendChild(d);

                data.replies.forEach(r => {
                    const m = document.createElement('div');
                    m.className = r.is_staff_reply ? 'message staff' : 'message customer';
                    const time = new Date(r.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
                    m.innerHTML = `<span class="msg-meta">${r.is_staff_reply ? 'Advocate' : 'You'} • ${time}</span>${r.message}`;
                    win.appendChild(m);
                });
                win.scrollTop = win.scrollHeight;

            } catch(e) {
                win.innerHTML = 'Error loading data.';
            }
        }

        async function submitReply(e) {
            e.preventDefault();
            const msgInput = document.getElementById('replyMessage');
            if(!activeTicketId) return;

            try {
                await fetch(`/customer/support/ajax/reply/${activeTicketId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ message: msgInput.value })
                });
                msgInput.value = '';
                viewTicketIntel(activeTicketId);
            } catch(e) {}
        }

        // --- Delete ---
        function confirmDelete(id) {
             const colors = getSwalColors();
             Swal.fire({
                title: 'Terminate Protocol?',
                icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Select Terminate',
                background: colors.background, color: colors.color
             }).then(async (r) => {
                 if(r.isConfirmed) {
                     await fetch(`{{ url('customer/support') }}/${id}`, {
                        method: 'DELETE', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept':'application/json'}
                     });
                     location.reload();
                 }
             });
        }

        // --- Search ---
        document.getElementById('ticketSearch').addEventListener('keyup', function() {
            const val = this.value.toLowerCase();
            document.querySelectorAll('.ticket-row').forEach(row => {
                row.style.display = row.dataset.search.includes(val) ? '' : 'none';
            });
        });

        // --- Global Clicks ---
        window.onclick = (e) => {
            if(e.target === document.getElementById('newTicketModal')) closeNewTicketModal();
            if(e.target === document.getElementById('intelModal')) closeIntelModal();
        }

        // --- File Input ---
        document.querySelectorAll('input[type="file"]').forEach(i => i.addEventListener('change', function(){
            document.getElementById('fileName').textContent = this.files[0] ? this.files[0].name : '';
        }));
    </script>
