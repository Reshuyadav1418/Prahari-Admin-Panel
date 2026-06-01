<!-- Start::app-sidebar -->
<aside class="app-sidebar sticky" id="sidebar">

    <style>
        /* SIDEBAR BACKGROUND */
        .app-sidebar {
            background: #1f2a37 !important;
        }

        /* LOGO */
        .header-logo {
            color: black !important;
            font-weight: bold;
            font-size: 18px;
        }

        /* MENU ITEMS */
        .side-menu__item {
            color: #fff !important;
            font-weight: 600;
            padding: 10px 15px;
            display: flex;
            align-items: center;
        }

        /* ICONS */
        .side-menu__item i {
            color: #fff !important;
        }

        /* REMOVE BOOTSTRAP PRIMARY COLOR */
        .text-primary {
            color: #fff !important;
        }

        /* HOVER */
        .side-menu__item:hover {
            background: #e1bb80 !important;
            border-radius: 5px;
        }

        /* ACTIVE */
        .side-menu__item.active {
            background: #555 !important;
            border-radius: 5px;
        }
    </style>

    <!-- Header -->
    <div class="main-sidebar-header">
        <a href="#" class="header-logo">
            <b>PRAHARI ADMIN</b>
        </a>
    </div>

    <!-- Sidebar Menu -->
    <div class="main-sidebar" id="sidebar-scroll" style="background-color: #685634">

        <nav class="main-menu-container nav nav-pills flex-column">

            <ul class="main-menu">

                @if(\App\Models\Setting::get('show_dashboard', 1))
                <li class="slide">
                    <a href="{{ route('adminDashboard') }}" class="side-menu__item">
                        <i class="bi bi-house-door me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @endif

                @if(\App\Models\Setting::get('show_praharis', 1))
                <li class="slide">
                    <a href="{{ route('admin.praharis') }}" class="side-menu__item">
                        <i class="bi bi-person-badge me-2"></i>
                        <span>Praharis</span>
                    </a>
                </li>
                @endif

                

                @if(\App\Models\Setting::get('show_cases', 1))
                <li class="slide">
                    <a href="{{ route('admin.cases') }}" class="side-menu__item">
                        <i class="bi bi-folder me-2"></i>
                        <span>Cases</span>
                    </a>
                </li>
                @endif

                @if(\App\Models\Setting::get('show_challans', 1))
                <li class="slide">
                    <a href="{{ route('admin.challan') }}" class="side-menu__item">
                        <i class="bi bi-receipt me-2"></i>
                        <span>Challans</span>
                    </a>
                </li>
                @endif

                @if(\App\Models\Setting::get('show_payments', 1))
                <li class="slide">
                    <a href="{{ route('admin.payments') }}" class="side-menu__item">
                        <i class="bi bi-credit-card me-2"></i>
                        <span>Payments</span>
                    </a>
                </li>
                @endif

                @if(\App\Models\Setting::get('show_reports', 1))
                <li class="slide">
                    <a href="{{ route('admin.reports') }}" class="side-menu__item">
                        <i class="bi bi-bar-chart me-2"></i>
                        <span>Reports</span>
                    </a>
                </li>
                @endif

                @if(\App\Models\Setting::get('show_admins', 1))
                <li class="slide">
                    <a href="{{ route('admin.admins') }}" class="side-menu__item">
                        <i class="bi bi-shield-lock me-2"></i>
                        <span>Admin Management</span>
                    </a>
                </li>
                @endif

              

                @if(\App\Models\Setting::get('show_settings', 1))
                <li class="slide">
                    <a href="{{ route('admin.settings') }}" class="side-menu__item">
                        <i class="bi bi-gear me-2"></i>
                        <span>Settings</span>
                    </a>
                </li>
                @endif

                <!-- LOGOUT -->
                <li class="slide">
                    <a href="#" class="side-menu__item" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                        <i class="bi bi-box-arrow-left me-2"></i>
                        <span>Logout</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

            </ul>

        </nav>

    </div>

</aside>
<!-- End::app-sidebar -->