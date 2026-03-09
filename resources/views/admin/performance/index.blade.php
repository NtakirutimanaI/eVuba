@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Employee Performance Analytics</h1>
            <p>Measure and track team productivity and task completion</p>
        </div>
    </div>

    <div class="dashboard-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
        <div class="glass-panel">
            <div class="metric-label">Top Performer</div>
            <div class="flex-center mt-2" style="display: flex; align-items: center;">
                <div class="employee-avatar"
                    style="width: 40px; height: 40px; border-radius: 50%; background: #6b7280; color: white; display: flex; align-items: center; justify-content: center; margin-right: 12px; font-weight: 600;">
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
            <div class="metric-label">Team Tasks Completed</div>
            <div class="metric-value" style="color: #10b981; font-size: 28px; font-weight: 700; margin: 10px 0;">
                {{ $performance->sum('tasks_completed') }}
            </div>
            <div style="font-size: 12px; color: var(--text-muted);">
                out of {{ $performance->sum('tasks_assigned') }} assigned
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
            <h3 style="font-size: 18px; margin-bottom: 20px; font-weight: 700;">Task Completion Trends (Last 6 Months)
            </h3>
            <div id="taskTrendChart"></div>
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
                <input type="text" id="employeeFilter" placeholder="Filter employees..."
                    oninput="filterTable(this.value)">
            </div>
        </div>
        <div style="overflow-x: auto;">
            <table class="pro-table" id="performanceTable">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Bookings</th>
                        <th>Appointments</th>
                        <th>Tickets Resolved</th>
                        <th>Tasks Completed</th>
                        <th>Active Workload</th>
                        <th>Completion Rate</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($performance as $p)
                        <tr>
                            <td>
                                <div class="flex-center" style="display: flex; align-items: center;">
                                    <div class="employee-avatar"
                                        style="width: 32px; height: 32px; border-radius: 50%; background: #6b7280; color: white; display: flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 0.8rem; font-weight: 600;">
                                        {{ substr($p['name'], 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $p['name'] }}</div>
                                        <div style="font-size: 11px; color: var(--text-muted);">{{ $p['position'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="status-badge status-processing">{{ $p['bookings'] }}</span></td>
                            <td><span class="status-badge status-pending">{{ $p['appointments'] }}</span></td>
                            <td>
                                <span class="status-badge" style="background: #d1fae5; color: #065f46;">
                                    {{ $p['tickets_resolved'] }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 13px; font-weight: 600;">
                                    {{ $p['tasks_completed'] }} / {{ $p['tasks_assigned'] }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge"
                                    style="background: #fef3c7; color: #b45309; font-weight: 700; border: 1px solid #fde68a;">
                                    {{ $p['active_tasks'] }} Active
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div class="progress-bar-container"
                                        style="height: 6px; width: 70px; background: #e2e8f0; border-radius: 3px;">
                                        <div class="progress-bar"
                                            style="width: {{ $p['task_rate'] }}%; background: #6366f1; height: 100%; border-radius: 3px;">
                                        </div>
                                    </div>
                                    <span style="font-size: 12px; color: var(--text-muted);">{{ $p['task_rate'] }}%</span>
                                </div>
                            </td>
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

    <!-- Active Tasks Detail Table -->
    <div class="glass-panel" style="padding: 0; overflow: hidden; margin-top: 30px;">
        <div style="padding: 24px; border-bottom: 1px solid var(--glass-border);">
            <h3 style="font-size: 18px; margin: 0; color: var(--text-primary); font-weight: 700;">Current Active Tasks
                per Technician</h3>
            <p style="margin: 5px 0 0; font-size: 13px; color: var(--text-muted);">Pending and in-progress tasks
                currently assigned across all technicians.</p>
        </div>
        <div style="overflow-x: auto;">
            <table class="pro-table">
                <thead>
                    <tr>
                        <th>Task / Description</th>
                        <th>Technician</th>
                        <th>Customer / Client</th>
                        <th>Scheduled Date & Time</th>
                        <th>Priority</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeTasksList as $task)
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: var(--text-primary);">{{ $task->title }}</div>
                                @if($task->description)
                                    <div
                                        style="font-size: 12px; color: var(--text-muted); max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ Str::limit($task->description, 50) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <div
                                        style="width: 28px; height: 28px; border-radius: 50%; background: #3b82f6; color: white; display: flex; align-items: center; justify-content: center; margin-right: 8px; font-size: 0.75rem; font-weight: 600;">
                                        {{ substr($task->employee->name ?? 'T', 0, 1) }}
                                    </div>
                                    <span style="font-weight: 500;">{{ $task->employee->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td>
                                <span style="color: var(--text-muted); font-size: 13px;">
                                    {{ $task->user->name ?? 'Internal Task (No Customer)' }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 13px;">
                                    <i class="fas fa-calendar-day" style="color: var(--primary); margin-right: 4px;"></i>
                                    {{ \Carbon\Carbon::parse($task->scheduled_at)->format('M d, Y') }}
                                    <br>
                                    <small
                                        style="color: var(--text-muted); margin-left: 18px;">{{ \Carbon\Carbon::parse($task->scheduled_at)->format('h:i A') }}</small>
                                </div>
                            </td>
                            <td>
                                @if($task->priority == 'urgent' || $task->priority == 'high')
                                    <span class="status-badge" style="background: #fee2e2; color: #ef4444;"><i
                                            class="fas fa-exclamation-circle"></i> {{ ucfirst($task->priority) }}</span>
                                @elseif($task->priority == 'medium')
                                    <span class="status-badge"
                                        style="background: #fef3c7; color: #f59e0b;">{{ ucfirst($task->priority) }}</span>
                                @else
                                    <span class="status-badge status-processing">{{ ucfirst($task->priority ?? 'Low') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-{{ $task->status }}">
                                    {{ $task->status == 'confirmed' ? 'In Progress' : ucfirst($task->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                                <i class="fas fa-check-circle"
                                    style="font-size: 24px; color: #10b981; margin-bottom: 10px; display: block;"></i>
                                No active tasks across all technicians at the moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($activeTasksList->hasPages())
            <div class="pagination-wrapper" style="padding: 15px 24px; border-top: 1px solid var(--glass-border); background: var(--surface);">
                {{ $activeTasksList->appends(request()->except('active_tasks_page'))->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>

</div>

<script>
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const chartTheme = { mode: isDark ? 'dark' : 'light' };

    // Task Completion Trend Chart
    var taskOptions = {
        theme: chartTheme,
        series: @json($taskTrends),
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
                formatter: function (val) { return Math.round(val) + ' tasks'; }
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

    var taskChart = new ApexCharts(document.querySelector("#taskTrendChart"), taskOptions);
    taskChart.render();

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

    // Live filter
    function filterTable(query) {
        const rows = document.querySelectorAll('#performanceTable tbody tr');
        rows.forEach(row => {
            const name = row.querySelector('td:first-child')?.textContent.toLowerCase() || '';
            row.style.display = name.includes(query.toLowerCase()) ? '' : 'none';
        });
    }
</script>