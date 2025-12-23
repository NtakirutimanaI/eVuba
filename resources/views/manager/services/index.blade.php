@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1>Service Portfolio</h1>
            <p style="color: var(--secondary); margin-top: 0.5rem;">Manage and showcase your professional offerings</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('manager.services.create') }}" class="action-btn btn-primary">
                <i class="fas fa-plus"></i> Add New Service
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="dashboard-grid">
        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Total Services</div>
                    <div class="value">{{ $services->total() }}</div>
                    <div class="stat-trend up">
                        <i class="fas fa-check"></i> Active Inventory
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                    <i class="fas fa-globe"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Published</div>
                    <div class="value">{{ \App\Models\Service::where('is_published', true)->count() }}</div>
                    <div class="stat-trend up">
                        Visible to Clients
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Drafts</div>
                    <div class="value">{{ \App\Models\Service::where('is_published', false)->count() }}</div>
                    <div class="stat-trend">
                        Awaiting Review
                    </div>
                </div>
            </div>
        </div>

        <div class="pro-card">
            <div class="stat-widget">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #f87171);">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-info">
                    <div class="label">Specialists</div>
                    <div class="value">{{ \App\Models\Service::distinct('employee_id')->count() }}</div>
                    <div class="stat-trend">
                        Dedicated Experts
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="glass-card" style="background: rgba(34, 197, 94, 0.1); border-color: rgba(34, 197, 94, 0.2); margin-bottom: 2rem; padding: 1rem;">
            <div style="color: var(--success); display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="services-grid">
        @forelse($services as $service)
            <div class="pro-card service-v-card">
                <div class="service-image-header">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}">
                    @else
                        <div class="service-placeholder">
                            <i class="fas fa-image fa-3x"></i>
                        </div>
                    @endif
                    <div class="service-badge {{ $service->is_published ? 'status-completed' : 'status-pending' }}">
                        {{ $service->is_published ? 'Published' : 'Draft' }}
                    </div>
                </div>

                <div class="service-body">
                    <h3 class="service-title">{{ $service->name }}</h3>
                    <p class="service-description">{{ Str::limit($service->description, 80) }}</p>
                    
                    <div class="service-meta">
                        <div class="meta-item">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ $service->employee->name ?? 'Unassigned' }}</span>
                        </div>
                    </div>

                    <div class="service-actions">
                        <div style="display: flex; gap: 0.5rem; width: 100%;">
                            <a href="{{ route('manager.services.edit', $service->id) }}" class="action-btn" style="flex: 1; justify-content: center; background: rgba(99, 102, 241, 0.1); color: var(--primary);">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            
                            <form action="{{ route('manager.services.publish', $service->id) }}" method="POST" style="flex: 1;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn" style="width: 100%; justify-content: center; background: {{ $service->is_published ? 'rgba(245, 158, 11, 0.1)' : 'rgba(34, 197, 94, 0.1)' }}; color: {{ $service->is_published ? 'var(--warning)' : 'var(--success)' }}; border: none; cursor: pointer;">
                                    <i class="fas {{ $service->is_published ? 'fa-eye-slash' : 'fa-eye' }}"></i> 
                                    {{ $service->is_published ? 'Hide' : 'Show' }}
                                </button>
                            </form>

                            <form action="{{ route('manager.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Remove this service from catalog?');" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn" style="padding: 0.75rem; aspect-ratio: 1; min-width: 44px; justify-content: center; background: rgba(239, 68, 68, 0.1); color: var(--danger); border: none; cursor: pointer;">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="pro-card" style="grid-column: 1 / -1; text-align: center; padding: 4rem;">
                <i class="fas fa-boxes fa-4s" style="opacity: 0.1; margin-bottom: 2rem; display: block;"></i>
                <h2 style="color: var(--secondary);">No services cataloged yet.</h2>
                <p style="margin-top: 1rem; color: var(--secondary);">Start by creating your first service offering.</p>
                <a href="{{ route('manager.services.create') }}" class="action-btn btn-primary" style="display: inline-flex; margin-top: 2rem;">
                    Create Service
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $services->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
    }

    .service-v-card {
        padding: 0;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .service-image-header {
        position: relative;
        height: 200px;
        width: 100%;
        background: var(--bg-main);
    }

    .service-image-header img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .service-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--secondary);
        opacity: 0.3;
    }

    .service-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 1;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .service-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .service-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 0.75rem;
    }

    .service-description {
        color: var(--secondary);
        font-size: 0.875rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        flex: 1;
    }

    .service-meta {
        padding-top: 1rem;
        border-top: 1px solid var(--glass-border);
        margin-bottom: 1.5rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--dark);
        font-weight: 600;
    }

    .meta-item i {
        color: var(--primary);
    }

    .action-btn {
        font-size: 0.85rem;
        padding: 0.650rem 1rem;
    }

    .action-btn i {
        font-size: 0.9rem;
    }

    /* Status badge adjustments */
    .status-badge {
        padding: 0.35rem 0.85rem;
    }
</style>

