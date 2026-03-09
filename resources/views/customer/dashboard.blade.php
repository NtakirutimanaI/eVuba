@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="dashboard-wrapper">

    {{-- Page Header --}}
    <div class="pro-header">
        <div>
            <h1>
                <i class="fas fa-chart-line" style="color: var(--primary);"></i>
                My Dashboard
            </h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">
                Welcome back, <strong>{{ $user->name }}</strong>. Here's a summary of your activity.
            </p>
        </div>
        <div class="header-actions">
            <a href="{{ route('customer.bookings.index') }}" class="action-btn btn-primary"
                style="text-decoration: none; padding: 0 25px; border-radius: 12px; height: 46px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-calendar-plus"></i> New Booking
            </a>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="dashboard-grid" style="grid-template-columns: repeat(3, 1fr);">

        <div class="pro-card metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">My Bookings</div>
                <div class="metric-value">{{ $bookingsCount }}</div>
                <div class="metric-trend up">
                    <i class="fas fa-check-circle"></i> Service Synchronized
                </div>
            </div>
        </div>

        <div class="pro-card metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #10b981, #3b82f6);">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">Total Orders</div>
                <div class="metric-value">{{ $ordersCount }}</div>
                <div class="metric-trend up">
                    <i class="fas fa-box"></i> Logistics Registry
                </div>
            </div>
        </div>

        <div class="pro-card metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #ec4899, #8b5cf6);">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">Appointments</div>
                <div class="metric-value">{{ $appointmentsCount }}</div>
                <div class="metric-trend up">
                    <i class="far fa-clock"></i> Next Session Pending
                </div>
            </div>
        </div>

    </div>

    {{-- Main Content Grid --}}
    <div class="main-grid" style="margin-top: 1.5rem; margin-bottom: 3rem;">

        {{-- Recent Orders Table --}}
        <div class="pro-card glass-panel activity-card">
            <div class="card-header">
                <h3><i class="fas fa-exchange-alt"></i> Recent Orders</h3>
                <a href="{{ route('customer.orders.index') }}" class="view-all-link">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="table-container">
                <table class="pro-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: var(--dark);">
                                        {{ $order->product->name ?? $order->product_name ?? '—' }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--secondary);">
                                        {{ $order->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td>
                                    <span style="font-weight: 700; color: #10b981;">
                                        {{ number_format($order->price * $order->quantity) }} FRW
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 3rem; color: var(--secondary);">
                                    <i class="fas fa-ghost" style="display: block; font-size: 2rem; opacity: 0.2; margin-bottom: 10px;"></i>
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">

            {{-- Booking Status Chart --}}
            <div class="pro-card glass-panel analytical-card">
                <div class="card-header">
                    <h3><i class="fas fa-chart-pie"></i> Booking Status</h3>
                    <span class="header-pill">Distribution</span>
                </div>
                <div id="bookingChart" class="chart-container"></div>
            </div>

            {{-- Recent Bookings List --}}
            <div class="pro-card glass-panel activity-card">
                <div class="card-header">
                    <h3><i class="fas fa-stream"></i> Recent Bookings</h3>
                    <a href="{{ route('customer.bookings.index') }}" class="view-all-link">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="activity-list">
                    @forelse($recentBookings as $booking)
                        <div class="activity-item">
                            <div class="item-visual">
                                <i class="fas fa-concierge-bell"></i>
                            </div>
                            <div class="item-info">
                                <div class="item-title">
                                    {{ $booking->service->name ?? $booking->title ?? 'Booking #' . $booking->id }}
                                </div>
                                <div class="item-meta">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                </div>
                            </div>
                            <span class="status-badge status-{{ $booking->status }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 3rem; color: var(--secondary);">
                            <i class="fas fa-calendar-times" style="display: block; font-size: 2rem; opacity: 0.2; margin-bottom: 10px;"></i>
                            No bookings yet.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    @if(count($bookingCounts) > 0)
    var bookingOptions = {
        series: @json($bookingCounts),
        chart: {
            type: 'donut',
            height: 300,
            fontFamily: 'inherit'
        },
        labels: @json($bookingLabels),
        colors: ['#10b981', '#f59e0b', '#ef4444', '#6366f1', '#a855f7'],
        legend: {
            position: 'bottom',
            fontWeight: 600,
            labels: { colors: '#64748b' }
        },
        dataLabels: { enabled: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Bookings',
                            color: '#64748b',
                            fontWeight: 700,
                            formatter: () => '{{ $bookingsCount }}'
                        }
                    }
                }
            }
        },
        stroke: { width: 0 },
        tooltip: { y: { formatter: val => val + ' booking(s)' } }
    };
    new ApexCharts(document.querySelector('#bookingChart'), bookingOptions).render();
    @else
    document.querySelector('#bookingChart').innerHTML =
        '<p style="text-align:center; padding: 3rem; color: var(--secondary);">No booking data to display.</p>';
    @endif
</script>