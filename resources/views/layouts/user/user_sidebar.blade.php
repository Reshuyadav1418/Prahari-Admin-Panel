<!-- Start::app-sidebar -->
<aside class="app-sidebar sticky" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="index.html" class="header-logo">
            <img src="../assets/images/brand-logos/desktop-logo.png" alt="logo" class="desktop-logo">
            <img src="../assets/images/brand-logos/toggle-dark.png" alt="logo" class="toggle-dark">
            <img src="../assets/images/brand-logos/desktop-dark.png" alt="logo" class="desktop-dark">
            <img src="../assets/images/brand-logos/toggle-logo.png" alt="logo" class="toggle-logo">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu">

               <li class="slide">
                    <a href="{{ route('userDashboard') }}" class="side-menu__item">
                        <i class="bi bi-speedometer me-2 text-primary fs-5"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li> 
               <li class="slide">
                    <a href="{{ route('users.index') }}" class="side-menu__item">
                        <i class="bi bi-people me-2 text-primary fs-5"></i>
                        <span class="side-menu__label">Products</span>
                    </a>
                </li> 
              
                
               <li class="slide">
                    <a href="{{ route('users.getTransaction') }}" class="side-menu__item">
                        <i class="bi bi-people me-2 text-primary fs-5"></i>
                        <span class="side-menu__label">Transactions</span>
                    </a>
                </li> 
                <li class="slide has-sub">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="1em" height="1em"
                            viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.5" color="currentColor">
                                <path
                                    d="M15.129 13.747a.906.906 0 0 1-1.258 0c-1.544-1.497-3.613-3.168-2.604-5.595A3.53 3.53 0 0 1 14.5 6c1.378 0 2.688.84 3.233 2.152c1.008 2.424-1.056 4.104-2.604 5.595M14.5 9.5h.009" />
                                <path
                                    d="M2.5 12c0-4.478 0-6.718 1.391-8.109S7.521 2.5 12 2.5c4.478 0 6.718 0 8.109 1.391S21.5 7.521 21.5 12c0 4.478 0 6.718-1.391 8.109S16.479 21.5 12 21.5c-4.478 0-6.718 0-8.109-1.391S2.5 16.479 2.5 12M17 21L3 7m7 7l-6 6" />
                            </g>
                        </svg>
                        <span class="side-menu__label">Maps</span>
                        <i class="ri-arrow-down-s-line side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">Maps</a>
                        </li>
                        <li class="slide">
                            <a href="vector-maps.html" class="side-menu__item">Vector Maps</a>
                        </li>
                        <li class="slide">
                            <a href="leaflet-maps.html" class="side-menu__item">Leaflet Maps</a>
                        </li>
                        <li class="slide">
                            <a href="google-maps.html" class="side-menu__item">Google Maps</a>
                        </li>
                    </ul>
                </li>

                <li class="slide">
                    <a href="{{ route('logout') }}" class="side-menu__item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-left me-2 text-primary fs-5"></i>
                        <span class="side-menu__label">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg></div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>
<!-- End::app-sidebar -->
