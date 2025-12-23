@include('layouts.header')
@include('layouts.sidebar')

<div class="roles-dashboard-wrapper">
    <div class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-key"></i> Roles & Permissions</h1>
            <p>Define role capabilities and manage user access across the organization.</p>
        </div>
        <div class="header-stats">
            <div class="stat-pill"><strong>{{ $roles->count() }}</strong> Roles</div>
            <div class="stat-pill"><strong>{{ $permissions->count() }}</strong> Permissions</div>
        </div>
    </div>

    <!-- TABS NAVIGATION -->
    <div class="tabs-nav">
        <button class="tab-btn active" data-tab="matrix"><i class="fas fa-th"></i> Permission Matrix</button>
        <button class="tab-btn" data-tab="users"><i class="fas fa-users-cog"></i> User Roles</button>
        <button class="tab-btn" data-tab="manage"><i class="fas fa-cogs"></i> Manage Entities</button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <!-- TAB CONTENTS -->
    <div class="tab-container">
        
        <!-- TAP 1: PERMISSION MATRIX -->
        <div class="tab-content active" id="matrix">
            <div class="glass-card matrix-card">
                <div class="card-header">
                    <h3>Role Capability Matrix</h3>
                    <p>Toggle checkboxes to instantly sync permissions for each role.</p>
                </div>
                <div class="matrix-table-wrapper">
                    <table class="matrix-table">
                        <thead>
                            <tr>
                                <th class="perm-col">Permissions / Modules</th>
                                @foreach($roles as $role)
                                    <th class="role-col">{{ ucfirst($role->name) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupedPermissions as $group => $perms)
                                <tr class="group-header">
                                    <td colspan="{{ $roles->count() + 1 }}">{{ strtoupper($group) }} Module</td>
                                </tr>
                                @foreach($perms as $permission)
                                    <tr>
                                        <td class="perm-name">{{ str_replace(['-', $group], [' ', ''], $permission->name) }}</td>
                                        @foreach($roles as $role)
                                            <td class="toggle-cell">
                                                <label class="switch">
                                                    <input type="checkbox" 
                                                           class="perm-toggle" 
                                                           data-role-id="{{ $role->id }}" 
                                                           data-permission-id="{{ $permission->id }}"
                                                           {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 2: USER ROLES -->
        <div class="tab-content" id="users">
            <div class="glass-card">
                <div class="card-header">
                    <h3>User Role Assignment</h3>
                </div>
                <div class="user-grid">
                    @foreach($users as $user)
                        <div class="user-mini-card">
                            <div class="u-info">
                                <strong>{{ $user->name }}</strong>
                                <span>{{ $user->email }}</span>
                                <div class="badge-row">
                                    @foreach($user->getRoleNames() as $rName)
                                        <span class="role-badge">{{ $rName }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="u-action">
                                <select class="user-role-sync" data-user-id="{{ $user->id }}">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="pagination-footer">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>

        <!-- TAB 3: MANAGE ENTITIES (Roles/Permissions CRUD) -->
        <div class="tab-content" id="manage">
            <div class="manage-split">
                <!-- ROLES CRUD -->
                <div class="glass-card">
                    <div class="card-header">
                        <h3>Roles</h3>
                        <button class="add-mini-btn" onclick="openModal('roleModal')"><i class="fas fa-plus"></i></button>
                    </div>
                    <ul class="entity-list">
                        @foreach($roles as $role)
                            <li>
                                <span>{{ $role->name }}</span>
                                <div class="actions">
                                    <form action="{{ route('admin.roles.destroyRole', $role->id) }}" method="POST" onsubmit="return confirm('Delete this role?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="trash-btn"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- PERMISSIONS CRUD -->
                <div class="glass-card">
                    <div class="card-header">
                        <h3>Permissions</h3>
                        <button class="add-mini-btn" onclick="openModal('permModal')"><i class="fas fa-plus"></i></button>
                    </div>
                    <ul class="entity-list scrollable">
                        @foreach($permissions as $permission)
                            <li>
                                <span>{{ $permission->name }}</span>
                                <div class="actions">
                                    <form action="{{ route('admin.permissions.destroyPermission', $permission->id) }}" method="POST" onsubmit="return confirm('Delete this permission?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="trash-btn"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALS -->
<div id="roleModal" class="modal">
    <div class="modal-content glass">
        <h3>Create New Role</h3>
        <form action="{{ route('admin.roles.storeRole') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Role Name (e.g. support)" required>
            <div class="m-footer">
                <button type="button" onclick="closeModal('roleModal')">Cancel</button>
                <button type="submit" class="primary-btn">Save Role</button>
            </div>
        </form>
    </div>
</div>

<div id="permModal" class="modal">
    <div class="modal-content glass">
        <h3>Create New Permission</h3>
        <form action="{{ route('admin.permissions.storePermission') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Permission Name (e.g. delete-users)" required>
            <div class="m-footer">
                <button type="button" onclick="closeModal('permModal')">Cancel</button>
                <button type="submit" class="primary-btn">Save Permission</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Theme Variables */
/* Rely on global variables from app layout */

body {
    background: var(--body-bg);
    font-family: 'Inter', system-ui, sans-serif;
    color: var(--text-primary);
    margin: 0;
    min-height: 100vh;
}

.roles-dashboard-wrapper {
    margin-left: 242px;
    padding: 30px;
    background: var(--body-bg);
    min-height: 100vh;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.header-content h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 5px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.header-content p {
    color: var(--text-muted);
    margin: 0;
}

.stat-pill {
    display: inline-block;
    background: var(--surface);
    padding: 8px 16px;
    border-radius: 20px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    font-size: 14px;
    margin-left: 10px;
    border: 1px solid var(--header-border);
    color: var(--text-primary);
}

.tabs-nav {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    border-bottom: 1px solid var(--header-border);
    padding-bottom: 10px;
}

.tab-btn {
    padding: 10px 20px;
    border: none;
    background: none;
    color: var(--text-muted);
    font-weight: 600;
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.2s;
}

.tab-btn:hover {
    color: var(--primary);
    background: var(--surface);
}

.tab-btn.active {
    background: var(--primary);
    color: #fff;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.tab-content { display: none; }
.tab-content.active { display: block; animation: fadeIn 0.3s ease; }

@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.glass-card {
    background: var(--surface);
    backdrop-filter: blur(10px);
    border: 1px solid var(--header-border);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.card-header h3 { margin: 0; font-size: 18px; color: var(--text-primary); }
.card-header p { margin: 5px 0 0 0; font-size: 13px; color: var(--text-muted); }

/* MATRIX TABLE */
.matrix-table-wrapper {
    overflow-x: auto;
    border-radius: 12px;
    border: 1px solid var(--header-border);
}

.matrix-table {
    width: 100%;
    border-collapse: collapse;
}

.matrix-table th {
    background: var(--surface);
    color: var(--text-primary);
    padding: 15px;
    text-align: center;
    font-size: 13px;
    border-bottom: 1px solid var(--header-border);
}

.matrix-table th.perm-col { text-align: left; background: var(--surface); border-right: 1px solid var(--header-border); }

.group-header td {
    background: var(--body-bg);
    padding: 10px 15px;
    font-weight: 800;
    font-size: 11px;
    color: var(--text-muted);
    letter-spacing: 0.05em;
    border-bottom: 1px solid var(--header-border);
}

.matrix-table td {
    padding: 12px 15px;
    border-bottom: 1px solid var(--header-border);
    color: var(--text-primary);
}

.perm-name { font-weight: 600; color: var(--text-primary); font-size: 14px; text-transform: capitalize; border-right: 1px solid var(--header-border); }

.toggle-cell { text-align: center; }

/* SWITCH STYLING */
.switch { position: relative; display: inline-block; width: 40px; height: 22px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--header-border); transition: .4s; }
.slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; transition: .4s; }
input:checked + .slider { background-color: var(--primary); }
input:checked + .slider:before { transform: translateX(18px); }
.slider.round { border-radius: 34px; }
.slider.round:before { border-radius: 50%; }

/* USER GRID */
.user-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 15px;
}

.user-mini-card {
    background: var(--surface);
    border: 1px solid var(--header-border);
    border-radius: 12px;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}

.u-info strong { display: block; font-size: 15px; color: var(--text-primary); }
.u-info span { font-size: 12px; color: var(--text-muted); }

.badge-row { margin-top: 8px; display: flex; gap: 5px; }
.role-badge {
    background: rgba(99, 102, 241, 0.1);
    color: var(--primary);
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 10px;
    font-weight: 700;
    border: 1px solid rgba(99, 102, 241, 0.2);
}

.u-action select {
    padding: 6px 12px;
    border-radius: 8px;
    border: 1px solid var(--header-border);
    font-size: 13px;
    background: var(--body-bg);
    color: var(--text-primary);
}

/* MANAGE SPLIT */
.manage-split {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.entity-list { list-style: none; padding: 0; margin: 0; }
.entity-list li {
    padding: 12px 0;
    border-bottom: 1px solid var(--header-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: var(--text-primary);
}
.entity-list.scrollable { max-height: 400px; overflow-y: auto; }

.trash-btn { background: none; border: none; color: #ef4444; cursor: pointer; padding: 5px; transition: transform 0.2s; }
.trash-btn:hover { transform: scale(1.1); }
.add-mini-btn { background: var(--primary); color: #fff; border: none; border-radius: 6px; padding: 5px 10px; cursor: pointer; transition: background 0.2s; }
.add-mini-btn:hover { background: var(--primary-dark); }


/* PAGINATION */
.pagination-footer {
    padding: 20px 25px;
    border-top: 1px solid var(--header-border);
    display: flex;
    justify-content: center;
}
.pagination-footer .pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 5px;
}
.pagination-footer .page-item .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 10px;
    border-radius: 8px;
    background: var(--body-bg);
    border: 1px solid var(--header-border);
    color: var(--text-primary);
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
}
.pagination-footer .page-item .page-link:hover {
    border-color: var(--primary);
    color: var(--primary);
}
.pagination-footer .page-item.active .page-link {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}
.pagination-footer .page-item.disabled .page-link {
    opacity: 0.5;
    pointer-events: none;
}

/* MODALS */
.modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); }
.modal-content { 
    background: var(--surface); 
    width: 90%; 
    max-width: 400px; 
    margin: 100px auto; 
    padding: 30px; 
    border-radius: 20px; 
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); 
    border: 1px solid var(--header-border);
}
.modal-content h3 { color: var(--text-primary); margin-top: 0; }
.modal-content input {
    width: 90%;
    padding: 12px;
    margin: 15px 0;
    border: 1px solid var(--header-border);
    border-radius: 10px;
    background: var(--body-bg);
    color: var(--text-primary);
}
.modal-content input:focus { outline: 2px solid var(--primary); border-color: transparent; }

.m-footer { display: flex; justify-content: flex-end; gap: 10px; }
.m-footer button[type="button"] { background: var(--body-bg); border: 1px solid var(--header-border); color: var(--text-muted); padding: 10px 20px; border-radius: 10px; cursor: pointer; }
.m-footer button[type="button"]:hover { border-color: var(--text-muted); color: var(--text-primary); }

.primary-btn { background: var(--primary); color: #fff; border: none; padding: 10px 20px; border-radius: 10px; cursor: pointer; }
.primary-btn:hover { background: var(--primary-dark); }

@media (max-width: 1024px) {
    .roles-dashboard-wrapper { margin-left: 0; }
    .manage-split { grid-template-columns: 1fr; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // TAB SWITCHING
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).classList.add('active');
        });
    });

    // AJAX PERMISSION TOGGLE
    document.querySelectorAll('.perm-toggle').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const roleId = this.dataset.roleId;
            const permId = this.dataset.permissionId;

            fetch("{{ route('admin.roles.togglePermission') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ role_id: roleId, permission_id: permId })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    showToast(data.message, 'success');
                }
            })
            .catch(err => {
                this.checked = !this.checked;
                showToast('Error updating permissions', 'error');
            });
        });
    });

    // AJAX USER ROLE UPDATE
    document.querySelectorAll('.user-role-sync').forEach(select => {
        select.addEventListener('change', function() {
            const userId = this.dataset.userId;
            const roleName = this.value;

            fetch(`/admin/roles/${userId}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ role: [roleName] })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    showToast(data.message, 'success');
                    // Reload to reflect badge changes or DOM update
                    setTimeout(() => location.reload(), 800);
                }
            });
        });
    });
});

function openModal(id) { document.getElementById(id).style.display = 'block'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

function showToast(msg, type) {
    const toast = document.createElement('div');
    toast.style.position = 'fixed';
    toast.style.bottom = '20px';
    toast.style.right = '20px';
    toast.style.padding = '12px 24px';
    toast.style.borderRadius = '12px';
    toast.style.color = '#fff';
    toast.style.zIndex = '9999';
    toast.style.fontWeight = '600';
    toast.style.boxShadow = '0 10px 15px rgba(0,0,0,0.1)';
    toast.style.backgroundColor = type === 'success' ? '#22c55e' : '#ef4444';
    toast.innerText = msg;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
