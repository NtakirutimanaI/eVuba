@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<style>
    :root {
        --sidebar-width: 222px;
        --content-gap: 20px;
    }

    .performance-container {
        margin-left: calc(var(--sidebar-width) + var(--content-gap));
        padding: 30px;
        margin-top: 70px;
        background: var(--body-bg);
        min-height: calc(100vh - 70px);
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header-section h1 {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .glass-card {
        background: var(--surface);
        backdrop-filter: blur(10px);
        border: 1px solid var(--header-border);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        transition: transform 0.2s;
        color: var(--text-primary);
    }

    .glass-card:hover {
        transform: translateY(-5px);
    }

    .chart-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    @media (max-width: 1200px) {
        .chart-container {
            grid-template-columns: 1fr;
        }
    }

    .performance-table-card {
        background: var(--surface);
        border-radius: 16px;
        padding: 0;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        border: 1px solid var(--header-border);
    }

    .performance-table {
        width: 100%;
        border-collapse: collapse;
    }

    .performance-table th {
        background: var(--body-bg);
        padding: 16px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--header-border);
    }

    .performance-table td {
        padding: 16px;
        border-bottom: 1px solid var(--header-border);
        font-size: 14px;
        color: var(--text-primary);
    }

    .badge {
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-success { background: #dcfce7; color: #166534; }
    .badge-primary { background: #dbeafe; color: #1e40af; }
    .badge-warning { background: #fef9c3; color: #854d0e; }

    .employee-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #6366f1;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 12px;
    }

    .flex-center {
        display: flex;
        align-items: center;
    }

    .metric-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 10px 0;
    }

    .metric-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
    }
</style>

<div class="performance-container">
    <div class="header-section">
        <div>
            <h1>Employee Performance Analytics</h1>
            <p style="color: #64748b; margin-top: 5px;">Measure and track team productivity and efficiency</p>
        </div>
        <div class="flex-center gap-3">
            <button class="btn-primary" style="padding: 10px 20px; border-radius: 10px;" onclick="window.print()">
                <i class="fas fa-download mr-2"></i> Export Report
            </button>
        </div>
    </div>

    <div class="stats-grid">
        <div class="glass-card">
            <div class="metric-label">Top Performer</div>
            <div class="flex-center mt-2">
                <div class="employee-avatar">{{ substr($performance[0]['name'], 0, 1) }}</div>
                <div>
                    <div style="font-weight: 700; color: var(--text-primary);">{{ $performance[0]['name'] }}</div>
                    <div style="font-size: 12px; color: var(--text-muted);">{{ $performance[0]['position'] }}</div>
                </div>
            </div>
            <div class="metric-value" style="color: #6366f1;">{{ number_format($performance[0]['total_score'], 0) }}</div>
            <div class="metric-label">Performance Score</div>
        </div>

        <div class="glass-card">
            <div class="metric-label">Team Total Sales</div>
            <div class="metric-value" style="color: #10b981;">${{ number_format($performance->sum('sales'), 2) }}</div>
            <div style="font-size: 12px; color: #10b981;">
                <i class="fas fa-arrow-up mr-1"></i> +12.5% vs last month
            </div>
        </div>

        <div class="glass-card">
            <div class="metric-label">Avg Task Completion</div>
            <div class="metric-value" style="color: #f59e0b;">{{ round($performance->avg('task_rate'), 1) }}%</div>
            <div class="progress-bar-container" style="height: 6px; background: #fef3c7;">
                <div class="progress-bar" style="width: {{ $performance->avg('task_rate') }}%; background: #f59e0b;"></div>
            </div>
        </div>
    </div>

    <div class="chart-container">
        <div class="glass-card">
            <h3 style="font-size: 18px; margin-bottom: 20px;">Sales Performance Trends</h3>
            <div id="salesHistoryChart"></div>
        </div>
        <div class="glass-card">
            <h3 style="font-size: 18px; margin-bottom: 20px;">Activity Distribution</h3>
            <div id="distributionChart"></div>
        </div>
    </div>

    <div class="performance-table-card">
        <div style="padding: 24px; border-bottom: 1px solid var(--header-border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 18px; margin: 0; color: var(--text-primary);">Detailed Performance Matrix</h3>
            <input type="text" placeholder="Filter employees..." style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--header-border); outline: none; width: 250px; background: var(--body-bg); color: var(--text-primary);">
        </div>
        <table class="performance-table">
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
                        <div class="flex-center">
                            <div class="employee-avatar" style="background: hsl({{ $loop->index * 40 % 360 }}, 70%, 60%)">
                                {{ substr($p['name'], 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $p['name'] }}</div>
                                <div style="font-size: 11px; color: #64748b;">{{ $p['position'] }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-weight: 600;">${{ number_format($p['sales'], 2) }}</td>
                    <td><span class="badge badge-primary">{{ $p['bookings'] }}</span></td>
                    <td><span class="badge badge-warning">{{ $p['appointments'] }}</span></td>
                    <td>
                        <div style="font-size: 12px; margin-bottom: 4px;">{{ $p['tasks_completed'] }} Done</div>
                        <div class="progress-bar-container" style="height: 4px; width: 60px;">
                            <div class="progress-bar" style="width: {{ $p['task_rate'] }}%; background: #6366f1;"></div>
                        </div>
                    </td>
                    <td>{{ $p['avg_response'] }}h</td>
                    <td>
                        <div style="font-weight: 700; color: #6366f1;">{{ number_format($p['total_score'], 0) }}</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
            zoom: { enabled: false }
        },
        colors: ['#6366f1', '#10b981', '#f59e0b', '#ef4444'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: {
            categories: @json($months),
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
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
            borderColor: '#f1f5f9',
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
            height: 350
        },
        labels: @json($activityDistribution['labels']),
        colors: ['#6366f1', '#10b981', '#f59e0b', '#ef4444'],
        legend: {
            position: 'bottom'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Actions',
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


