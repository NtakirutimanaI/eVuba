@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Manager Dashboard</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Manage your team and operations efficiently.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <button class="action-btn btn-primary">
                <i class="fas fa-user-plus"></i> Add Team Member
            </button>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="dashboard-grid">
        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Team Size</div>
                    <div class="value">{{ $teamCount }}</div>
                    <div class="stat-trend up">Active members</div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #3b82f6);">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Bookings</div>
                    <div class="value">{{ $bookingsCount }}</div>
                    <div class="stat-trend up">{{ $bookingsCount }} Total</div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Tickets</div>
                    <div class="value">{{ $ticketsCount }}</div>
                    <div class="stat-trend down">{{ $ticketsCount }} open cases</div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ec4899, #8b5cf6);">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Activities</div>
                    <div class="value">{{ $appointmentsCount }}</div>
                    <div class="stat-trend up">Assigned tasks</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="main-grid">
        <div class="pro-card">
            <h3 style="margin-top:0; font-size:1.1rem; font-weight:700;">Bookings per Employee</h3>
            <div id="bookingChart" class="chart-container"></div>
        </div>
        
        <div class="pro-card">
            <h3 style="margin-top:0; font-size:1.1rem; font-weight:700;">Ticket Status</h3>
            <div id="ticketChart" class="chart-container"></div>
        </div>
    </div>

    {{-- Low Stock & Recent Activity --}}
    <div class="main-grid" style="margin-top: 1.5rem;">
        <div class="pro-card">
            <h3 style="margin-top:0; margin-bottom:1rem; font-size:1.1rem; font-weight:700;">Low Stock Alerts</h3>
            <div style="overflow-x: auto;">
                <table class="pro-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Current Stock</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockItems as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td><strong>{{ $product->remaining_stock }}</strong></td>
                                <td><span class="status-badge status-cancelled">Low</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align:center; padding:1rem;">All items are well stocked</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pro-card">
            <h3 style="margin-top:0; margin-bottom:1rem; font-size:1.1rem; font-weight:700;">Recent Team Activity</h3>
            <ul style="list-style:none; padding:0; margin:0;">
                @forelse($recentTeam as $member)
                    <li style="display:flex; align-items:center; gap:1rem; padding:0.75rem 0; border-bottom:1px solid #f1f5f9;">
                        <div style="width:36px; height:36px; background:var(--light); border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--primary); font-weight:bold;">
                            {{ substr($member->name ?? 'U', 0, 1) }}
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:600; font-size:0.9rem;">{{ $member->name ?? 'Unknown' }}</div>
                            <div style="font-size:0.75rem; color:var(--secondary);">{{ $member->position }}</div>
                        </div>
                    </li>
                @empty
                    <li style="text-align:center; padding:1rem; color:var(--secondary);">No recent activity</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<script>
    // Booking Chart
    var bookingOptions = {
        series: [{
            name: 'Bookings',
            data: @json($bookingCounts)
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: { show: false }
        },
        colors: ['#6366f1'],
        plotOptions: {
            bar: { borderRadius: 10, columnWidth: '50%' }
        },
        xaxis: { categories: @json($bookingLabels) },
        title: { text: 'Workload Distribution', align: 'left', style: { color: '#64748b' } }
    };
    new ApexCharts(document.querySelector("#bookingChart"), bookingOptions).render();

    // Ticket Status Chart
    var ticketOptions = {
        series: @json($ticketStatusCounts),
        chart: { type: 'pie', height: 350 },
        labels: @json($ticketStatusLabels),
        colors: ['#3b82f6', '#f59e0b', '#ef4444', '#10b981'],
        legend: { position: 'bottom' }
    };
    new ApexCharts(document.querySelector("#ticketChart"), ticketOptions).render();
</script>
