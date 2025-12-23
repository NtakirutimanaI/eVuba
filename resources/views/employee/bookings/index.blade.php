@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-calendar-check" style="color: var(--primary);"></i> Service Bookings</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Manage and track customer service requests assigned to you.</p>
        </div>
        <div class="header-actions">
            <div class="glass-panel" style="padding: 10px 20px; display: flex; align-items: center; gap: 15px; border-radius: 12px;">
                <div class="stat-item">
                    <span style="font-size: 0.8rem; color: var(--secondary);">Active Queue</span>
                    <strong style="display: block; font-size: 1.1rem; color: var(--primary);">{{ $bookings->total() }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Timeline Feed --}}
    <div class="timeline-container" style="margin-top: 3rem; background: var(--white); border-radius: 30px; padding: 40px; box-shadow: var(--shadow-sm);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
            <div>
                <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: var(--dark);">Mission Timeline</h3>
                <p style="color: var(--secondary); margin-top: 4px;">Sequential overview of your service engagements</p>
            </div>
            <div class="mega-search" style="max-width: 300px; flex: 1; background: var(--light);">
                <i class="fas fa-search" style="color: var(--secondary);"></i>
                <input type="text" id="bookingSearch" placeholder="Filter by customer..." class="search-input" style="background: transparent; border: none; padding: 10px;">
            </div>
        </div>

        <div class="mission-timeline">
            @forelse($bookings as $booking)
                <div class="timeline-item booking-row">
                    <div class="timeline-date">
                        <span class="day">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d') }}</span>
                        <span class="month">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M') }}</span>
                    </div>

                    <div class="timeline-marker">
                        <div class="marker-dot {{ $booking->status == 'completed' ? 'active' : 'pending' }}"></div>
                    </div>

                    <div class="timeline-content card-row">
                        <div class="card-inner">
                            <div class="booking-meta">
                                <span class="time-badge"><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($booking->booking_date)->format('h:i A') }}</span>
                                <span class="status-pill {{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                            </div>
                            
                            <div class="main-info">
                                <div class="customer-info" style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">
                                    <div class="client-avatar">{{ strtoupper(substr($booking->user->name ?? 'G', 0, 1)) }}</div>
                                    <div>
                                        <h4 style="margin: 0; font-size: 1.1rem; color: var(--dark);">{{ $booking->user->name ?? 'Guest' }}</h4>
                                        <span style="font-size: 0.8rem; color: var(--secondary);">{{ $booking->user->email ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="service-highlight">
                                    <i class="fas fa-bolt" style="color: var(--primary);"></i>
                                    <strong>{{ $booking->service->name ?? 'Service' }}</strong>
                                </div>
                            </div>

                            <div class="timeline-actions">
                                <button class="action-trigger" onclick="viewBookingDetails({{ $booking->id }})">
                                    <i class="fas fa-layer-group"></i> View Details
                                </button>
                                <button class="action-trigger primary" onclick="manageBookingStatus({{ $booking->id }})">
                                    <i class="fas fa-terminal"></i> Manage Status
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 4rem; opacity: 0.5;">
                    <i class="fas fa-stopwatch-20" style="font-size: 3rem; margin-bottom: 20px;"></i>
                    <p>No missions scheduled on your timeline yet.</p>
                </div>
            @endforelse
        </div>

        <div style="margin-top: 40px; border-top: 1px solid var(--glass-border); padding-top: 30px;">
            {{ $bookings->links() }}
        </div>
    </div>
</div>

{{-- Details Modal --}}
<div id="bookingDetailsModal" class="modal-overlay">
    <div class="pro-modal" style="max-width: 600px;">
        <div class="modal-header">
            <h3><i class="fas fa-file-invoice" style="color: var(--primary);"></i> Booking Particulars</h3>
            <button class="close-modal" onclick="closeBookingModal()">&times;</button>
        </div>
        <div class="modal-body" id="bookingDetailsContent">
            <div style="text-align:center; padding: 3rem;">
                <i class="fas fa-circle-notch fa-spin" style="font-size: 2.5rem; color: var(--primary);"></i>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeBookingModal()">Close Manifest</button>
        </div>
    </div>
</div>

<style>
    /* Timeline Engine */
    .mission-timeline { position: relative; margin-top: 20px; }
    .mission-timeline::before { content: ''; position: absolute; left: 100px; top: 0; bottom: 0; width: 2px; background: linear-gradient(to bottom, transparent, var(--glass-border) 10%, var(--glass-border) 90%, transparent); }

    .timeline-item { display: flex; gap: 40px; margin-bottom: 30px; position: relative; }
    .timeline-date { width: 60px; text-align: right; display: flex; flex-direction: column; justify-content: center; }
    .timeline-date .day { display: block; font-size: 1.8rem; font-weight: 900; color: var(--dark); line-height: 1; }
    .timeline-date .month { display: block; font-size: 0.75rem; font-weight: 700; color: var(--secondary); text-transform: uppercase; letter-spacing: 1px; }

    .timeline-marker { width: 40px; display: flex; align-items: center; justify-content: center; z-index: 2; }
    .marker-dot { width: 14px; height: 14px; border-radius: 50%; background: var(--white); border: 3px solid var(--glass-border); transition: 0.3s; }
    .marker-dot.active { border-color: #10b981; box-shadow: 0 0 15px rgba(16, 185, 129, 0.4); background: #10b981; }
    .marker-dot.pending { border-color: #f59e0b; }

    .timeline-content { flex: 1; background: var(--light); border-radius: 20px; padding: 25px; transition: 0.3s; border: 1px solid transparent; }
    .timeline-content:hover { background: var(--white); border-color: var(--primary); transform: translateX(10px); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }

    .booking-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .time-badge { font-size: 0.8rem; font-weight: 700; color: var(--primary); background: rgba(99, 102, 241, 0.08); padding: 4px 12px; border-radius: 10px; }
    .status-pill { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; padding: 4px 10px; border-radius: 8px; }
    .status-pill.completed { background: #10b981; color: white; }
    .status-pill.pending { background: #f59e0b; color: white; }
    .status-pill.confirmed { background: #6366f1; color: white; }

    .client-avatar { width: 40px; height: 40px; border-radius: 12px; background: var(--dark); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; }
    .service-highlight { display: inline-flex; align-items: center; gap: 8px; font-size: 1rem; color: var(--dark); background: var(--white); padding: 8px 15px; border-radius: 12px; border: 1px solid var(--glass-border); }

    .timeline-actions { margin-top: 20px; display: flex; gap: 12px; border-top: 1px solid rgba(0,0,0,0.05); padding-top: 15px; }
    .action-trigger { background: transparent; border: 1px solid var(--glass-border); color: var(--secondary); padding: 8px 16px; border-radius: 10px; font-size: 0.8rem; font-weight: 700; cursor: pointer; transition: 0.2s; }
    .action-trigger:hover { background: var(--dark); color: white; border-color: var(--dark); }
    .action-trigger.primary { background: var(--primary); color: white; border-color: var(--primary); }
    .action-trigger.primary:hover { opacity: 0.9; transform: scale(1.02); }

    /* Modal Styling */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(8px);
        z-index: 10001;
        align-items: center;
        justify-content: center;
    }
    .pro-modal {
        background: var(--white);
        width: 95%;
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        overflow: hidden;
        animation: slideUp 0.4s ease-out;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(40px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .modal-header { padding: 25px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center; }
    .close-modal { background: none; border: none; font-size: 2rem; color: var(--secondary); cursor: pointer; height: 32px; width: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; }
    .close-modal:hover { background: var(--light); color: var(--dark); }
    .modal-body { padding: 30px; max-height: 70vh; overflow-y: auto; }
    .modal-footer { padding: 20px 30px; background: var(--light); display: flex; justify-content: flex-end; }
    .btn-cancel { padding: 12px 24px; border-radius: 12px; background: var(--white); border: 1px solid var(--glass-border); color: var(--secondary); font-weight: 600; cursor: pointer; transition: 0.2s; }
    .btn-cancel:hover { background: var(--dark); color: white; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Search Functionality
    document.getElementById('bookingSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.booking-row');
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    async function viewBookingDetails(id) {
        const modal = document.getElementById('bookingDetailsModal');
        const content = document.getElementById('bookingDetailsContent');
        const footer = modal.querySelector('.modal-footer');
        modal.style.display = 'flex';
        content.innerHTML = '<div style="text-align:center; padding: 3rem;"><i class="fas fa-circle-notch fa-spin" style="font-size: 2.5rem; color: var(--primary);"></i></div>';
        footer.innerHTML = '<button class="btn-cancel" onclick="closeBookingModal()">Dismiss Manifest</button>';

        try {
            const response = await fetch(`/employee/bookings/${id}/details`, {
                headers: { 'Accept': 'application/json' }
            });
            const res = await response.json();
            if (res.success) {
                const b = res.data;
                content.innerHTML = `
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                        <div class="glass-panel" style="padding: 20px; border-radius: 16px;">
                            <label style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary); font-weight: 700;">Client Manifest</label>
                            <div style="margin-top: 15px; display: flex; align-items: center; gap: 15px;">
                                <div class="user-avatar-placeholder" style="width: 50px; height: 50px; font-size: 18px;">${(b.customer_name || 'G').charAt(0)}</div>
                                <div>
                                    <div style="font-weight: 700; font-size: 1.1rem;">${b.customer_name || 'Guest'}</div>
                                    <div style="font-size: 0.85rem; color: var(--secondary);">${b.customer_email || 'N/A'}</div>
                                </div>
                            </div>
                        </div>
                        <div class="glass-panel" style="padding: 20px; border-radius: 16px;">
                            <label style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary); font-weight: 700;">Service Logistics</label>
                            <div style="margin-top: 15px;">
                                <div style="font-weight: 700; color: var(--primary); font-size: 1.1rem;">${b.service_name || 'Service'}</div>
                                <div style="margin-top: 5px; font-weight: 600; color: #10b981;"><i class="fas fa-tags"></i> ${b.service_price || '0 RWF'}</div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 30px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                        <div>
                            <label style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary); font-weight: 700;">Deployment Schedule</label>
                            <div style="margin-top: 10px; font-weight: 600; font-size: 1rem;">
                                <i class="far fa-calendar-alt" style="color: var(--primary);"></i> ${b.booking_date || 'N/A'}
                            </div>
                        </div>
                        <div>
                            <label style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary); font-weight: 700;">Workflow Status</label>
                            <div style="margin-top: 10px;">
                                <span class="status-badge status-${(b.status === 'completed' || b.status === 'confirmed') ? 'active' : 'pending'}">${b.status || 'Pending'}</span>
                            </div>
                        </div>
                    </div>
                `;

                footer.innerHTML = `
                    <button class="btn-cancel" onclick="deleteBooking(${b.id})" style="color: #ef4444; margin-right: auto;"><i class="far fa-trash-alt"></i> Cancel Engagement</button>
                    <button class="btn-cancel" onclick="closeBookingModal()">Dismiss Manifest</button>
                    <button class="action-btn btn-primary" onclick="editBooking(${b.id}, '${b.title ? b.title.replace(/'/g, "\\'") : ''}', '${b.booking_date}', '${b.status}')" style="width: auto; padding: 0 20px; border-radius: 12px; height: 44px; margin-left: 10px;">
                        <i class="fas fa-edit"></i> Synchronize Logistics
                    </button>
                `;
            } else {
                content.innerHTML = `<p style="color:red; text-align:center; padding: 2rem;">${res.message || 'Access Denied'}</p>`;
            }
        } catch (e) {
            content.innerHTML = '<p style="color:red; text-align:center;">Failed to retrieve manifest. Please check your connection.</p>';
        }
    }

    async function deleteBooking(id) {
        const result = await Swal.fire({
            title: 'Cancel Engagement?',
            text: "This will permanently remove the booking from your schedule and notify the client.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: 'var(--secondary)',
            confirmButtonText: 'Yes, Terminate!'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/employee/bookings/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const res = await response.json();
                if (res.success) {
                    Swal.fire('Terminated!', res.message, 'success').then(() => location.reload());
                }
            } catch (e) {
                Swal.fire('Error', 'Termination failed.', 'error');
            }
        }
    }

    async function editBooking(id, currentTitle, currentDate, currentStatus) {
        const { value: formValues } = await Swal.fire({
            title: 'Update Logistics',
            html:
                `<input id="swal-title" class="swal2-input" placeholder="Booking Title" value="${currentTitle}">` +
                `<input id="swal-date" type="datetime-local" class="swal2-input" value="${new Date(currentDate).toISOString().slice(0, 16)}">` +
                `<select id="swal-status" class="swal2-select">
                    <option value="pending" ${currentStatus === 'pending' ? 'selected' : ''}>Pending</option>
                    <option value="confirmed" ${currentStatus === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                    <option value="completed" ${currentStatus === 'completed' ? 'selected' : ''}>Completed</option>
                    <option value="cancelled" ${currentStatus === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                </select>`,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Sync Logistics',
            preConfirm: () => {
                return {
                    title: document.getElementById('swal-title').value,
                    booking_date: document.getElementById('swal-date').value,
                    status: document.getElementById('swal-status').value
                }
            }
        });

        if (formValues) {
            try {
                const response = await fetch(`/employee/bookings/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formValues)
                });
                const res = await response.json();
                if (res.success) {
                    Swal.fire('Synchronized!', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Failed', res.message, 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Logistics sync failed.', 'error');
            }
        }
    }

    function closeBookingModal() {
        document.getElementById('bookingDetailsModal').style.display = 'none';
    }

    async function manageBookingStatus(id) {
        const { value: status } = await Swal.fire({
            title: 'Transition Workflow State',
            text: 'Selecting a new state will update the client and management records.',
            input: 'select',
            inputOptions: {
                'pending': 'Pending Review',
                'confirmed': 'Confirmed / Active',
                'completed': 'Task Completed',
                'cancelled': 'Cancelled'
            },
            inputPlaceholder: 'Choose State',
            showCancelButton: true,
            confirmButtonColor: 'var(--primary)',
            cancelButtonColor: 'var(--secondary)',
            customClass: {
                popup: 'premium-swal-popup',
                confirmButton: 'premium-swal-confirm'
            }
        });

        if (status) {
            try {
                const response = await fetch(`/employee/bookings/${id}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ status: status })
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const res = await response.json();
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Workflow Updated',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Update Failed', res.message || 'Operation could not be completed.', 'error');
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Update Failed', 'Internal server transition error. Please try again later.', 'error');
            }
        }
    }

    window.onclick = function(event) {
        if (event.target === document.getElementById('bookingDetailsModal')) closeBookingModal();
    }
</script>
