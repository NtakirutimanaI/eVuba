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
    {{-- Stats Grid --}}
    <div class="dashboard-grid" style="grid-template-columns: repeat(2, 1fr);">
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
    </div>

    {{-- Main Content Grid --}}
    <div class="main-grid">
        {{-- Left Column: Recent Sales Table --}}
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
                            <td>{{ number_format($sale->total_amount) }} FRW</td>
                            <td>{{ $sale->created_at->format('M d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align:center; padding:1rem;">No recent sales</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Right Column: Charts & Appointments --}}
        <div style="display: flex; flex-direction: column; gap: 20px;">
            {{-- Task Chart --}}
            <div class="pro-card">
                <h3 style="margin-top:0; font-size:1.1rem; font-weight:700;">Task Status</h3>
                <div id="taskChart" class="chart-container"></div>
            </div>

            {{-- Upcoming Appointments --}}
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
</div>

<script>


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
