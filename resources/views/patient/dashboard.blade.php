@extends('layouts.app')
@section('title', 'MyVitalz - Patient')

@section('head')
  <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
  <style>
    /* Premium card styling */
    .card { border: 1px solid rgba(0,0,0,0.04); border-radius: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); transition: all 0.3s ease; }
    .card:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(105,108,255,0.12) !important; }
    .card-header { border-bottom: 0; background: transparent; padding: 1.5rem; font-weight: 600; }
    .card-body { padding: 1.5rem; }

    /* Table styling */
    .table thead th { background: #f9fafb; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 1rem; }
    .table tbody td { padding: 1rem; vertical-align: middle; }
    .table-hover tbody tr:hover { background-color: rgba(105,108,255,0.06); }

    /* Button enhancements */
    .btn { font-weight: 500; transition: all 0.2s ease; }
    .btn-primary { box-shadow: 0 2px 8px rgba(105,108,255,0.24); }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(105,108,255,0.32); }

    /* Dropdown styling */
    .dropdown-menu { border: 0; box-shadow: 0 8px 32px rgba(0,0,0,0.12); border-radius: 12px; padding: 0.5rem; }
    .dropdown-item { border-radius: 8px; padding: 0.625rem 1rem; font-weight: 500; }
    .dropdown-item:hover { background: rgba(105,108,255,0.08); color: #696cff; }

    /* Input styling */
    .form-control:focus, .form-select:focus { border-color: #696cff; box-shadow: 0 0 0 3px rgba(105,108,255,0.12); }

    /* Animations */
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .fade-in-up { animation: fadeInUp 0.5s ease-out; }

    /* Color utilities */
    .txt-color-pink { color: #FF618F; }
    .bg-color-pink { background-color: #FF618F; }
    .txt-color-purple { color: #A64FFE; }
    .bg-color-purple { background-color: #A64FFE; }
    .txt-color-blue { color: #2C68BF; }
    .bg-color-blue { background-color: #2C68BF; }
    .txt-color-red { color: #FF6161; }
    .bg-color-red { background-color: #FF6161; }
    .txt-color-green { color: #4BA33D; }
    .bg-color-green { background-color: #4BA33D; }
    .txt-color-orange { color: #FF8E3C; }
    .bg-color-orange { background-color: #FF8E3C; }
  </style>
@endsection

@section('sidebar')
  <ul class="menu-inner py-5">
    <li class="menu-item {{ (request()->query('pg')==='home' || !request()->has('pg')) ? 'active' : '' }}">
      <a href="?pg=home" class="menu-link"><i class="menu-icon tf-icons bx bx-home"></i><div>Dashboard</div></a>
    </li>
    <li class="menu-item {{ request()->query('pg')==='appointments' ? 'active' : '' }}">
      <a href="?pg=appointments" class="menu-link"><i class="menu-icon tf-icons bx bx-notepad"></i><div>Appointments</div></a>
    </li>
    <li class="menu-item {{ request()->query('pg')==='readings' ? 'active' : '' }}">
      <a href="?pg=readings" class="menu-link"><i class="menu-icon tf-icons bx bx-bar-chart"></i><div>Vital Readings</div></a>
    </li>
    <li class="menu-item {{ request()->query('pg')==='rx' ? 'active' : '' }}">
      <a href="?pg=rx" class="menu-link"><i class="menu-icon tf-icons bx bx-file-medical"></i><div>Prescriptions</div></a>
    </li>
    <li class="menu-item {{ request()->query('pg')==='medications' ? 'active' : '' }}">
      <a href="?pg=medications" class="menu-link"><i class="menu-icon tf-icons bx bx-capsule"></i><div>Medications</div></a>
    </li>
    <li class="menu-item {{ in_array(request()->query('pg'), ['storefronts', 'doctor-storefront', 'storefront-cart', 'storefront-checkout']) ? 'active' : '' }}">
      <a href="?pg=storefronts" class="menu-link"><i class="menu-icon tf-icons bx bx-store-alt"></i><div>Doctor Storefronts</div></a>
    </li>
    <li class="menu-item {{ request()->query('pg')==='storefront-orders' ? 'active' : '' }}">
      <a href="?pg=storefront-orders" class="menu-link"><i class="menu-icon tf-icons bx bx-package"></i><div>My Orders</div></a>
    </li>
    <li class="menu-item {{ request()->query('pg')==='shop' ? 'active' : '' }}">
      <a href="?pg=shop" class="menu-link"><i class="menu-icon tf-icons bx bx-cart"></i><div>Shop</div></a>
    </li>
    <li class="menu-item {{ request()->query('pg')==='communities' ? 'active' : '' }}">
      <a href="?pg=communities" class="menu-link"><i class="menu-icon tf-icons bx bx-group"></i><div>Communities</div></a>
    </li>
    <li class="menu-item {{ request()->query('pg')==='referrals' ? 'active' : '' }}">
      <a href="?pg=referrals" class="menu-link"><i class="menu-icon tf-icons bx bx-user-plus"></i><div>Referrals</div></a>
    </li>
    <li class="menu-item {{ request()->query('pg')==='affiliates' ? 'active' : '' }}">
      <a href="?pg=affiliates" class="menu-link"><i class="menu-icon tf-icons bx bx-group"></i><div>Affiliates</div></a>
    </li>
    <li class="menu-item {{ request()->query('pg')==='support' ? 'active' : '' }}">
      <a href="?pg=support" class="menu-link"><i class="menu-icon tf-icons bx bx-headphone"></i><div>Support</div></a>
    </li>
    <li class="menu-item {{ request()->query('pg')==='messages' ? 'active' : '' }}">
      <a href="?pg=messages" class="menu-link"><i class="menu-icon tf-icons bx bx-message-rounded-dots"></i><div>Messages</div></a>
    </li>
  </ul>
@endsection

@section('navbar-right')
  <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
      <i class="bx bx-bell bx-sm"></i>
      @if(!empty($notifications))<span class="badge bg-danger rounded-pill badge-notifications">{{ count($notifications) }}</span>@endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end py-0">
      <li class="dropdown-menu-header border-bottom"><div class="dropdown-header d-flex align-items-center py-3"><h5 class="text-body mb-0 me-auto">Notification</h5></div></li>
      <li class="dropdown-notifications-list scrollable-container ps">
        <ul class="list-group list-group-flush">
          @if(!empty($notifications))
            @for($i=count($notifications)-1; $i>=0; $i--)
              <li class="list-group-item list-group-item-action dropdown-notifications-item">
                <a href="{{ $notifications[$i]->link }}" onclick="seen_notification('{{ $notifications[$i]->id }}')">
                  <div class="d-flex">
                    <div class="flex-grow-1">
                      <small class="mb-0">{{ $notifications[$i]->description }}</small><br>
                      <small class="text-muted">{{ \App\functions::format_date_time($notifications[$i]->date) }}</small>
                    </div>
                    <div class="flex-shrink-0 dropdown-notifications-actions"><span class="badge badge-dot"></span></div>
                  </div>
                </a>
              </li>
            @endfor
          @else
            <li class="list-group-item list-group-item-action dropdown-notifications-item"><div class="d-flex"><div class="flex-grow-1"><small class="mb-0">No notifications</small></div></div></li>
          @endif
        </ul>
      </li>
    </ul>
  </li>
  <li class="nav-item xl-1 me-3">Hi, {{ $user[0]->first_name }}!</li>
  <li class="nav-item navbar-dropdown dropdown-user dropdown">
    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
      <div class="avatar "><img src="../assets/{{ $user[0]->photo ? 'images/'.$user[0]->photo : 'img/avatars/user.png' }}" alt class="w-px-40 h-auto rounded-circle" /></div>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
      <li><a class="dropdown-item" href="?pg=profile"><i class="bx bx-edit me-2"></i><span class="align-middle">Edit Profile</span></a></li>
      <li><div class="dropdown-divider"></div></li>
      <li><a class="dropdown-item" href="/logout"><i class="bx bx-power-off me-2"></i><span class="align-middle">Log Out</span></a></li>
    </ul>
  </li>
@endsection

@section('content')
  <div class="row mb-4 fade-in-up">
    <div class="col-12 col-lg-8 mb-3 mb-lg-0">
      <div class="card" style="background: linear-gradient(135deg, rgba(105,108,255,0.05) 0%, rgba(139,92,246,0.05) 100%); border: 1px solid rgba(105,108,255,0.1);">
        <div class="card-body d-flex align-items-center justify-content-between py-4">
          <div>
            <span class="badge bg-primary bg-opacity-10 text-primary mb-2">Patient Dashboard</span>
            <h4 class="page-title mb-1">Welcome back, {{ $user[0]->first_name }}! 👋</h4>
            <p class="text-muted mb-0">Manage your health journey - appointments, vitals, and prescriptions all in one place.</p>
          </div>
          <div class="d-none d-md-flex gap-2">
            <a href="?pg=appointments" class="btn btn-primary"><i class="bx bx-calendar me-1"></i>Appointments</a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#DropModalReadings" class="btn btn-outline-primary"><i class="bx bx-plus me-1"></i>Add Reading</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-4">
      <div class="card h-100">
        <div class="card-body d-flex flex-column justify-content-center">
          <div class="d-flex align-items-center mb-3">
            <div class="icon-wrapper bg-primary bg-opacity-10 me-3" style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
              <i class="bx bx-bell bx-sm text-primary"></i>
            </div>
            <div>
              <h6 class="mb-0">Notifications</h6>
              @if(!empty($notifications))
                <span class="badge bg-danger">{{ count($notifications) }} new</span>
              @else
                <small class="text-muted">All caught up!</small>
              @endif
            </div>
          </div>
          <p class="text-muted small mb-2">Stay updated with messages from your healthcare providers.</p>
          <a href="?pg=appointments" class="small fw-medium">View upcoming appointments <i class="bx bx-right-arrow-alt"></i></a>
        </div>
      </div>
    </div>
  </div>

  <?php @include(app_path().'/'.$pagename.'.php'); ?>

  <!-- Modal: Take Vital Readings -->
  <div class="modal fade" id="DropModalReadings" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <form class="modal-content" method="post" style="border-radius: 16px; overflow: hidden;">
        <input type="hidden" name="_token" value="<?php echo csrf_token() ?>">
        <div class="modal-header border-0 pb-0">
          <div>
            <h5 class="modal-title fw-bold">Take Vital Readings</h5>
            <p class="text-muted small mb-0">Record your health measurements</p>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body pt-3">
          <div class="mb-3">
            <label class="form-label fw-medium">Vital to Measure</label>
            <select class="form-select" name="vitalz" required onchange="var vitalz = $(this).find('option:selected').text(); get_si_units(vitalz);">
              <option value="">Select a vital</option>
              <?php for($i=0; $i<count($allvitalz); $i++){ ?><option value="<?php echo $allvitalz[$i]->id; ?>"><?php echo $allvitalz[$i]->name; ?></option><?php } ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Measurement Reading</label>
            <input type="text" name="vital_reading" class="form-control" placeholder="Enter reading eg. 120" required />
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Unit of Reading</label>
            <select name="si_unit" class="form-select" id="si_unit" required></select>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bx bx-check me-1"></i>Save Reading</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    function search_doctor(){ $('#sresults').html("Searching..."); let pname =$('#pname').val(); $.get('/search-doctors', {pname: pname}, function(data){ $('#sresults').html(data); }); }
    function public_doctor(){ $.get('/public-doctors', {}, function(data){ $('#public_doctors').html(data); }); }
    function add_doctor(pcode, btn_id){ $('#'+btn_id).html("Request sent"); $.get('/add-doctors', {pcode: pcode}); }
    function appointmt_time(doc_ref, appointment_date){ $('#appt_time').empty().append("<option value=''>Loading...</option>"); $.get('/get-appointment-intervals', {doc_ref: doc_ref, appointment_date:appointment_date}, function(data){ $('#appt_time').empty().append(data); }); }
    function get_si_units(vitalz){ $('#si_unit').empty(); $.get('/get-si-units', {vitalz: vitalz}, function(data){ $('#si_unit').append(data); }); }
    function seen_notification(noti_id){ $.get('/seen-notification', {not_id: noti_id}); }
    function addToCart(pd, qty, btn){ $('#'+btn).html("Adding to Cart..."); $.get('/add-to-cart', {pd: pd, qty: qty}, function(){ $('#'+btn).html("Added to Cart"); }); }
    function removeModal(){ var uri = window.location.toString(); if (uri.indexOf("?" )>0) { var clean_uri = uri.substring(0, uri.indexOf("&")); window.history.replaceState({}, document.title, clean_uri); } }
    public_doctor();
  </script>
  @if(!empty($a_type))
    @php($atype = ($a_type==="success")?"bg-primary":"bg-danger")
    @php($aicon = ($a_type==="success")?"check":"x")
    @php($atitle = ($a_type==="success")?"Success":"Warning")
    <div class="bs-toast toast show toast-placement-ex m-2 fade {{ $atype }} top-0 start-50 translate-middle-x" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
      <div class="toast-header"><i class="bx bx-{{ $aicon }} me-2"></i><div class="me-auto fw-semibold">{{ $atitle }}</div><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close" onclick="removeModal()"></button></div>
      <div class="toast-body">{{ $a_message }}</div>
    </div>
  @endif
@endsection
