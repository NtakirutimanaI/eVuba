@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-calendar-alt" style="color: var(--primary);"></i> Operational Timeline</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Chronological manifest of your scheduled engagements.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('customer.appointments.create') }}" class="action-btn btn-primary" style="text-decoration:none; padding: 0 25px; border-radius: 12px; height: 46px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-plus"></i> Initialize Engagement
            </a>
        </div>
    </div>

    <div class="timeline-container">
        @if($appointments->count())
            @foreach($appointments as $appointment)
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-date">
                        <span class="day">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d') }}</span>
                        <span class="month">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('M') }}</span>
                    </div>
                    <div class="timeline-content glass-panel" style="padding: 20px;">
                        <div class="content-header">
                            <div>
                                <h3 style="margin: 0; font-size: 1.1rem; color: var(--dark); font-weight: 800;">{{ $appointment->title }}</h3>
                                <div style="color: var(--secondary); font-size: 0.85rem; margin-top: 5px;">
                                    <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('H:i') }}
                                    &nbsp;•&nbsp; 
                                    <i class="fas fa-user-tie"></i> {{ $appointment->employee ? $appointment->employee->name : 'Specialist Pending' }}
                                </div>
                            </div>
                            <span class="status-badge status-{{ strtolower($appointment->status ?? 'pending') }}">
                                {{ ucfirst($appointment->status ?? 'pending') }}
                            </span>
                        </div>
                        
                        <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.5; margin: 15px 0;">
                            {{ Str::limit($appointment->description, 150) }}
                        </p>

                        <div class="content-actions">
                            <button class="action-btn-sm" onclick="openViewModal(
                                '{{ addslashes($appointment->title) }}',
                                '{{ addslashes($appointment->description) }}',
                                '{{ $appointment->scheduled_at }}',
                                '{{ $appointment->employee ? addslashes($appointment->employee->name) : 'Pending' }}',
                                '{{ $appointment->status ? ucfirst($appointment->status) : 'Pending' }}'
                            )"><i class="fas fa-eye"></i> Operations Intel</button>

                            @if(strtolower($appointment->status) != 'cancelled' && strtolower($appointment->status) != 'completed')
                                <button class="action-btn-sm" onclick="openEditModal(
                                    {{ $appointment->id }},
                                    '{{ addslashes($appointment->title) }}',
                                    '{{ addslashes($appointment->description) }}',
                                    '{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('Y-m-d\TH:i') }}'
                                )"><i class="fas fa-pen"></i> Modify</button>

                                <form action="{{ route('customer.appointments.destroy', $appointment->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Abort this operation?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn-sm btn-delete"><i class="fas fa-times"></i> Abort</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="glass-panel" style="text-align: center; padding: 60px;">
                <div style="font-size: 3rem; color: var(--glass-border); margin-bottom: 20px;"><i class="far fa-calendar-times"></i></div>
                <h3 style="color: var(--secondary); font-weight: 600;">No active engagements detected.</h3>
                <p style="color: var(--text-muted);">Initialize a new engagement to populate your timeline.</p>
            </div>
        @endif
    </div>
</div>

<!-- Intel Modal -->
<div id="viewModal" class="modal-overlay">
    <div class="pro-modal">
        <div class="modal-header">
            <h3>Engagement Intelligence</h3>
            <button class="close-modal" onclick="closeViewModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="info-group">
                <label>Operation Title</label>
                <div id="view_title" class="info-value"></div>
            </div>
            <div class="info-group">
                <label>Operational Context</label>
                <div id="view_description" class="info-value"></div>
            </div>
            <div class="info-grid">
                <div class="info-group">
                    <label>Execution Time</label>
                    <div id="view_scheduled_at" class="info-value"></div>
                </div>
                <div class="info-group">
                    <label>Assigned Specialist</label>
                    <div id="view_employee" class="info-value"></div>
                </div>
            </div>
            <div class="info-group">
                <label>Current Status</label>
                <div id="view_status" class="info-value"></div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay">
    <div class="pro-modal">
        <div class="modal-header">
            <h3>Modify Engagement Parameters</h3>
            <button class="close-modal" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editAppointmentForm" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <input type="hidden" name="appointment_id" id="edit_appointment_id">
                
                <div class="form-group">
                    <label>Operation Title</label>
                    <input type="text" name="title" id="edit_title" class="pro-input" required>
                </div>

                <div class="form-group">
                    <label>Operational Context</label>
                    <textarea name="description" id="edit_description" class="pro-input" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label>Reschedule Execution</label>
                    <input type="datetime-local" name="scheduled_at" id="edit_scheduled_at" class="pro-input" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="action-btn secondary" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="action-btn btn-primary">Confirm Modification</button>
            </div>
        </form>
    </div>
</div>



<script>
function openViewModal(title, description, scheduled_at, employee, status){
    document.getElementById('viewModal').style.display = 'flex';
    document.getElementById('view_title').textContent = title;
    document.getElementById('view_description').textContent = description;
    document.getElementById('view_scheduled_at').textContent = new Date(scheduled_at).toLocaleString();
    document.getElementById('view_employee').textContent = employee;
    document.getElementById('view_status').innerHTML = `<span class="status-badge status-${(status||'pending').toLowerCase()}">${status||'Pending'}</span>`;
}

function closeViewModal(){
    document.getElementById('viewModal').style.display = 'none';
}

function openEditModal(id, title, description, scheduled_at){
    document.getElementById('editModal').style.display = 'flex';
    document.getElementById('edit_appointment_id').value = id;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_scheduled_at').value = scheduled_at;
    document.getElementById('editAppointmentForm').action = '/customer/appointments/' + id;
}

function closeEditModal(){
    document.getElementById('editModal').style.display = 'none';
}

window.onclick = function(event){
    if(event.target == document.getElementById('viewModal')) closeViewModal();
    if(event.target == document.getElementById('editModal')) closeEditModal();
}
</script>
