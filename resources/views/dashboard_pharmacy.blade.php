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

    <title>MyVitalz - Pharmacy Dashboard</title>

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
       <script src="assets/vendor/libs/jquery/jquery.js"></script>
  </head>
    
    <style>
        
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

        /* Pharmacy-specific colors */
        .txt-color-pharmacy{
            color:#28a745; 
        }
        
        .bg-color-pharmacy{
            background-color:#28a745; 
        }
        
        .txt-color-pharmacy-light{
            color:#d4edda; 
        }
        
        .bg-color-pharmacy-light{
            background-color:#d4edda; 
        }
        
    </style>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="/dashboard-pharmacy" class="app-brand-link">
              <span class="app-brand-logo demo">
                <img src="logo.png" height="45px" alt="MyVitalz"/>
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
              
            <li class="menu-item <?php if(@$_GET['pg']=='network'){ echo 'active';} ?>">
              <a href="?pg=network" class="menu-link">
                <i class="menu-icon tf-icons bx bx-network-chart"></i>
                <div data-i18n="Analytics">My Network</div>
              </a>
            </li>
              
            <li class="menu-item <?php if(@$_GET['pg']=='prescriptions'){ echo 'active';} ?>">
              <a href="?pg=prescriptions" class="menu-link">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div data-i18n="Analytics">E-Prescriptions</div>
              </a>
            </li>
              
            <li class="menu-item <?php if(@$_GET['pg']=='inventory'){ echo 'active';} ?>">
              <a href="?pg=inventory" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div data-i18n="Analytics">Inventory</div>
              </a>
            </li>
              
            <li class="menu-item <?php if(@$_GET['pg']=='patients'){ echo 'active';} ?>">
              <a href="?pg=patients" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-plus"></i>
                <div data-i18n="Analytics">My Patients</div>
              </a>
            </li>
              
            <li class="menu-item <?php if(@$_GET['pg']=='monitoring'){ echo 'active';} ?>">
              <a href="?pg=monitoring" class="menu-link">
                <i class="menu-icon tf-icons bx bx-pulse"></i>
                <div data-i18n="Analytics">Monitoring</div>
              </a>
            </li>
            
            <li class="menu-item <?php if(@$_GET['pg']=='rewards'){ echo 'active';} ?>">
              <a href="?pg=rewards" class="menu-link">
                <i class="menu-icon tf-icons bx bx-gift"></i>
                <div data-i18n="Analytics">Doctor Rewards</div>
              </a>
            </li>  
              
            <li class="menu-item <?php if(@$_GET['pg']=='profile'){ echo 'active';} ?>">
              <a href="?pg=profile" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Analytics">Profile</div>
              </a>
            </li>

            
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
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
                        <small class="text-muted"><?php echo $notifications[$i]->date; ?></small>
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
                 Hi, <?php echo $user[0]->first_name; ?>! <span class="badge bg-success">Pharmacy</span>
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
                  MyVitalz Pharmacy
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
             
    function seen_notification(noti_id){
          $.get('/seen-notification', {not_id: noti_id}, function(data){
						
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
