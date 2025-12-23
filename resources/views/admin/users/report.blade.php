@include('layouts.header')
@include('layouts.sidebar')

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary: #4f46e5;
        --secondary: #64748b;
        --glass-bg: rgba(255, 255, 255, 0.95);
        --glass-border: 1px solid rgba(226, 232, 240, 0.8);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        font-family: 'Inter', sans-serif;
        color: #1e293b;
    }

    .main-content {
        margin-left: 242px;
        padding: 30px;
        transition: margin-left 0.3s ease;
    }

    .report-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        border: var(--glass-border);
        border-radius: 20px;
        padding: 35px;
        box-shadow: var(--shadow-lg);
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 40px;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 25px;
    }

    .title-area h2 {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.5px;
    }

    .title-area p {
        color: var(--secondary);
        margin: 8px 0 0 0;
        font-size: 15px;
    }

    /* Filters Section */
    .filter-bar {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 35px;
    }

    .filter-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        align-items: flex-end;
    }

    .input-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .input-group label {
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .input-group input {
        padding: 12px 15px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .input-group input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }

    .btn-generate {
        background: var(--primary);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s;
    }

    .btn-generate:hover {
        background: #4338ca;
        transform: translateY(-1px);
    }

    /* Actions Section */
    .action-row {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
    }

    .btn-export {
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-pdf { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
    .btn-excel { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .btn-email { background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; }

    .btn-export:hover { transform: translateY(-1px); filter: brightness(0.95); }

    /* Table Design */
    .table-container {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .report-table th {
        background: #f8fafc;
        padding: 16px 20px;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        border-bottom: 2px solid #f1f5f9;
    }

    .report-table td {
        padding: 18px 20px;
        font-size: 14px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }

    .role-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        background: #f1f5f9;
        color: #475569;
    }

    .status-active { background: #dcfce7; color: #15803d; }
    .status-inactive { background: #fee2e2; color: #dc2626; }

    @media (max-width: 1024px) {
        .main-content { margin-left: 0; }
    }
</style>

<div class="main-content">
    <div class="report-card">
        <div class="header-section">
            <div class="title-area">
                <h2>User Intelligence Report</h2>
                <p>Comprehensive analysis of user registrations and status.</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 14px; font-weight: 600; color: #64748b;">REPORT DATE</div>
                <div style="font-size: 18px; font-weight: 800; color: #0f172a;">{{ date('M d, Y') }}</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-bar">
            <form action="{{ route('admin.users.report') }}" method="GET" class="filter-form">
                <div class="input-group">
                    <label>From Date</label>
                    <input type="date" name="from_date" value="{{ $request->from_date }}" required>
                </div>
                <div class="input-group">
                    <label>To Date</label>
                    <input type="date" name="to_date" value="{{ $request->to_date }}" required>
                </div>
                <button type="submit" class="btn-generate">
                    <i class="fas fa-sync-alt"></i> Generate Report
                </button>
            </form>
        </div>

        <!-- Actions -->
        @if(count($users) > 0)
        <div class="action-row">
            <a href="{{ route('admin.users.report.pdf', ['from_date' => $request->from_date, 'to_date' => $request->to_date]) }}" class="btn-export btn-pdf">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('admin.users.report.excel', ['from_date' => $request->from_date, 'to_date' => $request->to_date]) }}" class="btn-export btn-excel">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <button type="button" class="btn-export btn-email" onclick="openEmailModal()">
                <i class="fas fa-envelope"></i> Email Report
            </button>
        </div>
        @endif

        <!-- Data table -->
        <div class="table-container">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Details</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Verification</th>
                        <th>Joined Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td style="font-weight: 700; color: #94a3b8;">#{{ $user->id }}</td>
                        <td>
                            <div style="font-weight: 700; color: #0f172a;">{{ $user->name }}</div>
                            <div style="font-size: 12px; color: #94a3b8;">{{ $user->email }}</div>
                        </td>
                        <td><span class="role-badge">{{ ucfirst($user->role ?? 'User') }}</span></td>
                        <td>
                            <span class="role-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            @if($user->email_verified_at)
                                <span style="color: #15803d; font-size: 12px; font-weight: 600;"><i class="fas fa-check-circle"></i> Verified</span>
                            @else
                                <span style="color: #f59e0b; font-size: 12px; font-weight: 600;"><i class="fas fa-clock"></i> Pending</span>
                            @endif
                        </td>
                        <td style="font-family: monospace;">{{ $user->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 50px; color: #64748b;">
                            <i class="fas fa-info-circle" style="font-size: 30px; margin-bottom: 15px; display: block;"></i>
                            No users found for the selected date range.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Email Modal -->
<div id="emailModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:10001; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:15px; width:400px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="margin-top:0;">Email User Report</h3>
        <form action="{{ route('admin.users.report.email') }}" method="POST">
            @csrf
            <input type="hidden" name="from_date" value="{{ $request->from_date }}">
            <input type="hidden" name="to_date" value="{{ $request->to_date }}">
            <div class="input-group" style="margin-bottom: 20px;">
                <label>Recipient Email</label>
                <input type="email" name="email" placeholder="enter@email.com" required style="width:100%;">
            </div>
            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn-generate" style="flex:1;">Send Now</button>
                <button type="button" onclick="closeEmailModal()" style="padding:10px 20px; border:none; background:#f1f5f9; border-radius:10px; cursor:pointer;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEmailModal() {
        document.getElementById('emailModal').style.display = 'flex';
    }
    function closeEmailModal() {
        document.getElementById('emailModal').style.display = 'none';
    }
</script>
