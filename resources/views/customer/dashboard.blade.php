@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-chart-line" style="color: var(--primary);"></i> Command Center</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Welcome back,
                <strong>{{ Auth::user()->name }}</strong>. Monitoring your operational footprint.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('customer.bookings.index') }}" class="action-btn btn-primary"
                style="text-decoration:none; padding: 0 25px; border-radius: 12px; height: 46px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-calendar-plus"></i> Initialize Engagement
            </a>
        </div>
    </div>

    {{-- Command Metrics --}}
    <div class="dashboard-grid" style="grid-template-columns: repeat(3, 1fr);">
        <div class="pro-card metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">Active Bookings</div>
                <div class="metric-value">{{ $bookingsCount }}</div>
                <div class="metric-trend up"><i class="fas fa-check-circle"></i> Service Synchronized</div>
            </div>
        </div>

        <div class="pro-card metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #10b981, #3b82f6);">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">Total Acquisitions</div>
                <div class="metric-value">{{ $ordersCount }}</div>
                <div class="metric-trend up"><i class="fas fa-box"></i> Logistics Registry</div>
            </div>
        </div>



        <div class="pro-card metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #ec4899, #8b5cf6);">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">Appointments</div>
                <div class="metric-value">{{ $appointmentsCount }}</div>
                <div class="metric-trend up"><i class="far fa-clock"></i> Next Session Pending</div>
            </div>
        </div>
    </div>



    {{-- Activity Feed --}}
    <div class="main-grid" style="margin-top: 1.5rem; margin-bottom: 3rem;">
        <div class="pro-card glass-panel activity-card">
            <div class="card-header">
                <h3><i class="fas fa-exchange-alt"></i> Transactional Pulse</h3>
                <a href="{{ route('customer.orders.index') }}" class="view-all-link">Archive <i
                        class="fas fa-arrow-right"></i></a>
            </div>
            <div class="table-container">
                <table class="pro-table">
                    <thead>
                        <tr>
                            <th>Manifest</th>
                            <th>Investment</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: var(--dark);">
                                        {{ $order->product->name ?? $order->product_name }}</div>
                                    <div style="font-size: 0.7rem; color: var(--secondary);">
                                        {{ $order->created_at->diffForHumans() }}</div>
                                </td>
                                <td><span
                                        style="font-weight: 700; color: #10b981;">{{ number_format($order->price * $order->quantity) }}
                                        FRW</span></td>
                                <td><span
                                        class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align:center; padding:3rem; color: var(--secondary);"><i
                                        class="fas fa-ghost"
                                        style="display:block; font-size: 2rem; opacity: 0.2; margin-bottom: 10px;"></i> No
                                    transactional pulse detected.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="pro-card glass-panel analytical-card">
                <div class="card-header">
                    <h3><i class="fas fa-chart-pie"></i> Engagement Lifecycle</h3>
                    <span class="header-pill">Distribution</span>
                </div>
                <div id="bookingChart" class="chart-container"></div>
            </div>

            <div class="pro-card glass-panel activity-card">
                <div class="card-header">
                    <h3><i class="fas fa-stream"></i> Engagement Pipeline</h3>
                    <a href="{{ route('customer.bookings.index') }}" class="view-all-link">Commands <i
                            class="fas fa-arrow-right"></i></a>
                </div>
                <div class="activity-list">
                    @forelse($recentBookings as $booking)
                        <div class="activity-item">
                            <div class="item-visual">
                                <i class="fas fa-concierge-bell"></i>
                            </div>
                            <div class="item-info">
                                <div class="item-title">{{ $booking->service->name ?? $booking->title }}</div>
                                <div class="item-meta"><i class="far fa-calendar-alt"></i>
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</div>
                            </div>
                            <span class="status-badge status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                        </div>
                    @empty
                        <div style="text-align:center; padding:3rem; color: var(--secondary);">
                            <i class="fas fa-terminal"
                                style="display:block; font-size: 2rem; opacity: 0.2; margin-bottom: 10px;"></i>
                            No active pipeline.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>



<script>


    // Booking Chart
    var bookingOptions = {
        series: @json($bookingCounts),
        chart: { type: 'donut', height: 300, fontFamily: 'inherit' },
        labels: @json($bookingLabels),
        colors: ['#10b981', '#f59e0b', '#ef4444', '#6366f1'],
        legend: { position: 'bottom', fontWeight: 600, labels: { colors: '#64748b' } },
        dataLabels: { enabled: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Engagements',
                            color: '#64748b',
                            fontWeight: 700,
                            formatter: () => '{{ $bookingsCount }}'
                        }
                    }
                }
            }
        },
        stroke: { width: 0 }
    };
    new ApexCharts(document.querySelector("#bookingChart"), bookingOptions).render();
</script>