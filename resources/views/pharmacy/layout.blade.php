<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="/assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'Doctor Dashboard') - MyVitalz</title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <style>
        :root {
            --pharmacy-primary: #5a5fc7;
            --pharmacy-secondary: #4a4eb3;
        }
        .pharmacy-badge {
            background: linear-gradient(135deg, #5a5fc7 0%, #4a4eb3 100%);
            color: white;
        }
        .stat-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .bg-label-primary { background-color: rgba(90, 95, 199, 0.16); color: #5a5fc7; }
        .bg-label-success { background-color: rgba(113, 221, 55, 0.16); color: #71dd37; }
        .bg-label-warning { background-color: rgba(255, 171, 0, 0.16); color: #ffab00; }
        .bg-label-danger { background-color: rgba(255, 62, 29, 0.16); color: #ff3e1d; }
        .bg-label-info { background-color: rgba(3, 195, 236, 0.16); color: #03c3ec; }

        /* Demo-inspired gradient navbar */
        .navbar-pharmacy {
            background: linear-gradient(135deg, var(--pharmacy-primary) 0%, var(--pharmacy-secondary) 100%) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .navbar-pharmacy .nav-link, .navbar-pharmacy .navbar-brand { color: #fff !important; }

        /* Gradient sidebar */
        .layout-menu.menu-vertical.menu.bg-menu-theme {
            background: radial-gradient(1200px 400px at -10% -10%, rgba(255,255,255,0.08), transparent 60%),
                        linear-gradient(180deg, var(--pharmacy-primary) 0%, var(--pharmacy-secondary) 100%) !important;
        }
        /* Remove white fade shadow at top of sidebar */
        .menu-inner-shadow { display: none !important; }
        .layout-menu { background-repeat: no-repeat; background-size: cover; position: relative; }
        .layout-menu .app-brand { background: transparent !important; }
        .layout-menu .menu-inner .menu-item .menu-link, .layout-menu .menu-inner .menu-header {
            color: rgba(255,255,255,0.86) !important;
        }
        .layout-menu .menu-inner .menu-item .menu-link { 
            display: flex; align-items: center; gap: 10px; border-radius: 10px; position: relative;
        }
        .layout-menu .menu-inner .menu-item .menu-link .menu-icon { 
            color: rgba(255,255,255,0.9); filter: drop-shadow(0 1px 0 rgba(0,0,0,.06));
        }
        .layout-menu .menu-inner .menu-item .menu-link .menu-icon + div { font-weight: 500; letter-spacing: .2px; }
        .layout-menu .menu-inner .menu-item .menu-link:hover { transform: translateX(4px); }
        .layout-menu .menu-inner .menu-item.active > .menu-link,
        .layout-menu .menu-inner .menu-item .menu-link:hover {
            background-color: rgba(255,255,255,0.18) !important;
            color: #fff !important;
        }
        /* Left indicator for active/hovered items */
        .layout-menu .menu-inner .menu-item > .menu-link::before {
            content: ''; position: absolute; left: -6px; top: 8px; bottom: 8px; width: 3px;
            background: transparent; border-radius: 4px; transition: background .2s ease;
        }
        .layout-menu .menu-inner .menu-item.active > .menu-link::before,
        .layout-menu .menu-inner .menu-item > .menu-link:hover::before { background: #fff; }
        /* Section headers & dividers */
        .layout-menu .menu-header { opacity: .9; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; padding: .75rem 1.25rem; }
        .layout-menu .menu-divider { height: 1px; margin: .25rem .75rem; background: rgba(255,255,255,0.15); border-radius: 1px; }

        /* Utility button class to match demo */
        .btn-pharmacy { background-color: var(--pharmacy-primary); border-color: var(--pharmacy-primary); color: #fff; }
        .btn-pharmacy:hover { background-color: var(--pharmacy-secondary); border-color: var(--pharmacy-secondary); color: #fff; }

        /* CTA harmonization: map default primary to pharmacy palette */
        .btn-primary { background-color: var(--pharmacy-primary) !important; border-color: var(--pharmacy-primary) !important; }
        .btn-primary:hover, .btn-primary:focus { background-color: var(--pharmacy-secondary) !important; border-color: var(--pharmacy-secondary) !important; }
        .btn-outline-primary { color: var(--pharmacy-primary) !important; border-color: var(--pharmacy-primary) !important; }
        .btn-outline-primary:hover { background-color: var(--pharmacy-primary) !important; color: #fff !important; }

        /* Card polish */
        .card { border: 0; border-radius: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
        .card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
        .card-header { border-bottom: 0; }

        /* Navbar badges */
        .badge-notifications { box-shadow: 0 0 0 3px rgba(255,255,255,0.5); }

        /* Table aesthetics */
        .table { --tbl-border: #eceff3; }
        .table thead th { border-top: 0; color: #5a6169; font-weight: 600; letter-spacing: .2px; }
        .table > :not(caption) > * > * { border-bottom: 1px solid var(--tbl-border); }
        .table-hover tbody tr:hover { background-color: rgba(105,108,255,0.06); }
        .table-striped > tbody > tr:nth-of-type(odd) > * { background-color: rgba(0,0,0,.015); }

        /* Empty state utility */
        .empty-state { text-align: center; padding: 2rem 1rem; color: #98a2b3; }
        .empty-state .icon { width: 48px; height: 48px; border-radius: 50%; background: rgba(105,108,255,0.12); color: var(--pharmacy-primary); display: inline-flex; align-items: center; justify-content: center; margin-bottom: .75rem; }
        .empty-state .title { font-weight: 600; color: #475467; }
        .empty-state .hint { font-size: .9rem; }

        /* PREMIUM ENHANCEMENTS */
        /* Typography & Spacing */
        body { font-family: 'Public Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; letter-spacing: -0.01em; }
        h1, h2, h3, h4, h5, h6 { font-weight: 600; letter-spacing: -0.02em; }
        .page-title { font-size: 1.75rem; font-weight: 700; color: #1a1d1f; margin-bottom: 1.5rem; }
        .section-title { font-size: 1.125rem; font-weight: 600; color: #344054; margin-bottom: 1rem; }

        /* Premium Card Styles */
        .card { border: 1px solid rgba(0,0,0,0.04); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .card:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(105,108,255,0.12) !important; }
        .card-header { background: transparent; padding: 1.5rem; font-weight: 600; }
        .card-body { padding: 1.5rem; }

        /* Stat Cards Premium */
        .stat-card { position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: linear-gradient(135deg, rgba(105,108,255,0.08), transparent); border-radius: 50%; transform: translate(30%, -30%); }
        .stat-card .content-left span { font-size: 0.8125rem; font-weight: 500; color: #667085; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-card .content-left h4 { font-size: 2rem; font-weight: 700; color: #101828; }
        .stat-card .avatar-initial { width: 56px; height: 56px; font-size: 1.5rem; }

        /* Alert Premium */
        .alert { border: 0; border-radius: 12px; padding: 1rem 1.25rem; }
        .alert-primary { background: linear-gradient(135deg, rgba(105,108,255,0.12), rgba(105,108,255,0.08)); color: var(--pharmacy-primary); border-left: 4px solid var(--pharmacy-primary); }

        /* Button Enhancements */
        .btn { font-weight: 500; letter-spacing: 0.02em; padding: 0.625rem 1.25rem; transition: all 0.2s ease; }
        .btn-primary, .btn-pharmacy { box-shadow: 0 2px 8px rgba(105,108,255,0.24); }
        .btn-primary:hover, .btn-pharmacy:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(105,108,255,0.32); }
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.875rem; }

        /* Badge Premium */
        .badge { font-weight: 500; padding: 0.375rem 0.75rem; border-radius: 6px; letter-spacing: 0.02em; }

        /* Table Premium */
        .table { font-size: 0.9375rem; }
        .table thead th { background: #f9fafb; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 1rem; }
        .table tbody td { padding: 1rem; vertical-align: middle; }
        .table-hover tbody tr { transition: background-color 0.15s ease; }

        /* Avatar Enhancements */
        .avatar { box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .avatar-initial { font-weight: 600; }

        /* Sidebar Menu Premium */
        .layout-menu .menu-inner .menu-item .menu-link { padding: 0.75rem 1.1rem; margin: 0.25rem 0.75rem; border-radius: 10px; font-weight: 500; transition: all 0.2s ease; }
        .layout-menu .menu-inner .menu-item .menu-link:hover { transform: translateX(4px); }
        .layout-menu .menu-inner .menu-item.active > .menu-link { font-weight: 700; box-shadow: 0 6px 16px rgba(0,0,0,0.12); }
        .layout-menu .menu-inner .menu-item .menu-link .menu-icon { font-size: 1.15rem; }

        /* Navbar Premium */
        .layout-navbar { backdrop-filter: blur(10px); }
        .navbar-pharmacy { box-shadow: 0 4px 20px rgba(105,108,255,0.15) !important; }

        /* Dropdown Premium */
        .dropdown-menu { border: 0; box-shadow: 0 8px 32px rgba(0,0,0,0.12); border-radius: 12px; padding: 0.5rem; }
        .dropdown-item { border-radius: 8px; padding: 0.625rem 1rem; font-weight: 500; transition: all 0.15s ease; }
        .dropdown-item:hover { background: rgba(105,108,255,0.08); color: var(--pharmacy-primary); transform: translateX(2px); }

        /* Input Premium */
        .form-control, .form-select { border: 1px solid #e4e7ec; border-radius: 8px; padding: 0.625rem 1rem; transition: all 0.2s ease; }
        .form-control:focus, .form-select:focus { border-color: var(--pharmacy-primary); box-shadow: 0 0 0 3px rgba(105,108,255,0.12); }

        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in-up { animation: fadeInUp 0.5s ease-out; }

        /* Loading States */
        .skeleton { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: loading 1.5s infinite; border-radius: 8px; }
        @keyframes loading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

        /* Scrollbar Premium */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f3f5; }
        ::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a0aec0; }

        /* Brand logo container for better contrast */
        .brand-logo-box {
            background: #fff;
            border-radius: 10px;
            padding: 6px 10px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .brand-logo-box img { display: block; height: 28px; width: auto; }
    </style>

    <!-- Helpers -->
    <script src="/assets/vendor/js/helpers.js"></script>
    <script src="/assets/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <?php /* Ensure $page is defined to avoid undefined variable notices in menu state */ ?>
        @php($page = $page ?? '')
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="/dashboard-pharmacy" class="app-brand-link">
                        <span class="app-brand-logo demo brand-logo-box">
                            <img src="/logo.png" alt="MyVitalz Doctor Portal" />
                        </span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Vitals Monitoring -->
                    <li class="menu-item {{ in_array($page, ['monitoring', 'patient-vitals']) ? 'active' : '' }}">
                        <a href="/dashboard-pharmacy?pg=monitoring" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-pulse"></i>
                            <div data-i18n="Vitals Monitoring">Vitals Monitoring</div>
                        </a>
                    </li>

                    <!-- Pharmacy Dashboard -->
                    <li class="menu-item {{ $page == 'home' ? 'active' : '' }}">
                        <a href="/dashboard-pharmacy?pg=home" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-clinic"></i>
                            <div data-i18n="Pharmacy">Pharmacy</div>
                        </a>
                    </li>

                    <!-- Appointments -->
                    <li class="menu-item {{ $page == 'appointments' ? 'active' : '' }}">
                        <a href="/dashboard-pharmacy?pg=appointments" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-calendar"></i>
                            <div data-i18n="Appointments">Appointments</div>
                        </a>
                    </li>

                    <!-- E-Prescriptions -->
                    <li class="menu-item {{ $page == 'prescriptions' ? 'active' : '' }}">
                        <a href="/dashboard-pharmacy?pg=prescriptions" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-receipt"></i>
                            <div data-i18n="E-Prescriptions">E-Prescriptions</div>
                        </a>
                    </li>

                    <!-- Inventory -->
                    <li class="menu-item {{ $page == 'inventory' ? 'active' : '' }}">
                        <a href="/dashboard-pharmacy?pg=inventory" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-package"></i>
                            <div data-i18n="Inventory">Inventory</div>
                        </a>
                    </li>

                    <!-- Marketplace -->
                    <li class="menu-item {{ $page == 'marketplace' ? 'active' : '' }}">
                        <a href="/dashboard-pharmacy?pg=marketplace" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-store"></i>
                            <div data-i18n="Marketplace">Marketplace</div>
                        </a>
                    </li>

                    <!-- My Storefront -->
                    <li class="menu-item {{ in_array($page, ['storefront', 'storefront-settings']) ? 'active' : '' }}">
                        <a href="/dashboard-pharmacy?pg=storefront" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
                            <div data-i18n="My Storefront">My Storefront</div>
                        </a>
                    </li>

                    <!-- Network & Affiliates -->
                    <li class="menu-item {{ in_array($page, ['network', 'affiliates']) ? 'active' : '' }}">
                        <a href="/dashboard-pharmacy?pg=network" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-network-chart"></i>
                            <div data-i18n="Network">Network</div>
                        </a>
                    </li>

                    <!-- Affiliates -->
                    <li class="menu-item {{ $page == 'affiliates' ? 'active' : '' }}" style="padding-left: 1rem;">
                        <a href="/dashboard-pharmacy?pg=affiliates" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-group"></i>
                            <div data-i18n="Affiliates">Affiliates</div>
                        </a>
                    </li>

                    <!-- Profile -->
                    <li class="menu-item {{ $page == 'profile' ? 'active' : '' }}">
                        <a href="/dashboard-pharmacy?pg=profile" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-user"></i>
                            <div data-i18n="Profile">Profile</div>
                        </a>
                    </li>

                    <!-- Doctor Rewards -->
                    <li class="menu-item {{ $page == 'rewards' ? 'active' : '' }}">
                        <a href="/dashboard-pharmacy?pg=rewards" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-gift"></i>
                            <div data-i18n="Doctor Rewards">Doctor Rewards</div>
                        </a>
                    </li>

                    <!-- Messages -->
                    <li class="menu-item {{ $page == 'messages' ? 'active' : '' }}">
                        <a href="/dashboard-pharmacy?pg=messages" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-message-rounded-dots"></i>
                            <div data-i18n="Messages">Messages</div>
                        </a>
                    </li>

                    <!-- Logout -->
                    <li class="menu-item">
                        <a href="/logout" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-power-off"></i>
                            <div data-i18n="Logout">Logout</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme navbar-pharmacy" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Notifications -->
                            <li class="nav-item navbar-dropdown dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="bx bx-bell bx-sm"></i>
                                    @if(count($notifications ?? []) > 0)
                                    <span class="badge bg-danger rounded-pill badge-notifications">{{ count($notifications) }}</span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end py-0">
                                    <li class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h5 class="text-body mb-0 me-auto">Notifications</h5>
                                            <a href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read"><i class="bx fs-4 bx-envelope-open"></i></a>
                                        </div>
                                    </li>
                                    <li class="dropdown-notifications-list scrollable-container">
                                        <ul class="list-group list-group-flush">
                                            @forelse($notifications ?? [] as $notif)
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-bell"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $notif->title ?? 'Notification' }}</h6>
                                                        <p class="mb-0">{{ $notif->message ?? '' }}</p>
                                                        <small class="text-muted">{{ $notif->date ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </li>
                                            @empty
                                            <li class="list-group-item text-center py-3">
                                                <small class="text-muted">No new notifications</small>
                                            </li>
                                            @endforelse
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="https://via.placeholder.com/40/5a5fc7/ffffff?text=P" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="https://via.placeholder.com/40/5a5fc7/ffffff?text=P" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">{{ $user->name ?? 'Doctor' }}</span>
                                                    <small class="text-muted">Doctor</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/dashboard-pharmacy?pg=settings">
                                            <i class="bx bx-cog me-2"></i>
                                            <span class="align-middle">Settings</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/logout">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                © {{ date('Y') }} MyVitalz Pharmacy. All rights reserved.
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="/assets/vendor/libs/popper/popper.js"></script>
    <script src="/assets/vendor/js/bootstrap.js"></script>
    <script src="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="/assets/vendor/js/menu.js"></script>

    <!-- Main JS -->
    <script src="/assets/js/main.js"></script>

    @yield('scripts')
</body>
</html>
