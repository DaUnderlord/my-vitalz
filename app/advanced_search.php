<?php
// Get search parameters
$search_query = isset($_GET['q']) ? $this->sanitizeInput($_GET['q']) : '';
$category = isset($_GET['category']) ? $this->sanitizeInput($_GET['category']) : '';
$min_price = isset($_GET['min_price']) ? $this->sanitizeInput($_GET['min_price']) : '';
$max_price = isset($_GET['max_price']) ? $this->sanitizeInput($_GET['max_price']) : '';
$state = isset($_GET['state']) ? $this->sanitizeInput($_GET['state']) : '';
$sort_by = isset($_GET['sort']) ? $this->sanitizeInput($_GET['sort']) : 'relevance';

// Build search query
$where_clauses = [];
$params = [];

if($search_query){
    $where_clauses[] = "(md.drug_name LIKE ? OR md.generic_name LIKE ? OR md.description LIKE ?)";
    $search_term = '%'.$search_query.'%';
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

if($category){
    $where_clauses[] = "md.category = ?";
    $params[] = $category;
}

if($min_price){
    $where_clauses[] = "dsi.retail_price >= ?";
    $params[] = $min_price;
}

if($max_price){
    $where_clauses[] = "dsi.retail_price <= ?";
    $params[] = $max_price;
}

if($state){
    $where_clauses[] = "u.state = ?";
    $params[] = $state;
}

// Always show active products only
$where_clauses[] = "dsi.is_active = 1";
$where_clauses[] = "dss.is_active = 1";

$where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

// Determine sort order
$order_by = 'dsi.created_at DESC'; // default
switch($sort_by){
    case 'price_low':
        $order_by = 'dsi.retail_price ASC';
        break;
    case 'price_high':
        $order_by = 'dsi.retail_price DESC';
        break;
    case 'name':
        $order_by = 'md.drug_name ASC';
        break;
    case 'rating':
        $order_by = 'avg_rating DESC';
        break;
}

// Search products across all storefronts
$search_results = DB::select("
    SELECT dsi.*, md.drug_name, md.generic_name, md.category, md.unit, md.photo, md.description,
        u.id as doctor_id, u.first_name, u.last_name, u.ref_code, u.state, u.city,
        dss.storefront_name, dss.primary_color,
        COALESCE(AVG(pr.rating), 0) as avg_rating,
        COUNT(DISTINCT pr.id) as review_count
    FROM doctor_storefront_inventory dsi
    INNER JOIN marketplace_drugs md ON dsi.marketplace_drug_id = md.id
    INNER JOIN users u ON dsi.doctor_id = u.id
    INNER JOIN doctor_storefront_settings dss ON u.id = dss.doctor_id
    LEFT JOIN product_reviews pr ON dsi.id = pr.doctor_inventory_id
    $where_sql
    GROUP BY dsi.id, md.drug_name, md.generic_name, md.category, md.unit, md.photo, md.description,
        u.id, u.first_name, u.last_name, u.ref_code, u.state, u.city,
        dss.storefront_name, dss.primary_color
    ORDER BY $order_by
    LIMIT 50
", $params);

// Get available categories
$categories = DB::select('SELECT DISTINCT category FROM marketplace_drugs WHERE category IS NOT NULL ORDER BY category');

// Get available states
$states = DB::select('SELECT DISTINCT state FROM users WHERE doctor=1 AND state IS NOT NULL ORDER BY state');
?>

<div class="row">
  <div class="col-12">
    <h5 class="mb-1">Advanced Search</h5>
    <p class="text-muted mb-4">Find medications across all doctor storefronts</p>
  </div>
</div>

<!-- Search Filters -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <form method="GET" action="">
          <input type="hidden" name="pg" value="advanced-search">
          
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label small">Search</label>
              <input type="text" class="form-control" name="q" value="<?php echo $search_query; ?>" placeholder="Drug name, generic name...">
            </div>
            
            <div class="col-md-2">
              <label class="form-label small">Category</label>
              <select class="form-select" name="category">
                <option value="">All Categories</option>
                <?php foreach($categories as $cat){ ?>
                  <option value="<?php echo $cat->category; ?>" <?php echo $category == $cat->category ? 'selected' : ''; ?>>
                    <?php echo $cat->category; ?>
                  </option>
                <?php } ?>
              </select>
            </div>
            
            <div class="col-md-2">
              <label class="form-label small">Min Price</label>
              <input type="number" class="form-control" name="min_price" value="<?php echo $min_price; ?>" placeholder="₦0">
            </div>
            
            <div class="col-md-2">
              <label class="form-label small">Max Price</label>
              <input type="number" class="form-control" name="max_price" value="<?php echo $max_price; ?>" placeholder="₦10,000">
            </div>
            
            <div class="col-md-2">
              <label class="form-label small">State</label>
              <select class="form-select" name="state">
                <option value="">All States</option>
                <?php foreach($states as $st){ ?>
                  <option value="<?php echo $st->state; ?>" <?php echo $state == $st->state ? 'selected' : ''; ?>>
                    <?php echo $st->state; ?>
                  </option>
                <?php } ?>
              </select>
            </div>
            
            <div class="col-md-1 d-flex align-items-end">
              <button type="submit" class="btn btn-primary w-100">
                <i class="bx bx-search"></i>
              </button>
            </div>
          </div>
          
          <div class="row mt-3">
            <div class="col-md-2">
              <label class="form-label small">Sort By</label>
              <select class="form-select" name="sort" onchange="this.form.submit()">
                <option value="relevance" <?php echo $sort_by == 'relevance' ? 'selected' : ''; ?>>Relevance</option>
                <option value="price_low" <?php echo $sort_by == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price_high" <?php echo $sort_by == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                <option value="name" <?php echo $sort_by == 'name' ? 'selected' : ''; ?>>Name: A-Z</option>
                <option value="rating" <?php echo $sort_by == 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
              </select>
            </div>
            
            <div class="col-md-10 d-flex align-items-end">
              <a href="?pg=advanced-search" class="btn btn-outline-secondary">Clear Filters</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Search Results -->
<div class="row mb-3">
  <div class="col-12">
    <h6><?php echo count($search_results); ?> Results Found</h6>
  </div>
</div>

<div class="row">
  <?php if(empty($search_results)){ ?>
    <div class="col-12">
      <div class="card">
        <div class="card-body text-center py-5">
          <i class="bx bx-search bx-lg text-muted mb-3"></i>
          <h5 class="text-muted">No Results Found</h5>
          <p class="text-muted">Try adjusting your search filters</p>
        </div>
      </div>
    </div>
  <?php } else { ?>
    <?php foreach($search_results as $product){ ?>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card h-100">
        <?php if($product->photo){ ?>
          <img src="/assets/products/<?php echo $product->photo; ?>" class="card-img-top" alt="<?php echo $product->drug_name; ?>" style="height: 150px; object-fit: cover;">
        <?php } else { ?>
          <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
            <i class="bx bx-package bx-lg text-muted"></i>
          </div>
        <?php } ?>
        
        <div class="card-body d-flex flex-column">
          <h6 class="card-title mb-1"><?php echo $product->drug_name; ?></h6>
          <?php if($product->generic_name){ ?>
            <small class="text-muted mb-2"><?php echo $product->generic_name; ?></small>
          <?php } ?>
          
          <div class="mb-2">
            <span class="badge bg-label-info"><?php echo $product->category; ?></span>
          </div>
          
          <!-- Rating -->
          <?php if($product->review_count > 0){ ?>
          <div class="mb-2">
            <small class="text-warning">
              <?php for($i = 1; $i <= 5; $i++){ ?>
                <i class="bx <?php echo $i <= round($product->avg_rating) ? 'bxs-star' : 'bx-star'; ?>"></i>
              <?php } ?>
              (<?php echo $product->review_count; ?>)
            </small>
          </div>
          <?php } ?>
          
          <div class="mb-2">
            <h5 class="mb-0" style="color: <?php echo $product->primary_color; ?>;">₦<?php echo number_format($product->retail_price, 2); ?></h5>
          </div>
          
          <div class="mb-2">
            <small class="text-muted d-block">
              <i class="bx bx-store-alt me-1"></i><?php echo $product->storefront_name; ?>
            </small>
            <small class="text-muted d-block">
              <i class="bx bx-map me-1"></i><?php echo $product->city; ?>, <?php echo $product->state; ?>
            </small>
          </div>
          
          <div class="mt-auto">
            <a href="?pg=doctor-storefront&doctor=<?php echo $product->ref_code; ?>" class="btn btn-sm w-100" style="background: <?php echo $product->primary_color; ?>; color: white;">
              <i class="bx bx-shopping-bag me-1"></i> Visit Storefront
            </a>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  <?php } ?>
</div>
