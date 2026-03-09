<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@php
    $role = auth()->user()->role ?? null;

    $dashboardRoute = match ($role) {
        'admin' => 'admin.dashboard',
        'manager' => 'manager.dashboard',
        'employee' => 'employee.dashboard',
        'customer' => 'customer.dashboard',
        default => 'login'
    };
@endphp

<!-- HAMBURGER -->
<div class="hamburger" id="hamburger">
    <span></span><span></span><span></span>
</div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar" style="margin-top:0;">
    <ul class="sidebar-menu">

        <!-- DASHBOARD -->
        <li>
            <a href="{{ route($dashboardRoute) }}">
                <i class="fa fa-dashboard" style="font-size:22px;color:#007bff;"></i>
                <span class="label">Dashboard</span>
            </a>
        </li>

        {{-- ===========================
        ADMIN MENU
        ============================ --}}
        @if($role == 'admin')
            <li><a href="{{ route('admin.users.index') }}"><i class="fa fa-users"></i> Users</a></li>
            <li><a href="{{ route('admin.employees.index') }}"><i class="fas fa-user-tie"></i> Employees</a></li>
            <li><a href="{{ route('admin.customers.index') }}"><i class="fas fa-user-friends"></i> Customers</a></li>
            <li><a href="{{ route('admin.services.index') }}"><i class="fa fa-concierge-bell"></i> Services</a></li>
            <li><a href="{{ route('admin.tasks.index') }}"><i class="fas fa-tasks"></i> Tasks</a></li>
            <li><a href="{{ route('admin.inventory.index') }}"><i class="fa fa-warehouse"></i> Inventory</a></li>
            <li><a href="{{ route('admin.announcements.index') }}"><i class="fas fa-bullhorn"></i> Broadcast Manager</a>
            </li>
            <li><a href="{{ route('admin.schedules.index') }}"><i class="fa fa-calendar"></i> Schedules</a></li>
            <li><a href="{{ route('admin.appointments.index') }}"><i class="fa fa-clipboard-list"></i> Appointments</a></li>
            <li><a href="{{ route('admin.roles_permissions') }}"><i class="fas fa-key"></i> Roles & Permissions</a></li>
            <li><a href="{{ route('admin.settings') }}"><i class="fas fa-cogs"></i> Settings</a></li>
        @endif

        {{-- ===========================
        MANAGER MENU
        ============================ --}}
        @if($role == 'manager')
            <li><a href="{{ route('manager.team.index') }}"><i class="fas fa-users-cog"></i> Team</a></li>
            <li><a href="{{ route('manager.tasks') }}"><i class="fas fa-tasks"></i> Tasks</a></li>

            <!-- DROPDOWN -->
            <li class="dropdown">
                <a class="dropdown-toggle">
                    <i class="fas fa-concierge-bell"></i> Our Services
                    <i class="fa fa-caret-down dropdown-arrow"></i>
                </a>

                <ul class="dropdown-menu">
                    <li><a href="{{ route('manager.services.index') }}"><i class="fa fa-cog"></i> Manage Services</a></li>
                    <li><a href="{{ route('manager.bookings.index') }}"><i class="fa fa-book"></i> Manage Bookings</a></li>
                </ul>
            </li>

            <li><a href="{{ route('manager.inventory') }}"><i class="fa fa-warehouse"></i> Inventory</a></li>
            <li><a href="{{ route('manager.reports') }}"><i class="fas fa-chart-pie"></i> Reports</a></li>
            <li><a href="{{ route('manager.appointments') }}"><i class="fa fa-calendar-check"></i> Appointments</a></li>
        @endif

        {{-- ===========================
        EMPLOYEE MENU
        ============================ --}}
        @if($role == 'employee')
            <li><a href="{{ route('employee.tasks') }}"><i class="fas fa-tasks"></i> Tasks</a></li>
            <li><a href="{{ route('employee.reports') }}"><i class="fas fa-chart-pie"></i> Reports</a></li>
            <li><a href="{{ route('employee.appointments') }}"><i class="fa fa-calendar-check"></i> Appointments</a></li>
        @endif

        {{-- ===========================
        CUSTOMER MENU
        ============================ --}}
        @if($role == 'customer')
            <li><a href="{{ route('customer.bookings.index') }}"><i class="fas fa-book"></i> Bookings</a></li>
            <li><a href="{{ route('customer.appointments.index') }}"><i class="fa fa-calendar-check"></i> Appointments</a>
            </li>
            <li><a href="{{ route('customer.orders') }}"><i class="fas fa-shopping-cart"></i> Orders</a></li>
            <li><a href="{{ route('customer.support') }}"><i class="fas fa-headset"></i> Support</a></li>
        @endif

        <!-- LOGOUT -->
        <li>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="label">Logout</span>
                </button>
            </form>
        </li>
    </ul>
</div>

<!-- JS -->
<script>
    document.getElementById("hamburger").addEventListener("click", function () {
        document.getElementById("sidebar").classList.toggle("active");
    });
</script>

<!-- CSS -->
<style>
    /* SIDEBAR */
    .sidebar {
        width: 200px;
        position: fixed;
        top: 64px;
        /* match topbar height instead of 40px */
        left: 0;
        height: calc(100vh - 64px);
        /* compute correct height */
        background: var(--surface, #f8f9fa);
        padding-top: 20px;
        padding-bottom: 60px;
        /* extra space at bottom */
        z-index: 999;
        transition: .3s;
        border-right: 1px solid var(--header-border, #eee);
        overflow-y: auto;
        /* Allow scrolling to the bottom */
    }

    /* Custom Scrollbar for Sidebar */
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(0, 123, 255, 0.3);
        border-radius: 4px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 123, 255, 0.6);
    }

    .sidebar ul li a {
        padding: 8px 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-primary, #333);
        text-decoration: none;
        transition: all 0.2s;
    }

    .sidebar ul li a:hover {
        background: var(--primary, #007bff);
        color: #fff;
    }

    /* =============================
   HOVER DROPDOWN OVERLAY
   ============================= */
    .dropdown {
        position: relative;
        z-index: 2000;
        /* dropdown now floats ABOVE everything */
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 0;
        left: 155px;
        width: 155px;
        background: var(--dropdown-bg, #fff);
        border-left: 2px solid var(--primary, #007bff);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        list-style: none;
        padding: 10px 0;
        z-index: 3000;
        font-size: 15px;
        border-radius: 0 10px 10px 0;
    }

    .dropdown-menu li a {
        padding: 4px 15px;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        width: 120px;
    }

    .dropdown-menu li a:hover {
        background: #007bff;
        color: #fff;
    }

    /* HOVER OPEN */
    .dropdown:hover .dropdown-menu {
        display: block;
    }

    /* ARROW */
    .dropdown-toggle {
        cursor: pointer;
    }

    .dropdown-arrow {
        margin-left: auto;
    }

    /* LOGOUT */
    .logout-btn {
        background: none;
        border: none;
        width: 100%;
        padding: 10px 15px;
        text-align: left;
        cursor: pointer;
        color: #007bff;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    /* MOBILE */
    @media(max-width:768px) {
        .sidebar {
            left: -250px;
            width: 250px;
        }

        .sidebar.active {
            left: 0;
        }

        .dropdown-menu {
            position: relative;
            left: 0;
            box-shadow: none;
            border: none;
        }
    }
</style>