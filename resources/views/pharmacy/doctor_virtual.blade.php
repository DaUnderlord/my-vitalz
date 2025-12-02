@extends('pharmacy.layout')

@section('title', 'Doctor Virtual Pharmacy')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Doctor /</span> Virtual Pharmacy
</h4>

@if(request()->query('a_type'))
  <div class="alert alert-{{ request()->query('a_type') === 'success' ? 'success' : (request()->query('a_type') === 'warning' ? 'warning' : 'info') }}" role="alert">
    {{ urldecode(request()->query('a_message', '')) }}
  </div>
@endif

<div class="row">
  <div class="col-lg-8">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Your Virtual Pharmacy</h5>
      </div>
      <div class="card-body">
        <p class="mb-3">Share this link with your patients to view your virtual pharmacy storefront.</p>
        <div class="input-group mb-3">
          <input type="text" id="vpLink" class="form-control" value="{{ $virtual_link }}" readonly>
          <button class="btn btn-outline-primary" onclick="copyVP()"><i class="bx bx-copy me-1"></i>Copy</button>
        </div>
        <a href="{{ $virtual_link }}" target="_blank" class="btn btn-primary"><i class="bx bx-link-external me-2"></i>Open Virtual Pharmacy</a>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Create Prescription</h5>
      </div>
      <div class="card-body">
        <form method="post" action="/doctor/virtual-pharmacy/prescribe">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Patient Email or Phone *</label>
              <input type="text" name="patient" class="form-control" placeholder="patient@email.com or 0803..." required>
              <small class="text-muted">We will match by email or normalized phone.</small>
            </div>
            <div class="col-md-6">
              <label class="form-label">Medication Name *</label>
              <input type="text" name="medication_name" class="form-control" placeholder="e.g. Amoxicillin 500mg" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Dosage *</label>
              <input type="text" name="dosage" class="form-control" placeholder="e.g. 500mg" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Frequency *</label>
              <input type="text" name="frequency" class="form-control" placeholder="e.g. 2x daily" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Duration *</label>
              <input type="text" name="duration" class="form-control" placeholder="e.g. 7 days" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Quantity</label>
              <input type="number" name="quantity" class="form-control" value="1" min="1">
            </div>
            <div class="col-md-4">
              <label class="form-label">Unit Price (optional)</label>
              <input type="number" step="0.01" name="unit_price" class="form-control" placeholder="0.00">
            </div>
          </div>
          <div class="mt-3">
            <button type="submit" class="btn btn-primary"><i class="bx bx-edit-alt me-1"></i>Create Prescription</button>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Upgrade to Full Pharmacy Account</h5>
      </div>
      <div class="card-body">
        <p>Upgrade your account to manage inventory, e‑prescriptions, rewards and more directly in the pharmacy module.</p>
        <form method="post" action="/doctor/virtual-pharmacy/upgrade">
          @csrf
          <button type="submit" class="btn btn-success">
            <i class="bx bx-capsule me-2"></i>Upgrade to Pharmacy
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
function copyVP(){
  const el = document.getElementById('vpLink');
  el.select();
  document.execCommand('copy');
  alert('Link copied to clipboard');
}
</script>
@endsection
