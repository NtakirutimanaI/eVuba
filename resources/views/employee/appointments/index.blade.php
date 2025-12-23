@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>My Assigned Activities</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Manage your daily appointments and client engagements.</p>
        </div>
        <div class="header-actions">
            <div class="glass-panel" style="padding: 5px 15px; display: flex; align-items: center; gap: 15px;">
                <div class="stat-item">
                    <span style="font-size: 0.8rem; color: var(--secondary);">Total Engagements</span>
                    <strong style="display: block; font-size: 1.1rem; color: var(--primary);">{{ $appointments->total() }}</strong>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-top: 1rem; padding: 12px 20px; border-radius: 12px; background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.2); display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Focus Mode Top Section --}}
    @php
        $nextUp = $appointments->where('status', '!=', 'completed')->sortBy('scheduled_at')->first();
    @endphp

    <div class="focus-mode-container" style="margin-top: 2rem;">
        <div class="primary-target-card">
            <div class="target-glow"></div>
            <div class="target-header">
                <span class="pulse-tag"><i class="fas fa-satellite-dish"></i> Next Mission Critical</span>
                <span class="target-time">{{ $nextUp ? \Carbon\Carbon::parse($nextUp->scheduled_at)->diffForHumans() : 'No pending missions' }}</span>
            </div>
            
            @if($nextUp)
                <div class="target-main">
                    <div class="target-info">
                        <h2 class="target-title">{{ $nextUp->title }}</h2>
                        <p class="target-desc">{{ Str::limit($nextUp->description, 150) }}</p>
                        <div class="target-client">
                            <i class="fas fa-user-shield"></i> {{ $nextUp->user->name ?? 'External Client' }}
                        </div>
                    </div>
                    <div class="target-actions">
                        <button class="mega-btn" onclick="viewDetails({{ $nextUp->id }})">
                            <i class="fas fa-expand"></i> Deep Dive
                        </button>
                        <button class="mega-btn success" onclick="quickComplete({{ $nextUp->id }})">
                            <i class="fas fa-check-circle"></i> Mark Final
                        </button>
                    </div>
                </div>
            @else
                <div style="text-align:center; padding: 40px; color: rgba(255,255,255,0.6);">
                    <i class="fas fa-calendar-check" style="font-size: 3rem; margin-bottom: 15px;"></i>
                    <p>All objectives cleared for now. Stand by for new assignments.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Agenda Stream --}}
    <div style="margin-top: 3rem; background: var(--white); border-radius: 30px; padding: 40px; border: 1px solid var(--glass-border);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px;">
            <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: var(--dark);">Agenda Stream</h3>
            <div class="mega-search" style="max-width: 300px; flex: 1; background: var(--light);">
                <i class="fas fa-search" style="color: var(--secondary);"></i>
                <input type="text" id="appointmentSearch" placeholder="Scan agenda..." class="search-input" style="background: transparent; border: none; padding: 10px;">
            </div>
        </div>

        <div class="agenda-stream">
            @forelse($appointments as $appointment)
                <div class="agenda-item appointment-row {{ $appointment->status == 'completed' ? 'archived' : '' }}">
                    <div class="agenda-time">
                        <span class="clock">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('H:i') }}</span>
                        <span class="period">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('A') }}</span>
                    </div>
                    
                    <div class="agenda-connector">
                        <div class="connector-dot {{ $appointment->status }}"></div>
                        <div class="connector-line"></div>
                    </div>

                    <div class="agenda-card">
                        <div class="agenda-main">
                            <h4>{{ $appointment->title }}</h4>
                            <div class="agenda-meta">
                                <span><i class="far fa-user"></i> {{ $appointment->user->name ?? 'Guest' }}</span>
                                <span><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('M d') }}</span>
                            </div>
                        </div>
                        <div class="agenda-actions">
                            <button class="stream-btn" onclick="openHelpModal({{ $appointment->id }}, '{{ addslashes($appointment->title) }}')" title="Support">
                                <i class="fas fa-headset"></i>
                            </button>
                            <button class="stream-btn" onclick="viewDetails({{ $appointment->id }})" title="Details">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            @if($appointment->status != 'completed')
                                <button class="stream-btn success" onclick="quickComplete({{ $appointment->id }})">
                                    <i class="fas fa-check"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align:center; padding: 60px; opacity: 0.4;">
                    <i class="fas fa-layer-group" style="font-size: 3rem; margin-bottom: 20px;"></i>
                    <p>No agenda items detected in the current stream.</p>
                </div>
            @endforelse
        </div>

        <div style="margin-top: 30px; border-top: 1px solid var(--glass-border); padding-top: 25px;">
            {{ $appointments->links() }}
        </div>
    </div>
</div>

{{-- Modern Help Modal --}}
<div id="helpModal" class="modal-overlay">
    <div class="pro-modal">
        <div class="modal-header">
            <h3><i class="fas fa-life-ring" style="color: var(--warning);"></i> Engagement Support</h3>
            <button class="close-modal" onclick="closeHelpModal()">&times;</button>
        </div>
        <form id="helpForm" method="POST" action="{{ route('employee.appointments.help') }}">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="appointment_id" id="help_appointment_id">
                <div class="form-group">
                    <label>Activity Context</label>
                    <input type="text" id="help_title" readonly class="glass-input" style="background: var(--light) !important;">
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <label>Support Message / Feedback</label>
                    <textarea name="message" id="help_message" rows="5" class="glass-input" placeholder="Explain what support you need for this activity..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeHelpModal()">Cancel</button>
                <button type="submit" class="btn-submit">Dispatch Request</button>
            </div>
        </form>
    </div>
</div>

{{-- Details Modal --}}
<div id="detailsModal" class="modal-overlay">
    <div class="pro-modal" style="max-width: 600px;">
        <div class="modal-header">
            <h3><i class="fas fa-info-circle" style="color: var(--primary);"></i> Engagement Details</h3>
            <button class="close-modal" onclick="closeDetailsModal()">&times;</button>
        </div>
        <div class="modal-body" id="detailsContent">
            {{-- Loaded via AJAX --}}
            <div style="text-align:center; padding: 2rem;">
                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary);"></i>
                <p>Retrieving information...</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeDetailsModal()">Close</button>
        </div>
    </div>
</div>

<style>
    /* Mission Command Focus Mode */
    .primary-target-card { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-radius: 30px; padding: 40px; position: relative; overflow: hidden; color: white; border: 1px solid rgba(255,255,255,0.1); }
    .target-glow { position: absolute; top: -50%; right: -20%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%); pointer-events: none; }
    
    .target-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .pulse-tag { background: rgba(99, 102, 241, 0.2); color: #818cf8; padding: 6px 15px; border-radius: 20px; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 8px; }
    .pulse-tag i { animation: pulseSate 2s infinite; }
    @keyframes pulseSate { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(1.2); } 100% { opacity: 1; transform: scale(1); } }
    .target-time { font-size: 0.85rem; color: rgba(255,255,255,0.5); font-weight: 600; }

    .target-main { display: flex; justify-content: space-between; align-items: flex-end; gap: 40px; }
    .target-title { font-size: 2.2rem; font-weight: 900; margin: 0 0 15px; line-height: 1.1; letter-spacing: -1px; }
    .target-desc { font-size: 1rem; color: rgba(255,255,255,0.7); max-width: 600px; line-height: 1.6; margin: 0 0 20px; }
    .target-client { font-size: 0.9rem; font-weight: 700; color: #818cf8; display: flex; align-items: center; gap: 8px; }

    .target-actions { display: flex; flex-direction: column; gap: 10px; min-width: 200px; }
    .mega-btn { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 14px 25px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px; }
    .mega-btn:hover { background: white; color: black; transform: translateY(-3px); }
    .mega-btn.success { background: #10b981; border: none; }
    .mega-btn.success:hover { background: #059669; color: white; }

    /* Agenda Stream */
    .agenda-stream { position: relative; margin-top: 10px; }
    .agenda-item { display: flex; gap: 30px; margin-bottom: 25px; align-items: center; transition: 0.3s; }
    .agenda-item.archived { opacity: 0.4; filter: grayscale(1); }
    .agenda-time { width: 60px; text-align: right; }
    .agenda-time .clock { display: block; font-size: 1.25rem; font-weight: 900; color: var(--dark); line-height: 1; }
    .agenda-time .period { font-size: 0.7rem; font-weight: 700; color: var(--secondary); text-transform: uppercase; }

    .agenda-connector { display: flex; flex-direction: column; align-items: center; height: 100%; align-self: stretch; }
    .connector-dot { width: 12px; height: 12px; border-radius: 50%; border: 3px solid var(--glass-border); background: white; position: relative; z-index: 2; margin-top: 5px; }
    .connector-dot.pending { border-color: #f59e0b; }
    .connector-dot.completed { border-color: #10b981; background: #10b981; }
    .connector-line { flex: 1; width: 2px; background: rgba(0,0,0,0.05); margin-top: 5px; margin-bottom: -25px; }
    .agenda-item:last-child .connector-line { display: none; }

    .agenda-card { flex: 1; background: var(--light); border-radius: 20px; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; transition: 0.3s; border: 1px solid transparent; }
    .agenda-card:hover { background: var(--white); border-color: var(--primary); transform: translateX(10px); box-shadow: 0 15px 30px rgba(0,0,0,0.05); }
    .agenda-card h4 { margin: 0 0 5px; font-size: 1.1rem; font-weight: 800; color: var(--dark); }
    .agenda-meta { display: flex; gap: 15px; font-size: 0.75rem; color: var(--secondary); font-weight: 600; }
    
    .agenda-actions { display: flex; gap: 10px; }
    .stream-btn { width: 38px; height: 38px; border-radius: 12px; border: none; background: rgba(0,0,0,0.03); color: var(--secondary); cursor: pointer; transition: 0.2s; display: flex; align-items: center; justify-content: center; }
    .stream-btn:hover { background: var(--dark); color: white; }
    .stream-btn.success:hover { background: #10b981; }

    /* Modal Styling */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(10px);
        z-index: 10001;
        align-items: center;
        justify-content: center;
    }
    .pro-modal {
        background: var(--white);
        width: 100%;
        max-width: 600px;
        border-radius: 30px;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        overflow: hidden;
        animation: modalFadeIn 0.3s ease-out;
    }
    .modal-header { padding: 30px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center; }
    .close-modal { background: none; border: none; font-size: 2rem; color: var(--secondary); cursor: pointer; height: 40px; width: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; }
    .close-modal:hover { background: var(--light); }
    .modal-body { padding: 40px; max-height: 60vh; overflow-y: auto; }
    .modal-footer { padding: 25px 30px; background: var(--light); display: flex; justify-content: flex-end; gap: 15px; }
    
    .glass-input { width: 100%; padding: 15px; border-radius: 15px; border: 1px solid var(--glass-border); background: var(--white); font-family: inherit; font-size: 0.95rem; }
    .btn-submit { background: var(--primary); color: white; border: none; padding: 12px 25px; border-radius: 12px; font-weight: 800; cursor: pointer; }
    .btn-cancel { background: transparent; color: var(--secondary); border: none; padding: 12px 25px; cursor: pointer; font-weight: 700; }

    @keyframes modalFadeIn { from { opacity: 0; transform: translateY(40px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Search Functionality
    document.getElementById('appointmentSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.appointment-row');

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    function openHelpModal(id, title){
        const modal = document.getElementById('helpModal');
        modal.style.display = 'flex';
        document.getElementById('help_appointment_id').value = id;
        document.getElementById('help_title').value = title;
    }

    function closeHelpModal(){
        document.getElementById('helpModal').style.display = 'none';
    }

    async function viewDetails(id) {
        const modal = document.getElementById('detailsModal');
        const content = document.getElementById('detailsContent');
        modal.style.display = 'flex';
        content.innerHTML = '<div style="text-align:center; padding: 2rem;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary);"></i><p>Retrieving information...</p></div>';

        try {
            const response = await fetch(`/employee/appointments/${id}/details`);
            const result = await response.json();

            if (result.success) {
                const app = result.data;
                content.innerHTML = `
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <label style="font-size: 0.75rem; color: var(--secondary); text-transform: uppercase;">Engagement Title</label>
                            <div style="font-weight: 700; font-size: 1.1rem; margin-top: 5px;">${app.title}</div>
                        </div>
                        <div>
                            <label style="font-size: 0.75rem; color: var(--secondary); text-transform: uppercase;">Work Status</label>
                            <div style="margin-top: 5px;">
                                <span class="status-badge status-${app.status === 'completed' ? 'active' : 'pending'}">${app.status}</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 25px;">
                        <label style="font-size: 0.75rem; color: var(--secondary); text-transform: uppercase;">Description</label>
                        <div style="margin-top: 8px; color: var(--dark); line-height: 1.6; background: var(--light); padding: 15px; border-radius: 12px;">
                            ${app.description || '<span style="opacity:0.5;">No detailed brief provided for this activity.</span>'}
                        </div>
                    </div>
                    <div style="margin-top: 25px; border-top: 1px dashed var(--glass-border); padding-top: 20px;">
                        <label style="font-size: 0.75rem; color: var(--secondary); text-transform: uppercase;">Client Information</label>
                        <div style="display: flex; align-items: center; gap: 12px; margin-top: 10px;">
                            <div class="user-avatar-placeholder" style="width: 40px; height: 40px; background: var(--primary); font-size: 14px;">${app.customer_name.charAt(0)}</div>
                            <div>
                                <div style="font-weight: 700;">${app.customer_name}</div>
                                <div style="font-size: 0.8rem; color: var(--secondary);">${app.customer_email}</div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 20px;">
                        <label style="font-size: 0.75rem; color: var(--secondary); text-transform: uppercase;">Timeline</label>
                        <div style="font-weight: 600; margin-top: 5px;">
                            <i class="far fa-calendar-alt" style="margin-right: 5px; color: var(--primary);"></i> ${app.scheduled_at}
                        </div>
                    </div>
                `;
            }
        } catch (err) {
            content.innerHTML = '<div style="color:red; text-align:center; padding: 2rem;">Failed to load details. Please try again.</div>';
        }
    }

    function closeDetailsModal(){
        document.getElementById('detailsModal').style.display = 'none';
    }

    async function quickComplete(id) {
        const result = await Swal.fire({
            title: 'Complete Activity?',
            text: "Marking this engagement as completed will notify your manager.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'var(--success)',
            confirmButtonText: 'Yes, Complete it!'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/employee/appointments/${id}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ status: 'completed' })
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Great Job!', text: data.message, timer: 1500, showConfirmButton: false })
                    .then(() => location.reload());
                } else {
                    Swal.fire('Update Failed', data.message || 'Operation could not be completed.', 'error');
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Update failed. Please try again later.', 'error');
            }
        }
    }

    window.onclick = function(event){
        const helpModal = document.getElementById('helpModal');
        const detailsModal = document.getElementById('detailsModal');
        if(event.target == helpModal) closeHelpModal();
        if(event.target == detailsModal) closeDetailsModal();
    }
</script>
