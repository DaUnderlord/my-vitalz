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
    <link rel="stylesheet" href="/assets/css/app-custom.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
      <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
       <script src="assets/vendor/libs/jquery/jquery.js"></script>
  </head>
    
    <style>
        :root {
            --patient-primary: #696cff;
            --patient-secondary: #5f61e6;
        }

        /* Theme alignment */
        :root{ --bs-primary: #696cff; }
        .btn-primary{ background-color:#696cff; border-color:#696cff; }
        .btn-outline-primary{ color:#696cff; border-color:#696cff; }
        .btn-outline-primary:hover{ background-color:#696cff; color:#fff; }
        .text-primary{ color:#696cff !important; }
        .bg-primary{ background-color:#696cff !important; }
        .badge.bg-primary{ background-color:#696cff !important; }
        a{ color:#696cff; }
        a:hover{ color:#5f61e6; }

        /* Gradient navbar */
        .navbar-pharmacy, .layout-navbar {
            background: linear-gradient(135deg, var(--patient-primary) 0%, var(--patient-secondary) 100%) !important;
            box-shadow: 0 4px 20px rgba(105,108,255,0.15) !important;
        }
        .navbar-pharmacy .nav-link, .navbar-pharmacy .navbar-brand, .layout-navbar .nav-link { color: #fff !important; }
        .layout-navbar .bx { color: #fff; }

        /* Premium gradient sidebar */
        .layout-menu.menu-vertical.menu.bg-menu-theme { background: linear-gradient(180deg, var(--patient-primary) 0%, var(--patient-secondary) 100%) !important; }
        .menu-inner-shadow { display: none !important; }
        .layout-menu { background-repeat: no-repeat; background-size: cover; }
        .layout-menu .app-brand { background: transparent !important; }
        .app-brand .brand-logo-box{ background:#fff; border-radius:10px; padding:6px 10px; box-shadow:0 6px 18px rgba(0,0,0,.08); display:inline-flex; align-items:center; gap:8px; }
        .app-brand .brand-logo-box img{ display:block; height:28px; width:auto; }
        
        /* Sidebar link polish */
        .layout-menu .menu-inner .menu-item .menu-link, .layout-menu .menu-inner .menu-header{ color:rgba(255,255,255,.85)!important; border-radius:10px; }
        .layout-menu .menu-inner .menu-item .menu-link .menu-icon{ color:#fff; }
        .layout-menu .menu-inner .menu-item.active > .menu-link, .layout-menu .menu-inner .menu-item .menu-link:hover{ background-color:rgba(255,255,255,.18)!important; color:#fff!important; }
        .layout-menu .menu-inner{ padding:12px; }
        .layout-menu .menu-inner .menu-item .menu-link { padding: 0.75rem 1.25rem; margin: 0.25rem 0; border-radius: 10px; font-weight: 500; transition: all 0.2s ease; }
        .layout-menu .menu-inner .menu-item .menu-link:hover { transform: translateX(4px); }
        .layout-menu .menu-inner .menu-item.active > .menu-link { font-weight: 600; box-shadow: 0 4px 12px rgba(255,255,255,0.15); }

        /* CTA harmonization */
        .btn-primary { background-color: var(--patient-primary) !important; border-color: var(--patient-primary) !important; }
        .btn-primary:hover, .btn-primary:focus { background-color: var(--patient-secondary) !important; border-color: var(--patient-secondary) !important; }
        .btn-outline-primary { color: var(--patient-primary) !important; border-color: var(--patient-primary) !important; }
        .btn-outline-primary:hover { background-color: var(--patient-primary) !important; color: #fff !important; }

        /* Card polish */
        .card { border: 1px solid rgba(0,0,0,0.04); border-radius: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .card:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(105,108,255,0.12) !important; }
        .card-header { border-bottom: 0; background: transparent; padding: 1.5rem; font-weight: 600; }
        .card-body { padding: 1.5rem; }
        .content-wrapper h4.page-title{ font-weight:700; letter-spacing:-0.02em; }

        /* Badge Premium */
        .badge { font-weight: 500; padding: 0.375rem 0.75rem; border-radius: 6px; }
        .badge-notifications { box-shadow: 0 0 0 3px rgba(255,255,255,0.5); }

        /* Table aesthetics */
        .table { font-size: 0.9375rem; }
        .table thead th { background: #f9fafb; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 1rem; }
        .table tbody td { padding: 1rem; vertical-align: middle; }
        .table-hover tbody tr:hover { background-color: rgba(105,108,255,0.06); }

        /* Button Enhancements */
        .btn { font-weight: 500; letter-spacing: 0.02em; padding: 0.625rem 1.25rem; transition: all 0.2s ease; }
        .btn-primary { box-shadow: 0 2px 8px rgba(105,108,255,0.24); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(105,108,255,0.32); }
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.875rem; }

        /* Input Premium */
        .form-control, .form-select { border: 1px solid #e4e7ec; border-radius: 8px; padding: 0.625rem 1rem; transition: all 0.2s ease; }
        .form-control:focus, .form-select:focus { border-color: var(--patient-primary); box-shadow: 0 0 0 3px rgba(105,108,255,0.12); }

        /* Dropdown Premium */
        .dropdown-menu { border: 0; box-shadow: 0 8px 32px rgba(0,0,0,0.12); border-radius: 12px; padding: 0.5rem; }
        .dropdown-item { border-radius: 8px; padding: 0.625rem 1rem; font-weight: 500; transition: all 0.15s ease; }
        .dropdown-item:hover { background: rgba(105,108,255,0.08); color: var(--patient-primary); transform: translateX(2px); }

        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in-up { animation: fadeInUp 0.5s ease-out; }

        /* Scrollbar Premium */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f3f5; }
        ::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a0aec0; }
        
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
              <span class="app-brand-text demo menu-text fw-bolder ms-2 text-white">MyVitalz</span>
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
              
              <li class="menu-item <?php if(@$_GET['pg']=='appointments'){ echo 'active';} ?>">
              <a href="?pg=appointments" class="menu-link">
                <i class="menu-icon tf-icons bx bx-notepad"></i>
                <div data-i18n="Analytics">Appointments</div>
              </a>
            </li>
              
            <li class="menu-item <?php if(@$_GET['pg']=='readings'){ echo 'active';} ?>">
              <a href="?pg=readings" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart"></i>
                <div data-i18n="Analytics">Vital Readings</div>
              </a>
            </li>
            
            <li class="menu-item <?php if(@$_GET['pg']=='rx'){ echo 'active';} ?>">
              <a href="?pg=rx" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file-medical"></i>
                <div data-i18n="Analytics">Prescriptions</div>
              </a>
            </li>
              
            <li class="menu-item <?php if(@$_GET['pg']=='medications'){ echo 'active';} ?>">
              <a href="?pg=medications" class="menu-link">
                <i class="menu-icon tf-icons bx bx-capsule"></i>
                <div data-i18n="Analytics">Medications</div>
              </a>
            </li>
            
            <li class="menu-item <?php if(in_array(@$_GET['pg'], ['storefronts', 'doctor-storefront', 'storefront-cart', 'storefront-checkout'])){ echo 'active';} ?>">
              <a href="?pg=storefronts" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store-alt"></i>
                <div data-i18n="Analytics">Doctor Storefronts</div>
              </a>
            </li>
            
            <li class="menu-item <?php if(@$_GET['pg']=='storefront-orders'){ echo 'active';} ?>">
              <a href="?pg=storefront-orders" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div data-i18n="Analytics">My Orders</div>
              </a>
            </li>
              
            <li class="menu-item <?php if(@$_GET['pg']=='shop'){ echo 'active';} ?>">
              <a href="?pg=shop" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div data-i18n="Analytics">Shop</div>
              </a>
            </li>
              
            <li class="menu-item <?php if(@$_GET['pg']=='communities'){ echo 'active';} ?>">
              <a href="?pg=communities" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div data-i18n="Analytics">Communities</div>
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

            <!-- Premium header section -->
            <div class="row mb-4">
              <div class="col-12 col-lg-8 mb-3 mb-lg-0">
                <div class="card">
                  <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                      <h4 class="page-title mb-1">Welcome, <?php echo $user[0]->first_name; ?> 👋</h4>
                      <p class="text-muted mb-0">Manage your appointments, vitals and prescriptions in one place.</p>
                    </div>
                    <div class="d-none d-md-block">
                      <a href="?pg=appointments" class="btn btn-primary me-2"><i class="bx bx-calendar me-1"></i>Appointments</a>
                      <a href="#" data-bs-toggle="modal" data-bs-target="#DropModalReadings" class="btn btn-outline-primary"><i class="bx bx-plus me-1"></i>Add Reading</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-lg-4">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                      <i class="bx bx-bell me-2"></i>
                      <strong>Notifications</strong>
                      <?php if(!empty($notifications)){ ?><span class="badge bg-primary ms-2"><?php echo count($notifications); ?></span><?php } ?>
                    </div>
                    <p class="text-muted mb-2">Stay updated with messages from your doctors and pharmacies.</p>
                    <a href="?pg=appointments" class="small">View upcoming appointments →</a>
                  </div>
                </div>
              </div>
            </div>

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
                        <div class="modal fade" id="DropModalReadings" data-bs-backdrop="static" tabindex="-1">
                          <div class="modal-dialog">
                            <form class="modal-content" method="post">
                        <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                              <div class="modal-header">
                                <h5 class="modal-title" id="backDropModalTitle">Take Vital Readings</h5>
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
                                    <label for="nameBackdrop" class="form-label">Vital to Measure</label>
                                   <select class="form-select" name="vitalz" aria-label="Default select example" required onchange="var vitalz = $(this).find('option:selected').text(); get_si_units(vitalz);">
                          <option value="">Select a vital</option>
                                       <?php
                            for($i=0; $i<count($allvitalz); $i++){
                                ?>
                          <option value="<?php echo $allvitalz[$i]->id; ?>"><?php echo $allvitalz[$i]->name; ?></option>
                                       <?php
                            }
                               ?>
                         
                        </select>
                                  </div>
                                </div> 
                                <div class="row">
                                  <div class="col mb-3">
                                    <label  class="form-label">Measurement Reading</label>
                                    <input
                                      type="text"
                                      name="vital_reading"
                                      class="form-control"
                                      placeholder="Enter reading eg. (mg/dl)"
                                           required
                                    />
                                  </div>
                                </div>
                                  
                                <div class="row">
                                  <div class="col mb-3">
                                    <label  class="form-label">Unit of Reading</label>
                                    <select
                                      name="si_unit"
                                      class="form-select"
                                      id="si_unit"
                                           required
                                            ></select>
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
                  <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close" onclick="removeModal()"></button>
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
      function search_doctor(){
           $('#sresults').html("Searching...");
          let pname =$('#pname').val();
          $.get('/search-doctors', {pname: pname}, function(data){
				
            $('#sresults').html(data);
						
					});
      }
             
      function public_doctor(){
           
          $.get('/public-doctors', {}, function(data){
				
            $('#public_doctors').html(data);
						
					});
      }
          
    function add_doctor(pcode, btn_id){
           $('#'+btn_id).html("Request sent");
          $.get('/add-doctors', {pcode: pcode}, function(data){
						
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
             
    function get_si_units(vitalz){
           $('#si_unit').empty();
          $.get('/get-si-units', {vitalz: vitalz}, function(data){
						$('#si_unit').append(data);
					});
      }
             
                
    function seen_notification(noti_id){
          $.get('/seen-notification', {not_id: noti_id}, function(data){
						
					});
      }
             
             public_doctor();
      </script>
      
        
        <script>
          
    function addToCart(pd, qty, btn){
		
        $('#'+btn).html("Adding to Cart...");
			$.get('/add-to-cart', {pd: pd, qty: qty}, function(data){
				
            $('#'+btn).html("Added to Cart");
						
					});
		}
          
          function removeModal(){
            var uri = window.location.toString();
    if (uri.indexOf("?") > 0) {
        var clean_uri = uri.substring(0, uri.indexOf("&"));
        window.history.replaceState({}, document.title, clean_uri);
    }
          }
            
        </script>
  </body>
</html>
