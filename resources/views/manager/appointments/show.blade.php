@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="dashboard-wrapper" style="padding: 2rem;">
    <!-- Tactical Header -->
    <div class="pro-header" style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <a href="{{ route('manager.appointments') }}" class="action-btn" style="background: var(--bg-main); color: var(--secondary); border: 1px solid var(--glass-border);">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 style="font-size: 1.75rem; letter-spacing: -1px;">Engagement Audit #{{ $appointment->id }}</h1>
                <p style="color: var(--secondary); font-size: 0.9rem; margin-top: 0.25rem;">Detailed tactical summary and orchestration controls</p>
            </div>
        </div>
        <div class="header-actions" style="display: flex; gap: 0.75rem;">
            <span class="status-badge status-{{ $appointment->status }}" style="font-size: 0.85rem; padding: 0.5rem 1.25rem; font-weight: 800; border-radius: 2rem;">
                {{ strtoupper($appointment->status) }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="glass-alert success" style="margin-bottom: 2rem; padding: 1rem 1.5rem; border-radius: 1rem; background: rgba(34, 197, 94, 0.05); color: #15803d; border: 1px solid rgba(34, 197, 94, 0.1); display: flex; align-items: center; gap: 1rem;">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="main-grid" style="display: grid; grid-template-columns: 1fr 380px; gap: 2rem;">
        <!-- Primary Audit Panel -->
        <div class="content-panel">
            <div class="pro-card" style="margin-bottom: 2rem; padding: 2rem;">
                <h3 style="font-size: 1.1rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem;">
                    <i class="fas fa-info-circle" style="color: var(--primary);"></i>
                    Engagement Intelligence
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem;">
                    <div>
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--secondary); text-transform: uppercase; margin-bottom: 0.5rem;">Title</label>
                            <div style="font-size: 1.1rem; font-weight: 700; color: var(--dark);">{{ $appointment->title }}</div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--secondary); text-transform: uppercase; margin-bottom: 0.5rem;">Description / Directives</label>
                            <div style="font-size: 0.95rem; color: var(--secondary); line-height: 1.6;">{{ $appointment->description }}</div>
                        </div>
                    </div>
                    
                    <div style="background: rgba(99, 102, 241, 0.03); padding: 1.5rem; border-radius: 1rem; border: 1px solid rgba(99, 102, 241, 0.05);">
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--secondary); text-transform: uppercase; margin-bottom: 0.5rem;">Tactical Window</label>
                            <div style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">
                                <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('l, M d, Y') }}
                            </div>
                            <div style="font-size: 1rem; color: var(--secondary); margin-top: 0.25rem;">
                                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') }}
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--secondary); text-transform: uppercase; margin-bottom: 0.5rem;">Registry Signature</label>
                            <div style="font-size: 0.85rem; color: var(--secondary);">Created on {{ $appointment->created_at->format('M d, Y @ h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Client & Agent Dossier -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div class="pro-card" style="padding: 1.5rem;">
                    <h3 style="font-size: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-user-tie" style="color: var(--primary);"></i>
                        Client Entity Details
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 45px; height: 45px; border-radius: 50%; background: var(--bg-main); display: flex; align-items: center; justify-content: center; font-weight: 800; color: var(--primary);">
                                {{ substr($appointment->user->name ?? 'D', 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 700;">{{ $appointment->user->name ?? 'Direct Client' }}</div>
                                <div style="font-size: 0.8rem; color: var(--secondary);">{{ $appointment->user->email ?? 'no-digital-signature' }}</div>
                            </div>
                        </div>
                        <div style="padding: 1rem; background: var(--bg-main); border-radius: 0.75rem; font-size: 0.85rem;">
                            <div style="margin-bottom: 0.5rem;"><i class="fas fa-phone" style="width: 20px; color: var(--secondary);"></i> {{ $appointment->phone ?? 'Unlisted' }}</div>
                            <div style="margin-bottom: 0.5rem;"><i class="fas fa-envelope" style="width: 20px; color: var(--secondary);"></i> {{ $appointment->email ?? $appointment->user->email ?? 'Unlisted' }}</div>
                            <div><i class="fas fa-map-marker-alt" style="width: 20px; color: var(--secondary);"></i> {{ $appointment->address ?? 'On-site / TBD' }}</div>
                        </div>
                    </div>
                </div>

                <div class="pro-card" style="padding: 1.5rem;">
                    <h3 style="font-size: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-user-shield" style="color: var(--primary);"></i>
                        Assigned Operative
                    </h3>
                    @if($appointment->employee)
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                            <div style="width: 45px; height: 45px; border-radius: 50%; background: rgba(34, 197, 94, 0.1); display: flex; align-items: center; justify-content: center; font-weight: 800; color: #10b981;">
                                {{ substr($appointment->employee->name, 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 700;">{{ $appointment->employee->name }}</div>
                                <div style="font-size: 0.8rem; color: var(--secondary);">Field Agent / Personnel</div>
                            </div>
                        </div>
                    @else
                        <div style="padding: 1.5rem; text-align: center; background: rgba(245, 158, 11, 0.05); border-radius: 1rem; border: 1px dashed rgba(245, 158, 11, 0.2); margin-bottom: 1.5rem;">
                            <i class="fas fa-user-plus" style="font-size: 1.5rem; color: var(--warning); opacity: 0.5; margin-bottom: 0.5rem; display: block;"></i>
                            <div style="font-size: 0.85rem; color: var(--secondary); font-weight: 600;">Awaiting Agent Delegation</div>
                        </div>
                    @endif

                    <form action="{{ route('manager.appointments.assign', $appointment->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <div style="display: flex; gap: 0.5rem;">
                            <select name="employee_id" required class="glass-select" style="flex: 1; padding: 0.6rem; font-size: 0.85rem;">
                                <option value="">Select Replacement Agent</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ $appointment->employee_id == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="action-btn btn-primary" style="padding: 0.6rem 1rem;">
                                SYNC
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Orchestration Sidebar -->
        <div class="sidebar-panel">
            <div class="pro-card" style="margin-bottom: 2rem;">
                <h3 style="font-size: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-cogs" style="color: var(--primary);"></i>
                    Status Orchestration
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="padding: 1rem; background: var(--bg-main); border-radius: 0.75rem; border: 1px solid var(--glass-border);">
                        <div style="font-size: 0.7rem; color: var(--secondary); text-transform: uppercase; font-weight: 800; margin-bottom: 0.5rem;">Current Operational State</div>
                        <div style="font-weight: 800; color: var(--dark); font-size: 1.1rem;">{{ strtoupper($appointment->status) }}</div>
                    </div>

                    @if($appointment->status !== 'completed' && $appointment->status !== 'canceled')
                        <form action="{{ route('manager.appointments.complete', $appointment->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="action-btn" style="width: 100%; padding: 1rem; background: #10b981; color: white; border: none; font-weight: 700; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem;" onclick="return confirm('Settle this tactical engagement?')">
                                <i class="fas fa-check-circle"></i> SETTLE ENGAGEMENT
                            </button>
                        </form>
                    @endif

                    @if($appointment->status !== 'canceled')
                        <form action="{{ route('manager.appointments.destroy', $appointment->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn" style="width: 100%; padding: 0.75rem; background: rgba(239, 68, 68, 0.05); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.1); font-weight: 700; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem;" onclick="return confirm('Abort this tactical cycle?')">
                                <i class="fas fa-ban"></i> ABORT CYCLE
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="pro-card glass-card" style="background: rgba(99, 102, 241, 0.03);">
                <h3 style="font-size: 1rem; margin-bottom: 1rem;"><i class="fas fa-shield-check" style="color: var(--primary); margin-right: 0.5rem;"></i> Audit Integrity</h3>
                <p style="font-size: 0.8rem; color: var(--secondary); line-height: 1.6;">
                    All modifications to this engagement are logged in the master distribution ledger. Ensure all field operatives are briefed before status transitions.
                </p>
                <div style="margin-top: 1.5rem; text-align: center;">
                    <button class="action-btn" style="font-size: 0.75rem; background: var(--white); color: var(--secondary); border: 1px solid var(--glass-border); padding: 0.5rem 1rem;" onclick="window.print()">
                        <i class="fas fa-print"></i> PRINT AUDIT COPY
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .glass-select { background: var(--bg-main); border: 1px solid var(--glass-border); border-radius: 0.6rem; color: var(--dark); outline: none; }
    .status-badge.status-pending { background: rgba(148, 163, 184, 0.1); color: #64748b; border: 1px solid rgba(148, 163, 184, 0.2); }
    .status-badge.status-confirmed { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2); }
    .status-badge.status-assigned { background: rgba(99, 102, 241, 0.1); color: #6366f1; border: 1px solid rgba(99, 102, 241, 0.2); }
    .status-badge.status-completed { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .status-badge.status-canceled { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }

    @media (max-width: 1200px) {
        .main-grid { grid-template-columns: 1fr; }
    }

    @media print {
        .layouts-sidebar, .layouts-header, .action-btn, .header-actions, .glass-select, form { display: none !important; }
        .dashboard-wrapper { margin-left: 0 !important; padding: 0 !important; }
        .pro-card { box-shadow: none !important; border: 1px solid #eee !important; }
    }
</style>
