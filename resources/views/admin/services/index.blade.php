@include('layouts.header')
@include('layouts.sidebar')

<div class="services-dashboard-wrapper">
    <!-- Header -->
    <div class="page-header">
        <div class="header-title">
            <h1><i class="fas fa-concierge-bell"></i> Service Management</h1>
            <p>Showcase your offerings, manage pricing, and assign staff.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.services.create') }}" class="btn-glass primary">
                <i class="fas fa-plus"></i> Create Service
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card glass-panel">
            <div class="icon-box blue"><i class="fas fa-layer-group"></i></div>
            <div class="stat-info">
                <h3>{{ $services->total() }}</h3>
                <span>Total Services</span>
            </div>
        </div>
        <div class="stat-card glass-panel">
            <div class="icon-box green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                @php $publishedCount = count($services->where('is_published', true)); @endphp
                <h3>{{ $publishedCount }}</h3>
                <span>Published</span>
            </div>
        </div>
        <!-- Placeholder for Views/Bookings if available in future -->
        <div class="stat-card glass-panel">
            <div class="icon-box purple"><i class="fas fa-users-cog"></i></div>
            <div class="stat-info">
                <h3>{{ $services->unique('employee_id')->count() }}</h3>
                <span>Staff Assigned</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-panel glass-panel">
        <div class="panel-toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="serviceSearch" placeholder="Search services..." onkeyup="filterServices()">
            </div>
        </div>

        <div class="services-grid" id="serviceGrid">
            @forelse($services as $service)
            <div class="service-card">
                <div class="card-image">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}">
                    @else
                        <div class="no-image"><i class="fas fa-box-open"></i></div>
                    @endif
                    <div class="status-badge {{ $service->is_published ? 'active' : 'inactive' }}">
                        {{ $service->is_published ? 'Active' : 'Draft' }}
                    </div>
                </div>

                <div class="card-content">
                    <h4>{{ $service->name }}</h4>
                    <p class="description">{{ Str::limit($service->description, 60) }}</p>
                    
                    <div class="assignee">
                        <i class="fas fa-user-tie"></i>
                        <span>{{ $service->employee->name ?? 'Unassigned' }}</span>
                    </div>

                    <div class="card-actions-row">
                        <!-- Publish Toggle -->
                        <form action="{{ route('admin.services.publish', $service->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-toggle {{ $service->is_published ? 'on' : 'off' }}" title="Toggle Visibility">
                                <i class="fas {{ $service->is_published ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                            </button>
                        </form>

                        <div class="action-buttons">
                            <a href="{{ route('admin.services.edit', $service->id) }}" class="btn-icon edit"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Delete this service?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <p>No services found. Add your first service to get started.</p>
            </div>
            @endforelse
        </div>

        <div class="pagination-wrapper">
             {{ $services->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<style>
/* Global Theme Integration */
body { background: var(--body-bg); font-family: 'Inter', sans-serif; }

.services-dashboard-wrapper { margin-left: 250px; padding: 30px 40px; min-height: 100vh; }

/* HEADER */
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.header-title h1 { font-size: 28px; font-weight: 800; color: var(--text-primary); margin: 0; }
.header-title p { color: var(--text-muted); margin: 5px 0 0; }

.btn-glass {
    padding: 10px 20px; border-radius: 12px; font-weight: 600; text-decoration: none;
    display: flex; align-items: center; gap: 8px; transition: all 0.2s;
}
.btn-glass.primary { background: var(--primary); color: #fff; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
.btn-glass.primary:hover { transform: translateY(-2px); }

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

/* CONTENT */
.content-panel { background: var(--surface); border-radius: 20px; border: 1px solid var(--header-border); padding: 25px; }
.panel-toolbar { margin-bottom: 30px; }
.search-box { position: relative; max-width: 400px; }
.search-box i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
.search-box input {
    width: 100%; padding: 12px 15px 12px 40px; border-radius: 10px; border: 1px solid var(--header-border);
    background: var(--body-bg); color: var(--text-primary); font-size: 14px; outline: none; transition: all 0.2s;
}
.search-box input:focus { border-color: var(--primary); background: var(--surface); box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }

/* GRID */
.services-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }

.service-card {
    background: var(--surface); border: 1px solid var(--header-border); border-radius: 16px; overflow: hidden;
    transition: all 0.2s; display: flex; flex-direction: column;
}
.service-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08); }

.card-image { height: 160px; position: relative; background: var(--body-bg); }
.card-image img { width: 100%; height: 100%; object-fit: cover; }
.no-image { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 40px; color: var(--text-muted); }

.status-badge {
    position: absolute; top: 12px; right: 12px; padding: 4px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
    background: rgba(255,255,255,0.9); backdrop-filter: blur(4px);
    color: #1e293b; 
}
.status-badge.active { color: #15803d; background: rgba(220, 252, 231, 0.9); }
.status-badge.inactive { color: #94a3b8; background: rgba(241, 245, 249, 0.9); }

.card-content { padding: 20px; flex: 1; display: flex; flex-direction: column; }
.card-content h4 { margin: 0 0 8px; font-size: 16px; color: var(--text-primary); font-weight: 700; }
.description { font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 15px; flex: 1; }

.assignee { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-primary); background: var(--body-bg); padding: 8px 12px; border-radius: 8px; margin-bottom: 20px; }
.assignee i { color: var(--primary); }

.card-actions-row { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--header-border); padding-top: 15px; }

.btn-toggle {
    background: none; border: none; cursor: pointer; font-size: 16px; transition: color 0.2s;
}
.btn-toggle.on { color: #10b981; }
.btn-toggle.off { color: var(--text-muted); }
.btn-toggle:hover { opacity: 0.8; }

.action-buttons { display: flex; gap: 8px; }
.btn-icon {
    width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center;
    border: 1px solid var(--header-border); color: var(--text-muted); transition: all 0.2s; text-decoration: none;
}
.btn-icon:hover { background: var(--primary); color: #fff; border-color: var(--primary); }
.btn-icon.delete:hover { background: #ef4444; border-color: #ef4444; }

.empty-state { grid-column: 1 / -1; text-align: center; padding: 50px; color: var(--text-muted); }
.empty-state i { font-size: 48px; margin-bottom: 15px; opacity: 0.5; }
.pagination-wrapper { margin-top: 30px; display: flex; justify-content: center; }

/* PAGINATION STYLES */
.pagination {
    display: flex;
    justify-content: center;
    padding-left: 0;
    list-style: none;
    gap: 5px;
}
.page-item .page-link {
    position: relative;
    display: block;
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: var(--primary);
    background-color: var(--surface);
    border: 1px solid var(--header-border);
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s;
    font-size: 14px;
    font-weight: 600;
}
.page-item .page-link:hover {
    background-color: var(--body-bg);
    color: var(--primary);
    border-color: var(--primary);
}
.page-item.active .page-link {
    z-index: 1;
    color: #fff;
    background-color: var(--primary);
    border-color: var(--primary);
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
}
.page-item.disabled .page-link {
    color: var(--secondary);
    pointer-events: none;
    cursor: auto;
    background-color: var(--body-bg);
    border-color: var(--header-border);
    opacity: 0.6;
}

@media (max-width: 768px) {
    .services-dashboard-wrapper { margin-left: 0; padding: 20px; }
}
</style>

<script>
function filterServices() {
    let input = document.getElementById('serviceSearch').value.toLowerCase();
    let cards = document.querySelectorAll('.service-card');
    
    cards.forEach(card => {
        let text = card.textContent.toLowerCase();
        card.style.display = text.includes(input) ? 'flex' : 'none';
    });
}
</script>
