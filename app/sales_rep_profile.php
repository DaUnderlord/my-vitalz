<?php
// Sales Rep Profile Page
// Assumes $uid and $user are available from the controller context

$rep = $user[0] ?? null;
?>

<div class="row">
  <div class="col-12">
    <h5 class="mb-1">My Profile</h5>
    <p class="text-muted mb-4">Update your company and contact information</p>
  </div>
</div>

<div class="row">
  <div class="col-lg-8">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="mb-0">Company Information</h6>
      </div>
      <div class="card-body">
        <form method="POST">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Company Name</label>
              <input type="text" class="form-control" name="company_name" value="<?php echo $rep->company_name ?? '';?>" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Company Email</label>
              <input type="email" class="form-control" name="company_email" value="<?php echo $rep->company_email ?? $rep->email ?? '';?>" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">State</label>
              <input type="text" class="form-control" name="state" value="<?php echo $rep->state ?? '';?>" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">City</label>
              <input type="text" class="form-control" name="city" value="<?php echo $rep->city ?? '';?>">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea class="form-control" name="address" rows="2" placeholder="Business address"><?php echo $rep->address ?? '';?></textarea>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Contact Phone</label>
              <input type="text" class="form-control" name="phone" value="<?php echo $rep->phone ?? '';?>">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Website</label>
              <input type="text" class="form-control" name="website" value="<?php echo $rep->website ?? '';?>" placeholder="https://...">
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="mb-0">Bank Details (for payouts)</h6>
      </div>
      <div class="card-body">
        <form method="POST">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <input type="hidden" name="action" value="update_bank_details">
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Bank Name</label>
              <input type="text" class="form-control" name="bank_name" value="<?php echo $rep->bank_name ?? '';?>">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Account Number</label>
              <input type="text" class="form-control" name="account_number" value="<?php echo $rep->account_number ?? '';?>">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Account Name</label>
              <input type="text" class="form-control" name="account_name" value="<?php echo $rep->account_name ?? '';?>">
            </div>
          </div>
          <button type="submit" class="btn btn-outline-primary">Update Bank Details</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0">Profile Photo</h6>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div class="avatar avatar-xl me-3">
            <?php if(!empty($rep->photo)){ ?>
              <img src="/assets/images/<?php echo $rep->photo; ?>" alt="Profile" class="rounded-circle" style="width: 64px; height: 64px; object-fit: cover;">
            <?php } else { ?>
              <span class="avatar-initial rounded-circle bg-label-primary">SR</span>
            <?php } ?>
          </div>
          <div>
            <strong><?php echo $rep->first_name.' '.$rep->last_name; ?></strong>
            <div class="text-muted small">Sales Representative</div>
          </div>
        </div>
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <input type="file" name="upload_profile" class="form-control mb-2" accept="image/*">
          <button type="submit" class="btn btn-sm btn-secondary">Upload New Photo</button>
        </form>
      </div>
    </div>

    <div class="card mt-3">
      <div class="card-body">
        <h6 class="mb-2">Tips</h6>
        <ul class="mb-0 small">
          <li>Ensure your state is set correctly for geo-locking.</li>
          <li>Keep company details up-to-date for trust.</li>
          <li>Fill in bank details to enable payouts.</li>
        </ul>
      </div>
    </div>
  </div>
</div>
