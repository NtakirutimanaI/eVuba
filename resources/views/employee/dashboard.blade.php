@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1 style="display: flex; align-items: center; gap: 10px;">
                <span style="opacity: 0.6; font-weight: 400;">Welcome back,</span> 
                {{ Auth::user()->name }}! 
                <span style="font-size: 1.5rem;">👋</span>
            </h1>
            <p style="color: var(--secondary); margin: 5px 0 0; font-weight: 500;">
                <i class="far fa-calendar-alt"></i> {{ now()->format('l, F j, Y') }} | <i class="far fa-clock"></i> <span id="live-clock">{{ now()->format('H:i') }}</span>
            </p>
        </div>
        <div class="header-actions">
            <a href="{{ route('profile.show') }}" class="action-btn btn-primary" style="text-decoration:none; padding: 10px 20px; width: auto; height: auto; border-radius: 12px; font-weight: 600;">
                <i class="fas fa-user-circle"></i> View Profile
            </a>
        </div>
    </div>

    <script>
        setInterval(() => {
            const now = new Date();
            const clock = document.getElementById('live-clock');
            if(clock) clock.textContent = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
        }, 30000);
    </script>

    {{-- Stats Grid --}}
    <div class="dashboard-grid">
        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-info">
                    <div class="label">My Tasks</div>
                    <div class="value">{{ $tasksCount }}</div>
                    <div class="stat-trend up">Assigned to you</div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #3b82f6);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Total Sales</div>
                    <div class="value">{{ number_format($salesCount) }} RWF</div>
                    <div class="stat-trend up">Your revenue</div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Appointments</div>
                    <div class="value">{{ $appointmentsCount }}</div>
                    <div class="stat-trend up">Scheduled meetings</div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ec4899, #8b5cf6);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Tickets Resolved</div>
                    <div class="value">{{ $ticketsResolvedCount }}</div>
                    <div class="stat-trend up">Great job!</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="main-grid">
        <div class="pro-card">
            <h3 style="margin-top:0; font-size:1.1rem; font-weight:700;">My Sales Performance (Last 14 Days)</h3>
            <div id="salesChart" class="chart-container"></div>
        </div>
        
        <div class="pro-card">
            <h3 style="margin-top:0; font-size:1.1rem; font-weight:700;">Task Status</h3>
            <div id="taskChart" class="chart-container"></div>
        </div>
    </div>

    {{-- Recent Items --}}
    <div class="main-grid" style="margin-top: 1.5rem;">
        <div class="pro-card">
            <h3 style="margin-top:0; margin-bottom:1rem; font-size:1.1rem; font-weight:700;">Recent Sales</h3>
            <table class="pro-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSales as $sale)
                        <tr>
                            <td>{{ $sale->product->name ?? 'Product' }}</td>
                            <td>{{ number_format($sale->total_amount) }} RWF</td>
                            <td>{{ $sale->created_at->format('M d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align:center; padding:1rem;">No recent sales</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pro-card">
            <h3 style="margin-top:0; margin-bottom:1rem; font-size:1.1rem; font-weight:700;">Upcoming Appointments</h3>
            <ul style="list-style:none; padding:0; margin:0;">
                @forelse($recentAppointments as $app)
                    <li style="display:flex; align-items:center; gap:1rem; padding:0.75rem 0; border-bottom:1px solid #f1f5f9;">
                        <div style="width:10px; height:10px; background:var(--primary); border-radius:50%;"></div>
                        <div style="flex:1;">
                            <div style="font-weight:600; font-size:0.9rem;">{{ $app->title }}</div>
                            <div style="font-size:0.75rem; color:var(--secondary);">{{ $app->scheduled_at ? \Carbon\Carbon::parse($app->scheduled_at)->format('M d, H:i') : 'N/A' }}</div>
                        </div>
                    </li>
                @empty
                    <li style="text-align:center; padding:1rem; color:var(--secondary);">No upcoming meetings</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<script>
    // Sales Chart
    var salesOptions = {
        series: [{
            name: 'Sales Amount',
            data: @json($performanceValues)
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: { show: false }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth' },
        xaxis: { categories: @json($performanceLabels) },
        colors: ['#10b981'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
            }
        }
    };
    new ApexCharts(document.querySelector("#salesChart"), salesOptions).render();

    // Task Chart
    var taskOptions = {
        series: @json($taskCounts),
        chart: { type: 'donut', height: 350 },
        labels: @json($taskLabels),
        colors: ['#6366f1', '#f59e0b', '#ef4444', '#10b981'],
        legend: { position: 'bottom' }
    };
    new ApexCharts(document.querySelector("#taskChart"), taskOptions).render();
</script>
