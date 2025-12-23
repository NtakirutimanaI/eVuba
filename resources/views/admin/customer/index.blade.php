@include('layouts.header')
@include('layouts.sidebar')

<div class="customer-dashboard-wrapper">
    <!-- Header -->
    <div class="page-header">
        <div class="header-title">
            <h1><i class="fas fa-address-book"></i> Customer Database</h1>
            <p>Manage client details, contact information, and reporting.</p>
        </div>
        <div class="header-actions">
            <button onclick="toggleReportPanel()" class="btn-glass secondary"><i class="fas fa-file-export"></i> Reports</button>
            <a href="{{ route('admin.customers.create') }}" class="btn-glass primary"><i class="fas fa-plus"></i> Add Customer</a>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card glass-panel">
            <div class="icon-box blue"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <h3>{{ $customers->count() }}</h3>
                <span>Total Customers</span>
            </div>
        </div>
        <div class="stat-card glass-panel">
            <div class="icon-box green"><i class="fas fa-user-plus"></i></div>
            <div class="stat-info">
                <h3>{{ $customers->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                <span>New This Month</span>
            </div>
        </div>
        <!-- Logic for 'With Phone' count -->
        <div class="stat-card glass-panel">
            <div class="icon-box purple"><i class="fas fa-phone-alt"></i></div>
            <div class="stat-info">
                <h3>{{ $customers->whereNotNull('phone')->count() }}</h3>
                <span>Phone Contacts</span>
            </div>
        </div>
    </div>

    <!-- Report Panel (Hidden) -->
    <div id="reportPanel" class="report-panel glass-panel">
        <div class="rp-header">
            <h3>Generate Client Report</h3>
            <button onclick="toggleReportPanel()" class="close-btn">&times;</button>
        </div>
        <form action="{{ route('admin.customers.report') }}" method="GET" class="rp-body">
            <div class="form-group">
                <label>Registration Date Range</label>
                <div class="date-inputs">
                    <input type="date" name="from_date" class="glass-input" required>
                    <span class="separator">to</span>
                    <input type="date" name="to_date" class="glass-input" required>
                </div>
            </div>
            <div class="rp-actions">
                <button type="submit" name="type" value="pdf" class="btn-glass pdf"><i class="fas fa-file-pdf"></i> PDF</button>
                <button type="submit" name="type" value="excel" class="btn-glass excel"><i class="fas fa-file-excel"></i> Excel</button>
            </div>
        </form>
    </div>

    <!-- Main Content -->
    <div class="content-panel glass-panel">
        <div class="panel-toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="customerSearch" placeholder="Search by name, email, or phone..." onkeyup="filterCustomers()">
            </div>
        </div>

        <div class="customer-list">
            @forelse($customers as $customer)
            <div class="customer-item">
                <div class="c-avatar">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                
                <div class="c-info-main">
                    <h4>{{ $customer->name }}</h4>
                    <span class="c-email"><i class="fas fa-envelope"></i> {{ $customer->email }}</span>
                </div>

                <div class="c-details">
                    @if($customer->phone)
                    <div class="detail-pill">
                        <i class="fas fa-phone"></i> {{ $customer->phone }}
                    </div>
                    @endif
                    
                    @if($customer->address)
                    <div class="detail-pill address" title="{{ $customer->address }}">
                        <i class="fas fa-map-marker-alt"></i> {{ Str::limit($customer->address, 30) }}
                    </div>
                    @endif
                </div>

                <div class="c-meta">
                    <span>Joined {{ $customer->created_at->format('M Y') }}</span>
                </div>

                <div class="c-actions">
                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn-icon edit" title="Edit"><i class="fas fa-pen"></i></a>
                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Delete this customer?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-icon delete" title="Delete"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-users-slash"></i>
                <p>No customers found.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
/* Global Theme Integration */
body { background: var(--body-bg); font-family: 'Inter', sans-serif; }

.customer-dashboard-wrapper { margin-left: 250px; padding: 30px 40px; min-height: 100vh; }

/* HEADER */
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.header-title h1 { font-size: 28px; font-weight: 800; color: var(--text-primary); margin: 0; }
.header-title p { color: var(--text-muted); margin: 5px 0 0; }

.header-actions { display: flex; gap: 15px; }

.btn-glass {
    padding: 10px 20px; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;
    border: 1px solid var(--header-border); transition: all 0.2s; cursor: pointer; font-size: 14px;
}
.btn-glass.primary { background: var(--primary); color: #fff; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
.btn-glass.primary:hover { transform: translateY(-2px); }
.btn-glass.secondary { background: var(--surface); color: var(--text-primary); border: 1px solid var(--header-border); }
.btn-glass.secondary:hover { background: var(--body-bg); }
.btn-glass.pdf { background: #ef4444; color: #fff; border: none; }
.btn-glass.excel { background: #10b981; color: #fff; border: none; }

/* STATS */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
.stat-card {
    background: var(--surface); padding: 20px; border-radius: 16px; border: 1px solid var(--header-border);
    display: flex; align-items: center; gap: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
}
.icon-box { width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.icon-box.blue { background: rgba(59, 130, 246, 0.1); color: var(--primary); }
.icon-box.green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.icon-box.purple { background: rgba(147, 51, 234, 0.1); color: #9333ea; }
.stat-info h3 { margin: 0; font-size: 22px; font-weight: 800; color: var(--text-primary); }
.stat-info span { font-size: 12px; color: var(--text-muted); }

/* REPORT PANEL */
.report-panel { display: none; background: var(--surface); border-radius: 16px; padding: 25px; margin-bottom: 30px; border: 1px solid var(--header-border); }
.rp-header { display: flex; justify-content: space-between; margin-bottom: 20px; }
.rp-header h3 { margin: 0; font-size: 16px; color: var(--text-primary); }
.close-btn { background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted); }
.date-inputs { display: flex; gap: 15px; align-items: center; margin-bottom: 20px; }
.glass-input { padding: 10px; border: 1px solid var(--header-border); border-radius: 8px; outline: none; background: var(--body-bg); color: var(--text-primary); }
.rp-actions { display: flex; gap: 10px; }

/* CONTENT */
.content-panel { background: var(--surface); border-radius: 20px; border: 1px solid var(--header-border); overflow: hidden; padding-bottom: 10px; }
.panel-toolbar { padding: 20px; border-bottom: 1px solid var(--header-border); background: var(--surface); }
.search-box { position: relative; max-width: 400px; }
.search-box i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
.search-box input {
    width: 100%; padding: 12px 15px 12px 40px; border-radius: 10px; border: 1px solid var(--header-border);
    background: var(--body-bg); color: var(--text-primary); font-size: 14px; outline: none; transition: all 0.2s;
}
.search-box input:focus { background: var(--surface); border-color: var(--primary); box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }

/* LIST */
.customer-list { display: flex; flex-direction: column; }
.customer-item {
    display: flex; align-items: center; padding: 20px 25px; border-bottom: 1px solid var(--header-border);
    transition: background 0.2s; gap: 20px;
}
.customer-item:hover { background: rgba(59, 130, 246, 0.05); }
.customer-item:last-child { border-bottom: none; }

.c-avatar {
    width: 45px; height: 45px; border-radius: 12px;
    background: linear-gradient(135deg, rgba(224, 231, 255, 0.5), rgba(199, 210, 254, 0.5)); color: var(--primary);
    display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;
    flex-shrink: 0;
    border: 1px solid var(--header-border);
}
.c-info-main { width: 250px; }
.c-info-main h4 { margin: 0 0 5px; font-size: 15px; color: var(--text-primary); }
.c-email { font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 6px; }

.c-details { flex: 1; display: flex; gap: 10px; flex-wrap: wrap; }
.detail-pill {
    background: var(--body-bg); border: 1px solid var(--header-border); padding: 5px 12px;
    border-radius: 20px; font-size: 12px; color: var(--text-primary); display: flex; align-items: center; gap: 6px;
}
.detail-pill i { color: var(--text-muted); }

.c-meta { font-size: 12px; color: var(--text-muted); width: 120px; text-align: right; }

.c-actions { display: flex; gap: 8px; margin-left: 20px; }
.btn-icon {
    width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center;
    border: none; background: transparent; color: var(--text-muted); cursor: pointer; transition: all 0.2s;
}
.btn-icon:hover { background: var(--body-bg); color: var(--primary); }
.btn-icon.edit:hover { color: var(--primary); background: rgba(99, 102, 241, 0.1); }
.btn-icon.delete:hover { color: #ef4444; background: rgba(239, 68, 68, 0.1); }

.empty-state { text-align: center; padding: 50px; color: var(--text-muted); }
.empty-state i { font-size: 48px; margin-bottom: 15px; opacity: 0.5; }

@media (max-width: 1024px) {
    .customer-item { flex-direction: column; align-items: flex-start; gap: 15px; }
    .c-info-main { width: 100%; }
    .c-meta { text-align: left; width: 100%; }
    .c-actions { margin-left: 0; justify-content: flex-end; width: 100%; }
}
</style>

<script>
function toggleReportPanel() {
    const p = document.getElementById('reportPanel');
    p.style.display = p.style.display === 'block' ? 'none' : 'block';
}

function filterCustomers() {
    let input = document.getElementById('customerSearch').value.toLowerCase();
    let items = document.querySelectorAll('.customer-item');
    
    items.forEach(item => {
        let text = item.textContent.toLowerCase();
        item.style.display = text.includes(input) ? 'flex' : 'none';
    });
}
</script>
