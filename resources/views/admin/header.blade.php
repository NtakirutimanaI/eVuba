
<!-- ================== HEADER CSS ================== -->
<link rel="stylesheet" href="{{ asset('css/header.css') }}">

<style>
/* ============ TOPBAR FIXED ============ */
.topbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 60px;
    background-color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    z-index: 9999; /* Always on top */
}

.topbar-logo {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: bold;
    font-size: 18px;
    color: #2c3e50;
}

.topbar-logo img {
    height: 35px;
}

.mega-search {
    display: flex;
    align-items: center;
    gap: 5px;
}

.search-input {
    padding: 5px 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
    font-size: 14px;
    width: 200px;
}

.search-icon {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

.topbar-icons {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-circle {
    border-radius: 50%;
    width: 32px;
    height: 32px;
    border: none;
    cursor: pointer;
    font-size: 18px;
}

.btn-blue {
    background-color: #007bff;
    color: white;
}

.icon-button {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
}

.avatar-img {
    width: 35px;
    height: 35px;
    border-radius: 50%;
}

.avatar {
    width: 35px;
    height: 35px;
    background-color: #6c757d;
    color: white;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 16px;
}

/* Prevent content from hiding under fixed header */
body {
    padding-top: 70px;
}
</style>

<!-- ================== TOPBAR ================== -->
<div class="topbar">
    {{-- Logo --}}
    <div class="topbar-logo">
        <img src="{{ asset('images/eVuba.png') }}" alt="eVubaConnect">
        <span>eVubaConnect</span>
    </div>

    {{-- Mega Search --}}
    <div class="mega-search">
        <button class="search-icon" id="searchBtn">🔍</button>
        <input type="text" placeholder="Search Everything" class="search-input" id="searchInput">
    </div>

    {{-- Icons --}}
    <div class="topbar-icons">
        <button class="btn-circle btn-blue">+</button>
        <button class="icon-button">🌗</button>
        <button class="icon-button" title="Notifications">🔔</button>
        <button class="icon-button" title="Messages">✉️</button>

        {{-- User Avatar --}}
        <a href="{{ route('profile.show') }}">
            @auth
                @if(Auth::user()->profile_image)
                    <img src="{{ asset('storage/profile_images/' . Auth::user()->profile_image) }}" alt="Avatar" class="avatar-img">
                @else
                    <div class="avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
            @else
                <div class="avatar">👤</div>
            @endauth
        </a>
    </div>
</div>

<!-- ================== SEARCH SCRIPT ================== -->
<script>
document.getElementById('searchBtn').addEventListener('click', function (e) {
    e.preventDefault();
    const query = document.getElementById('searchInput').value.trim();

    if (query) {
        console.log('Searching for:', query);
        // Example redirect:
        // window.location.href = `/search?q=${encodeURIComponent(query)}`;
    } else {
        alert('Please enter a search term.');
    }
});
</script>

