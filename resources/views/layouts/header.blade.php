@php
    $user = Auth::user();
    $role = $user->role ?? 'customer';
    
    // Notifications count
    $unreadNotifications = $user ? $user->unreadNotifications()->count() : 0;
    
    // Messages count (Role specific logic)
    if (in_array($role, ['admin', 'manager'])) {
        $unreadMessages = \App\Models\MessageUs::where('read', false)->count();
    } else {
        $unreadMessages = $user ? \App\Models\Message::where('recipient_id', $user->id)->whereNull('read_at')->count() : 0;
    }
@endphp

<script>
    // Immediate Theme Application to prevent flicker
    (function() {
        const theme = "{{ $user->theme ?? 'light' }}";
        document.documentElement.setAttribute('data-theme', theme);
    })();
</script>

<link rel="stylesheet" href="{{ asset('css/header.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<header class="topbar" id="crm-topbar" data-turbo-permanent>
    <div class="logo-area">
        <a href="{{ url('/') }}" class="topbar-logo">
            <!-- Ensure logo image has transparent bg -->
            <img src="{{ asset('images/logo.png') }}" alt="eVuba Logo">
            <span>eVubaConnect</span>
        </a>
    </div>

    <!-- Mega Search -->
    <div class="mega-search-container">
        <div class="mega-search">
            <i class="fas fa-search" style="color: var(--text-muted);"></i>
            <input type="text" id="globalSearchInput" placeholder="Search orders, customers, tasks..." class="search-input" autocomplete="off">
            <div id="searchLoader" style="display:none;"><i class="fas fa-spinner fa-spin" style="color: var(--primary);"></i></div>
        </div>
        <div id="globalSearchResults" class="search-results">
            <!-- Results populated via JS -->
        </div>
    </div>

    <div class="topbar-icons">
        <!-- Quick Add Dropdown -->
        <div class="dropdown-wrapper">
            <button class="icon-btn" title="Quick Add" id="quickAddBtn" style="background: var(--primary); color: white;">
                <i class="fas fa-plus"></i>
            </button>
            <div class="dropdown-menu" id="quickAddDropdown">
                <div class="dropdown-header">Quick Actions</div>
                @if($role == 'admin' || $role == 'manager')
                    <a href="{{ route($role.'.orders.index') }}" class="dropdown-item"><i class="fas fa-shopping-cart"></i> Create New Order</a>
                    <a href="{{ route($role == 'admin' ? 'admin.customers.index' : 'manager.dashboard') }}" class="dropdown-item"><i class="fas fa-user-plus"></i> Add New Customer</a>
                    <a href="{{ route($role.'.product.index') }}" class="dropdown-item"><i class="fas fa-box-open"></i> Add New Product</a>
                @endif
                @if($role == 'admin')
                    <a href="{{ route('admin.employees.index') }}" class="dropdown-item"><i class="fas fa-user-tie"></i> Register Employee</a>
                @endif
                @if(in_array($role, ['admin', 'manager', 'employee']))
                    <a href="{{ route($role == 'manager' ? 'manager.appointments' : $role.'.appointments.index') }}" class="dropdown-item"><i class="fas fa-calendar-plus"></i> New Activity/Task</a>
                @endif
                <a href="{{ route('customer.bookings.index') }}" class="dropdown-item"><i class="fas fa-bookmark"></i> Book a Service</a>
            </div>
        </div>

        <!-- Theme Toggle -->
        <button class="icon-btn" id="themeToggle" title="Toggle Theme">
            <i class="fas fa-moon" id="themeIcon"></i>
        </button>

        <!-- Cart & Orders (Customer Only) -->
        @if($role == 'customer')
            <a href="#" class="icon-btn" title="My Shopping Cart">
                <i class="fas fa-shopping-cart"></i>
                <span class="badge" id="cartBadge" style="display:none;">0</span>
            </a>
            <a href="{{ route('customer.orders.index', ['section' => 'archive']) }}" class="icon-btn" title="Order History">
                <i class="fas fa-clipboard-list"></i>
            </a>
        @endif

        <!-- Messages -->
        <a href="{{ $role == 'admin' ? route('admin.messages.index') : route('messages.page') }}" class="icon-btn" title="Messages">
            <i class="far fa-envelope"></i>
            @if($unreadMessages > 0)
                <span class="badge">{{ $unreadMessages }}</span>
            @endif
        </a>

        <!-- Notifications -->
        <a href="{{ route('notifications.page') }}" class="icon-btn" title="Notifications">
            <i class="far fa-bell"></i>
            @if($unreadNotifications > 0)
                <span class="badge">{{ $unreadNotifications }}</span>
            @endif
        </a>

        <!-- Profile Dropdown -->
        <div class="dropdown-wrapper">
            <div id="profileBtn" class="user-avatar-container" style="cursor: pointer;">
                @if($user && $user->photo)
                    <img src="{{ asset('storage/profile-photos/' . $user->photo) }}" class="user-avatar-img">
                @else
                    <div class="user-avatar-placeholder">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="dropdown-menu" id="profileDropdown">
                <div class="dropdown-header">Account</div>
                <div style="padding: 10px 15px; border-bottom: 1px solid var(--header-border); margin-bottom: 5px;">
                    <div style="font-weight: 700; color: var(--text-main); font-size: 14px;">{{ $user->name ?? 'User' }}</div>
                    <div style="font-size: 11px; color: var(--text-muted);">{{ ucfirst($role) }} Account</div>
                </div>
                <a href="{{ route('profile.show') }}" class="dropdown-item"><i class="far fa-user"></i> My Profile</a>
                <a href="{{ ($role == 'admin') ? route('admin.settings') : '#' }}" class="dropdown-item"><i class="fas fa-sliders-h"></i> Settings</a>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;" data-turbo="false">
                    @csrf
                    <button type="submit" class="dropdown-item" style="width:100%; text-align:left; border:none; background:none; cursor:pointer; color: #ef4444;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<style>
    /* Intact Design Update */
    .topbar {
        height: 64px;
        background: var(--surface); /* Default to surface */
        display: flex;
        align-items: center;
        padding: 0; /* Remove default padding to let logo flush */
        position: fixed;
        top: 0; 
        left: 0; 
        right: 0;
        z-index: 2000; /* Highest Priority: Always on top of everything */
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        border-bottom: 1px solid var(--header-border);
    }

    /* Logo Area matches Header (White) */
    .logo-area {
        width: 222px; /* Sidebar Width */
        height: 100%;
        background: transparent; /* Seamless with white header */
        display: flex;
        align-items: center;
        padding-left: 20px;
        flex-shrink: 0;
        position: relative;
        z-index: 1002;
        /* border-right removed */
    }

    .topbar-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--primary); /* Brand Color for Logo Text */
        text-decoration: none;
        font-weight: 800;
        font-size: 19px;
        font-family: 'Inter', sans-serif;
        letter-spacing: -0.5px;
    }
    
    .topbar-logo img {
        height: 32px;
        width: auto;
    }

    .mega-search-container {
        flex: 1;
        max-width: 600px;
        margin-left: 20px;
        position: relative;
    }
    
    /* Adjust icons container */
    .topbar-icons {
        margin-left: auto;
        padding-right: 30px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
</style>

<script>
    // Theme Toggle Logic
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    // Initial theme from PHP (User's database preference)
    let currentTheme = "{{ $user->theme ?? 'light' }}";

    document.documentElement.setAttribute('data-theme', currentTheme);
    updateThemeIcon(currentTheme);

    themeToggle.addEventListener('click', () => {
        let theme = document.documentElement.getAttribute('data-theme');
        let newTheme = theme === 'light' ? 'dark' : 'light';
        
        // Update UI immediately
        document.documentElement.setAttribute('data-theme', newTheme);
        updateThemeIcon(newTheme);
        
        // Persist to database
        fetch("{{ route('profile.theme') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ theme: newTheme })
        }).catch(err => console.error('Theme persistence failed:', err));
    });

    function updateThemeIcon(theme) {
        if(theme === 'dark') {
            themeIcon.classList.replace('fa-moon', 'fa-sun');
        } else {
            themeIcon.classList.replace('fa-sun', 'fa-moon');
        }
    }

    // Dropdown Toggles
    function setupDropdown(btnId, menuId) {
        const btn = document.getElementById(btnId);
        const menu = document.getElementById(menuId);
        if(!btn || !menu) return;

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = menu.style.display === 'block';
            closeAllDropdowns();
            menu.style.display = isOpen ? 'none' : 'block';
        });
    }

    setupDropdown('quickAddBtn', 'quickAddDropdown');
    setupDropdown('profileBtn', 'profileDropdown');

    function closeAllDropdowns() {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
        document.getElementById('globalSearchResults').style.display = 'none';
    }

    window.addEventListener('click', closeAllDropdowns);

    // Global Search Logic
    const searchInput = document.getElementById('globalSearchInput');
    const searchResults = document.getElementById('globalSearchResults');
    const searchLoader = document.getElementById('searchLoader');
    let searchTimeout;

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.trim();
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            searchLoader.style.display = 'block';
            fetch(`{{ route('global.search') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    searchLoader.style.display = 'none';
                    renderSearchResults(data.results);
                })
                .catch(err => {
                    console.error(err);
                    searchLoader.style.display = 'none';
                });
        }, 400);
    });

    function renderSearchResults(results) {
        if (results.length === 0) {
            searchResults.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--text-muted); font-size: 13px;">No results found.</div>';
        } else {
            searchResults.innerHTML = results.map(item => `
                <a href="${item.url}" class="search-result-item">
                    <i class="${item.icon}"></i>
                    <div>
                        <div style="font-weight: 700; font-size: 14px;">${item.title}</div>
                        <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase;">${item.type}</div>
                    </div>
                </a>
            `).join('');
        }
        searchResults.style.display = 'block';
    }
</script>
