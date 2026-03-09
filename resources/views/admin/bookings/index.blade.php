@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Operations Center</h1>
            <p style="color: var(--secondary); margin-top: 0.5rem;">Monitor and manage client service bookings</p>
        </div>
        <div class="header-actions" style="display: flex; gap: 1rem; align-items: flex-end;">
            <form action="{{ route('admin.bookings.index') }}" method="GET"
                style="display: flex; gap: 0.75rem; align-items: flex-end; background: var(--white); padding: 1rem; border-radius: 1rem; border: 1px solid var(--glass-border);">
                <div class="form-group" style="margin: 0;">
                    <label
                        style="font-size: 0.7rem; color: var(--secondary); margin-bottom: 0.25rem; display: block;">Start
                        Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--glass-border); font-size: 0.85rem; background: var(--bg-main); color: var(--text-main);">
                </div>
                <div class="form-group" style="margin: 0;">
                    <label
                        style="font-size: 0.7rem; color: var(--secondary); margin-bottom: 0.25rem; display: block;">End
                        Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--glass-border); font-size: 0.85rem; background: var(--bg-main); color: var(--text-main);">
                </div>
                <button type="submit" class="action-btn btn-primary" style="height: 38px;">
                    <i class="fas fa-filter"></i>
                </button>
                <a href="{{ route('admin.bookings.index') }}" class="action-btn"
                    style="height: 38px; background: var(--bg-main); color: var(--secondary);">
                    <i class="fas fa-undo"></i>
                </a>
            </form>

            <div style="display: flex; gap: 0.5rem;">
                <a id="pdfReportBtn" data-base-url="{{ route('admin.bookings.generateReport') }}"
                    href="{{ route('admin.bookings.generateReport', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                    class="action-btn"
                    style="background: var(--white); color: var(--secondary); height: 38px; padding: 0 1rem; width: auto; gap: 0.5rem;">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a id="excelReportBtn" data-base-url="{{ route('admin.bookings.generateReport') }}"
                    href="{{ route('admin.bookings.generateReport', ['type' => 'excel', 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                    class="action-btn"
                    style="background: var(--white); color: var(--secondary); height: 38px; padding: 0 1rem; width: auto; gap: 0.5rem;">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Overview (Filtered) -->
    <div class="dashboard-grid">
        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Total Bookings</div>
                    <div class="value">{{ $stats['total'] }}</div>
                    <div class="stat-trend up">
                        <i class="fas fa-filter"></i> Target Period
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Approved Rate</div>
                    <div class="value">
                        {{ $stats['total'] > 0 ? round(($stats['approved'] / $stats['total']) * 100) : 0 }}%
                    </div>
                    <div class="stat-trend up">
                        {{ $stats['approved'] }} Total Cases
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="fas fa-stopwatch"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Action Required</div>
                    <div class="value">{{ $stats['pending'] }}</div>
                    <div class="stat-trend {{ $stats['pending'] > 0 ? 'down' : 'up' }}">
                        {{ $stats['pending'] > 5 ? 'High Volume' : 'Under Control' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #0ea5e9, #38bdf8);">
                    <i class="fas fa-award"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Fulfillment</div>
                    <div class="value">{{ $stats['completed'] }}</div>
                    <div class="stat-trend up">
                        Successful Delivery
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Professional Intelligence Report View -->


    @if(session('success'))
        <div class="glass-card"
            style="background: rgba(34, 197, 94, 0.1); border-color: rgba(34, 197, 94, 0.2); margin-bottom: 2rem; padding: 1rem;">
            <div style="color: var(--success); display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="pro-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 700;">Booking Ledger</h2>
            <div class="pro-search">
                <i class="fas fa-search"></i>
                <input type="text" id="bookingSearch" placeholder="Filter by client, service, or date..."
                    style="background: transparent; border: none; outline: none; padding: 0.5rem; width: 300px; color: var(--text-main);">
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="pro-table" id="bookingsTable">
                <thead>
                    <tr>
                        <th>Booking Information</th>
                        <th>Service Schedule</th>
                        <th>Stakeholders</th>
                        <th>Current Status</th>
                        <th style="text-align: right;">Orchestration</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: var(--dark);">{{ $booking->title }}</div>
                                <div style="font-size: 0.75rem; color: var(--secondary); margin-top: 0.25rem;">
                                    {{ Str::limit($booking->description, 50) }}
                                </div>
                            </td>
                            <td>
                                <div
                                    style="display: flex; align-items: center; gap: 0.5rem; color: var(--dark); font-weight: 500;">
                                    <i class="far fa-calendar-alt text-primary"></i>
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--secondary); margin-top: 0.25rem;">
                                    Scheduled Arrival
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div
                                        style="width: 32px; height: 32px; border-radius: 50%; background: var(--bg-main); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; color: var(--primary);">
                                        {{ strtoupper(substr($booking->user->name ?? 'N', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--dark);">
                                            {{ $booking->user->name ?? 'External Client' }}
                                        </div>
                                        <div style="font-size: 0.7rem; color: var(--secondary);">Customer</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $booking->status }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.4rem; justify-content: flex-end;">
                                    <!-- Admin specific actions can go here. For now, mirroring manager capabilities + potentially more -->

                                    @if($booking->status !== 'approved' && $booking->status !== 'completed')
                                        <form
                                            action="{{ route('admin.bookings.updateStatus', ['booking' => $booking->id, 'status' => 'approved']) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" class="action-btn" title="Approve Booking"
                                                style="background: rgba(34, 197, 94, 0.1); color: var(--success); border: none;">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($booking->status !== 'completed')
                                        <form
                                            action="{{ route('admin.bookings.updateStatus', ['booking' => $booking->id, 'status' => 'completed']) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" class="action-btn" title="Mark Completed"
                                                style="background: rgba(14, 165, 233, 0.1); color: var(--info); border: none;">
                                                <i class="fas fa-flag-checkered"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('admin.bookings.reschedule', $booking->id) }}" class="action-btn"
                                        title="Reschedule"
                                        style="background: rgba(99, 102, 241, 0.1); color: var(--primary);">
                                        <i class="fas fa-clock"></i>
                                    </a>

                                    <button type="button" class="action-btn" title="Assign Employee"
                                        onclick="openAssignModal('{{ $booking->id }}')"
                                        style="background: rgba(168, 85, 247, 0.1); color: #a855f7; border: none;">
                                        <i class="fas fa-user-plus"></i>
                                    </button>

                                    <form
                                        action="{{ route('admin.bookings.updateStatus', ['booking' => $booking->id, 'status' => 'rejected']) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="action-btn" title="Reject"
                                            style="background: rgba(245, 158, 11, 0.1); color: var(--warning); border: none;">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>

                                    <form
                                        action="{{ route('admin.bookings.updateStatus', ['booking' => $booking->id, 'status' => 'cancelled']) }}"
                                        method="POST" onsubmit="return confirm('Cancel this booking?');">
                                        @csrf
                                        <button type="submit" class="action-btn" title="Cancel"
                                            style="background: rgba(239, 68, 68, 0.1); color: var(--danger); border: none;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div style="text-align: center; padding: 4rem; color: var(--secondary);">
                                    <i class="fas fa-calendar-times"
                                        style="font-size: 3rem; opacity: 0.1; margin-bottom: 1.5rem; display: block;"></i>
                                    <h3>No Bookings Found</h3>
                                    <p>The operations ledger is currently clear.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $bookings->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- Assign Employee Modal -->
<div id="assignModal"
    style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; display: none; align-items: center; justify-content: center;">
    <div
        style="background: var(--white); padding: 2rem; border-radius: 1rem; width: 100%; max-width: 400px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; display: flex; align-items: center; gap: 0.5rem; color: var(--dark);"><i class="fas fa-user-plus"></i>
                Assign Employee</h3>
            <button onclick="closeAssignModal()"
                style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--secondary);">&times;</button>
        </div>

        <form id="assignForm" method="POST" action="">
            @csrf

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.9rem; color: var(--dark);">Select
                    Employee <span style="color: red;">*</span></label>
                <select name="employee_id" required
                    style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid var(--glass-border); outline: none; background: var(--bg-main); color: var(--text-main);">
                    <option value="">Choose employee...</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-primary"
                style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: none; font-weight: 600; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 0.5rem; color: white;">
                <i class="fas fa-check"></i> Assign Booking
            </button>
        </form>
    </div>
</div>

<style>
    .dashboard-wrapper {
        margin-left: 250px;
        padding: 30px;
        background: var(--bg-main);
        min-height: 100vh;
    }

    .pro-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 2rem;
    }

    .pro-header h1 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--dark);
        margin: 0;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .pro-card {
        background: var(--white);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--glass-border);
    }

    .stat-widget {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .stat-info .value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--dark);
        line-height: 1.2;
    }

    .stat-info .label {
        font-size: 0.8rem;
        color: var(--secondary);
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .stat-trend {
        font-size: 0.75rem;
        margin-top: 0.25rem;
        font-weight: 500;
    }

    .stat-trend.up {
        color: var(--success);
    }

    .stat-trend.down {
        color: var(--danger);
    }

    /* Search & Table */
    .pro-search {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: var(--light);
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid var(--glass-border);
        transition: all 0.2s;
    }

    .pro-search:focus-within {
        background: var(--white);
        border-color: var(--info);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .pro-table {
        width: 100%;
        border-collapse: collapse;
    }

    .pro-table th {
        text-align: left;
        padding: 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--secondary);
        border-bottom: 2px solid var(--glass-border);
    }

    .pro-table td {
        padding: 1.25rem 1rem;
        border-bottom: 1px solid var(--glass-border);
        vertical-align: middle;
        color: var(--text-main);
    }

    .action-btn {
        width: 36px;
        height: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        border: none;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        filter: brightness(0.95);
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .status-badge {
        padding: 0.35rem 0.85rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-approved {
        background: rgba(34, 197, 94, 0.1);
        color: #15803d;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #b45309;
    }

    .status-completed {
        background: rgba(14, 165, 233, 0.1);
        color: #0369a1;
    }

    .status-cancelled,
    .status-rejected {
        background: rgba(239, 68, 68, 0.1);
        color: #b91c1c;
    }

    @media (max-width: 1024px) {
        .dashboard-wrapper {
            margin-left: 0;
            padding: 15px;
        }

        .dashboard-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .pro-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .header-actions {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 600px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Pagination Styling */
    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: flex-end;
    }

    .pagination {
        display: flex !important;
        padding-left: 0 !important;
        list-style: none !important;
        margin: 0 !important;
        gap: 0.25rem;
    }

    .page-link {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        color: var(--info);
        text-decoration: none;
        background-color: var(--white);
        border: 1px solid var(--glass-border);
        border-radius: 0.375rem;
        transition: all 0.2s;
        line-height: 1.25;
    }

    .page-link:hover {
        z-index: 2;
        color: var(--info);
        background-color: var(--bg-main);
        border-color: var(--glass-border);
    }

    .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: var(--info);
        border-color: var(--info);
    }

    .page-item.disabled .page-link {
        color: var(--secondary);
        pointer-events: none;
        background-color: var(--white);
        border-color: var(--glass-border);
    }
</style>

<script>
    function openAssignModal(bookingId) {
        document.getElementById('assignForm').action = `/admin/bookings/${bookingId}/assign-employee`;
        document.getElementById('assignModal').style.display = 'flex';
    }

    function closeAssignModal() {
        document.getElementById('assignModal').style.display = 'none';
    }

    function updateReportLinks() {
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDate = document.querySelector('input[name="end_date"]').value;

        const pdfBtn = document.getElementById('pdfReportBtn');
        const excelBtn = document.getElementById('excelReportBtn');

        let pdfUrl = new URL(pdfBtn.getAttribute('data-base-url'), window.location.origin);
        let excelUrl = new URL(excelBtn.getAttribute('data-base-url'), window.location.origin);

        if (startDate) {
            pdfUrl.searchParams.set('start_date', startDate);
            excelUrl.searchParams.set('start_date', startDate);
        }
        if (endDate) {
            pdfUrl.searchParams.set('end_date', endDate);
            excelUrl.searchParams.set('end_date', endDate);
        }

        pdfBtn.href = pdfUrl.toString();
        excelUrl.searchParams.set('type', 'excel');
        excelBtn.href = excelUrl.toString();
    }

    document.querySelectorAll('input[name="start_date"], input[name="end_date"]').forEach(input => {
        input.addEventListener('change', updateReportLinks);
    });

    function toggleReportMode() {
        const content = document.getElementById('analyticsContent');
        const btn = event.currentTarget;
        if (content.style.display === 'none') {
            content.style.display = 'block';
            btn.innerHTML = '<i class="fas fa-compress-alt"></i> HIDE ANALYTICS';
        } else {
            content.style.display = 'none';
            btn.innerHTML = '<i class="fas fa-expand-alt"></i> ANALYTICS VIEW';
        }
    }

    document.getElementById('bookingSearch').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#bookingsTable tbody tr');

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>