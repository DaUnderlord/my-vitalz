<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>MyVitalz - Sales Rep Dashboard</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
      <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
  </head>
    
    <style>
        :root {
            --salesrep-primary: #ff6b35;
            --salesrep-secondary: #f7931e;
        }
        
        /* Sales Rep theme */
        :root{ --bs-primary: #ff6b35; }
        .btn-primary{ background-color:#ff6b35; border-color:#ff6b35; }
        .btn-outline-primary{ color:#ff6b35; border-color:#ff6b35; }
        .btn-outline-primary:hover{ background-color:#ff6b35; color:#fff; }
        .text-primary{ color:#ff6b35 !important; }
        .bg-primary{ background-color:#ff6b35 !important; }
        .badge.bg-primary{ background-color:#ff6b35 !important; }
        .menu .menu-item.active > .menu-link{ background: rgba(255,107,53,0.08); color:#ff6b35; }
        .menu .menu-item.active .menu-icon{ color:#ff6b35; }
        a{ color:#ff6b35; }
        a:hover{ color:#f7931e; }

        /* Gradient navbar */
        .navbar-salesrep {
            background: linear-gradient(135deg, var(--salesrep-primary) 0%, var(--salesrep-secondary) 100%) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .navbar-salesrep .nav-link, .navbar-salesrep .navbar-brand { color: #fff !important; }

        /* Gradient sidebar */
        .layout-menu.menu-vertical.menu.bg-menu-theme {
            background: linear-gradient(180deg, var(--salesrep-primary) 0%, var(--salesrep-secondary) 100%) !important;
        }
        .menu-inner-shadow { display: none !important; }
        .layout-menu { background-repeat: no-repeat; background-size: cover; }
        .layout-menu .app-brand { background: transparent !important; }
        .layout-menu .menu-item .menu-link { color: rgba(255,255,255,0.85) !important; }
        .layout-menu .menu-item .menu-link:hover { background: rgba(255,255,255,0.1) !important; color: #fff !important; }
        .layout-menu .menu-item.active .menu-link { background: rgba(255,255,255,0.2) !important; color: #fff !important; }
        .layout-menu .menu-item .menu-icon { color: rgba(255,255,255,0.85) !important; }
        .layout-menu .menu-item.active .menu-icon { color: #fff !important; }
        .layout-menu .menu-sub .menu-link { color: rgba(255,255,255,0.75) !important; }
        
        /* Brand logo container (match doctor dashboard) */
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

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="/dashboard-sales-rep" class="app-brand-link">
              <span class="app-brand-logo demo brand-logo-box">
                <img src="/logo.png" alt="MyVitalz" />
              </span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-5">
            <li class="menu-item <?php echo (!isset($_GET['pg']) || $_GET['pg']=='home') ? 'active' : ''; ?>">
              <a href="?pg=home" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div>Dashboard</div>
              </a>
            </li>
            <li class="menu-item <?php echo (isset($_GET['pg']) && $_GET['pg']=='products') ? 'active' : ''; ?>">
              <a href="?pg=products" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div>My Products</div>
              </a>
            </li>
            <li class="menu-item <?php echo (isset($_GET['pg']) && $_GET['pg']=='upload') ? 'active' : ''; ?>">
              <a href="?pg=upload" class="menu-link">
                <i class="menu-icon tf-icons bx bx-upload"></i>
                <div>Upload Product</div>
              </a>
            </li>
            <li class="menu-item <?php echo (isset($_GET['pg']) && $_GET['pg']=='orders') ? 'active' : ''; ?>">
              <a href="?pg=orders" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div>Orders</div>
              </a>
            </li>
            <li class="menu-item <?php echo (isset($_GET['pg']) && $_GET['pg']=='doctors') ? 'active' : ''; ?>">
              <a href="?pg=doctors" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-plus"></i>
                <div>Doctors Network</div>
              </a>
            </li>
            <li class="menu-item <?php echo (isset($_GET['pg']) && $_GET['pg']=='analytics') ? 'active' : ''; ?>">
              <a href="?pg=analytics" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                <div>Analytics</div>
              </a>
            </li>
            <li class="menu-item <?php echo (isset($_GET['pg']) && $_GET['pg']=='payout') ? 'active' : ''; ?>">
              <a href="?pg=payout" class="menu-link">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div>Payouts</div>
              </a>
            </li>
            <li class="menu-item <?php echo (isset($_GET['pg']) && $_GET['pg']=='profile') ? 'active' : ''; ?>">
              <a href="?pg=profile" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>Profile</div>
              </a>
            </li>
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme navbar-salesrep"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <i class="bx bx-search fs-4 lh-0 text-white"></i>
                  <input
                    type="text"
                    class="form-control border-0 shadow-none"
                    placeholder="Search products..."
                    aria-label="Search..."
                    style="background: rgba(255,255,255,0.2); color: #fff;"
                  />
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Notifications -->
                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="bx bx-bell bx-sm text-white"></i>
                    <?php if(count($notifications) > 0){ ?>
                    <span class="badge bg-danger rounded-pill badge-notifications"><?php echo count($notifications); ?></span>
                    <?php } ?>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-bell me-2"></i>
                        <span class="align-middle"><?php echo count($notifications); ?> New Notifications</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ Notifications -->

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <span class="avatar-initial rounded-circle bg-white text-primary"><?php echo strtoupper(substr($user[0]->first_name, 0, 1)); ?></span>
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <span class="avatar-initial rounded-circle bg-primary"><?php echo strtoupper(substr($user[0]->first_name, 0, 1)); ?></span>
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block"><?php echo $user[0]->first_name.' '.$user[0]->last_name; ?></span>
                            <small class="text-muted">Sales Representative</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="?pg=profile">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="/logout">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              
              <?php if($a_type != ""){ ?>
              <div class="alert alert-<?php echo $a_type; ?> alert-dismissible" role="alert">
                <?php echo $a_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <?php } ?>
              
              <?php include(app_path().'/'.$pagename.'.php'); ?>
              
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  ©
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  MyVitalz - Pharmaceutical Marketplace
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
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
