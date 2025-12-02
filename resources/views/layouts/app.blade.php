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
    :root { 
      --pharmacy-primary: #696cff !important; 
      --pharmacy-secondary: #5f61e6 !important;
      --bs-primary: #696cff !important;
    }
    
    /* Gradient sidebar - EXACT MATCH TO DOCTOR DASHBOARD */
    .layout-menu.menu-vertical.menu.bg-menu-theme { 
      background: linear-gradient(180deg, #696cff 0%, #5f61e6 100%) !important; 
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
    
    /* Gradient navbar - EXACT MATCH TO DOCTOR DASHBOARD */
    .layout-navbar,
    .navbar-pharmacy,
    nav.layout-navbar {
      background: linear-gradient(135deg, #696cff 0%, #5f61e6 100%) !important;
      box-shadow: 0 4px 20px rgba(105,108,255,0.15) !important;
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
    
    /* Primary button colors */
    .btn-primary {
      background-color: #696cff !important;
      border-color: #696cff !important;
    }
    .btn-primary:hover, .btn-primary:focus {
      background-color: #5f61e6 !important;
      border-color: #5f61e6 !important;
    }
    .btn-outline-primary {
      color: #696cff !important;
      border-color: #696cff !important;
    }
    .btn-outline-primary:hover {
      background-color: #696cff !important;
      color: #fff !important;
    }
    
    /* Text and badge colors */
    .text-primary { color: #696cff !important; }
    .bg-primary { background-color: #696cff !important; }
    .badge.bg-primary { background-color: #696cff !important; }
    a { color: #696cff; }
    a:hover { color: #5f61e6; }
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
