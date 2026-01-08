@include('layouts.header')
@include('layouts.sidebar')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary: #4F46E5;
        --primary-dark: #4338ca;
        --primary-light: #EEF2FF;
        --secondary: #64748B;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark: #0f172a;
        --light: #f8fafc;
        --white: #ffffff;
        --border: #e2e8f0;

        /* Semantic Colors (Light Mode Default) */
        --bg-body: #f1f5f9;
        --bg-card: #ffffff;
        --bg-input: #ffffff;
        --bg-hover: #f8fafc;
        --bg-modal: #ffffff;
        --bg-modal-header: #f8fafc;
        --text-main: #0f172a;
        --text-secondary: #64748B;
        --border-color: #e2e8f0;
        --glass-bg: rgba(255, 255, 255, 0.95);
        --glass-border: rgba(255, 255, 255, 0.1);

        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    [data-theme="dark"] {
        --bg-body: #0f172a;
        --bg-card: #1e293b;
        --bg-input: #0f172a;
        /* Darker input bg */
        --bg-hover: #334155;
        --bg-modal: #1e293b;
        --bg-modal-header: #334155;
        --text-main: #f8fafc;
        --text-secondary: #94a3b8;
        --border-color: #334155;
        --primary-light: rgba(79, 70, 229, 0.2);
        /* Adjust primary light for dark mode */
        --light: #1e293b;
        /* Re-map light utility to be dark in this context if used generally */
        --glass-bg: rgba(30, 41, 59, 0.95);
        --glass-border: rgba(255, 255, 255, 0.05);

        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.3);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--bg-body);
        color: var(--text-main);
        transition: background 0.3s, color 0.3s;
    }

    .dashboard-container {
        padding: 30px;
        width: 80%;
        margin-left: 222px;
    }

    /* Header Section */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-title h1 {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--text-main) 0%, var(--primary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }

    .page-title p {
        color: var(--text-secondary);
        margin-top: 5px;
        font-weight: 500;
    }

    /* Stats Cards - Simplified */
    .stats-single {
        background: var(--bg-card);
        padding: 20px 25px;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        display: inline-flex;
        align-items: center;
        gap: 15px;
        border: 1px solid var(--border-color);
        margin-bottom: 30px;
    }

    .stats-single .icon {
        width: 45px;
        height: 45px;
        background: var(--primary-light);
        color: var(--primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .stats-single .info h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-main);
    }

    .stats-single .info p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Main Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 1.8fr 1fr;
        gap: 30px;
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Sections */
    .section-card {
        background: var(--bg-card);
        border-radius: 24px;
        padding: 30px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    .filter-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 20px;
        flex-wrap: wrap;
    }

    /* Tabs */
    .tabs {
        display: flex;
        gap: 5px;
        background: var(--bg-body);
        padding: 5px;
        border-radius: 12px;
    }

    .tab-link {
        padding: 8px 20px;
        border-radius: 8px;
        text-decoration: none;
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.9rem;
        transition: 0.2s;
        border: none;
        background: transparent;
        cursor: pointer;
    }

    .tab-link.active {
        background: var(--bg-card);
        color: var(--primary);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .search-box {
        position: relative;
        flex: 1;
        max-width: 300px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        outline: none;
        font-family: inherit;
        background: var(--bg-input);
        color: var(--text-main);
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 13px;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    /* Table Styling */
    .bookings-table {
        width: 100%;
        border-collapse: collapse;
    }

    .bookings-table th {
        text-align: left;
        padding: 15px 20px;
        color: var(--text-secondary);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--border-color);
    }

    .bookings-table td {
        padding: 20px 20px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        color: var(--text-main);
    }

    .bookings-table tr:last-child td {
        border-bottom: none;
    }

    .bookings-table tr:hover td {
        background: var(--bg-hover);
    }

    .service-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .service-icon {
        width: 45px;
        height: 45px;
        background: var(--primary-light);
        color: var(--primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .service-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Status Badges */
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .badge-pending {
        background: #FEF3C7;
        color: #D97706;
    }

    .badge-confirmed {
        background: #E0E7FF;
        color: var(--primary);
    }

    .badge-completed {
        background: #D1FAE5;
        color: var(--success);
    }

    .badge-cancelled {
        background: #FEE2E2;
        color: var(--danger);
    }

    /* Service Catalog */
    .catalog-list {
        display: grid;
        gap: 20px;
    }

    .catalog-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        transition: 0.2s;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        background: var(--bg-card);
    }

    .catalog-item:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .catalog-img {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        object-fit: cover;
        margin-right: 20px;
        background: var(--bg-body);
    }

    .catalog-details {
        flex: 1;
    }

    .catalog-price {
        font-weight: 700;
        color: var(--success);
        font-size: 0.95rem;
    }

    .book-btn-mini {
        background: var(--bg-body);
        color: var(--primary);
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .catalog-item:hover .book-btn-mini {
        background: var(--primary);
        color: white;
    }

    /* Action Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        gap: 8px;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    .btn-icon {
        padding: 8px;
        border-radius: 8px;
        background: transparent;
        color: var(--text-secondary);
        font-size: 1.1rem;
        border: none;
        cursor: pointer;
    }

    .btn-icon:hover {
        background: var(--bg-hover);
        color: var(--primary);
    }

    /* Modal */
    /* Modal - Renamed to avoid conflicts */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 50;
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .modal-backdrop.active {
        opacity: 1;
    }

    .custom-modal-panel {
        background: var(--bg-modal);
        width: 100%;
        max-width: 550px;
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        transform: scale(0.95);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .modal-backdrop.active .custom-modal-panel {
        transform: scale(1);
    }

    .custom-modal-header {
        padding: 25px 30px !important;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--bg-modal-header);
        box-sizing: border-box;
    }

    .custom-modal-header h3 {
        color: var(--text-main);
    }

    .custom-modal-header button {
        color: var(--text-secondary);
    }

    .custom-modal-body {
        padding: 30px !important;
        box-sizing: border-box;
    }

    .custom-modal-footer {
        padding: 20px 30px !important;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        background: var(--bg-modal-header);
        box-sizing: border-box;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-main);
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        box-sizing: border-box;
        padding: 12px 15px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        font-family: inherit;
        font-size: 0.95rem;
        background: var(--bg-input);
        color: var(--text-main);
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-light);
        outline: none;
    }
</style>

<div class="dashboard-wrapper">
    <div class="dashboard-container">

        <!-- Header -->
        <div class="page-header">
            <div class="page-title">
                <h1>Booking Management</h1>
                <p>Track your engagements and schedule new services.</p>
            </div>

        </div>

        <!-- Single Stat (Active) -->
        <div class="stats-single">
            <div class="icon"><i class="fas fa-calendar-check"></i></div>
            <div class="info">
                <h3>{{ $activeBookings }}</h3>
                <p>Active Engagements</p>
            </div>
        </div>

        <div class="content-grid">
            <!-- Left Column: Bookings List -->
            <div class="section-card">

                <!-- Filters -->
                <div class="filter-bar">
                    <div class="tabs">
                        <a href="{{ route('customer.bookings.index') }}"
                            class="tab-link {{ !request('status') || request('status') == 'all' ? 'active' : '' }}">All</a>
                        <a href="{{ route('customer.bookings.index', ['status' => 'pending']) }}"
                            class="tab-link {{ request('status') == 'pending' ? 'active' : '' }}">Pending</a>
                        <a href="{{ route('customer.bookings.index', ['status' => 'confirmed']) }}"
                            class="tab-link {{ request('status') == 'confirmed' ? 'active' : '' }}">Confirmed</a>
                        <a href="{{ route('customer.bookings.index', ['status' => 'completed']) }}"
                            class="tab-link {{ request('status') == 'completed' ? 'active' : '' }}">Completed</a>
                    </div>
                    <form action="{{ route('customer.bookings.index') }}" method="GET" class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Search bookings..."
                            value="{{ request('search') }}">
                        @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="bookings-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Service & Date</th>
                                <th>Status</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td style="font-weight: 700; color: var(--text-secondary); text-align: center;">
                                        {{ $bookings->firstItem() + $loop->index }}
                                    </td>
                                    <td>
                                        <div class="service-info">
                                            <div class="service-icon">
                                                @if($booking->service && $booking->service->image)
                                                    <img src="{{ asset('storage/' . $booking->service->image) }}">
                                                @else
                                                    <i class="fas fa-briefcase"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div style="font-weight: 700; color: var(--text-main);">{{ $booking->title }}

                                                </div>
                                                <div style="font-size: 0.85rem; color: var(--secondary); margin-top: 4px;">
                                                    <i class="far fa-calendar" style="font-size: 0.75rem;"></i>
                                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                                    </td>
                                    <td style="text-align: right;">
                                        <button class="btn-icon" onclick="viewBooking({{ $booking->id }})"
                                            title="View Details"><i class="far fa-eye"></i></button>
                                        @if($booking->status === 'pending')
                                            <button class="btn-icon"
                                                onclick="editBooking({{ $booking->id }}, '{{ addslashes($booking->title) }}', '{{ $booking->booking_date->format('Y-m-d') }}', '{{ addslashes($booking->description ?? '') }}', {{ $booking->service_id }})"
                                                title="Reschedule"><i class="fas fa-edit"></i></button>
                                            <button class="btn-icon" onclick="cancelBooking({{ $booking->id }})" title="Cancel"
                                                style="color: var(--danger);"><i class="far fa-trash-alt"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 40px;">
                                        <div style="color: var(--text-secondary); font-style: italic;">No bookings found. Try
                                            adjusting your filters.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 20px;">
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            </div>

            <!-- Right Column: Service Catalog -->
            <div class="section-card" id="catalog-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                    <div style="font-weight: 700; color: var(--text-main); font-size: 1.1rem;">Service Catalog</div>
                    <span
                        style="font-size: 0.75rem; color: var(--secondary); background: var(--light); padding: 4px 10px; border-radius: 10px;">{{ $services->count() }}
                        Available</span>
                </div>

                <div class="catalog-list">
                    @foreach($services as $service)
                        <div class="catalog-item"
                            onclick="initiateBooking({{ $service->id }}, '{{ addslashes($service->name) }}', {{ $service->price ?? 0 }})">
                            <img src="{{ $service->image ? asset('storage/' . $service->image) : 'https://via.placeholder.com/60?text=S' }}"
                                class="catalog-img">
                            <div class="catalog-details">
                                <h4 style="margin: 0 0 5px; font-weight: 700; color: var(--text-main); font-size: 0.95rem;">
                                    {{ $service->name }}</h4>
                                <div class="catalog-price">{{ number_format($service->price) }} RWF</div>
                            </div>
                            <div class="book-btn-mini"><i class="fas fa-arrow-right"></i></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Booking Modal (Create) -->
<div id="bookingModal" class="modal-backdrop">
    <div class="custom-modal-panel">
        <div class="custom-modal-header">
            <h3 style="margin: 0; font-size: 1.25rem;">New Booking</h3>
            <button onclick="closeModal('bookingModal')"
                style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('customer.bookings.store') }}" method="POST" id="bookingForm">
            @csrf
            <input type="hidden" name="service_id" id="service_id_input">
            <div class="custom-modal-body">
                <div
                    style="background: var(--primary-light); padding: 15px; border-radius: 12px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span
                            style="display: block; font-size: 0.75rem; color: var(--secondary); font-weight: 700; text-transform: uppercase;">Service</span>
                        <strong id="service_name_display" style="color: var(--primary); font-size: 1.1rem;">Service
                            Name</strong>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Target Execution Date</label>
                    <input type="date" name="booking_date" class="form-control" required min="{{ date('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Operational Context & Requirements</label>
                    <textarea name="description" class="form-control"
                        placeholder="Describe the mission requirements..."></textarea>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn" style="background: var(--light); color: var(--secondary);"
                    onclick="closeModal('bookingModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm Booking</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Booking Modal -->
<div id="editModal" class="modal-backdrop">
    <div class="custom-modal-panel">
        <div class="custom-modal-header">
            <h3 style="margin: 0; font-size: 1.25rem;">Modify Engagement Parameters</h3>
            <button onclick="closeModal('editModal')"
                style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="service_id" id="edit_service_id">
            <input type="hidden" name="status" value="pending">

            <div class="custom-modal-body">
                <div style="margin-bottom: 20px;">
                    <h4 id="edit_title_display" style="margin: 0 0 5px; color: var(--primary);">Service Title</h4>
                    <p style="margin: 0; font-size: 0.85rem; color: var(--secondary);">You are modifying an existing
                        request.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Reschedule Execution</label>
                    <input type="date" name="booking_date" id="edit_booking_date" class="form-control" required
                        min="{{ date('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Update Notes</label>
                    <textarea name="description" id="edit_description" class="form-control"
                        placeholder="Update your requirements..."></textarea>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn" style="background: var(--light); color: var(--secondary);"
                    onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- View Modal -->
<div id="viewModal" class="modal-backdrop">
    <div class="custom-modal-panel">
        <div class="custom-modal-header">
            <h3 style="margin: 0; font-size: 1.25rem;">Engagement Intelligence</h3>
            <button onclick="closeModal('viewModal')"
                style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        <div class="custom-modal-body">
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                <label
                    style="font-size: 0.75rem; font-weight: 700; color: var(--secondary); text-transform: uppercase; margin-bottom: 5px; display: block;">Operation
                    Title</label>
                <h2 id="view_title" style="margin: 0 0 10px; font-size: 1.3rem; color: var(--text-main);">Service Title</h2>
                <div style="display: flex; gap: 10px;">
                    <span id="view_status" class="badge">Status</span>
                    <span id="view_price"
                        style="background: #ecfdf5; color: #10b981; padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.75rem;">Price</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label
                        style="font-size: 0.75rem; font-weight: 700; color: var(--secondary); text-transform: uppercase;">Execution
                        Time</label>
                    <div id="view_date" style="font-weight: 600; font-size: 1rem; color: var(--text-main); margin-top: 4px;">
                        -</div>
                </div>
                <div>
                    <label
                        style="font-size: 0.75rem; font-weight: 700; color: var(--secondary); text-transform: uppercase;">Assigned
                        Specialist</label>
                    <div id="view_employee"
                        style="font-weight: 600; font-size: 1rem; color: var(--text-main); margin-top: 4px;">-</div>
                </div>
                <div style="grid-column: 1/-1;">
                    <label
                        style="font-size: 0.75rem; font-weight: 700; color: var(--secondary); text-transform: uppercase;">Operational
                        Context</label>
                    <div id="view_description"
                        style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 15px; border-radius: 12px; margin-top: 5px; font-size: 0.95rem; line-height: 1.5; color: var(--text-secondary);">
                    </div>
                </div>
            </div>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn" onclick="closeModal('viewModal')"
                style="background: var(--light); color: var(--dark);">Close</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // AJAX Navigation & Actions
    document.addEventListener('DOMContentLoaded', () => {
        const tableContainer = document.querySelector('.table-responsive');
        const paginationContainer = document.querySelector('.table-responsive + div');
        const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';

        // --- 1. Handling Filters & Search (GET HTML) ---
        function fetchBookings(url) {
            tableContainer.style.opacity = '0.5';

            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Update Table
                    const newTable = doc.querySelector('.table-responsive') ? doc.querySelector('.table-responsive').innerHTML : '';
                    if (newTable) tableContainer.innerHTML = newTable;

                    // Update Pagination
                    const newPagination = doc.querySelector('.table-responsive + div') ? doc.querySelector('.table-responsive + div').innerHTML : '';
                    if (paginationContainer && newPagination) {
                        paginationContainer.innerHTML = newPagination;
                    }

                    // Update History State
                    window.history.pushState({}, '', url);
                    tableContainer.style.opacity = '1';
                })
                .catch(err => {
                    console.error('Error loading bookings:', err);
                    tableContainer.style.opacity = '1';
                });
        }

        // Intercept Filter Tabs
        document.body.addEventListener('click', function (e) {
            if (e.target.classList.contains('tab-link')) {
                e.preventDefault();
                document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
                e.target.classList.add('active');
                fetchBookings(e.target.href);
            }
            // Intercept Pagination
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                const link = e.target.closest('a');
                fetchBookings(link.href);
            }
        });

        // Intercept Search Form
        const searchForm = document.querySelector('.search-box');
        if (searchForm) {
            searchForm.addEventListener('submit', (e) => { e.preventDefault(); });

            let searchTimeout;
            const searchInput = searchForm.querySelector('input');
            if (searchInput) {
                searchInput.addEventListener('keyup', function (e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        const url = new URL(searchForm.action);
                        url.searchParams.set('search', this.value);
                        const status = document.querySelector('.tab-link.active');
                        if (status) {
                            const statusVal = new URL(status.href).searchParams.get('status');
                            if (statusVal && statusVal !== 'all') url.searchParams.set('status', statusVal);
                        }
                        fetchBookings(url.toString());
                    }, 500);
                });
            }
        }

        // --- 2. Handling Forms (POST/PUT/DELETE JSON) ---
        async function submitForm(form, modalId) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const res = await response.json();

                if (res.success || response.ok) {
                    closeModal(modalId);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message || 'Operation completed.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    fetchBookings(window.location.href);
                    form.reset();
                } else {
                    Swal.fire('Error', res.message || 'Validation error', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Communication failed.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }

        const bookingForm = document.getElementById('bookingForm');
        if (bookingForm) {
            bookingForm.addEventListener('submit', function (e) {
                e.preventDefault();
                submitForm(this, 'bookingModal');
            });
        }

        const editForm = document.getElementById('editForm');
        if (editForm) {
            editForm.addEventListener('submit', function (e) {
                e.preventDefault();
                submitForm(this, 'editModal');
            });
        }

        // Expose function for canceling
        window.cancelBooking = async function (id) {
            const result = await Swal.fire({
                title: 'Cancel Engagement?',
                text: "Are you sure you want to cancel this booking?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, Cancel'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/customer/bookings/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                    if (response.ok) {
                        Swal.fire({
                            title: 'Cancelled',
                            text: 'Booking has been cancelled.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        // Smooth refresh
                        fetchBookings(window.location.href);
                    }
                } catch (e) {
                    console.error(e);
                    Swal.fire('Error', 'Failed to cancel.', 'error');
                }
            }
        };

    });

    // Existing functions (Modified to not reload)
    function scrollToCatalog() {
        document.getElementById('catalog-section').scrollIntoView({ behavior: 'smooth' });
    }

    function openModal(id) {
        let modal = document.getElementById(id);
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('active'), 10);
    }

    function closeModal(id) {
        let modal = document.getElementById(id);
        modal.classList.remove('active');
        setTimeout(() => modal.style.display = 'none', 300);
    }

    function initiateBooking(id, name, price) {
        document.getElementById('service_id_input').value = id;
        document.getElementById('service_name_display').textContent = name;
        openModal('bookingModal');
    }

    function editBooking(id, title, date, desc, serviceId) {
        document.getElementById('edit_service_id').value = serviceId;
        document.getElementById('edit_title_display').textContent = title;
        document.getElementById('edit_booking_date').value = date;
        document.getElementById('edit_description').value = desc;
        document.getElementById('editForm').action = `/customer/bookings/${id}`;
        openModal('editModal');
    }

    async function viewBooking(id) {
        try {
            const response = await fetch(`/customer/bookings/${id}`);
            const data = await response.json();
            if (data.success) {
                const b = data.booking;
                document.getElementById('view_title').textContent = b.title;
                document.getElementById('view_date').textContent = new Date(b.booking_date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                document.getElementById('view_status').textContent = b.status.toUpperCase();
                document.getElementById('view_status').className = `badge badge-${b.status}`;
                document.getElementById('view_description').textContent = b.description || 'No additional notes provided.';
                document.getElementById('view_employee').textContent = b.employee ? b.employee.name : 'Pending Assignment';
                document.getElementById('view_price').textContent = b.service ? new Intl.NumberFormat().format(b.service.price) + ' RWF' : 'N/A';
                openModal('viewModal');
            }
        } catch (e) {
            Swal.fire('Error', 'Unable to fetch booking details.', 'error');
        }
    }

    // Outside click
    window.onclick = function (event) {
        if (event.target.classList.contains('modal-backdrop')) {
            closeModal(event.target.id);
        }
    }
</script>