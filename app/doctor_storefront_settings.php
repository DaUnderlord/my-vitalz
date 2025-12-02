<?php
// Handle both array and object formats
$user_obj = is_array($user) ? $user[0] : $user;

// Current doctor's ID
$uid = isset($user_obj->id) ? (int)$user_obj->id : 0;

// Get storefront settings
$storefront_settings = DB::select('SELECT * FROM doctor_storefront_settings WHERE doctor_id = '.$uid);
$has_settings = !empty($storefront_settings);

// Default values
$storefront_name = $has_settings ? $storefront_settings[0]->storefront_name : $user_obj->first_name.' '.$user_obj->last_name.' Pharmacy';
$storefront_logo = $has_settings ? $storefront_settings[0]->storefront_logo : '';
$storefront_banner = $has_settings ? $storefront_settings[0]->storefront_banner : '';
$primary_color = $has_settings ? $storefront_settings[0]->primary_color : '#696cff';
$description = $has_settings ? $storefront_settings[0]->description : '';
$is_active = $has_settings ? $storefront_settings[0]->is_active : 1;
?>

<div class="row">
  <div class="col-12">
    <h5 class="mb-1">Storefront Settings</h5>
    <p class="text-muted mb-4">Customize your virtual pharmacy appearance</p>
  </div>
</div>

<div class="row">
  <div class="col-lg-8">
    <div class="card mb-4">
      <div class="card-header">
        <h6 class="mb-0">Basic Information</h6>
      </div>
      <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <input type="hidden" name="action" value="save_storefront_settings">
          
          <div class="mb-3">
            <label for="storefront_name" class="form-label">Storefront Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="storefront_name" name="storefront_name" value="<?php echo $storefront_name; ?>" required>
            <small class="text-muted">This will be displayed to patients browsing your storefront</small>
          </div>
          
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Tell patients about your pharmacy and services"><?php echo $description; ?></textarea>
          </div>
          
          <div class="mb-3">
            <label for="storefront_logo" class="form-label">Storefront Logo</label>
            <?php if($storefront_logo){ ?>
              <div class="mb-2">
                <img src="/assets/storefronts/<?php echo $storefront_logo; ?>" alt="Current Logo" style="max-height: 80px;">
              </div>
            <?php } ?>
            <input type="file" class="form-control" id="storefront_logo" name="storefront_logo" accept="image/*">
            <small class="text-muted">Recommended: Square image, 200x200px minimum</small>
          </div>
          
          <div class="mb-3">
            <label for="storefront_banner" class="form-label">Storefront Banner</label>
            <?php if($storefront_banner){ ?>
              <div class="mb-2">
                <img src="/assets/storefronts/<?php echo $storefront_banner; ?>" alt="Current Banner" style="max-height: 120px; width: 100%; object-fit: cover;">
              </div>
            <?php } ?>
            <input type="file" class="form-control" id="storefront_banner" name="storefront_banner" accept="image/*">
            <small class="text-muted">Recommended: 1200x300px for best results</small>
          </div>
          
          <div class="mb-3">
            <label for="primary_color" class="form-label">Primary Color</label>
            <div class="input-group">
              <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" value="<?php echo $primary_color; ?>">
              <input type="text" class="form-control" value="<?php echo $primary_color; ?>" readonly>
            </div>
            <small class="text-muted">This color will be used for buttons and highlights in your storefront</small>
          </div>
          
          <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" <?php echo $is_active ? 'checked' : ''; ?>>
            <label class="form-check-label" for="is_active">
              <strong>Storefront Active</strong>
              <br><small class="text-muted">When active, patients can browse and purchase from your storefront</small>
            </label>
          </div>
          
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bx bx-save me-1"></i> Save Settings
            </button>
            <a href="?pg=storefront" class="btn btn-outline-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <div class="col-lg-4">
    <div class="card mb-3">
      <div class="card-header">
        <h6 class="mb-0"><i class="bx bx-info-circle me-1"></i> Preview</h6>
      </div>
      <div class="card-body">
        <div class="border rounded p-3" style="background: <?php echo $primary_color; ?>10;">
          <div class="text-center mb-3">
            <?php if($storefront_logo){ ?>
              <img src="/assets/storefronts/<?php echo $storefront_logo; ?>" alt="Logo" style="max-height: 60px;">
            <?php } else { ?>
              <div class="avatar avatar-lg">
                <span class="avatar-initial rounded-circle" style="background: <?php echo $primary_color; ?>;">
                  <?php echo strtoupper(substr($user_obj->first_name, 0, 1)); ?>
                </span>
              </div>
            <?php } ?>
          </div>
          <h6 class="text-center mb-2"><?php echo $storefront_name; ?></h6>
          <?php if($description){ ?>
            <p class="small text-muted text-center mb-3"><?php echo substr($description, 0, 100); ?><?php echo strlen($description) > 100 ? '...' : ''; ?></p>
          <?php } ?>
          <button class="btn btn-sm w-100" style="background: <?php echo $primary_color; ?>; color: white;">
            Sample Button
          </button>
        </div>
      </div>
    </div>
    
    <div class="card mb-3">
      <div class="card-header">
        <h6 class="mb-0"><i class="bx bx-link me-1"></i> Storefront Link</h6>
      </div>
      <div class="card-body">
        <div class="input-group input-group-sm mb-2">
          <input type="text" class="form-control" value="<?php echo $_SERVER['HTTP_HOST']; ?>/patient/storefront/<?php echo $user_obj->ref_code; ?>" readonly id="storefrontLink">
          <button class="btn btn-outline-primary" type="button" onclick="copyLink()">
            <i class="bx bx-copy"></i>
          </button>
        </div>
        <small class="text-muted">Share this link with patients to visit your storefront</small>
        
        <?php if($is_active){ ?>
          <div class="mt-3">
            <a href="/patient/storefront/<?php echo $user_obj->ref_code; ?>" target="_blank" class="btn btn-sm btn-outline-primary w-100">
              <i class="bx bx-show me-1"></i> Preview Storefront
            </a>
          </div>
        <?php } ?>
      </div>
    </div>
    
    <div class="card bg-light">
      <div class="card-body">
        <h6 class="mb-3"><i class="bx bx-bulb me-1"></i> Tips</h6>
        <ul class="list-unstyled mb-0 small">
          <li class="mb-2">
            <i class="bx bx-check text-success me-1"></i>
            Use a clear, professional logo
          </li>
          <li class="mb-2">
            <i class="bx bx-check text-success me-1"></i>
            Choose colors that match your brand
          </li>
          <li class="mb-2">
            <i class="bx bx-check text-success me-1"></i>
            Write a compelling description
          </li>
          <li class="mb-2">
            <i class="bx bx-check text-success me-1"></i>
            Keep your storefront active for sales
          </li>
          <li>
            <i class="bx bx-check text-success me-1"></i>
            Update regularly with new products
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
function copyLink() {
  var copyText = document.getElementById("storefrontLink");
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  navigator.clipboard.writeText(copyText.value);
  
  var btn = event.target.closest('button');
  var originalHTML = btn.innerHTML;
  btn.innerHTML = '<i class="bx bx-check"></i>';
  setTimeout(function() {
    btn.innerHTML = originalHTML;
  }, 2000);
}

// Live color preview
document.getElementById('primary_color')?.addEventListener('input', function(e) {
  var color = e.target.value;
  document.querySelector('.border.rounded.p-3').style.background = color + '10';
  document.querySelector('.btn-sm.w-100').style.background = color;
});
</script>
