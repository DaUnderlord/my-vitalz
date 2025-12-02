<?php
// Handle both array and object formats
$user_obj = is_array($user) ? $user[0] : $user;

// Get doctor's state for geo-filtering
$doctor_state = $user_obj->state ?? '';

// Current doctor's ID (used for storefront checks)
$uid = isset($user_obj->id) ? (int)$user_obj->id : 0;

// Get marketplace drugs filtered by doctor's state
$marketplace_drugs = [];
if($doctor_state){
    $marketplace_drugs = DB::select('
        SELECT md.*, u.first_name, u.last_name, u.company_name, u.city,
            (SELECT COUNT(*) FROM doctor_storefront_inventory WHERE marketplace_drug_id = md.id AND doctor_id = '.$uid.') as in_my_storefront
        FROM marketplace_drugs md
        INNER JOIN users u ON md.sales_rep_id = u.id
        WHERE md.state = "'.$doctor_state.'" AND md.status = "active" AND md.stock_quantity > 0
        ORDER BY md.created_at DESC
    ');
}

// Get categories for filter
$categories = DB::select('SELECT DISTINCT category FROM marketplace_drugs WHERE status="active" AND category IS NOT NULL');
?>

<div class="px-3 px-md-4">

<div class="row">
  <div class="col-12">
    <div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #eef2ff, #ffffff);">
      <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
        <div class="mb-3 mb-md-0">
          <h4 class="mb-1 d-flex align-items-center" style="gap:.5rem">
            <span class="badge bg-primary rounded-pill"><i class="bx bx-store me-1"></i> Marketplace</span>
          </h4>
          <p class="text-muted mb-0">Browse wholesale drugs from pharmaceutical sales reps in <strong><?php echo $doctor_state ?: 'your state'; ?></strong></p>
        </div>
        <div class="d-flex align-items-center" style="gap:.5rem">
          <a href="?pg=profile" class="btn btn-outline-primary"><i class="bx bx-map me-1"></i> Update Location</a>
          <a href="?pg=storefront" class="btn btn-primary"><i class="bx bx-store-alt me-1"></i> My Storefront</a>
        </div>
      </div>
    </div>
  </div>
  
</div>

<?php if(!$doctor_state){ ?>
<div class="row">
  <div class="col-12">
    <div class="alert alert-warning d-flex align-items-center">
      <i class="bx bx-error-circle me-2"></i>
      <div>
        <strong>Location Required!</strong> Please update your profile with your state to access the marketplace.
        <a href="?pg=profile" class="alert-link">Update Profile</a>
      </div>
    </div>
  </div>
</div>
<?php } else { ?>

<!-- Marketplace Stats -->
<div class="row mb-4">
  <div class="col-md-3 mb-3">
    <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg,#696cff,#8e92ff); color:#fff;">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="text-white mb-0"><?php echo count($marketplace_drugs); ?></h3>
            <small>Available Products</small>
          </div>
          <i class="bx bx-package bx-lg"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3">
    <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg,#28a745,#62d283); color:#fff;">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="text-white mb-0">
              <?php 
                $in_storefront = 0;
                foreach($marketplace_drugs as $drug) {
                  if($drug->in_my_storefront > 0) $in_storefront++;
                }
                echo $in_storefront;
              ?>
            </h3>
            <small>In My Storefront</small>
          </div>
          <i class="bx bx-store bx-lg"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3">
    <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg,#17a2b8,#59d1e7); color:#fff;">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="text-white mb-0">
              <?php 
                $unique_reps = [];
                foreach($marketplace_drugs as $drug) {
                  $unique_reps[$drug->company_name] = true;
                }
                echo count($unique_reps);
              ?>
            </h3>
            <small>Sales Reps</small>
          </div>
          <i class="bx bx-user-plus bx-lg"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3">
    <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg,#ffc107,#ffd75e); color:#fff;">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="text-white mb-0"><?php echo $doctor_state; ?></h3>
            <small>Your Location</small>
          </div>
          <i class="bx bx-map bx-lg"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Filters -->
<div class="row mb-3">
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="row g-2 align-items-center">
          <div class="col-lg-4 col-md-6">
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-light"><i class="bx bx-search"></i></span>
              <input type="text" class="form-control" id="searchDrug" placeholder="Search by name or generic...">
            </div>
          </div>
          <div class="col-md-2 col-6">
            <select class="form-select form-select-sm" id="filterCategory">
              <option value="">All Categories</option>
              <?php foreach($categories as $cat){ ?>
                <option value="<?php echo $cat->category; ?>"><?php echo $cat->category; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-2 col-6">
            <select class="form-select form-select-sm" id="filterPrice">
              <option value="">All Prices</option>
              <option value="0-1000">₦0 - ₦1,000</option>
              <option value="1000-5000">₦1,000 - ₦5,000</option>
              <option value="5000-10000">₦5,000 - ₦10,000</option>
              <option value="10000+">₦10,000+</option>
            </select>
          </div>
          <div class="col-md-2 col-6">
            <select class="form-select form-select-sm" id="filterAvailability">
              <option value="">All Products</option>
              <option value="not_in_storefront">Not in My Storefront</option>
              <option value="in_storefront">Already Added</option>
            </select>
          </div>
          <div class="col-md-2 col-6">
            <div class="d-flex" style="gap:.5rem">
              <select class="form-select form-select-sm" id="sortBy">
                <option value="newest">Sort: Newest</option>
                <option value="price_low">Price: Low → High</option>
                <option value="price_high">Price: High → Low</option>
                <option value="name">Name: A → Z</option>
              </select>
              <button class="btn btn-sm btn-outline-secondary" id="resetFilters" type="button"><i class="bx bx-reset"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Products Grid -->
<div class="row" id="marketGrid">
  <?php if(empty($marketplace_drugs)){ ?>
    <div class="col-12">
      <div class="card">
        <div class="card-body text-center py-5">
          <i class="bx bx-package bx-lg text-muted mb-3"></i>
          <h5 class="text-muted">No Products Available</h5>
          <p class="text-muted">No pharmaceutical sales reps have uploaded products in <?php echo $doctor_state; ?> yet.</p>
          <small class="text-muted">Products will appear here when sales reps in your state upload them.</small>
        </div>
      </div>
    </div>
  <?php } else { ?>
    <?php foreach($marketplace_drugs as $drug){ ?>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 market-card"
         data-name="<?php echo strtolower($drug->drug_name.' '.($drug->generic_name ?? '')); ?>"
         data-category="<?php echo strtolower($drug->category ?? ''); ?>"
         data-price="<?php echo (float)$drug->wholesale_price; ?>"
         data-availability="<?php echo $drug->in_my_storefront > 0 ? 'in_storefront' : 'not_in_storefront'; ?>">
      <div class="card h-100 border-0 shadow-sm market-item <?php echo $drug->in_my_storefront > 0 ? 'border-success' : ''; ?>" style="transition: transform .15s ease, box-shadow .15s ease; border-radius:12px; overflow:hidden;">
        <style>
          .market-item:hover{ transform: translateY(-3px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,.08) !important; }
          .market-img{ height:160px; background:#f6f7fb; display:flex; align-items:center; justify-content:center; }
          .market-img img{ width:100%; height:100%; object-fit:cover; }
          .market-body{ padding:12px 14px; display:flex; flex-direction:column; gap:.35rem; min-height:220px; }
          .market-meta small{ display:block; color:#6c757d; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
          .price-box{ background:#f9fbff; border:1px solid #eef1ff; border-radius:10px; padding:8px 10px; margin:.25rem 0 .5rem; }
          .add-btn{ border-top:1px dashed #eef1ff; padding:10px 14px; }
          .badge-top{ position:absolute; top:8px; right:8px; }
          .drug-title{ max-width:72%; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        </style>
        <?php if($drug->in_my_storefront > 0){ ?>
          <div class="badge-top">
            <span class="badge bg-success"><i class="bx bx-check"></i> In Storefront</span>
          </div>
        <?php } ?>

        <div class="market-img">
          <?php if($drug->photo){ ?>
            <img src="/assets/products/<?php echo $drug->photo; ?>" alt="<?php echo $drug->drug_name; ?>">
          <?php } else { ?>
            <i class="bx bx-package bx-lg text-muted"></i>
          <?php } ?>
        </div>

        <div class="market-body">
          <div class="d-flex justify-content-between align-items-start">
            <h6 class="card-title mb-1 drug-title"><?php echo $drug->drug_name; ?></h6>
            <?php if($drug->category){ ?>
              <span class="badge bg-label-info text-uppercase" style="font-size:.65rem; letter-spacing:.02em;"><?php echo $drug->category; ?></span>
            <?php } ?>
          </div>
          <?php if($drug->generic_name){ ?>
            <small class="text-muted"><?php echo $drug->generic_name; ?></small>
          <?php } ?>

          <div class="price-box">
            <div class="d-flex justify-content-between align-items-center">
              <small class="text-muted">Wholesale</small>
              <strong class="text-primary">₦<?php echo number_format($drug->wholesale_price, 2); ?></strong>
            </div>
            <?php if($drug->suggested_retail_price){ ?>
            <div class="d-flex justify-content-between align-items-center">
              <small class="text-muted">Suggested RRP</small>
              <small class="text-success">₦<?php echo number_format($drug->suggested_retail_price, 2); ?></small>
            </div>
            <?php } ?>
          </div>

          <div class="market-meta">
            <small>Stock: <?php echo $drug->stock_quantity; ?> <?php echo $drug->unit; ?></small>
            <small>Supplier: <?php echo $drug->company_name; ?></small>
            <small>Location: <?php echo $drug->city; ?>, <?php echo $drug->state; ?></small>
          </div>

          <?php if($drug->description){ ?>
          <small class="text-muted" style="line-height:1.35; display:block;">
            <?php echo substr($drug->description, 0, 90); ?><?php echo strlen($drug->description) > 90 ? '...' : ''; ?>
          </small>
          <?php } ?>
        </div>

        <div class="add-btn mt-auto">
          <?php if($drug->in_my_storefront > 0){ ?>
            <button class="btn btn-outline-success btn-sm w-100" disabled>
              <i class="bx bx-check me-1"></i> Already in Storefront
            </button>
          <?php } else { ?>
            <button class="btn btn-primary btn-sm w-100" onclick="addToStorefront(<?php echo $drug->id; ?>, '<?php echo addslashes($drug->drug_name); ?>', <?php echo $drug->wholesale_price; ?>, <?php echo $drug->suggested_retail_price ?? 0; ?>)">
              <i class="bx bx-plus me-1"></i> Add to My Storefront
            </button>
          <?php } ?>
        </div>
      </div>
    </div>
    <?php } ?>
  <?php } ?>
</div>

<div class="row" id="noResults" style="display:none;">
  <div class="col-12">
    <div class="card">
      <div class="card-body text-center py-5">
        <i class="bx bx-filter-alt bx-lg text-muted mb-3"></i>
        <h5 class="text-muted">No results match your filters</h5>
        <button class="btn btn-sm btn-outline-secondary mt-2" id="clearFiltersBtn"><i class="bx bx-reset"></i> Clear Filters</button>
      </div>
    </div>
  </div>
  
</div>

<!-- Add to Storefront Modal -->
<div class="modal fade" id="addToStorefrontModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add to My Storefront</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" id="addToStorefrontForm">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="action" value="add_to_storefront">
        <input type="hidden" name="marketplace_drug_id" id="modal_drug_id">
        <input type="hidden" name="wholesale_price" id="modal_wholesale_price">
        
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" class="form-control" id="modal_drug_name" readonly>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Wholesale Price (Your Cost)</label>
            <div class="input-group">
              <span class="input-group-text">₦</span>
              <input type="text" class="form-control" id="modal_wholesale_display" readonly>
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Your Retail Price (What Patients Pay) <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text">₦</span>
              <input type="number" class="form-control" name="retail_price" id="modal_retail_price" step="0.01" required>
            </div>
            <small class="text-muted">Suggested: ₦<span id="modal_suggested_price">0.00</span></small>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Your Markup</label>
            <div class="alert alert-info">
              <strong id="modal_markup_amount">₦0.00</strong> (<span id="modal_markup_percent">0</span>%)
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Initial Stock Quantity</label>
            <input type="number" class="form-control" name="stock_quantity" value="0" min="0">
            <small class="text-muted">Virtual stock - no physical inventory required</small>
          </div>
          
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured">
            <label class="form-check-label" for="is_featured">
              Feature this product in my storefront
            </label>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add to Storefront</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function addToStorefront(drugId, drugName, wholesalePrice, suggestedPrice) {
  document.getElementById('modal_drug_id').value = drugId;
  document.getElementById('modal_drug_name').value = drugName;
  document.getElementById('modal_wholesale_price').value = wholesalePrice;
  document.getElementById('modal_wholesale_display').value = wholesalePrice.toFixed(2);
  document.getElementById('modal_suggested_price').textContent = suggestedPrice.toFixed(2);
  document.getElementById('modal_retail_price').value = suggestedPrice > 0 ? suggestedPrice.toFixed(2) : (wholesalePrice * 1.3).toFixed(2);
  
  calculateMarkup();
  
  var modal = new bootstrap.Modal(document.getElementById('addToStorefrontModal'));
  modal.show();
}

document.getElementById('modal_retail_price')?.addEventListener('input', calculateMarkup);

function calculateMarkup() {
  var wholesale = parseFloat(document.getElementById('modal_wholesale_price').value) || 0;
  var retail = parseFloat(document.getElementById('modal_retail_price').value) || 0;
  
  var markup = retail - wholesale;
  var markupPercent = wholesale > 0 ? ((markup / wholesale) * 100) : 0;
  
  document.getElementById('modal_markup_amount').textContent = '₦' + markup.toFixed(2);
  document.getElementById('modal_markup_percent').textContent = markupPercent.toFixed(1);
}

// Client-side filtering & sorting
const searchDrug = document.getElementById('searchDrug');
const filterCategory = document.getElementById('filterCategory');
const filterPrice = document.getElementById('filterPrice');
const filterAvailability = document.getElementById('filterAvailability');
const sortBy = document.getElementById('sortBy');
const grid = document.getElementById('marketGrid');
const noResults = document.getElementById('noResults');
const resetFilters = document.getElementById('resetFilters');
const clearFiltersBtn = document.getElementById('clearFiltersBtn');

[searchDrug, filterCategory, filterPrice, filterAvailability, sortBy].forEach(function(el){
  el && el.addEventListener('input', applyFilters);
  el && el.addEventListener('change', applyFilters);
});

resetFilters && resetFilters.addEventListener('click', function(){
  if(searchDrug) searchDrug.value = '';
  if(filterCategory) filterCategory.value = '';
  if(filterPrice) filterPrice.value = '';
  if(filterAvailability) filterAvailability.value = '';
  if(sortBy) sortBy.value = 'newest';
  applyFilters();
});

clearFiltersBtn && clearFiltersBtn.addEventListener('click', function(){
  resetFilters.click();
});

function applyFilters(){
  const q = (searchDrug?.value || '').trim().toLowerCase();
  const cat = (filterCategory?.value || '').trim().toLowerCase();
  const price = (filterPrice?.value || '').trim();
  const avail = (filterAvailability?.value || '').trim();
  const sort = (sortBy?.value || 'newest');

  const cards = Array.from(grid.querySelectorAll('.market-card'));

  let visibleCount = 0;

  cards.forEach(function(card){
    const name = card.getAttribute('data-name') || '';
    const category = card.getAttribute('data-category') || '';
    const priceVal = parseFloat(card.getAttribute('data-price') || '0');
    const availability = card.getAttribute('data-availability') || '';

    let show = true;
    if(q && !name.includes(q)) show = false;
    if(cat && category !== cat.toLowerCase()) show = false;
    if(avail && availability !== avail) show = false;

    if(price){
      if(price === '0-1000' && !(priceVal >= 0 && priceVal <= 1000)) show = false;
      if(price === '1000-5000' && !(priceVal >= 1000 && priceVal <= 5000)) show = false;
      if(price === '5000-10000' && !(priceVal >= 5000 && priceVal <= 10000)) show = false;
      if(price === '10000+' && !(priceVal > 10000)) show = false;
    }

    card.style.display = show ? '' : 'none';
    if(show) visibleCount++;
  });

  // Sorting (only among visible)
  if(sort !== 'newest'){
    const visibleCards = cards.filter(c => c.style.display !== 'none');
    visibleCards.sort(function(a,b){
      if(sort === 'price_low'){
        return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
      }
      if(sort === 'price_high'){
        return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
      }
      if(sort === 'name'){
        return (a.dataset.name || '').localeCompare(b.dataset.name || '');
      }
      return 0;
    });
    // Re-append in new order
    visibleCards.forEach(card => grid.appendChild(card.parentElement ? card : card));
  }

  // Toggle empty state
  if(noResults){
    noResults.style.display = visibleCount === 0 ? '' : 'none';
  }
}

// Initialize filters on load
applyFilters();
</script>

</div>

<?php } ?>
