@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard-wrapper" style="padding: 1.5rem;">
    <!-- Sleek Header -->
    <div class="pro-header" style="margin-bottom: 2rem; align-items: flex-end;">
        <div>
            <h1 style="font-size: 1.6rem; letter-spacing: -0.5px;">Appointment Orchestration</h1>
            <p style="color: var(--secondary); font-size: 0.85rem; margin-top: 0.25rem;">Tactical scheduling and
                tactical service management</p>
        </div>
        <div class="header-actions" style="display: flex; gap: 1rem; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 0.7rem; font-weight: 700; color: var(--secondary);">START DATE</label>
                <input type="date" id="report_start" class="filter-input"
                    style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--glass-border); background: var(--bg-main); color: var(--dark);">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 0.7rem; font-weight: 700; color: var(--secondary);">END DATE</label>
                <input type="date" id="report_end" class="filter-input"
                    style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--glass-border); background: var(--bg-main); color: var(--dark);">
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button onclick="downloadReport('pdf')" class="action-btn"
                    style="background: #ef4444; color: white; padding: 0.5rem 1rem; border: none;">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
                <button onclick="downloadReport('excel')" class="action-btn"
                    style="background: #22c55e; color: white; padding: 0.5rem 1rem; border: none;">
                    <i class="fas fa-file-excel"></i> EXCEL
                </button>
            </div>
        </div>
    </div>

    <!-- Compact Operational Stats -->
    <div class="dashboard-grid"
        style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="pro-card" style="padding: 1.25rem;">
            <div class="stat-widget" style="gap: 1rem;">
                <div class="stat-icon"
                    style="width: 45px; height: 45px; font-size: 1.1rem; background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-info">
                    <div class="label" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">All
                        Bookings</div>
                    <div class="value" style="font-size: 1.3rem;">{{ number_format($stats['total']) }}</div>
                </div>
            </div>
        </div>

        <div class="pro-card" style="padding: 1.25rem;">
            <div class="stat-widget" style="gap: 1rem;">
                <div class="stat-icon"
                    style="width: 45px; height: 45px; font-size: 1.1rem; background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-info">
                    <div class="label" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">In
                        Review</div>
                    <div class="value" style="font-size: 1.3rem; color: var(--warning);">
                        {{ number_format($stats['pending']) }}</div>
                </div>
            </div>
        </div>

        <div class="pro-card" style="padding: 1.25rem;">
            <div class="stat-widget" style="gap: 1rem;">
                <div class="stat-icon"
                    style="width: 45px; height: 45px; font-size: 1.1rem; background: linear-gradient(135deg, #3b82f6, #60a5fa);">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-info">
                    <div class="label" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        Active Ops</div>
                    <div class="value" style="font-size: 1.3rem; color: var(--info);">
                        {{ number_format($stats['assigned']) }}</div>
                </div>
            </div>
        </div>

        <div class="pro-card" style="padding: 1.25rem;">
            <div class="stat-widget" style="gap: 1rem;">
                <div class="stat-icon"
                    style="width: 45px; height: 45px; font-size: 1.1rem; background: linear-gradient(135deg, #10b981, #34d399);">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="stat-info">
                    <div class="label" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        Settled</div>
                    <div class="value" style="font-size: 1.3rem; color: var(--success);">
                        {{ number_format($stats['completed']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics & Logic Tier -->
    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; margin-bottom: 2rem;">
        <div class="pro-card" style="padding: 1.5rem;">
            <h3 style="font-size: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-wave-square" style="color: var(--primary);"></i>
                Service Demand Pulse
            </h3>
            <div style="height: 180px; width: 100%;">
                <canvas id="appointmentPulseChart"></canvas>
            </div>
        </div>

        <div class="pro-card glass-card" style="padding: 1.5rem; background: rgba(99, 102, 241, 0.03);">
            <h3 style="font-size: 1rem; margin-bottom: 1rem;"><i class="fas fa-shield-alt"
                    style="margin-right: 0.5rem; color: var(--primary);"></i> Operational Logic</h3>
            <ul style="list-style: none; padding: 0; font-size: 0.8rem; color: var(--secondary); line-height: 1.6;">
                <li style="margin-bottom: 0.75rem; display: flex; gap: 0.75rem;">
                    <i class="fas fa-check-circle" style="color: var(--primary); margin-top: 3px;"></i>
                    <span>Assign agents to 'confirmed' cycles to initiate field operations.</span>
                </li>
                <li style="margin-bottom: 0.75rem; display: flex; gap: 0.75rem;">
                    <i class="fas fa-check-circle" style="color: var(--primary); margin-top: 3px;"></i>
                    <span>Settling a cycle archives it for permanent audit signature.</span>
                </li>
                <li>
                    <i class="fas fa-info-circle" style="color: var(--secondary);"></i>
                    <span>Canceled engagements are retained in registry for 90 days.</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- High-Density Ledger Section -->
    <div class="pro-card" style="padding: 0; overflow: hidden; border: 1px solid var(--glass-border);">
        <div
            style="padding: 1.25rem; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; flex-wrap: wrap; background: var(--glass);">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <h3 style="font-size: 1rem; margin: 0;">Scheduling Ledger</h3>
                <span
                    style="font-size: 0.75rem; background: var(--bg-main); padding: 2px 8px; border-radius: 4px; color: var(--secondary); font-weight: 600;">
                    Showing {{ $appointments->count() }} records
                </span>
            </div>

            <form action="{{ route('manager.appointments') }}" method="GET"
                style="display: flex; gap: 0.75rem; flex: 1; max-width: 600px;">
                <div class="pro-search"
                    style="flex: 1; min-width: 250px; background: var(--white); border: 1px solid var(--glass-border); border-radius: 0.5rem; padding: 0.4rem 0.75rem; display: flex; align-items: center;">
                    <i class="fas fa-search" style="font-size: 0.8rem; color: var(--secondary);"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search client, title, or status..."
                        style="border: none; background: none; font-size: 0.85rem; width: 100%; outline: none; margin-left: 0.5rem;">
                </div>
                <select name="status" class="glass-select"
                    style="padding: 0.4rem; font-size: 0.8rem; border-radius: 0.5rem; min-width: 120px;">
                    <option value="">All Cycles</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>PENDING</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>CONFIRMED</option>
                    <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>ASSIGNED</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>COMPLETED</option>
                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>CANCELED</option>
                </select>
                <button type="submit" class="action-btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.8rem;">
                    <i class="fas fa-sync-alt"></i> REFRESH
                </button>
            </form>
        </div>

        <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="pro-table" style="width: 100%; font-size: 0.85rem;">
                <thead style="position: sticky; top: 0; z-index: 10; background: var(--white);">
                    <tr>
                        <th style="padding: 1rem;">REF</th>
                        <th>Client Entity</th>
                        <th>Engagement Pulse</th>
                        <th>Status</th>
                        <th>Tactical Window</th>
                        <th style="text-align: right; padding-right: 1.25rem;">Orchestration</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <td style="padding: 1rem;"><span
                                    style="font-weight: 700; color: var(--primary);">#{{ $appointment->id }}</span></td>
                            <td>
                                <div style="font-weight: 700; color: var(--dark);">
                                    {{ $appointment->user->name ?? 'Direct Client' }}</div>
                                <div style="font-size: 0.7rem; color: var(--secondary);">
                                    {{ $appointment->user->email ?? 'digital-ghost' }}</div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--dark);">{{ $appointment->title }}</div>
                                <div
                                    style="font-size: 0.7rem; color: var(--secondary); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $appointment->description }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $appointment->status }}"
                                    style="font-size: 0.65rem; padding: 0.25rem 0.6rem; font-weight: 800;">
                                    {{ strtoupper($appointment->status) }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: var(--dark);">
                                    {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('M d, Y') }}
                                </div>
                                <div style="font-size: 0.7rem; color: var(--secondary);">
                                    <i class="far fa-clock"></i>
                                    {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') }}
                                </div>
                            </td>
                            <td style="padding-right: 1.25rem;">
                                <div style="display: flex; gap: 0.4rem; justify-content: flex-end; align-items: center;">
                                    <a href="{{ route('manager.appointments.show', $appointment->id) }}" class="action-btn"
                                        style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; background: rgba(99, 102, 241, 0.05); color: var(--primary); border: 1px solid rgba(99, 102, 241, 0.1);">
                                        <i class="fas fa-eye" style="font-size: 0.75rem;"></i>
                                    </a>

                                    @if($appointment->status !== 'completed')
                                        <form action="{{ route('manager.appointments.complete', $appointment->id) }}"
                                            method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="action-btn"
                                                style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; background: rgba(16, 185, 129, 0.05); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.1);"
                                                title="Settle Cycle">
                                                <i class="fas fa-check" style="font-size: 0.75rem;"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('manager.appointments.assign', $appointment->id) }}"
                                        method="POST"
                                        style="display: flex; gap: 0.25rem; align-items: center; background: var(--bg-main); padding: 2px 4px; border-radius: 0.4rem; border: 1px solid var(--glass-border);">
                                        @csrf @method('PATCH')
                                        <select name="employee_id" required class="glass-select"
                                            style="padding: 2px 4px; font-size: 0.65rem; border: none; background: none; font-weight: 700;">
                                            <option value="">Agent</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ $appointment->employee_id == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="action-btn"
                                            style="padding: 2px 6px; font-size: 0.65rem; background: var(--warning); color: #fff; border: none;"
                                            title="Delegate">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    </form>

                                    @if($appointment->status !== 'canceled')
                                        <form action="{{ route('manager.appointments.destroy', $appointment->id) }}"
                                            method="POST" onsubmit="return confirm('Abort this tactical cycle?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn"
                                                style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; background: rgba(239, 68, 68, 0.05); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.1);"
                                                title="Abort">
                                                <i class="fas fa-ban" style="font-size: 0.75rem;"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 5rem; color: var(--secondary);">
                                <i class="fas fa-calendar-times"
                                    style="font-size: 3rem; opacity: 0.05; margin-bottom: 1rem; display: block;"></i>
                                <h3 style="font-size: 1.1rem; opacity: 0.5;">Scheduling Registry Silent</h3>
                                <p style="font-size: 0.85rem;">No active tactical cycles detected in the current filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div
            style="padding: 1.25rem; border-top: 1px solid var(--glass-border); display: flex; justify-content: center;">
            {{ $appointments->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<style>
    .status-badge.status-pending {
        background: rgba(148, 163, 184, 0.08);
        color: #64748b;
    }

    .status-badge.status-confirmed {
        background: rgba(59, 130, 246, 0.08);
        color: #3b82f6;
    }

    .status-badge.status-assigned {
        background: rgba(99, 102, 241, 0.08);
        color: #6366f1;
    }

    .status-badge.status-completed {
        background: rgba(16, 185, 129, 0.08);
        color: #10b981;
    }

    .status-badge.status-canceled {
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
    }

    .glass-select {
        background: var(--bg-main);
        border: 1px solid var(--glass-border);
        color: var(--dark);
        outline: none;
        transition: all 0.2s;
    }

    .glass-select:focus {
        border-color: var(--primary);
    }

    .pro-table th {
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-size: 0.7rem;
        font-weight: 800;
        border-bottom: 1px solid var(--glass-border);
        background: var(--glass);
    }

    ::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 10px;
    }

    /* Pagination Styling */
    .pagination-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
    }

    .pagination-wrapper nav {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .pagination {
        display: flex;
        gap: 6px;
        list-style: none;
        padding: 0;
        margin: 0;
        flex-wrap: wrap;
        justify-content: center;
    }

    .pagination li {
        display: inline-block;
    }

    .pagination a,
    .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 12px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        border: 1px solid var(--glass-border);
        background: var(--bg-main);
        color: var(--dark);
    }

    .pagination a:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    .pagination .active span {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
    }

    .pagination .disabled span {
        opacity: 0.4;
        cursor: not-allowed;
        background: var(--bg-main);
    }

    /* Hide default laravel/bootstrap text summary "Showing x to y of z results" if it breaks layout */
    .pagination-wrapper nav>div.d-none.flex-sm-fill {
        display: none !important;
    }

    .pagination-wrapper nav>div:first-child {
        display: none !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('appointmentPulseChart').getContext('2d');
    const chartData = @json($chartData);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(d => d.date),
            datasets: [{
                label: 'Service Demand',
                data: chartData.map(d => d.count),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.05)',
                fill: true,
                tension: 0.45,
                pointRadius: 5,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                borderWidth: 2.5
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 10,
                    titleFont: { size: 12, weight: 'bold' },
                    bodyFont: { size: 11 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [5, 5], color: 'rgba(255,255,255,0.05)' },
                    ticks: { color: '#888', font: { size: 10, weight: '600' }, precision: 0 }
                },
                x: { grid: { display: false }, ticks: { color: '#888', font: { size: 10, weight: '600' } } }
            }
        }
    });

    function downloadReport(type) {
        const start = document.getElementById('report_start').value;
        const end = document.getElementById('report_end').value;
        if (!start || !end) {
            alert('Please select both start and end date for operational audit.');
            return;
        }
        const baseUrl = "{{ route('manager.appointments.export') }}";
        window.location.href = `${baseUrl}?type=${type}&start_date=${start}&end_date=${end}`;
    }
</script>