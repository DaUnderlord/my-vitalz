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

    <title>MyVitalz - Dashboard</title>

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
            --pharmacy-primary: #5155bc;
            --pharmacy-secondary: #4448a0;
        }
        
        /* Theme alignment - Brand Color: #5155bc */
        :root{ --bs-primary: #5155bc; }
        .btn-primary{ background-color:#5155bc; border-color:#5155bc; }
        .btn-outline-primary{ color:#5155bc; border-color:#5155bc; }
        .btn-outline-primary:hover{ background-color:#5155bc; color:#fff; }
        .text-primary{ color:#5155bc !important; }
        .bg-primary{ background-color:#5155bc !important; }
        .badge.bg-primary{ background-color:#5155bc !important; }
        .menu .menu-item.active > .menu-link{ background: rgba(81,85,188,0.08); color:#5155bc; }
        .menu .menu-item.active .menu-icon{ color:#5155bc; }
        a{ color:#5155bc; }
        a:hover{ color:#4448a0; }

        /* Demo-inspired gradient navbar */
        .navbar-pharmacy {
            background: linear-gradient(135deg, var(--pharmacy-primary) 0%, var(--pharmacy-secondary) 100%) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .navbar-pharmacy .nav-link, .navbar-pharmacy .navbar-brand { color: #fff !important; }

        /* Gradient sidebar */
        .layout-menu.menu-vertical.menu.bg-menu-theme {
            background: linear-gradient(180deg, var(--pharmacy-primary) 0%, var(--pharmacy-secondary) 100%) !important;
        }
        /* Remove white fade shadow at top of sidebar */
        .menu-inner-shadow { display: none !important; }
        .layout-menu { background-repeat: no-repeat; background-size: cover; }
        .layout-menu .app-brand { background: transparent !important; }
        .layout-menu .menu-inner .menu-item .menu-link, .layout-menu .menu-inner .menu-header {
            color: rgba(255,255,255,0.85) !important;
        }
        .layout-menu .menu-inner .menu-item.active > .menu-link,
        .layout-menu .menu-inner .menu-item .menu-link:hover {
            background-color: rgba(255,255,255,0.18) !important;
            color: #fff !important;
        }

        /* CTA harmonization */
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
        .table-hover tbody tr:hover { background-color: rgba(81,85,188,0.06); }
        .table-striped > tbody > tr:nth-of-type(odd) > * { background-color: rgba(0,0,0,.015); }

        /* Empty state utility */
        .empty-state { text-align: center; padding: 2rem 1rem; color: #98a2b3; }
        .empty-state .icon { width: 48px; height: 48px; border-radius: 50%; background: rgba(81,85,188,0.12); color: var(--pharmacy-primary); display: inline-flex; align-items: center; justify-content: center; margin-bottom: .75rem; }
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
        .card:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(81,85,188,0.12) !important; }
        .card-header { background: transparent; padding: 1.5rem; font-weight: 600; }
        .card-body { padding: 1.5rem; }

        /* Stat Cards Premium */
        .stat-card { position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: linear-gradient(135deg, rgba(81,85,188,0.08), transparent); border-radius: 50%; transform: translate(30%, -30%); }
        .stat-card .content-left span { font-size: 0.8125rem; font-weight: 500; color: #667085; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-card .content-left h4 { font-size: 2rem; font-weight: 700; color: #101828; }
        .stat-card .avatar-initial { width: 56px; height: 56px; font-size: 1.5rem; }

        /* Alert Premium */
        .alert { border: 0; border-radius: 12px; padding: 1rem 1.25rem; }
        .alert-primary { background: linear-gradient(135deg, rgba(81,85,188,0.12), rgba(81,85,188,0.08)); color: var(--pharmacy-primary); border-left: 4px solid var(--pharmacy-primary); }

        /* Button Enhancements */
        .btn { font-weight: 500; letter-spacing: 0.02em; padding: 0.625rem 1.25rem; transition: all 0.2s ease; }
        .btn-primary, .btn-pharmacy { box-shadow: 0 2px 8px rgba(81,85,188,0.24); }
        .btn-primary:hover, .btn-pharmacy:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(81,85,188,0.32); }
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
        .layout-menu .menu-inner .menu-item .menu-link { padding: 0.75rem 1.25rem; margin: 0.25rem 0.75rem; border-radius: 10px; font-weight: 500; transition: all 0.2s ease; }
        .layout-menu .menu-inner .menu-item .menu-link:hover { transform: translateX(4px); }
        .layout-menu .menu-inner .menu-item.active > .menu-link { font-weight: 600; box-shadow: 0 4px 12px rgba(255,255,255,0.15); }

        /* Navbar Premium */
        .layout-navbar { backdrop-filter: blur(10px); }
        .navbar-pharmacy { box-shadow: 0 4px 20px rgba(81,85,188,0.15) !important; }

        /* Dropdown Premium */
        .dropdown-menu { border: 0; box-shadow: 0 8px 32px rgba(0,0,0,0.12); border-radius: 12px; padding: 0.5rem; }
        .dropdown-item { border-radius: 8px; padding: 0.625rem 1rem; font-weight: 500; transition: all 0.15s ease; }
        .dropdown-item:hover { background: rgba(81,85,188,0.08); color: var(--pharmacy-primary); transform: translateX(2px); }

        /* Input Premium */
        .form-control, .form-select { border: 1px solid #e4e7ec; border-radius: 8px; padding: 0.625rem 1rem; transition: all 0.2s ease; }
        .form-control:focus, .form-select:focus { border-color: var(--pharmacy-primary); box-shadow: 0 0 0 3px rgba(81,85,188,0.12); }

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

        .txt-color-pink{
            color:#FF618F; 
        }
        
        .bg-color-pink{
            background-color:#FF618F; 
        }
        
        .txt-color-purple{
            color:#A64FFE; 
        }
        
        .bg-color-purple{
            background-color:#A64FFE; 
        }
        
        .txt-color-purple-light{
            color:#E052DA; 
        }
        
        .bg-color-purple-light{
            background-color:#E052DA; 
        }
        
        .txt-color-blue{
            color:#2C68BF; 
        }
        
        .bg-color-blue{
            background-color:#2C68BF; 
        }
        
        .txt-color-blue-light{
            color:#3C8AFF; 
        }
        
        .bg-color-blue-light{
            background-color:#3C8AFF; 
        }
        
        .txt-color-red{
            color:#FF6161; 
        }
        
        .bg-color-red{
            background-color:#FF6161; 
        }
        
        .txt-color-torquoise{
            color:#1C9A9A; 
        }
        
        .bg-color-torquoise{
            background-color:#1C9A9A; 
        }
        
        .txt-color-green{
            color:#4BA33D; 
        }
        
        .bg-color-green{
            background-color:#4BA33D; 
        }
        
        
        .txt-color-green-light{
            color:rgba(45, 176, 66, 0.15); 
        }
        
        .bg-color-green-light{
            background-color:rgba(45, 176, 66, 0.15); 
        }
        
        .txt-color-orange{
            color:#FF8E3C; 
        }
        
        .bg-color-orange{
            background-color:#FF8E3C; 
        }
        
        .txt-color-orange-light{
            color:rgba(255, 161, 53, 0.15); 
        }
        
        .bg-color-orange-light{
            background-color:rgba(255, 161, 53, 0.15); 
        }
        
    </style>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="/dashboard" class="app-brand-link">
              <span class="app-brand-logo demo brand-logo-box">
                <img src="/logo.png" alt="MyVitalz"/>
              </span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-5">
            <!-- Dashboard -->
            <li class="menu-item <?php if(@$_GET['pg']=='home' || !isset($_GET['pg'])){ echo 'active';} ?>">
              <a href="?pg=home" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div data-i18n="Analytics">Dashboard</div>
              </a>
            </li>
            <li class="menu-item <?php if(@$_GET['pg']=='communities'){ echo 'active';} ?>">
              <a href="?pg=communities" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div data-i18n="Analytics">Communities</div>
              </a>
            </li>
            
              <li class="menu-item <?php if(@$_GET['pg']=='appointments'){ echo 'active';} ?>">
              <a href="?pg=appointments" class="menu-link">
                <i class="menu-icon tf-icons bx bx-notepad"></i>
                <div data-i18n="Analytics">Appointments</div>
              </a>
            </li>
              <li class="menu-item <?php if(@$_GET['pg']=='marketplace'){ echo 'active';} ?>">
              <a href="?pg=marketplace" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div data-i18n="Analytics">Marketplace</div>
              </a>
            </li>
            <li class="menu-item <?php if(@$_GET['pg']=='storefront' || @$_GET['pg']=='storefront-settings'){ echo 'active';} ?>">
              <a href="?pg=storefront" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
                <div data-i18n="Analytics">My Storefront</div>
              </a>
            </li>
            <li class="menu-item <?php if(@$_GET['pg']=='store'){ echo 'active';} ?>">
              <a href="?pg=store" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div data-i18n="Analytics">Store</div>
              </a>
            </li>
            <li class="menu-item <?php if(@$_GET['pg']=='referrals'){ echo 'active';} ?>">
              <a href="?pg=referrals" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-plus"></i>
                <div data-i18n="Analytics">Referrals</div>
              </a>
            </li>  
              
              <li class="menu-item <?php if(@$_GET['pg']=='affiliates'){ echo 'active';} ?>">
              <a href="?pg=affiliates" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div data-i18n="Analytics">Affiliates</div>
              </a>
            </li> 
              <li class="menu-item <?php if(@$_GET['pg']=='support'){ echo 'active';} ?>">
              <a href="?pg=support" class="menu-link">
                <i class="menu-icon tf-icons bx bx-headphone"></i>
                <div data-i18n="Analytics">Support</div>
              </a>
            </li>

            
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme navbar-pharmacy"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
             
              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Virtual Pharmacy quick access for doctors -->
                <li class="nav-item me-3">
                  <a href="/doctor/virtual-pharmacy" class="btn btn-sm btn-primary d-flex align-items-center">
                    <i class="bx bx-capsule me-2"></i>
                    Virtual Pharmacy
                  </a>
                </li>
                <!-- Place this tag where you want the button to render. -->
                  <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
              <i class="bx bx-bell bx-sm"></i>
                 <?php
                    if(!empty($notifications)){
                        ?>
              <span class="badge bg-danger rounded-pill badge-notifications"><?php echo count($notifications); ?></span>
                <?php
                    }
                ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end py-0">
              <li class="dropdown-menu-header border-bottom">
                <div class="dropdown-header d-flex align-items-center py-3">
                  <h5 class="text-body mb-0 me-auto">Notification</h5>
                  <a href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Mark all as read" data-bs-original-title="Mark all as read"><i class="bx fs-4 bx-envelope-open"></i></a>
                </div>
              </li>
              <li class="dropdown-notifications-list scrollable-container ps">
                <ul class="list-group list-group-flush">
                    <?php
                    if(!empty($notifications)){
                        for($i=count($notifications)-1; $i>=0; $i--){
                        ?>
                  <li class="list-group-item list-group-item-action dropdown-notifications-item">
                       <a href="<?php echo $notifications[$i]->link; ?>" onclick="seen_notification('<?php echo $notifications[$i]->id; ?>')">
                    <div class="d-flex">
                      <div class="flex-grow-1">
                        <small class="mb-0"><?php echo $notifications[$i]->description; ?></small><br>
                        <small class="text-muted"><?php echo \App\functions::format_date_time($notifications[$i]->date); ?></small>
                      </div>
                      <div class="flex-shrink-0 dropdown-notifications-actions">
                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="bx bx-x"></span></a>
                      </div>
                    </div>
                      </a>
                  </li>
                    <?php
                        }
                    }else{
                            ?>
                 <li class="list-group-item list-group-item-action dropdown-notifications-item">
                    <div class="d-flex">
                     
                      <div class="flex-grow-1">
                        <small class="mb-0">No notifications</small><br>
<!--                        <small class="text-muted">1h ago</small>-->
                      </div>
                      <div class="flex-shrink-0 dropdown-notifications-actions">
                        
                      </div>
                    </div>
                  </li>
                    <?php
                    }
                    ?>
                
                </ul>
                </li>
            </ul>
          </li>
                <li class="nav-item xl-1 me-3">
                 Hi, <?php echo $user[0]->first_name; ?>!
                </li>

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar ">
                      <img src="../assets/<?php if($user[0]->photo!=""){echo "images/".$user[0]->photo;}else{ echo 'img/avatars/user.png'; } ?>" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                   
                   
                    <li>
                      <a class="dropdown-item" href="?pg=profile">
                        <i class="bx bx-edit me-2"></i>
                        <span class="align-middle">Edit Profile</span>
                      </a>
                    </li> 
                      <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-lock me-2"></i>
                        <span class="align-middle">Change Password</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-lock me-2"></i>
                        <span class="align-middle">Change Transaction Pin</span>
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
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

          <?php @include(app_path().'/'.$pagename.'.php'); ?>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  ©
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  MyVitalz
                </div>
                <div>
                  
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
        
        
             <!-- Modal -->
                        <div class="modal fade" id="AppointmentScheduleModal" data-bs-backdrop="static" tabindex="-1">
                          <div class="modal-dialog">
                            <form class="modal-content" method="post">
                        <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                              <div class="modal-header">
                                <h5 class="modal-title" id="backDropModalTitle">Set your appointment schedule</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"
                                ></button>
                              </div>
                              <div class="modal-body">
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Mondays</label>
                                      <div class="row">
                                      <div class="col-sm-6">
                                          <small>Start Time</small>
                                   <input
                                      type="time"
                                      name="monday_start"
                                      class="form-control"
                                      placeholder="Start time"
                                           <?php if(!empty($appointment_schedule)){
                                            ?>
                                          value= "<?php echo $appointment_schedule[0]->monday_start; ?>"
                                          <?php
                                }
                               ?>
                                    />
                                  </div>
                                      <div class="col-sm-6">
                                          <small>End Time</small>
                                   <input
                                      type="time"
                                      name="monday_end"
                                      class="form-control"
                                      placeholder="End time"
                                             <?php if(!empty($appointment_schedule)){
                                            ?>
                                          value= "<?php echo $appointment_schedule[0]->monday_end; ?>"
                                          <?php
                                }
                               ?>
                                    />
                                  </div>
                                  </div>
                                  </div>
                                </div> 
                                 <div class="row">
                                     <hr>
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Tuesdays</label>
                                      <div class="row">
                                      <div class="col-sm-6">
                                          <small>Start Time</small>
                                   <input
                                      type="time"
                                      name="tuesday_start"
                                      class="form-control"
                                      placeholder="Start time"
                                             <?php if(!empty($appointment_schedule)){
                                            ?>
                                          value= "<?php echo $appointment_schedule[0]->tuesday_start; ?>"
                                          <?php
                                }
                               ?>
                                    />
                                  </div>
                                      <div class="col-sm-6">
                                          <small>End Time</small>
                                   <input
                                      type="time"
                                      name="tuesday_end"
                                      class="form-control"
                                      placeholder="End time"
                                             <?php if(!empty($appointment_schedule)){
                                            ?>
                                          value= "<?php echo $appointment_schedule[0]->tuesday_end; ?>"
                                          <?php
                                }
                               ?>
                                    />
                                  </div>
                                  </div>
                                  </div>
                                </div> 
                                 <div class="row">
                                     <hr>
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Wednesdays</label>
                                      <div class="row">
                                      <div class="col-sm-6">
                                          <small>Start Time</small>
                                   <input
                                      type="time"
                                      name="wednesday_start"
                                      class="form-control"
                                      placeholder="Start time"
                                             <?php if(!empty($appointment_schedule)){
                                            ?>
                                          value= "<?php echo $appointment_schedule[0]->wednesday_start; ?>"
                                          <?php
                                }
                               ?>
                                    />
                                  </div>
                                      <div class="col-sm-6">
                                          <small>End Time</small>
                                   <input
                                      type="time"
                                      name="wednesday_end"
                                      class="form-control"
                                      placeholder="End time"
                                             <?php if(!empty($appointment_schedule)){
                                            ?>
                                          value= "<?php echo $appointment_schedule[0]->wednesday_end; ?>"
                                          <?php
                                }
                               ?>
                                    />
                                  </div>
                                  </div>
                                  </div>
                                </div> 
                                 <div class="row">
                                     <hr>
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Thursdays</label>
                                      <div class="row">
                                      <div class="col-sm-6">
                                          <small>Start Time</small>
                                   <input
                                      type="time"
                                      name="thursday_start"
                                      class="form-control"
                                      placeholder="Start time"
                                             <?php if(!empty($appointment_schedule)){
                                            ?>
                                          value= "<?php echo $appointment_schedule[0]->thursday_start; ?>"
                                          <?php
                                }
                               ?>
                                    />
                                  </div>
                                      <div class="col-sm-6">
                                          <small>End Time</small>
                                   <input
                                      type="time"
                                      name="thursday_end"
                                      class="form-control"
                                      placeholder="End time"
                                             <?php if(!empty($appointment_schedule)){
                                            ?>
                                          value= "<?php echo $appointment_schedule[0]->thursday_end; ?>"
                                          <?php
                                }
                               ?>
                                    />
                                  </div>
                                  </div>
                                  </div>
                                </div> 
                                 <div class="row">
                                     <hr>
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Fridays</label>
                                      <div class="row">
                                      <div class="col-sm-6">
                                          <small>Start Time</small>
                                   <input
                                      type="time"
                                      name="friday_start"
                                      class="form-control"
                                      placeholder="Start time"
                                             <?php if(!empty($appointment_schedule)){
                                            ?>
                                          value= "<?php echo $appointment_schedule[0]->friday_start; ?>"
                                          <?php
                                }
                               ?>
                                    />
                                  </div>
                                      <div class="col-sm-6">
                                          <small>End Time</small>
                                   <input
                                      type="time"
                                      name="friday_end"
                                      class="form-control"
                                      placeholder="End time"
                                          <?php if(!empty($appointment_schedule)){
                                            ?>
                                          value= "<?php echo $appointment_schedule[0]->friday_end; ?>"
                                          <?php
                                }
                               ?>   
                                    />
                                  </div>
                                  </div>
                                  </div>
                                </div> 
                                 <div class="row">
                                     <hr>
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Saturdays</label>
                                      <div class="row">
                                      <div class="col-sm-6">
                                          <small>Start Time</small>
                                   <input
                                      type="time"
                                      name="saturday_start"
                                      class="form-control"
                                      placeholder="Start time"
                                             <?php if(!empty($appointment_schedule)){
                                            ?>
                                          value= "<?php echo $appointment_schedule[0]->saturday_start; ?>"
                                          <?php
                                }
                               ?>
                                    />
                                  </div>
                                      <div class="col-sm-6">
                                          <small>End Time</small>
                                   <input
                                      type="time"
                                      name="saturday_end"
                                      class="form-control"
                                      placeholder="End time"
                                           <?php if(!empty($appointment_schedule)){
                                            ?>
                                          value= "<?php echo $appointment_schedule[0]->saturday_end; ?>"
                                          <?php
                                }
                               ?>  
                                    />
                                  </div>
                                  </div>
                                  </div>
                                </div> 
                                 <div class="row">
                                     <hr>
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Sundays</label>
                                      <div class="row">
                                      <div class="col-sm-6">
                                          <small>Start Time</small>
                                   <input
                                      type="time"
                                      name="sunday_start"
                                      class="form-control"
                                      placeholder="Start time"
                                          <?php if(!empty($appointment_schedule)){
                                            ?>
                                          value= "<?php echo $appointment_schedule[0]->sunday_start; ?>"
                                          <?php
                                }
                               ?>   
                                    />
                                  </div>
                                      <div class="col-sm-6">
                                          <small>End Time</small>
                                   <input
                                      type="time"
                                      name="sunday_end"
                                      class="form-control"
                                      placeholder="End time"
                                          <?php if(!empty($appointment_schedule)){
                                            ?>
                                          value= "<?php echo $appointment_schedule[0]->sunday_end; ?>"
                                          <?php
                                }
                               ?>   
                                    />
                                  </div>
                                  </div>
                                  </div>
                                </div> 
                               
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                  Close
                                </button>
                                <button type="submit" class="btn btn-primary">Save</button>
                              </div>
                            </form>
                          </div>
                        </div>
        
        <div class="modal fade" id="AddProductModal" data-bs-backdrop="static" tabindex="-1">
                          <div class="modal-dialog">
                            <form class="modal-content" method="post" enctype="multipart/form-data">
                        <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                              <div class="modal-header">
                                <h5 class="modal-title" id="backDropModalTitle">New Product</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"
                                ></button>
                              </div>
                              <div class="modal-body">
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Product Name</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <input
                                      type="text"
                                      name="product_name"
                                      class="form-control"
                                      placeholder="Product Name"
                                          required
                                    />
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div> 
                                  
                                  <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Category</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <input
                                      type="text"
                                      name="product_category"
                                      class="form-control"
                                      placeholder="Category"
                                          required
                                    />
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div> 
                                 
                                  <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Price</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <input
                                      type="number"
                                      name="product_price"
                                      class="form-control"
                                      placeholder="Price"
                                          required
                                    />
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div> 
                                 
                                  <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Description</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <textarea
                                      name="product_description"
                                      class="form-control"
                                      placeholder="Description"
                                             required
                                             ></textarea>
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div> 
                                  
                                  
                                  <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Product Image</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <input
                                      type="file"
                                      name="product_image"
                                      class="form-control"
                                          required
                                    />
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div> 
                                 
                                 
                               
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                  Close
                                </button>
                                <button type="submit" class="btn btn-primary">Save</button>
                              </div>
                            </form>
                          </div>
                        </div>
        
        
        <div class="modal fade" id="ComplianceModal" data-bs-backdrop="static" tabindex="-1">
                          <div class="modal-dialog">
                              <div class="modal-content ">
                              <div class="modal-header">
                                <h5 class="modal-title" id="backDropModalTitle">Medication Compliance</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"
                                ></button>
                              </div>
                              <div class="modal-body">
                              <div class="col-sm-12 table-responsive" id="compliance"></div>
                            </div>
                        </div>
                        </div>
                        </div>
        
        
        <div class="modal fade" id="AddStoreProductModal" data-bs-backdrop="static" tabindex="-1">
                          <div class="modal-dialog">
                            <form class="modal-content" method="post" enctype="multipart/form-data">
                        <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                        <input type = "hidden" name = "product_ref" id="product_ref">
                              <div class="modal-header">
                                <h5 class="modal-title" id="backDropModalTitle">Select Product from Store</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"
                                ></button>
                              </div>
                                
                              <div class="modal-body">
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Search Product</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <input
                                      type="text"
                                      name="product_search"
                                      id="product_search"
                                      class="form-control"
                                      placeholder="Product Name"
                                          required onkeyup="suggest_products()"
                                    />
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div>  
                                                                  <div class="row">
                                                                      <div class="col-sm-12" id="search_list"></div>
                                                                      
                                  </div>
                                  <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Product Selected</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <input
                                      type="text"
                                      name="product_selected"
                                      id="product_selected"
                                      class="form-control"
                                      placeholder="Product Selected"
                                          disabled
                                          required
                                    />
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div> 
                                  
                                 
                                  <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Price</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <input
                                      type="number"
                                      name="product_price"
                                      id="product_price"
                                      class="form-control"
                                      placeholder="Price"
                                          required
                                    />
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div> 
                                 
                               
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                  Close
                                </button>
                                <button type="submit" class="btn btn-primary">Save</button>
                              </div>
                            </form>
                          </div>
                        </div>
        
       
        <div class="modal fade" id="AddSupportTicket" data-bs-backdrop="static" tabindex="-1">
                          <div class="modal-dialog">
                            <form class="modal-content" method="post" enctype="multipart/form-data">
                        <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                              <div class="modal-header">
                                <h5 class="modal-title" id="backDropModalTitle">New Support Ticket</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"
                                ></button>
                              </div>
                              <div class="modal-body">
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Subject</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <input
                                      type="text"
                                      name="support_subject"
                                      class="form-control"
                                      placeholder="Enter a Subject"
                                          required
                                    />
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div> 
                                  
                                  <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Priority</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <select
                                      name="support_priority"
                                      class="form-select"
                                      placeholder="Priority"
                                          required
                                           >
                                          <option value="">Choose Priority</option>
                                          <option value="Low">Low</option>
                                          <option value="Medium">Medium</option>
                                          <option value="High">High</option>
                                          </select>
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div> 
                                 
                                  <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameBackdrop" class="form-label">Description</label>
                                      <div class="row">
                                      <div class="form-group">
                                   <textarea
                                      name="support_description"
                                      class="form-control"
                                      placeholder="Description"
                                             required
                                             ></textarea>
                                  </div>
                                   
                                  </div>
                                  </div>
                                </div> 
                                  
                                 
                               
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                  Close
                                </button>
                                <button type="submit" class="btn btn-primary">Send</button>
                              </div>
                            </form>
                          </div>
                        </div>
        
         
         
             
                       
        
        <?php 
        if($a_type!=""){
            $atype="";
            $aicon="";
            if($a_type=="success"){
               $atype="bg-primary"; 
               $aicon="check"; 
               $atitle="Success"; 
            }else if($a_type=="warning"){
               $atype="bg-danger"; 
                $aicon="x";
                $atitle="Warning";
            }
            ?>
        <div class="bs-toast toast show toast-placement-ex m-2 fade <?php echo $atype; ?> top-0 start-50 translate-middle-x" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
                <div class="toast-header">
                  <i class="bx bx-<?php echo $aicon; ?> me-2"></i>
                  <div class="me-auto fw-semibold"><?php echo $atitle; ?></div>
                  <!--<small>11 mins ago</small>-->
                  <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body"><?php echo $a_message; ?></div>
              </div>
        <?php
        }
            ?>
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

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
     <!--  <script src="../assets/js/ui-modals.js"></script>-->
       <script src="../assets/js/ui-toasts.js"></script>


    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
      
      <script>
      function search_patient(){
           $('#sresults').html("Searching...");
          let pname =$('#pname').val();
          $.get('/search-patients', {pname: pname}, function(data){
				
            $('#sresults').html(data);
						
					});
      }
        
      function search_hospital(){
           $('#hresults').html("Searching...");
          let pname =$('#hname').val();
          $.get('/search-hospital', {pname: pname}, function(data){
				
            $('#hresults').html(data);
						
					});
      }
        
      function search_pharmacy(){
           $('#presults').html("Searching...");
          let pname =$('#pname').val();
          $.get('/search-pharmacy', {pname: pname}, function(data){
				
            $('#presults').html(data);
						
					});
      }
          
    function add_patient(pcode, btn_id){
           $('#'+btn_id).html("Request sent");
          $.get('/add-patients', {pcode: pcode}, function(data){
						
					});
      }
        
    function add_hospital(pcode, btn_id){
           $('#'+btn_id).html("Request sent");
          $.get('/add-hospital', {pcode: pcode}, function(data){
						
					});
      }
        
    function add_pharmacy(pcode, btn_id){
           $('#'+btn_id).html("Request sent");
          $.get('/add-pharmacy', {pcode: pcode}, function(data){
						
					});
      }
        
     
    function refer_to_doctor(ptid, pcode, btn_id){
           $('#'+btn_id).html("Request sent");
          $.get('/refer-to-doctor', {pcode: pcode, ptid: ptid}, function(data){
						
					});
      } 
       
          
    function appointmt_time(doc_ref, appointment_date){
           $('#appt_time').empty();
           $('#appt_time').append("<option value=''>Loading...</option>");
          $.get('/get-appointment-intervals', {doc_ref: doc_ref, appointment_date:appointment_date}, function(data){
              $('#appt_time').empty();
						$('#appt_time').append(data);
					});
      }
             
          
    function seen_notification(noti_id){
          $.get('/seen-notification', {not_id: noti_id}, function(data){
						
					});
      }
          
    function suggest_products(){
       $('#search_list').html("searching...");
        var product_search = $('#product_search').val();
          $.get('/search-product', {product_search: product_search}, function(data){
						 
						 $('#search_list').html(data);
					});
      } 
          
    function check_compliance(ptid, pscid){
       $('#compliance').html("Loading...");
          $.get('/check-compliance', {ptid: ptid, pscid: pscid}, function(data){
						 
						 $('#compliance').html(data);
					});
      } 
          
    function select_suggested_product(product_id, product_name){
        $('#product_ref').val(product_id); 
        $('#product_selected').val(product_name); 
        
      }
        
      function search_doctor(){
           $('#sresults').html("Searching...");
          let pname =$('#pname').val();
          $.get('/search-refer-doctors', {pname: pname}, function(data){
				
            $('#sresults').html(data);
						
					});
      }  
        
        
            function show_patient(){
              $("#search-patient").css("display", "block");
              $("#search-hospital").css("display", "none");
              $("#search-pharmacy").css("display", "none");
            }
            
            function show_hospital(){
              $("#search-patient").css("display", "none");
              $("#search-hospital").css("display", "block");
              $("#search-pharmacy").css("display", "none");
            }
            
            function show_pharmacy(){
              $("#search-patient").css("display", "none");
              $("#search-hospital").css("display", "none");
              $("#search-pharmacy").css("display", "block");
            }
            
            show_patient();
      </script>
  </body>
</html>
