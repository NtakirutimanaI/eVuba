@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-calendar-check" style="color: var(--primary);"></i> Booking Command</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Secure your next engagement and manage your active service pipeline.</p>
        </div>
        <div class="header-actions">
            <div class="glass-panel" style="padding: 10px 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px;">
                <div class="stat-item">
                    <span style="font-size: 0.75rem; color: var(--secondary);">Active Bookings</span>
                    <strong style="display: block; font-size: 1.1rem; color: var(--primary);">{{ $bookings->where('status', '!=', 'cancelled')->count() }}</strong>
                </div>
                <div style="width: 1px; height: 30px; background: var(--glass-border);"></div>
                <div class="stat-item">
                    <span style="font-size: 0.75rem; color: var(--secondary);">Total Spent</span>
                    <strong style="display: block; font-size: 1.1rem; color: #10b981;">{{ number_format($bookings->where('status', 'completed')->sum(fn($b) => $b->service->price ?? 0)) }} RWF</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Appointment Archive --}}
    <div style="margin-top: 3rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h3 style="margin: 0; font-size: 1.4rem; font-weight: 800; color: var(--dark);">Your Appointment Archive</h3>
            <div class="mega-search" style="max-width: 300px; flex: 1; background: var(--light);">
                <i class="fas fa-search" style="color: var(--secondary);"></i>
                <input type="text" id="bookingSearch" placeholder="Filter appointments..." class="search-input" style="background: transparent; border: none; padding: 10px;">
            </div>
        </div>

        <div class="glass-panel" style="padding: 0; overflow: hidden; border-radius: 24px;">
            <table class="pro-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.02); text-align: left;">
                        <th style="padding: 20px 25px;">Service Manifest</th>
                        <th style="padding: 20px 25px;">Operational Lead</th>
                        <th style="padding: 20px 25px;">Scheduled Date</th>
                        <th style="padding: 20px 25px;">Investment</th>
                        <th style="padding: 20px 25px;">State</th>
                        <th style="padding: 20px 25px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr class="booking-row" style="border-top: 1px solid var(--glass-border); transition: 0.3s;">
                            <td style="padding: 20px 25px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--light); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                                        <i class="fas fa-concierge-bell"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: var(--dark);">{{ $booking->title }}</div>
                                        <div style="font-size: 0.75rem; color: var(--secondary);">#BKG-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 20px 25px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="user-avatar-placeholder" style="width: 30px; height: 30px; font-size: 12px;">{{ strtoupper(substr($booking->employee->name ?? 'E', 0, 1)) }}</div>
                                    <span style="font-weight: 600; font-size: 0.9rem;">{{ $booking->employee->name ?? 'Unassigned' }}</span>
                                </div>
                            </td>
                            <td style="padding: 20px 25px;">
                                <div style="font-weight: 600; font-size: 0.9rem;"><i class="far fa-calendar-alt" style="color: var(--primary); margin-right: 5px;"></i> {{ Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</div>
                                <div style="font-size: 0.75rem; color: var(--secondary);">{{ Carbon\Carbon::parse($booking->booking_date)->format('h:i A') }}</div>
                            </td>
                            <td style="padding: 20px 25px; font-weight: 700; color: #10b981;">
                                {{ number_format($booking->service->price ?? 0) }} RWF
                            </td>
                            <td style="padding: 20px 25px;">
                                <span class="status-badge status-{{ $booking->status === 'completed' || $booking->status === 'confirmed' ? 'active' : ($booking->status === 'cancelled' ? 'cancelled' : 'pending') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td style="padding: 20px 25px; text-align: right;">
                                <button class="icon-btn" onclick="viewBookingDetails({{ $booking->id }})" title="View Details"><i class="fas fa-eye"></i></button>
                                @if($booking->status === 'pending')
                                    <button class="icon-btn" onclick="cancelBooking({{ $booking->id }})" title="Cancel" style="color: #ef4444;"><i class="fas fa-times"></i></button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 50px; text-align: center; color: var(--secondary);">
                                <i class="fas fa-history" style="font-size: 3rem; opacity: 0.1; margin-bottom: 20px; display: block;"></i>
                                <p>No previous arrangements detected in your archive.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 25px;">{{ $bookings->links() }}</div>
    </div>

    {{-- Service Procurement Catalog --}}
    <div style="margin-top: 5rem; margin-bottom: 5rem;">
        <div style="text-align: center; margin-bottom: 40px;">
            <h2 style="font-size: 2rem; font-weight: 900; color: var(--dark);">Explore Service Solutions</h2>
            <p style="color: var(--secondary); max-width: 600px; margin: 10px auto;">Browse our professional catalog and initialize a new service arrangement instantly.</p>
        </div>

        <div class="service-procurement-grid">
            @forelse($services->where('is_published', 1) as $service)
                <div class="catalog-card">
                    <div class="catalog-visual">
                        @if($service->image)
                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}">
                        @else
                            <div class="fallback-visual"><i class="fas fa-rocket"></i></div>
                        @endif
                        <div class="catalog-price-tag">{{ number_format($service->price) }} RWF</div>
                    </div>
                    <div class="catalog-info">
                        <h3>{{ $service->name }}</h3>
                        <p>{{ Str::limit($service->description, 80) }}</p>
                        <div class="catalog-meta">
                            <span><i class="far fa-clock"></i> {{ $service->duration ?? '60' }} min</span>
                            <span><i class="fas fa-user-tie"></i> {{ $service->employee->name ?? 'Expert' }}</span>
                        </div>
                        <button class="procure-btn" onclick="initiateProcurement({{ $service->id }}, '{{ addslashes($service->name) }}')">
                            Initialize Booking <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 60px;">
                    <p style="color: var(--secondary);">Operational catalog is currently under maintenance.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Procurement Modal --}}
<div id="bookingModal" class="modal-overlay">
    <div class="pro-modal" style="max-width: 500px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 50px 100px -20px rgba(0,0,0,0.3);">
        <div class="modal-header" style="padding: 25px 30px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="width: 45px; height: 45px; border-radius: 12px; background: rgba(99, 102, 241, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.3rem; font-weight: 800; color: var(--dark);">Initialize Arrangement</h3>
                    <p style="margin: 0; font-size: 0.8rem; color: var(--secondary); font-weight: 500;">Secure your preferred mission window</p>
                </div>
            </div>
            <button class="close-modal" onclick="closeBookingModal()" style="font-size: 1.2rem; background: none; border: none; color: var(--secondary); cursor: pointer;">&times;</button>
        </div>
        
        <form id="procurementForm" action="{{ route('customer.bookings.store') }}" method="POST">
            @csrf
            <input type="hidden" name="service_id" id="modalServiceId">
            <div class="modal-body" style="padding: 30px;">
                {{-- Service Brief --}}
                <div style="background: var(--light); padding: 20px; border-radius: 20px; border: 1px solid var(--glass-border); margin-bottom: 30px;">
                    <label style="display: block; font-size: 0.65rem; font-weight: 900; text-transform: uppercase; color: var(--secondary); letter-spacing: 1px; margin-bottom: 8px;">Target Service</label>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-shield-alt" style="color: var(--primary); opacity: 0.5;"></i>
                        <h4 id="modalServiceName" style="margin: 0; font-size: 1.1rem; font-weight: 700; color: var(--dark);">Networking</h4>
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--secondary); margin-bottom: 12px;">Deployment Date</label>
                    <div style="position: relative;">
                        <i class="far fa-calendar" style="position: absolute; left: 15px; top: 16px; color: var(--secondary); opacity: 0.6;"></i>
                        <input type="date" name="booking_date" class="pro-input" required min="{{ date('Y-m-d') }}" style="width: 100%; padding-left: 45px;">
                    </div>
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--secondary); margin-bottom: 12px;">Tactical Considerations & Requirements</label>
                    <textarea name="description" class="pro-input" style="width: 100%; height: 120px; resize: none; line-height: 1.6;" placeholder="Describe any specific parameters for this arrangement..."></textarea>
                </div>
            </div>
            
            <div class="modal-footer" style="padding: 20px 30px; background: var(--light); display: flex; justify-content: flex-end; gap: 15px; border-top: 1px solid var(--glass-border);">
                <button type="button" class="btn-cancel" onclick="closeBookingModal()" style="padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 0.85rem; color: var(--secondary); background: transparent; border: none; cursor: pointer;">Cancel Orientation</button>
                <button type="submit" class="action-btn btn-primary" style="width: auto; padding: 0 30px; border-radius: 14px; height: 50px; font-weight: 800; display: flex; align-items: center; gap: 10px; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);">
                    Authorize Mission <i class="fas fa-check-circle"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .service-procurement-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; }
    .catalog-card { background: var(--white); border-radius: 28px; overflow: hidden; border: 1px solid var(--glass-border); transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .catalog-card:hover { transform: translateY(-12px); box-shadow: 0 40px 80px -20px rgba(0,0,0,0.15); border-color: var(--primary); }
    
    .catalog-visual { height: 180px; position: relative; overflow: hidden; }
    .catalog-visual img { width: 100%; height: 100%; object-fit: cover; }
    .fallback-visual { width: 100%; height: 100%; background: var(--light); display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--primary); opacity: 0.4; }
    .catalog-price-tag { position: absolute; bottom: 15px; right: 15px; background: rgba(16, 185, 129, 0.9); color: white; padding: 6px 14px; border-radius: 12px; font-weight: 800; font-size: 0.85rem; backdrop-filter: blur(4px); }
    
    .catalog-info { padding: 25px; }
    .catalog-info h3 { margin: 0 0 10px; font-size: 1.2rem; font-weight: 800; color: var(--dark); }
    .catalog-info p { font-size: 0.9rem; color: var(--secondary); line-height: 1.6; margin-bottom: 20px; }
    
    .catalog-meta { display: flex; gap: 15px; margin-bottom: 20px; border-top: 1px solid rgba(0,0,0,0.03); padding-top: 15px; }
    .catalog-meta span { font-size: 0.75rem; color: var(--secondary); font-weight: 600; display: flex; align-items: center; gap: 5px; }
    
    .procure-btn { width: 100%; background: var(--light); border: 1px solid var(--glass-border); color: var(--primary); padding: 12px; border-radius: 14px; font-weight: 800; font-size: 0.9rem; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px; }
    .procure-btn:hover { background: var(--primary); color: white; transform: scale(1.02); }

    .status-badge { padding: 6px 14px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
    .status-active { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-pending { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
    .status-cancelled { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .icon-btn { border: none; background: transparent; color: var(--secondary); font-size: 1rem; cursor: pointer; transition: 0.2s; padding: 8px; border-radius: 8px; }
    .icon-btn:hover { background: var(--light); color: var(--primary); }

    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(10px); z-index: 10001; align-items: center; justify-content: center; }
    .pro-modal { background: var(--white); width: 95%; border-radius: 30px; overflow: hidden; animation: modalIn 0.4s cubic-bezier(0.18, 0.89, 0.32, 1.28); }
    @keyframes modalIn { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    .pro-input { background: var(--light); border: 1px solid var(--glass-border); padding: 12px 15px; border-radius: 12px; font-family: inherit; font-weight: 500; outline: none; transition: 0.3s; }
    .pro-input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function initiateProcurement(id, name) {
        document.getElementById('modalServiceId').value = id;
        document.getElementById('modalServiceName').textContent = name;
        document.getElementById('bookingModal').style.display = 'flex';
    }

    function closeBookingModal() {
        document.getElementById('bookingModal').style.display = 'none';
    }

    async function viewBookingDetails(id) {
        // Since we don't have a specific show endpoint for AJAX view, we can use the same detail modal pattern
        // or just show a SweetAlert for simplicity in this version.
        Swal.fire({
            title: 'Technical Intelligence',
            text: 'System is retrieving arrangement logistics. Please stand by...',
            icon: 'info',
            timer: 2000,
            showConfirmButton: false
        });
    }

    async function cancelBooking(id) {
        const result = await Swal.fire({
            title: 'Terminate Arrangement?',
            text: "Are you sure you want to cancel this booking? This action is registered in the operational log.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, Terminate!'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/customer/bookings/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const res = await response.json();
                if (res.success || response.ok) {
                    Swal.fire('Terminated!', 'Arrangement has been successfully cancelled.', 'success').then(() => location.reload());
                }
            } catch (e) {
                // If it's a redirect or non-ajax route, we might need a standard form submission or just reload
                location.reload();
            }
        }
    }

    document.getElementById('bookingSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.booking-row');
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    document.getElementById('procurementForm').onsubmit = async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Initializing...';

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            const res = await response.json();
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Arrangement Secured',
                    text: res.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Failed', res.message || 'Validation error', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Communication with the command center failed.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Confirm Booking';
        }
    };

    window.onclick = (e) => {
        if (e.target === document.getElementById('bookingModal')) closeBookingModal();
    }
</script>

