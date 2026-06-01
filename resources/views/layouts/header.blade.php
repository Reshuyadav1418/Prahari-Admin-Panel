<!-- app-header -->
<header class="app-header sticky custom-header" id="header">

<style>
/* HEADER BACKGROUND */
.custom-header {
    background-color: #f5f5f5 !important; /* very light grey */
    border-bottom: 1px solid #ddd;
}

/* TEXT + ICON COLOR */
.custom-header,
.custom-header .header-link,
.custom-header .header-link-icon,
.custom-header .dropdown-item,
.custom-header input,
.custom-header .fs-13,
.custom-header .fs-16 {
    color: #000 !important; /* black text */
}

/* SEARCH BAR */
.custom-header .header-search-bar {
    background-color: #ffffff !important;
    color: #000 !important;
    border: 1px solid #ccc;
}

/* ICON HOVER */
.custom-header .header-link:hover {
    background-color: #e0e0e0 !important;
    border-radius: 6px;
}

/* DROPDOWN */
.custom-header .dropdown-menu {
    background-color: #ffffff;
    color: #000;
}

/* BADGES */
.custom-header .badge {
    background-color: #000 !important;
    color: #fff !important;
}
</style>

    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid" style="background-color: white">

        <!-- LEFT -->
        <div class="header-content-left">

            <div class="header-element">
                <div class="horizontal-logo">

                    <a href="index.html" class="header-logo text-dark fw-bold">
                        PRAHARI ADMIN
                    </a>
                   
                </div>
            </div>

            <div class="header-element mx-lg-0 mx-2">
                <a class="sidemenu-toggle header-link" data-bs-toggle="sidebar" href="#">
                    <i class="bi bi-list fs-4"></i>
                </a>
            </div>

            

        </div>

        <!-- RIGHT -->
        <ul class="header-content-right">

           
           <li class="header-element dropdown">
    <a class="header-link dropdown-toggle d-flex align-items-center gap-2" 
       data-bs-toggle="dropdown" href="javascript:void(0);">
        
        @if(auth()->user()->profile_image)
            <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;border:1px solid #ccc;" />
        @else
            <i class="bx bx-user-circle fs-3"></i>
        @endif

        <span class="fw-semibold">{{ auth()->user()->name ?? 'Admin User' }}</span>
    </a>

    <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.settings') }}">Settings</a></li>
        <li>
            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                Logout
            </a>
        </li>
    </ul>
</li>

        </ul>

    </div>
</header>
<!-- /app-header -->