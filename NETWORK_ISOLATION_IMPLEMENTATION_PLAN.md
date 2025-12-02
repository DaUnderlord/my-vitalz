# 🎯 NETWORK ISOLATION - IMPLEMENTATION PLAN

**Date:** November 1, 2025, 7:25 PM  
**Based On:** Current app analysis + Client vision  
**Approach:** Build on existing structure, don't rebuild

---

## 🏗️ ARCHITECTURE DESIGN

### Core Concept: **Registration Source Determines Network Access**

```
User Registration
    ↓
Check URL for affiliate code?
    ↓
YES → Affiliate Registration          NO → Direct Registration
    ↓                                      ↓
- Set registration_source            - Set registration_source = 'direct'
- Set affiliate_provider_id          - Set network_locked = false
- Set network_locked = true          - Can search all public providers
- Create relationship in patients    - Can see all stores
- Can ONLY see network providers     - Choose their own network
- Search is HIDDEN
```

---

## 📊 DATABASE DESIGN

### Migration 1: Extend `users` Table

```sql
ALTER TABLE users ADD COLUMN:
- registration_source VARCHAR(50) DEFAULT 'direct'
  -- Values: 'direct', 'doctor_link', 'pharmacy_link', 'hospital_link'
  
- affiliate_provider_id BIGINT UNSIGNED NULL
  -- ID of the provider who brought them (if affiliate)
  
- network_locked BOOLEAN DEFAULT 0
  -- 1 = locked to affiliate network, 0 = can search freely
  
- city VARCHAR(255) NULL
  -- City of practice/residence
  
- practice_location VARCHAR(255) NULL
  -- For doctors: where they practice
  
- INDEX (registration_source)
- INDEX (affiliate_provider_id)
- INDEX (network_locked)
```

### Migration 2: Create `affiliate_links` Table

```sql
CREATE TABLE affiliate_links (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    provider_id BIGINT UNSIGNED NOT NULL,
    provider_type ENUM('doctor', 'pharmacy', 'hospital') NOT NULL,
    link_code VARCHAR(20) UNIQUE NOT NULL,
    link_url TEXT NOT NULL,
    target_type ENUM('patient', 'doctor', 'pharmacy', 'hospital') NOT NULL,
    clicks INT DEFAULT 0,
    registrations INT DEFAULT 0,
    active_users INT DEFAULT 0,
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX (provider_id),
    INDEX (link_code),
    INDEX (provider_type)
);
```

### Migration 3: Create `network_commissions` Table

```sql
CREATE TABLE network_commissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    provider_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    transaction_type ENUM('drug_purchase', 'consultation', 'device_sale', 'subscription') NOT NULL,
    transaction_id BIGINT UNSIGNED NULL,
    amount DECIMAL(10,2) NOT NULL,
    commission_percentage DECIMAL(5,2) NOT NULL,
    commission_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'approved', 'paid', 'cancelled') DEFAULT 'pending',
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX (provider_id),
    INDEX (user_id),
    INDEX (status),
    INDEX (created_at)
);
```

### Migration 4: Create `patient_vitals_preferences` Table

```sql
CREATE TABLE patient_vitals_preferences (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    patient_id BIGINT UNSIGNED NOT NULL,
    vital_id INT NOT NULL,
    is_enabled BOOLEAN DEFAULT 1,
    forward_to_doctor BOOLEAN DEFAULT 1,
    alert_threshold_min DECIMAL(10,2) NULL,
    alert_threshold_max DECIMAL(10,2) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY (patient_id, vital_id),
    INDEX (patient_id),
    INDEX (vital_id)
);
```

---

## 🔧 IMPLEMENTATION PHASES

### **PHASE 1: Database Foundation** (Week 1)

#### Step 1.1: Create Migrations
- ✅ Create 4 migration files
- ✅ Test migrations on dev database
- ✅ Verify all indexes created

#### Step 1.2: Seed Initial Data
- Generate affiliate links for existing users
- Set network_locked = 0 for all existing users (backward compatibility)
- Set registration_source = 'direct' for existing users

---

### **PHASE 2: Affiliate Link System** (Week 1-2)

#### Step 2.1: Create AffiliateController

```php
class AffiliateController extends Controller {
    // Generate unique affiliate link
    public function generateLink(Request $request)
    
    // Track link click
    public function trackClick($link_code)
    
    // View link statistics
    public function viewStats(Request $request)
    
    // Deactivate/activate link
    public function toggleLink(Request $request)
}
```

#### Step 2.2: Link Generation Logic

```php
public function generateLink(Request $request) {
    $user = $this->checkAuth();
    $target_type = $request->input('target_type'); // patient, doctor, pharmacy
    
    // Generate unique code
    $link_code = $this->generateUniqueCode();
    
    // Create link
    $link_url = url('/signup-' . $target_type . '?ref=' . $link_code);
    
    // Save to database
    DB::insert('INSERT INTO affiliate_links (...) VALUES (...)');
    
    return $link_url;
}
```

---

### **PHASE 3: Registration Flow Update** (Week 2)

#### Step 3.1: Update loginController

```php
function signup_patient(Request $request) {
    // Check for affiliate code in URL
    $ref_code = $request->input('ref');
    
    if ($ref_code) {
        // AFFILIATE REGISTRATION
        $link = DB::select('SELECT * FROM affiliate_links WHERE link_code = ?', [$ref_code]);
        
        if ($link) {
            $provider_id = $link[0]->provider_id;
            $provider_type = $link[0]->provider_type;
            
            // Insert user with network lock
            DB::insert('INSERT INTO users (..., registration_source, affiliate_provider_id, network_locked) 
                        VALUES (..., ?, ?, 1)', [$provider_type . '_link', $provider_id]);
            
            // Create relationship in patients table
            $this->createNetworkRelationship($new_user_id, $provider_id, $provider_type);
            
            // Update link stats
            DB::update('UPDATE affiliate_links SET registrations = registrations + 1 WHERE id = ?', [$link[0]->id]);
        }
    } else {
        // DIRECT REGISTRATION
        DB::insert('INSERT INTO users (..., registration_source, network_locked) 
                    VALUES (..., "direct", 0)');
    }
}
```

#### Step 3.2: Create Network Relationship

```php
private function createNetworkRelationship($user_id, $provider_id, $provider_type) {
    if ($provider_type == 'doctor') {
        DB::insert('INSERT INTO patients (user, doctor, user_approve, doctor_approve) 
                    VALUES (?, ?, 1, 1)', [$user_id, $provider_id]);
    } elseif ($provider_type == 'pharmacy') {
        DB::insert('INSERT INTO patients (user, pharmacy, user_approve, pharmacy_approve) 
                    VALUES (?, ?, 1, 1)', [$user_id, $provider_id]);
        
        // Also add to pharmacy_networks
        DB::insert('INSERT INTO pharmacy_networks (pharmacy_id, member_id, member_type, status) 
                    VALUES (?, ?, "patient", "active")', [$provider_id, $user_id]);
    } elseif ($provider_type == 'hospital') {
        DB::insert('INSERT INTO patients (user, hospital, user_approve, hospital_approve) 
                    VALUES (?, ?, 1, 1)', [$user_id, $provider_id]);
    }
}
```

---

### **PHASE 4: Search Visibility Control** (Week 2-3)

#### Step 4.1: Update dashboardController

```php
public function search_doctors(Request $request) {
    $user = $this->checkAuth();
    
    // Check if user is network locked
    if ($user->network_locked == 1) {
        // LOCKED USER - Only show their network doctors
        $doctors = DB::select('
            SELECT u.* FROM users u
            INNER JOIN patients p ON u.id = p.doctor
            WHERE p.user = ? AND p.doctor_approve = 1 AND u.doctor = 1
        ', [$user->id]);
        
        return view('search_results', ['doctors' => $doctors, 'locked' => true]);
    } else {
        // FREE USER - Show all public doctors
        $doctors = DB::select('
            SELECT * FROM users 
            WHERE doctor = 1 AND public = 1
        ');
        
        return view('search_results', ['doctors' => $doctors, 'locked' => false]);
    }
}
```

#### Step 4.2: Hide Search UI for Locked Users

```blade
@if($user->network_locked == 0)
    <!-- Show search button -->
    <a href="/search-doctors" class="btn btn-primary">
        <i class='bx bx-search'></i> Find Doctors
    </a>
@else
    <!-- Hide search, show message -->
    <div class="alert alert-info">
        You are connected to your healthcare provider's network.
    </div>
@endif
```

---

### **PHASE 5: Store Visibility Control** (Week 3)

#### Step 5.1: Filter Products by Network

```php
public function viewProducts(Request $request) {
    $user = $this->checkAuth();
    
    if ($user->network_locked == 1) {
        // Get network providers
        $network_providers = $this->getNetworkProviders($user->id);
        
        // Show only their products
        $products = DB::select('
            SELECT p.* FROM products p
            WHERE p.user IN (?)
        ', [$network_providers]);
    } else {
        // Show all products
        $products = DB::select('SELECT * FROM products WHERE hide = 0');
    }
    
    return view('products', ['products' => $products]);
}

private function getNetworkProviders($user_id) {
    $providers = [];
    
    // Get from patients table
    $relationships = DB::select('
        SELECT doctor, pharmacy, hospital 
        FROM patients 
        WHERE user = ? AND (doctor_approve = 1 OR pharmacy_approve = 1 OR hospital_approve = 1)
    ', [$user_id]);
    
    foreach ($relationships as $rel) {
        if ($rel->doctor) $providers[] = $rel->doctor;
        if ($rel->pharmacy) $providers[] = $rel->pharmacy;
        if ($rel->hospital) $providers[] = $rel->hospital;
    }
    
    return $providers;
}
```

---

### **PHASE 6: Commission Tracking** (Week 3-4)

#### Step 6.1: Track Purchases

```php
public function recordPurchase($user_id, $amount, $product_id) {
    // Get user's affiliate provider
    $user = DB::select('SELECT affiliate_provider_id FROM users WHERE id = ?', [$user_id]);
    
    if ($user[0]->affiliate_provider_id) {
        $provider_id = $user[0]->affiliate_provider_id;
        
        // Calculate commission (5%-15% based on patient count)
        $commission_percentage = $this->calculateCommissionRate($provider_id);
        $commission_amount = $amount * ($commission_percentage / 100);
        
        // Record commission
        DB::insert('INSERT INTO network_commissions 
                    (provider_id, user_id, transaction_type, amount, commission_percentage, commission_amount, status) 
                    VALUES (?, ?, "drug_purchase", ?, ?, ?, "pending")', 
                    [$provider_id, $user_id, $amount, $commission_percentage, $commission_amount]);
    }
}

private function calculateCommissionRate($provider_id) {
    // Count active patients
    $patient_count = DB::select('
        SELECT COUNT(*) as count FROM patients 
        WHERE doctor = ? AND doctor_approve = 1
    ', [$provider_id])[0]->count;
    
    // Tiered rates
    if ($patient_count < 10) return 5.0;
    if ($patient_count < 40) return 7.5;
    if ($patient_count < 70) return 10.0;
    if ($patient_count < 100) return 12.5;
    return 15.0;
}
```

---

## 🧪 TESTING PLAN

### Test Case 1: Direct Registration
1. User visits /signup-patient (no ref code)
2. Registers account
3. Verify: registration_source = 'direct'
4. Verify: network_locked = 0
5. Verify: Can see search button
6. Verify: Can search public doctors

### Test Case 2: Affiliate Registration
1. Doctor generates affiliate link
2. Patient clicks link (has ref code)
3. Patient registers
4. Verify: registration_source = 'doctor_link'
5. Verify: affiliate_provider_id = doctor's ID
6. Verify: network_locked = 1
7. Verify: Relationship created in patients table
8. Verify: Search button is HIDDEN
9. Verify: Can only see doctor's products

### Test Case 3: Commission Tracking
1. Affiliate patient makes purchase
2. Verify: Commission record created
3. Verify: Correct percentage based on patient count
4. Verify: Provider can see earnings

---

## 📋 IMPLEMENTATION CHECKLIST

### Database:
- [ ] Create migration: extend users table
- [ ] Create migration: affiliate_links table
- [ ] Create migration: network_commissions table
- [ ] Create migration: patient_vitals_preferences table
- [ ] Run migrations
- [ ] Seed initial data

### Controllers:
- [ ] Create AffiliateController
- [ ] Update loginController (all signup methods)
- [ ] Update dashboardController (search methods)
- [ ] Update product controllers (visibility)
- [ ] Create CommissionController

### Views:
- [ ] Add affiliate link generation UI
- [ ] Update search pages (hide for locked users)
- [ ] Update product pages (filter by network)
- [ ] Create commission dashboard

### Routes:
- [ ] Add affiliate routes
- [ ] Add commission routes
- [ ] Update signup routes (handle ref parameter)

### Testing:
- [ ] Test direct registration
- [ ] Test affiliate registration
- [ ] Test search visibility
- [ ] Test store visibility
- [ ] Test commission calculation

---

## 🚀 DEPLOYMENT STRATEGY

### Week 1:
- Create all migrations
- Test on dev database
- Create AffiliateController
- Update registration flow

### Week 2:
- Implement search visibility
- Implement store visibility
- Test end-to-end

### Week 3:
- Implement commission tracking
- Create admin dashboards
- Final testing

### Week 4:
- Deploy to production
- Monitor and fix issues
- Document for client

---

**Implementation Plan Complete!** ✅  
**Ready to start coding!** 🚀

**Next Step:** Create the 4 migration files
