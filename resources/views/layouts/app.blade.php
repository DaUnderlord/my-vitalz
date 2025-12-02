<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" data-theme="theme-default" data-assets-path="/assets/" data-template="vertical-menu-template-free">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>@yield('title', 'MyVitalz')</title>
  <link rel="icon" type="image/x-icon" href="/assets/img/favicon/favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/assets/vendor/fonts/boxicons.css" />
  <link rel="stylesheet" href="/assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="/assets/css/demo.css" />
  <link rel="stylesheet" href="/assets/css/app-custom.css" />
  <link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <script src="/assets/vendor/js/helpers.js"></script>
  <script src="/assets/js/config.js"></script>
  <style>
    /* ============================================
       PATIENT DASHBOARD - EXACT MATCH TO DOCTOR DASHBOARD
       Brand Color: #5155bc
       ============================================ */
    :root { 
      --pharmacy-primary: #5155bc !important; 
      --pharmacy-secondary: #4448a0 !important;
      --bs-primary: #5155bc !important;
    }
    
    /* Gradient sidebar */
    .layout-menu.menu-vertical.menu.bg-menu-theme { 
      background: linear-gradient(180deg, #5155bc 0%, #4448a0 100%) !important; 
    }
    .menu-inner-shadow { display: none !important; }
    .layout-menu { background-repeat: no-repeat !important; background-size: cover !important; }
    .layout-menu .app-brand { background: transparent !important; }
    
    /* Brand logo */
    .app-brand .brand-logo-box { 
      background: #fff !important; 
      border-radius: 10px !important; 
      padding: 6px 10px !important; 
      box-shadow: 0 6px 18px rgba(0,0,0,.08) !important; 
      display: inline-flex !important; 
      align-items: center !important; 
    }
    .app-brand .brand-logo-box img { height: 28px !important; width: auto !important; }
    
    /* Menu items - white text on gradient */
    .layout-menu .menu-inner .menu-item .menu-link, 
    .layout-menu .menu-inner .menu-header { 
      color: rgba(255,255,255,0.85) !important; 
    }
    .layout-menu .menu-inner .menu-item .menu-link .menu-icon { 
      color: #fff !important; 
    }
    .layout-menu .menu-inner .menu-item.active > .menu-link,
    .layout-menu .menu-inner .menu-item .menu-link:hover { 
      background-color: rgba(255,255,255,0.18) !important; 
      color: #fff !important; 
    }
    
    /* Sidebar Menu Premium - EXACT MATCH TO DOCTOR */
    .layout-menu .menu-inner .menu-item .menu-link { 
      padding: 0.75rem 1.25rem; 
      margin: 0.25rem 0.75rem; 
      border-radius: 10px; 
      font-weight: 500; 
      transition: all 0.2s ease; 
    }
    .layout-menu .menu-inner .menu-item .menu-link:hover { 
      transform: translateX(4px); 
    }
    .layout-menu .menu-inner .menu-item.active > .menu-link { 
      font-weight: 600; 
      box-shadow: 0 4px 12px rgba(255,255,255,0.15); 
    }
    
    /* Gradient navbar */
    .layout-navbar,
    .navbar-pharmacy,
    nav.layout-navbar {
      background: linear-gradient(135deg, #5155bc 0%, #4448a0 100%) !important;
      box-shadow: 0 4px 20px rgba(81,85,188,0.15) !important;
      backdrop-filter: blur(10px);
    }
    .layout-navbar .nav-link, 
    .layout-navbar .navbar-brand,
    .navbar-pharmacy .nav-link, 
    .navbar-pharmacy .navbar-brand { 
      color: #fff !important; 
    }
    .layout-navbar .bx,
    .navbar-pharmacy .bx { 
      color: #fff !important; 
    }
    .layout-navbar .nav-item,
    .navbar-pharmacy .nav-item {
      color: #fff !important;
    }
    
    /* Navbar badges */
    .badge-notifications { box-shadow: 0 0 0 3px rgba(255,255,255,0.5); }
    
    /* Primary button colors */
    .btn-primary {
      background-color: #5155bc !important;
      border-color: #5155bc !important;
      box-shadow: 0 2px 8px rgba(81,85,188,0.24);
    }
    .btn-primary:hover, .btn-primary:focus {
      background-color: #4448a0 !important;
      border-color: #4448a0 !important;
      transform: translateY(-1px);
      box-shadow: 0 4px 16px rgba(81,85,188,0.32);
    }
    .btn-outline-primary {
      color: #5155bc !important;
      border-color: #5155bc !important;
    }
    .btn-outline-primary:hover {
      background-color: #5155bc !important;
      color: #fff !important;
    }
    
    /* Text and badge colors */
    .text-primary { color: #5155bc !important; }
    .bg-primary { background-color: #5155bc !important; }
    .badge.bg-primary { background-color: #5155bc !important; }
    a { color: #5155bc; }
    a:hover { color: #4448a0; }
    
    /* Card polish - EXACT MATCH TO DOCTOR */
    .card { 
      border: 1px solid rgba(0,0,0,0.04); 
      border-radius: 12px; 
      box-shadow: 0 1px 2px rgba(0,0,0,0.04); 
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
    }
    .card:hover { 
      transform: translateY(-2px); 
      box-shadow: 0 12px 40px rgba(81,85,188,0.12) !important; 
    }
    .card-header { border-bottom: 0; background: transparent; padding: 1.5rem; font-weight: 600; }
    .card-body { padding: 1.5rem; }
    
    /* Button Enhancements */
    .btn { font-weight: 500; letter-spacing: 0.02em; padding: 0.625rem 1.25rem; transition: all 0.2s ease; }
    .btn-sm { padding: 0.5rem 1rem; font-size: 0.875rem; }
    
    /* Badge Premium */
    .badge { font-weight: 500; padding: 0.375rem 0.75rem; border-radius: 6px; letter-spacing: 0.02em; }
    
    /* Dropdown Premium */
    .dropdown-menu { border: 0; box-shadow: 0 8px 32px rgba(0,0,0,0.12); border-radius: 12px; padding: 0.5rem; }
    .dropdown-item { border-radius: 8px; padding: 0.625rem 1rem; font-weight: 500; transition: all 0.15s ease; }
    .dropdown-item:hover { background: rgba(81,85,188,0.08); color: #5155bc; transform: translateX(2px); }
    
    /* Input Premium */
    .form-control, .form-select { border: 1px solid #e4e7ec; border-radius: 8px; padding: 0.625rem 1rem; transition: all 0.2s ease; }
    .form-control:focus, .form-select:focus { border-color: #5155bc; box-shadow: 0 0 0 3px rgba(81,85,188,0.12); }
    
    /* Table Premium */
    .table { font-size: 0.9375rem; }
    .table thead th { background: #f9fafb; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 1rem; border-top: 0; color: #5a6169; font-weight: 600; }
    .table tbody td { padding: 1rem; vertical-align: middle; }
    .table-hover tbody tr { transition: background-color 0.15s ease; }
    .table-hover tbody tr:hover { background-color: rgba(81,85,188,0.06); }
    
    /* Animations */
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .fade-in-up { animation: fadeInUp 0.5s ease-out; }
    
    /* Scrollbar Premium */
    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: #f1f3f5; }
    ::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #a0aec0; }
    
    /* Empty state utility */
    .empty-state { text-align: center; padding: 2rem 1rem; color: #98a2b3; }
    .empty-state .icon { width: 48px; height: 48px; border-radius: 50%; background: rgba(81,85,188,0.12); color: #5155bc; display: inline-flex; align-items: center; justify-content: center; margin-bottom: .75rem; }
    .empty-state .title { font-weight: 600; color: #475467; }
    .empty-state .hint { font-size: .9rem; }
  </style>
  @yield('head')
</head>
<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="/dashboard" class="app-brand-link">
            <span class="app-brand-logo demo brand-logo-box">
              <img src="/logo.png" alt="MyVitalz" />
            </span>
          </a>
          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
        </div>
        <div class="menu-inner-shadow"></div>
        @yield('sidebar')
      </aside>

      <div class="layout-page">
        <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center navbar-pharmacy" id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)"><i class="bx bx-menu bx-sm"></i></a>
          </div>
          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
              @yield('navbar-right')
            </ul>
          </div>
        </nav>

        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            @yield('content')
          </div>
          <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
              <div class="mb-2 mb-md-0">© <script>document.write(new Date().getFullYear());</script> MyVitalz</div>
              <div></div>
            </div>
          </footer>
          <div class="content-backdrop fade"></div>
        </div>
      </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>

  <script src="/assets/vendor/libs/jquery/jquery.js"></script>
  <script src="/assets/vendor/libs/popper/popper.js"></script>
  <script src="/assets/vendor/js/bootstrap.js"></script>
  <script src="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="/assets/vendor/js/menu.js"></script>
  <script src="/assets/js/main.js"></script>
  @yield('scripts')
</body>
</html>
