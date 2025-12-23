@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Team Performance Pulse</h1>
            <p style="color: var(--secondary); margin-top: 0.5rem;">Monitor and optimize institutional operational efficiency</p>
        </div>
        <div class="header-actions">
            <button class="action-btn btn-primary" onclick="window.print()">
                <i class="fas fa-file-pdf"></i> Operational Audit
            </button>
        </div>
    </div>

    <!-- Performance Telemetry -->
    <div class="dashboard-grid">
        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Elite Performer</div>
                    <div style="font-weight: 800; color: var(--dark); font-size: 1.1rem; margin-top: 5px;">{{ $performance[0]['name'] }}</div>
                    <div class="stat-trend up">
                        <i class="fas fa-star"></i> KPI: {{ number_format($performance[0]['total_score'], 0) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <i class="fas fa-rocket"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Total Orchestrations</div>
                    <div class="value">{{ $performance->sum('appointments') }}</div>
                    <div class="stat-trend">
                        Service Deployments
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #f87171);">
                    <i class="fas fa-microchip"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Avg Latency</div>
                    <div class="value">{{ round($performance->avg('avg_response'), 1) }}h</div>
                    <div class="stat-trend down">
                        Resolution Window
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Avg Task Rate</div>
                    <div class="value">{{ round($performance->avg('task_rate'), 1) }}%</div>
                    <div class="stat-trend up">
                        Target Consistency
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Tier -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <div class="pro-card">
            <h3 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-shopping-bag" style="color: var(--primary);"></i> Revenue Contribution Matrix
            </h3>
            <div id="salesHistoryChart"></div>
        </div>
        <div class="pro-card">
            <h3 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-tasks" style="color: var(--warning);"></i> Engagement Spread
            </h3>
            <div id="distributionChart"></div>
        </div>
    </div>

    <!-- Operational Matrix -->
    <div class="pro-card">
        <div style="padding-bottom: 1.5rem; border-bottom: 1px solid var(--glass-border); margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0;">Operational Performance Matrix</h3>
            <div class="pro-search">
                <i class="fas fa-search"></i>
                <input type="text" id="perfSearch" placeholder="Filter agents..." style="background: transparent; border: none; outline: none; padding: 0.5rem; width: 250px;">
            </div>
        </div>
        <div class="table-responsive">
            <table class="pro-table" id="perfLedger">
                <thead>
                    <tr>
                        <th>Strategic Agent</th>
                        <th>Revenue Contribution</th>
                        <th>Bookings</th>
                        <th>Deployment</th>
                        <th>Execution Rate</th>
                        <th>Latency</th>
                        <th>KPI Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($performance as $p)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 38px; height: 38px; border-radius: 10px; background: var(--bg-main); display: flex; align-items: center; justify-content: center; font-weight: 800; color: var(--primary); border: 1px solid var(--glass-border);">
                                    {{ substr($p['name'], 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 800; color: var(--dark);">{{ $p['name'] }}</div>
                                    <div style="font-size: 0.7rem; color: var(--secondary); text-transform: uppercase;">{{ $p['position'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-weight: 800; color: var(--dark);">FRW {{ number_format($p['sales']) }}</td>
                        <td><span class="status-badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary);">{{ $p['bookings'] }}</span></td>
                        <td><span class="status-badge" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">{{ $p['appointments'] }}</span></td>
                        <td>
                            <div style="width: 100px;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; margin-bottom: 4px; font-weight: 700;">
                                    <span>Rate</span>
                                    <span>{{ $p['task_rate'] }}%</span>
                                </div>
                                <div style="height: 6px; background: var(--bg-main); border-radius: 10px; overflow: hidden; border: 1px solid var(--glass-border);">
                                    <div style="width: {{ $p['task_rate'] }}%; height: 100%; background: linear-gradient(90deg, var(--primary), var(--info));"></div>
                                </div>
                            </div>
                        </td>
                        <td style="font-weight: 600;">{{ $p['avg_response'] }}h</td>
                        <td>
                            <div style="font-weight: 900; color: var(--success); font-size: 1.1rem;">{{ number_format($p['total_score'], 0) }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Sales Trend Chart
    var salesOptions = {
        series: @json($salesTrends),
        chart: {
            height: 350,
            type: 'area',
            toolbar: { show: false },
            zoom: { enabled: false },
            fontFamily: 'inherit'
        },
        colors: ['#6366f1', '#10b981', '#f59e0b', '#ef4444'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: {
            categories: @json($months),
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { colors: '#94a3b8' } }
        },
        yaxis: {
            labels: { 
                style: { colors: '#94a3b8' },
                formatter: function (val) { return "FRW " + val.toLocaleString(); }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.35,
                opacityTo: 0.05,
                stops: [20, 100]
            }
        },
        grid: {
            borderColor: 'rgba(226, 232, 240, 0.5)',
            strokeDashArray: 4
        },
        legend: { labels: { colors: '#475569' } }
    };

    var salesChart = new ApexCharts(document.querySelector("#salesHistoryChart"), salesOptions);
    salesChart.render();

    // Distribution Chart
    var distOptions = {
        series: @json($activityDistribution['data']),
        chart: {
            type: 'donut',
            height: 350,
            fontFamily: 'inherit'
        },
        labels: @json($activityDistribution['labels']),
        colors: ['#6366f1', '#10b981', '#f59e0b', '#ef4444'],
        legend: {
            position: 'bottom',
            labels: { colors: '#475569' }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Activity',
                            color: '#64748b',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                            }
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        stroke: { show: false }
    };

    var distChart = new ApexCharts(document.querySelector("#distributionChart"), distOptions);
    distChart.render();

    document.getElementById('perfSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#perfLedger tbody tr');
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>

@include('layouts.footer')
