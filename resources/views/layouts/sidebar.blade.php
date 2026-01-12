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
<div class="crm-hamburger" id="crm-hamburger">
    <span></span><span></span><span></span>
</div>

<!-- SIDEBAR -->
<div class="crm-sidebar" id="crm-sidebar" style="margin-top:0;" data-turbo-permanent>
    <ul class="crm-sidebar-menu">

        <!-- DASHBOARD -->
        <li>
            <a href="{{ route($dashboardRoute) }}">
                <i class="fa fa-dashboard" style="font-size:22px; color: var(--primary);"></i>
                <span class="crm-label">Dashboard</span>
            </a>
        </li>

        {{-- ===========================
        ADMIN MENU
        ============================ --}}
        @if($role == 'admin')
            @php
                $unreadMessages = \App\Models\MessageUs::where('read', false)->count();
            @endphp

            <!-- USER MANAGEMENT -->
            <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="fas fa-users-cog"></i> User Management
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>
                <ul class="crm-dropdown-menu">
                    <li><a href="{{ route('admin.users.index') }}"><i class="fa fa-users"></i> Users</a></li>
                    <li><a href="{{ route('admin.employees.index') }}"><i class="fas fa-user-tie"></i> Employees</a></li>
                    <li><a href="{{ route('admin.customers.index') }}"><i class="fas fa-user-friends"></i> Customers</a>
                    </li>
                    <li><a href="{{ route('admin.roles_permissions') }}"><i class="fas fa-key"></i> Roles & Permissions</a>
                    </li>
                    <li><a href="{{ route('admin.subscribers.index') }}"><i class="fas fa-user-plus"></i> Subscribers</a>
                    </li>
                </ul>
            </li>

            <!-- SERVICE OPS -->
            <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="fas fa-concierge-bell"></i> Service Ops
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>
                <ul class="crm-dropdown-menu">
                    <li><a href="{{ route('admin.services.index') }}"><i class="fa fa-cog"></i> Services</a></li>
                    <li><a href="{{ route('admin.bookings.index') }}"><i class="fa fa-book"></i> Bookings</a></li>
                    <li><a href="{{ route('admin.appointments.index') }}"><i class="fa fa-clipboard-list"></i>
                            Activity/Tasks</a></li>
                </ul>
            </li>

            <!-- INVENTORY -->
            <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="fas fa-warehouse"></i> Inventory
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>
                <ul class="crm-dropdown-menu">
                    <li><a href="{{ route('admin.product.index') }}"><i class="fa fa-shopping-bag"></i> Products</a></li>
                    <li><a href="{{ route('admin.stock_in.addSupplier') }}"><i class="fa fa-truck"></i> Suppliers</a></li>
                    <li><a href="{{ route('admin.stock_in.index') }}"><i class="fa fa-arrow-right"></i> Stock In</a></li>

                </ul>
            </li>

            <!-- COMMERCIAL -->
            <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="fas fa-chart-line"></i> Commercial
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>
                <ul class="crm-dropdown-menu">
                    <li><a href="{{ route('admin.orders.index') }}"><i class="fa fa-shopping-cart"></i> Orders &
                            Payments</a></li>
                </ul>
            </li>

            <!-- SUPPORT & HUB -->
            <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="fas fa-headset"></i> Support & Hub
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>
                <ul class="crm-dropdown-menu">
                    <li><a href="{{ route('admin.support.index') }}"><i class="fas fa-headset"></i> Support Tickets</a></li>
                    <li>
                        <a href="{{ route('admin.messages.index') }}">
                            <i class="fas fa-envelope"></i> Messages
                            @if($unreadMessages > 0)
                                <span
                                    style="margin-left:5px; background:#f43f5e; color:#fff; font-size:10px; padding:2px 6px; border-radius:10px;">{{ $unreadMessages }}</span>
                            @endif
                        </a>
                    </li>
                    <li><a href="{{ route('admin.performance.index') }}"><i class="fas fa-tasks"></i> Employee
                            Performance</a></li>
                </ul>
            </li>

            <li><a href="{{ route('admin.settings') }}"><i class="fas fa-cogs"></i> Settings</a></li>
        @endif

        {{-- ===========================
        MANAGER MENU
        ============================ --}}
        @if($role == 'manager')

            <!-- HR & TEAM DROPDOWN -->
            <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="fas fa-users-cog"></i>HR & Team
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>
                <ul class="crm-dropdown-menu">
                    <li><a href="{{ route('manager.team.index') }}"><i class="fas fa-user-tie"></i> Team Management</a></li>
                    <li><a href="{{ route('manager.performance.index') }}"><i class="fas fa-tasks"></i> Performance
                            Pulse</a></li>
                </ul>
            </li>

            <!-- SERVICES DROPDOWN -->
            <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="fas fa-concierge-bell"></i>Services
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>

                <ul class="crm-dropdown-menu">
                    <li><a href="{{ route('manager.services.index') }}"><i class="fa fa-cog"></i> Manage Services</a></li>
                    <li><a href="{{ route('manager.bookings.index') }}"><i class="fa fa-book"></i> Manage Bookings</a></li>
                </ul>
            </li>

            <!-- OPERATIONS DROPDOWN -->
            <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="fas fa-briefcase"></i> Operations
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>
                <ul class="crm-dropdown-menu">
                    <li><a href="{{ route('manager.customer.index') }}"><i class="fas fa-user-friends"></i> Customers</a>
                    </li>
                    <li><a href="{{ route('manager.appointments') }}"><i class="fa fa-calendar-check"></i> Engagements</a>
                    </li>
                </ul>
            </li>

            <!-- INVENTORY DROPDOWN -->
            <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="fas fa-warehouse"></i>Inventory
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>

                <ul class="crm-dropdown-menu">
                    <li><a href="{{ route('manager.product.index') }}"><i class="fa fa-shopping-bag"></i> Product
                            Catalog</a></li>
                    <li><a href="{{ route('manager.categories.index') }}"><i class="fa fa-tags"></i> Categories</a></li>
                    <li><a href="{{ route('manager.stockin.index') }}"><i class="fa fa-arrow-right"></i> Stock In</a></li>
                    <li><a href="{{ route('manager.stockout.index') }}"><i class="fa fa-arrow-left"></i> Stock Out</a></li>
                </ul>
            </li>

            <!-- COMMERCIAL DROPDOWN -->
            <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="fas fa-chart-line"></i> Commercial
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>
                <ul class="crm-dropdown-menu">
                    <li><a href="{{ route('manager.orders.ledger') }}"><i class="fa fa-shopping-cart"></i> Orders Ledger</a>
                    </li>
                </ul>
            </li>

            <!-- SUPPORT & REPORTS DROPDOWN -->
            <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="fas fa-headset"></i> Support & Reports
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>
                <ul class="crm-dropdown-menu">
                    <li><a href="{{ route('manager.support.index') }}"><i class="fas fa-headset"></i> Support Hub</a></li>
                    <li><a href="{{ route('manager.reports.index') }}"><i class="fas fa-chart-bar"></i> Report Center</a>
                    </li>
                </ul>
            </li>
        @endif


        {{-- ===========================
        EMPLOYEE MENU
        ============================ --}}
        @if($role == 'employee')
             <!-- MY WORK DROPDOWN -->
             <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="fas fa-briefcase"></i> My Work
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>
                <ul class="crm-dropdown-menu">
                    <li><a href="{{ route('employee.appointments.index') }}"><i class="fa fa-calendar-check"></i> Daily Engagements</a></li>
                    <li><a href="{{ route('employee.bookings.index') }}"><i class="fas fa-book"></i> Service Bookings</a></li>
                </ul>
            </li>

             <!-- RESOURCES DROPDOWN -->
             <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="fas fa-folder-open"></i> Resources
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>
                <ul class="crm-dropdown-menu">
                     <li><a href="{{ route('employee.support.index') }}"><i class="fas fa-headset"></i> Support Hub</a></li>
                     <li><a href="{{ route('employee.reports') }}"><i class="fas fa-file-alt"></i> Report Center</a></li>
                </ul>
            </li>
        @endif

        {{-- ===========================
        CUSTOMER MENU
        ============================ --}}
        @if($role == 'customer')
            <!-- MY SCHEDULE DROPDOWN -->
            <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="far fa-calendar-alt"></i> My Schedule
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>
                <ul class="crm-dropdown-menu">
                     <li><a href="{{ route('customer.bookings.index') }}"><i class="fas fa-book"></i> Booking Command</a></li>
                     <li><a href="{{ route('customer.appointments.index') }}"><i class="fa fa-calendar-check"></i> Appointments</a></li>
                </ul>
            </li>

            <!-- MARKETPLACE DROPDOWN -->
            <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="fas fa-store"></i> Marketplace
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>
                <ul class="crm-dropdown-menu">
                     <li><a href="{{ route('customer.orders.index') }}"><i class="fas fa-shopping-basket"></i> Products</a></li>
                     <li><a href="{{ route('customer.invoices.index') }}"><i class="fas fa-file-invoice-dollar"></i> Invoices</a></li>
                     <li><a href="{{ route('customer.orders.index', ['section' => 'archive']) }}"><i class="fas fa-history"></i> Order Archive</a></li>
                </ul>
            </li>

            <!-- ACCOUNT & SUPPORT DROPDOWN -->
             <li class="crm-dropdown">
                <a class="crm-dropdown-toggle">
                    <i class="fas fa-user-shield"></i> Account & Support
                    <i class="fa fa-caret-down crm-dropdown-arrow"></i>
                </a>
                <ul class="crm-dropdown-menu">
                     <li><a href="{{ route('notifications.page') }}"><i class="fas fa-bell"></i> Notifications</a></li>
                     <li><a href="{{ route('customer.support.index') }}"><i class="fas fa-headset"></i> Support Hub</a></li>
                     <li><a href="{{ route('password.change') }}"><i class="fas fa-user-cog"></i> Account Security</a></li>
                </ul>
            </li>
        @endif

        <!-- LOGOUT -->
        <li>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;" data-turbo="false">
                @csrf
                <button type="submit" class="crm-logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="crm-label">Logout</span>
                </button>
            </form>
        </li>
    </ul>
</div>

<!-- JS -->
<script>
    document.getElementById("crm-hamburger").addEventListener("click", function () {
        document.getElementById("crm-sidebar").classList.toggle("active");
    });

    // Accordion Dropdowns
    document.querySelectorAll('.crm-dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default anchor behavior

            const parent = toggle.closest('.crm-dropdown');
            const menu = parent.querySelector('.crm-dropdown-menu');
            const arrow = toggle.querySelector('.crm-dropdown-arrow');

            // Close other open menus (optional, usually preferred in accordions)
            document.querySelectorAll('.crm-dropdown').forEach(other => {
                if (other !== parent) {
                    other.querySelector('.crm-dropdown-menu').classList.remove('show');
                    other.querySelector('.crm-dropdown-arrow').classList.remove('rotate');
                }
            });

            // Toggle current
            menu.classList.toggle('show');
            arrow.classList.toggle('rotate');
        });
    });
</script>

<!-- UNIQUE CSS -->
<style>
    /* SIDEBAR CORE */
    .crm-sidebar {
        width: 222px;
        /* Updated to 222px as requested */
        position: fixed;
        top: 0;
        /* Revert: Sidebar starts at very top */
        left: 0;
        height: 100vh;
        /* Full height */
        padding-top: 64px;
        /* Push menu down by header height */
        background: #0f172a;
        z-index: 1000;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        /* Re-enabled for long sidebars */
        overflow-x: hidden;
        /* Prevent horizontal scroll from flyouts */
    }

    .crm-sidebar::-webkit-scrollbar {
        width: 5px;
    }

    .crm-sidebar::-webkit-scrollbar-thumb {
        background: #334155;
        border-radius: 10px;
    }

    .crm-sidebar-menu {
        list-style: none;
        padding: 15px 10px;
        margin: 0;
    }

    .crm-sidebar-menu li {
        margin-bottom: 4px;
    }

    .crm-sidebar-menu li a,
    .crm-logout-btn {
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #94a3b8;
        /* Muted text */
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .crm-sidebar-menu li a i {
        width: 20px;
        text-align: center;
        font-size: 16px;
    }

    .crm-sidebar-menu li a:hover,
    .crm-logout-btn:hover {
        background: rgba(59, 130, 246, 0.1);
        /* Subtle blue tint */
        color: #3b82f6;
        /* Bright Blue */
    }

    /* ACTIVE STATE (Subtle) */
    .crm-sidebar-menu li a:active {
        background: #3b82f6;
        color: #fff;
    }

    /* DROPDOWN STYLING (Accordion) */
    .crm-dropdown {
        position: relative;
    }

    .crm-dropdown-toggle {
        display: flex !important;
        justify-content: space-between;
        width: 100%;
        box-sizing: border-box;
        cursor: pointer;
    }

    .crm-dropdown-menu {
        display: none;
        /* Hidden by default */
        position: relative;
        /* Inline flow */
        left: 0;
        top: 0;
        width: 100%;
        list-style: none;
        padding: 5px 0;
        margin: 0;
        background: rgba(0, 0, 0, 0.2);
        /* Darker background for nested items */
        border-radius: 8px;
        box-shadow: none;
        border-left: none;
        z-index: 100;
    }

    .crm-dropdown-menu.show {
        display: block;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .crm-dropdown-menu li a {
        padding: 10px 20px 10px 45px;
        /* Increased padding for indentation */
        color: #94a3b8;
        font-size: 13px;
        border-radius: 0;
        transition: all 0.2s;
        display: flex;
        align-items: center;
    }

    .crm-dropdown-menu li a i {
        font-size: 13px;
        margin-right: 10px;
        opacity: 0.7;
    }

    .crm-dropdown-menu li a:hover {
        background: transparent;
        color: #fff;
    }

    /* Remove hover flyout logic */

    .crm-dropdown-arrow {
        font-size: 10px;
        transition: transform 0.3s;
        opacity: 0.7;
    }

    .crm-dropdown-arrow.rotate {
        transform: rotate(180deg);
    }

    /* LOGOUT BUTTON OVERRIDE */
    .crm-logout-btn {
        background: none;
        border: none;
        width: 100%;
        margin-top: 20px;
        border-top: 1px solid #1e293b;
        padding: 24px 16px 16px 16px;
        color: #ef4444;
        text-align: left;
    }

    .crm-logout-btn:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #f87171;
    }

    /* HAMBURGER */
    .crm-hamburger {
        display: none;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1001;
        background: #0f172a;
        padding: 8px;
        border-radius: 6px;
        cursor: pointer;
    }

    .crm-hamburger span {
        display: block;
        width: 20px;
        height: 2px;
        background: #fff;
        margin: 4px 0;
        transition: 0.3s;
    }

    /* MOBILE */
    @media(max-width:768px) {
        .crm-sidebar {
            left: -250px;
        }

        .crm-sidebar.active {
            left: 0;
        }

        .crm-hamburger {
            display: block;
        }

        .crm-dropdown-menu {
            position: relative;
            left: 0;
            top: 0;
            width: 100%;
            margin-left: 0;
            background: rgba(30, 41, 59, 0.5);
            box-shadow: none;
            border: none;
            display: none !important;
            /* Force hide in mobile unless JS is updated, but user said no-other-changes. We will keep as is */
        }

        .crm-dropdown:hover .crm-dropdown-menu {
            display: block !important;
        }

        .crm-dropdown-menu::before {
            display: none;
        }
    }

    /* GLOBAL LAYOUT OVERRIDES */
    @media (min-width: 1025px) {

        .inventory-container,
        .crm-table-container,
        .support-dashboard-wrapper {
            width: calc(100% - 262px) !important;
            /* Sidebar (222px) + Left Gap (20px) + Right Safety Space (20px) */
            margin-left: 242px !important;
            /* Sidebar (222px) + Gap (20px) */
            transition: all 0.3s ease;
        }
    }

    @media (max-width: 1024px) {

        .inventory-container,
        .crm-table-container,
        .support-dashboard-wrapper {
            width: calc(100% - 20px) !important;
            /* Simple right safety margin on mobile/small screens */
            margin-left: 0 !important;
            margin-right: 20px !important;
        }
    }
</style>