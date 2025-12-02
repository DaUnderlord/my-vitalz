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
  class="light-style customizer-hide"
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

    <title>MyVitalz - Signup</title>

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
    <!-- Page -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
  </head>

  <body class="mv-auth-bg">
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card mv-card-glass">
            <div class="card-body p-4 p-md-5">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="/" class="app-brand-link gap-2">
                  <span class="brand-logo-box"><img src="/logo.png" alt="MyVitalz"></span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mv-auth-title text-center mb-2">Create your account</h4>
              <p class="text-center mv-helper mb-4">Select your role to get started</p>
              
              <div class="row g-3">
                <div class="col-6 col-md-4">
                  <a href="/signup-patient" class="role-card d-block text-decoration-none h-100">
                    <div class="icon-wrapper">
                      <i class="bx bx-user"></i>
                    </div>
                    <h6 class="mb-1">Patient</h6>
                    <small class="text-muted">Track your health</small>
                  </a>
                </div>
                <div class="col-6 col-md-4">
                  <a href="/signup-doctor" class="role-card d-block text-decoration-none h-100">
                    <div class="icon-wrapper">
                      <i class="bx bx-stethoscope"></i>
                    </div>
                    <h6 class="mb-1">Doctor</h6>
                    <small class="text-muted">Manage patients</small>
                  </a>
                </div>
                <div class="col-6 col-md-4">
                  <a href="/signup-hospital" class="role-card d-block text-decoration-none h-100">
                    <div class="icon-wrapper">
                      <i class="bx bx-hospital"></i>
                    </div>
                    <h6 class="mb-1">Hospital</h6>
                    <small class="text-muted">Healthcare facility</small>
                  </a>
                </div>
                <div class="col-6 col-md-6">
                  <a href="/signup-pharmacy" class="role-card d-block text-decoration-none h-100">
                    <div class="icon-wrapper">
                      <i class="bx bx-clinic"></i>
                    </div>
                    <h6 class="mb-1">Pharmacy</h6>
                    <small class="text-muted">Dispense medications</small>
                  </a>
                </div>
                <div class="col-12 col-md-6">
                  <a href="/signup-sales-rep" class="role-card d-block text-decoration-none h-100">
                    <div class="icon-wrapper">
                      <i class="bx bx-briefcase-alt-2"></i>
                    </div>
                    <h6 class="mb-1">Sales Rep</h6>
                    <small class="text-muted">Pharmaceutical sales</small>
                  </a>
                </div>
              </div>
              
              <p class="text-center mt-4 mb-0">
                <span class="text-muted">Already have an account?</span>
                <a href="/" class="fw-medium ms-1">Sign in</a>
              </p>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
