@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Admin Dashboard</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Welcome back, {{ Auth::user()->name }}! Here's what's happening today.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.reports') }}" class="action-btn" style="background: var(--surface); border: 1px solid var(--header-border); color: var(--text-primary); text-decoration:none;">
                <i class="fas fa-file-export"></i> Reports
            </a>
            <button class="action-btn btn-primary">
                <i class="fas fa-plus"></i> New Project
            </button>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="dashboard-grid">
        <div class="pro-card metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">Total Revenue</div>
                <div class="metric-value">{{ number_format($totalSalesAmount) }} RWF</div>
                <div class="metric-trend {{ $salesGrowth >= 0 ? 'up' : 'down' }}">
                    <i class="fas fa-arrow-{{ $salesGrowth >= 0 ? 'up' : 'down' }}"></i> {{ number_format(abs($salesGrowth), 1) }}% growth
                </div>
            </div>
        </div>

        <div class="pro-card metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #10b981, #3b82f6);">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">Active Customers</div>
                <div class="metric-value">{{ $customersCount }}</div>
                <div class="metric-trend up">
                    <i class="fas fa-user-plus"></i> Registered
                </div>
            </div>
        </div>

        <div class="pro-card metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
                <i class="fas fa-shopping-basket"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">Total Orders</div>
                <div class="metric-value">{{ $ordersCount }}</div>
                <div class="metric-trend up">
                    <i class="fas fa-check-double"></i> Processed
                </div>
            </div>
        </div>

        <div class="pro-card metric-card">
            <div class="metric-icon" style="background: linear-gradient(135deg, #ec4899, #8b5cf6);">
                <i class="fas fa-cubes"></i>
            </div>
            <div class="metric-content">
                <div class="metric-label">Low Stock Alerts</div>
                <div class="metric-value">{{ $lowStockCount }}</div>
                <div class="metric-trend {{ $lowStockCount > 0 ? 'down' : 'up' }}">
                    <i class="fas fa-{{ $lowStockCount > 0 ? 'exclamation-triangle' : 'shield-alt' }}"></i> {{ $lowStockCount > 0 ? 'Action Required' : 'Optimal' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Main Charts Section --}}
    <div class="main-grid">
        <div class="pro-card glass-panel analytical-card">
            <div class="card-header">
                <h3><i class="fas fa-chart-line"></i> Performance Analytics</h3>
                <span class="header-pill">Revenue & Orders</span>
            </div>
            <div id="revenueChart" class="chart-container"></div>
        </div>
        
        <div class="pro-card glass-panel analytical-card">
            <div class="card-header">
                <h3><i class="fas fa-headset"></i> Support Intelligence</h3>
                <span class="header-pill">Ticket Status</span>
            </div>
            <div id="supportChart" class="chart-container"></div>
        </div>
    </div>

    {{-- Secondary Charts --}}
    <div class="main-grid" style="margin-top: 1.5rem; grid-template-columns: 1fr 1fr;">
        <div class="pro-card glass-panel">
            <div class="card-header">
                <h3><i class="fas fa-chart-pie"></i> Service Demographics</h3>
            </div>
            <div id="serviceChart" class="chart-container"></div>
        </div>
        <div class="pro-card glass-panel">
            <div class="card-header">
                <h3><i class="fas fa-user-friends"></i> User Acquisition</h3>
            </div>
            <div id="growthChart" class="chart-container"></div>
        </div>
    </div>

    {{-- Live Operations Data --}}
    <div class="dashboard-grid" style="grid-template-columns: 2fr 1fr 1fr; margin-top: 1.5rem;">
        
        {{-- Recent Orders --}}
        <div class="pro-card glass-panel">
            <div class="card-header">
                <h3><i class="fas fa-truck-loading"></i> Recent Logistics</h3>
                <a href="{{ route('admin.orders.index') }}" class="view-all-link">View All</a>
            </div>
            <div class="table-responsive">
                <table class="pro-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Status</th>
                            <th>Customer</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td><span style="font-family: monospace;">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                            <td><span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                            <td>{{ $order->customer->user->name ?? 'Guest' }}</td>
                            <td style="font-size: 0.8rem; color: var(--secondary);">{{ $order->created_at->format('M d') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align: center; color: var(--secondary);">No active orders.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="pro-card glass-panel">
            <div class="card-header">
                <h3><i class="fas fa-crown"></i> Market Leaders</h3>
            </div>
            <div class="activity-list">
                @forelse($topProducts as $tp)
                <div class="activity-item">
                    <div class="item-visual" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="item-info">
                        <div class="item-title">{{ $tp->product->name ?? 'Unknown' }}</div>
                        <div class="item-meta">{{ $tp->total_qty }} units distributed</div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; color: var(--secondary); padding: 20px;">No sales data yet.</div>
                @endforelse
            </div>
        </div>

        {{-- Support Feed --}}
        <div class="pro-card glass-panel">
            <div class="card-header">
                <h3><i class="fas fa-bell"></i> Support Feed</h3>
            </div>
            <div class="activity-list">
                @forelse($recentSupport as $ticket)
                <div class="activity-item">
                    <div class="item-visual" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="item-info">
                        <div class="item-title">{{ Str::limit($ticket->subject, 20) }}</div>
                        <div class="item-meta">{{ $ticket->created_at->diffForHumans() }}</div>
                    </div>
                    @if($ticket->status == 'open') <span style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%;"></span> @endif
                </div>
                @empty
                <div style="text-align: center; color: var(--secondary); padding: 20px;">All quiet.</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- New Arrivals Row --}}
    <div class="main-grid" style="margin-top: 1.5rem;">
        <div class="pro-card glass-panel">
            <div class="card-header">
                <h3><i class="fas fa-user-plus"></i> New Users</h3>
            </div>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                @foreach($recentUsers as $user)
                <div style="display: flex; align-items: center; gap: 10px; background: var(--bg-main); padding: 10px 15px; border-radius: 50px; border: 1px solid var(--glass-border);">
                    <div style="width: 30px; height: 30px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div style="font-size: 0.9rem; font-weight: 600;">{{ $user->name }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="pro-card glass-panel">
            <div class="card-header">
                <h3><i class="fas fa-id-badge"></i> New Employees</h3>
            </div>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                @foreach($recentEmployees as $emp)
                <div style="display: flex; align-items: center; gap: 10px; background: var(--bg-main); padding: 10px 15px; border-radius: 50px; border: 1px solid var(--glass-border);">
                    <div style="width: 30px; height: 30px; background: #64748b; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700;">
                        {{ substr($emp->name, 0, 1) }}
                    </div>
                    <div style="font-size: 0.9rem; font-weight: 600;">{{ $emp->name }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    const getThemeMode = () => document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
    
    // Revenue Area Chart
    var revenueOptions = {
        theme: { mode: getThemeMode() },
        series: [{
            name: 'Revenue',
            type: 'area',
            data: @json($revenueOverTime['data'])
        }, {
            name: 'Orders',
            type: 'line',
            data: @json($ordersOverTime['data'])
        }],
        chart: {
            height: 350,
            type: 'line',
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif',
            background: 'transparent'
        },
        stroke: {
            curve: 'smooth',
            width: [4, 4]
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 90, 100]
            }
        },
        colors: ['#6366f1', '#10b981'],
        labels: @json($revenueOverTime['labels']),
        yaxis: [{
            title: { text: 'Revenue (RWF)' },
        }, {
            opposite: true,
            title: { text: 'Orders' }
        }],
        legend: { position: 'top', horizontalAlign: 'right' }
    };
    var revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
    revenueChart.render();

    // Support Donut Chart
    var supportOptions = {
        theme: { mode: getThemeMode() },
        series: @json($supportStatus['data']),
        chart: {
            type: 'donut',
            height: 350,
            background: 'transparent'
        },
        labels: @json($supportStatus['labels']),
        colors: ['#22c55e', '#f59e0b', '#ef4444', '#6366f1'],
        legend: { position: 'bottom' },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        total: { show: true, label: 'Tickets', color: 'var(--text-primary)' },
                        value: { color: 'var(--text-primary)' },
                        name: { color: 'var(--text-primary)' }
                    }
                }
            }
        }
    };
    var supportChart = new ApexCharts(document.querySelector("#supportChart"), supportOptions);
    supportChart.render();

    // Service Bar Chart
    var serviceOptions = {
        theme: { mode: getThemeMode() },
        series: [{
            name: 'Count',
            data: @json($serviceDistribution['data'])
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: { show: false },
            background: 'transparent'
        },
        colors: ['#3b82f6'],
        plotOptions: {
            bar: {
                borderRadius: 8,
                horizontal: true,
            }
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: @json($serviceDistribution['labels']),
        }
    };
    var serviceChart = new ApexCharts(document.querySelector("#serviceChart"), serviceOptions);
    serviceChart.render();

    // Growth Line Chart
    var growthOptions = {
        theme: { mode: getThemeMode() },
        series: [{
            name: 'New Customers',
            data: @json($customersByMonth['data'])
        }],
        chart: {
            type: 'line',
            height: 350,
            zoom: { enabled: false },
            background: 'transparent'
        },
        dataLabels: { enabled: true },
        stroke: { curve: 'smooth', width: 4 },
        colors: ['#8b5cf6'],
        xaxis: {
            categories: @json($customersByMonth['labels']),
        }
    };
    var growthChart = new ApexCharts(document.querySelector("#growthChart"), growthOptions);
    growthChart.render();

    // Listen for theme changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === "attributes" && mutation.attributeName === "data-theme") {
                const newMode = getThemeMode();
                const update = { theme: { mode: newMode } };
                
                revenueChart.updateOptions(update);
                supportChart.updateOptions(update);
                serviceChart.updateOptions(update);
                growthChart.updateOptions(update);
            }
        });
    });

    observer.observe(document.documentElement, {
        attributes: true
    });
</script>
