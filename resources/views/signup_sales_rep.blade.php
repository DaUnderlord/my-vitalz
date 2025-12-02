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

    <title>MyVitalz - Pharmaceutical Sales Rep Signup</title>

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
             <?php 
               if($rtn!=""){
                    ?>
              <div class="alert alert-danger mb-4"><?php echo $rtn; ?></div> 
                <?php
                }
                ?>
              <h4 class="mv-auth-title text-center mb-3">Pharmaceutical Sales Rep Account</h4>
              <p class="text-center mv-helper mb-4">Upload products to the marketplace and reach doctors nationwide</p>
              <form class="mb-3" method="POST" >
                   <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>"> 
               
                  <div class="row">
                    <div class="col-md-6 mb-3 mv-input">
                      <label for="first_name" class="form-label">First Name</label>
                      <input type="text" class="form-control" name="first_name" placeholder="Enter your first name" required />
                    </div>
                    <div class="col-md-6 mb-3 mv-input">
                      <label for="last_name" class="form-label">Last Name</label>
                      <input type="text" class="form-control" name="last_name" placeholder="Enter your last name" required />
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6 mb-3 mv-input">
                      <label for="email" class="form-label">Email</label>
                      <input type="email" class="form-control" name="email" placeholder="Enter your email" required />
                    </div>
                    <div class="col-md-6 mb-3 mv-input">
                      <label for="phone" class="form-label">Phone Number</label>
                      <input type="tel" class="form-control" name="phone" placeholder="Enter your phone number" required />
                    </div>
                  </div>
                  
                  <div class="mb-3 mv-input">
                    <label for="company_name" class="form-label">Company Name</label>
                    <input type="text" class="form-control" name="company_name" placeholder="Enter pharmaceutical company name" required />
                  </div>
                  
                  <div class="mb-3 mv-input">
                    <label for="company_license" class="form-label">Company License Number</label>
                    <input type="text" class="form-control" name="company_license" placeholder="Enter company license/registration number" required />
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6 mb-3 mv-input">
                      <label for="state" class="form-label">State</label>
                      <select class="form-select" name="state" required>
                        <option value="">Select State</option>
                        <option value="Abia">Abia</option>
                        <option value="Adamawa">Adamawa</option>
                        <option value="Akwa Ibom">Akwa Ibom</option>
                        <option value="Anambra">Anambra</option>
                        <option value="Bauchi">Bauchi</option>
                        <option value="Bayelsa">Bayelsa</option>
                        <option value="Benue">Benue</option>
                        <option value="Borno">Borno</option>
                        <option value="Cross River">Cross River</option>
                        <option value="Delta">Delta</option>
                        <option value="Ebonyi">Ebonyi</option>
                        <option value="Edo">Edo</option>
                        <option value="Ekiti">Ekiti</option>
                        <option value="Enugu">Enugu</option>
                        <option value="FCT">FCT</option>
                        <option value="Gombe">Gombe</option>
                        <option value="Imo">Imo</option>
                        <option value="Jigawa">Jigawa</option>
                        <option value="Kaduna">Kaduna</option>
                        <option value="Kano">Kano</option>
                        <option value="Katsina">Katsina</option>
                        <option value="Kebbi">Kebbi</option>
                        <option value="Kogi">Kogi</option>
                        <option value="Kwara">Kwara</option>
                        <option value="Lagos">Lagos</option>
                        <option value="Nasarawa">Nasarawa</option>
                        <option value="Niger">Niger</option>
                        <option value="Ogun">Ogun</option>
                        <option value="Ondo">Ondo</option>
                        <option value="Osun">Osun</option>
                        <option value="Oyo">Oyo</option>
                        <option value="Plateau">Plateau</option>
                        <option value="Rivers">Rivers</option>
                        <option value="Sokoto">Sokoto</option>
                        <option value="Taraba">Taraba</option>
                        <option value="Yobe">Yobe</option>
                        <option value="Zamfara">Zamfara</option>
                      </select>
                    </div>
                    <div class="col-md-6 mb-3 mv-input">
                      <label for="city" class="form-label">City</label>
                      <input type="text" class="form-control" name="city" placeholder="Enter your city" required />
                    </div>
                  </div>
                  
                  <div class="mb-3 mv-input">
                    <label for="address" class="form-label">Office Address</label>
                    <textarea class="form-control" name="address" rows="2" placeholder="Enter your office address" required></textarea>
                  </div>
                  
                  <div class="mb-3 mv-input form-password-toggle">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group input-group-merge">
                      <input
                        type="password"
                        class="form-control"
                        name="password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="password"
                        required
                      />
                      <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" required />
                      <label class="form-check-label" for="terms-conditions">
                        I agree to
                        <a href="javascript:void(0);">privacy policy & terms</a>
                      </label>
                    </div>
                  </div>
                  <button class="btn btn-primary d-grid w-100 mv-btn-primary">Sign up</button>
                </form>

                <p class="text-center">
                  <span>Already have an account?</span>
                  <a href="/">
                    <span>Sign in instead</span>
                  </a>
                </p>
                
                <p class="text-center mt-3">
                  <span>Sign up as:</span>
                  <a href="/signup-patient" class="mx-2">Patient</a> |
                  <a href="/signup-doctor" class="mx-2">Doctor</a> |
                  <a href="/signup-hospital" class="mx-2">Hospital</a> |
                  <a href="/signup-pharmacy" class="mx-2">Pharmacy</a>
                </p>
              </div>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- / Content -->

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

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
