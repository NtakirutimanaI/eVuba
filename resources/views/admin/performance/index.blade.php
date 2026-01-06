@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Employee Performance Analytics</h1>
            <p>Measure and track team productivity and efficiency</p>
        </div>

    </div>

    <div class="dashboard-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
        <div class="glass-panel">
            <div class="metric-label">Top Performer</div>
            <div class="flex-center mt-2" style="display: flex; align-items: center;">
                <div class="employee-avatar"
                    style="width: 40px; height: 40px; border-radius: 50%; background: #6366f1; color: white; display: flex; align-items: center; justify-content: center; margin-right: 12px; font-weight: 600;">
                    {{ substr($performance[0]['name'], 0, 1) }}
                </div>
                <div>
                    <div style="font-weight: 700; color: var(--text-primary);">{{ $performance[0]['name'] }}</div>
                    <div style="font-size: 12px; color: var(--text-muted);">{{ $performance[0]['position'] }}</div>
                </div>
            </div>
            <div class="metric-value" style="color: #6366f1; font-size: 28px; font-weight: 700; margin: 10px 0;">
                {{ number_format($performance[0]['total_score'], 0) }}
            </div>
            <div class="metric-label">Performance Score</div>
        </div>

        <div class="glass-panel">
            <div class="metric-label">Team Total Sales</div>
            <div class="metric-value" style="color: #10b981; font-size: 28px; font-weight: 700; margin: 10px 0;">
                ${{ number_format($performance->sum('sales'), 2) }}</div>
            <div style="font-size: 12px; color: #10b981;">
                <i class="fas fa-arrow-up mr-1"></i> +12.5% vs last month
            </div>
        </div>

        <div class="glass-panel">
            <div class="metric-label">Avg Task Completion</div>
            <div class="metric-value" style="color: #f59e0b; font-size: 28px; font-weight: 700; margin: 10px 0;">
                {{ round($performance->avg('task_rate'), 1) }}%
            </div>
            <div class="progress-bar-container"
                style="height: 6px; background: #fef3c7; border-radius: 3px; overflow: hidden;">
                <div class="progress-bar"
                    style="width: {{ $performance->avg('task_rate') }}%; background: #f59e0b; height: 100%;"></div>
            </div>
        </div>
    </div>

    <div class="main-grid" style="margin-bottom: 30px;">
        <div class="glass-panel">
            <h3 style="font-size: 18px; margin-bottom: 20px; font-weight: 700;">Sales Performance Trends</h3>
            <div id="salesHistoryChart"></div>
        </div>
        <div class="glass-panel">
            <h3 style="font-size: 18px; margin-bottom: 20px; font-weight: 700;">Activity Distribution</h3>
            <div id="distributionChart"></div>
        </div>
    </div>

    <div class="glass-panel" style="padding: 0; overflow: hidden;">
        <div
            style="padding: 24px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 18px; margin: 0; color: var(--text-primary); font-weight: 700;">Detailed Performance
                Matrix</h3>
            <div class="mega-search" style="width: 250px;">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Filter employees...">
            </div>
        </div>
        <div style="overflow-x: auto;">
            <table class="pro-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Sales</th>
                        <th>Bookings</th>
                        <th>Appointments</th>
                        <th>Tasks</th>
                        <th>Resol. Time</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($performance as $p)
                        <tr>
                            <td>
                                <div class="flex-center" style="display: flex; align-items: center;">
                                    <div class="employee-avatar"
                                        style="width: 32px; height: 32px; border-radius: 50%; background: hsl({{ $loop->index * 40 % 360 }}, 70%, 60%); color: white; display: flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 0.8rem; font-weight: 600;">
                                        {{ substr($p['name'], 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $p['name'] }}</div>
                                        <div style="font-size: 11px; color: var(--text-muted);">{{ $p['position'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-weight: 600;">${{ number_format($p['sales'], 2) }}</td>
                            <td><span class="status-badge status-processing">{{ $p['bookings'] }}</span></td>
                            <td><span class="status-badge status-pending">{{ $p['appointments'] }}</span></td>
                            <td>
                                <div style="font-size: 12px; margin-bottom: 4px;">{{ $p['tasks_completed'] }} Done</div>
                                <div class="progress-bar-container"
                                    style="height: 4px; width: 60px; background: #e2e8f0; border-radius: 2px;">
                                    <div class="progress-bar"
                                        style="width: {{ $p['task_rate'] }}%; background: #6366f1; height: 100%; border-radius: 2px;">
                                    </div>
                                </div>
                            </td>
                            <td>{{ $p['avg_response'] }}h</td>
                            <td>
                                <div style="font-weight: 700; color: #6366f1;">{{ number_format($p['total_score'], 0) }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const chartTheme = { mode: isDark ? 'dark' : 'light' };

    // Sales Trend Chart
    var salesOptions = {
        theme: chartTheme,
        series: @json($salesTrends),
        chart: {
            height: 350,
            type: 'area',
            toolbar: { show: false },
            zoom: { enabled: false },
            background: 'transparent'
        },
        colors: ['#6366f1', '#10b981', '#f59e0b', '#ef4444'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: {
            categories: @json($months),
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { colors: 'var(--text-muted)' } }
        },
        yaxis: {
            labels: {
                style: { colors: 'var(--text-muted)' },
                formatter: function (val) { return "$" + val.toLocaleString(); }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [20, 100, 100, 100]
            }
        },
        grid: {
            borderColor: 'var(--glass-border)',
            strokeDashArray: 4
        }
    };

    var salesChart = new ApexCharts(document.querySelector("#salesHistoryChart"), salesOptions);
    salesChart.render();

    // Distribution Chart
    var distOptions = {
        theme: chartTheme,
        series: @json($activityDistribution['data']),
        chart: {
            type: 'donut',
            height: 350,
            background: 'transparent'
        },
        labels: @json($activityDistribution['labels']),
        colors: ['#6366f1', '#10b981', '#f59e0b', '#ef4444'],
        legend: {
            position: 'bottom',
            labels: { colors: 'var(--text-main)' }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        name: { color: 'var(--text-muted)' },
                        value: { color: 'var(--text-main)' },
                        total: {
                            show: true,
                            label: 'Total Actions',
                            color: 'var(--text-muted)',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                            }
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false }
    };

    var distChart = new ApexCharts(document.querySelector("#distributionChart"), distOptions);
    distChart.render();
</script>