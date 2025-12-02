# Pharmaceutical Sales Rep & Marketplace Implementation Plan

## 📊 Code Analysis Summary

### Current Architecture Understanding:
1. **User System**: Uses `users` table with boolean flags (`doctor`, `pharmacy`, `hospital`)
2. **Authentication**: Cookie-based (`uid` cookie)
3. **Controllers**: Separate controllers for each user type (dashboardController, dashboardDoctorController, dashboardPharmacyController)
4. **Routing**: Pattern `/dashboard-{usertype}` for different dashboards
5. **Views**: Blade templates with page switching via `?pg=` query parameter
6. **Database**: Direct DB queries (no Eloquent ORM)
7. **Ref Codes**: Pattern `MV` (patient), `MVD` (doctor), `MVH` (hospital), `MVP` (pharmacy)

---

## 🎯 Implementation Phases

### **PHASE 1: Database Schema & Migrations** ✅ READY TO BUILD

#### Migration 1: Add Sales Rep Flag to Users Table
```
File: 2025_10_14_000001_add_sales_rep_to_users_table.php
- Add `sales_rep` boolean column
- Add `company_name` string column
- Add `company_license` string column
- Add `state` string column (for geo-locking)
- Add `city` string column
- Add `address` text column
```

#### Migration 2: Create Marketplace Drugs Table
```
File: 2025_10_14_000002_create_marketplace_drugs_table.php
Columns:
- id (primary key)
- sales_rep_id (foreign key to users)
- drug_name (string)
- generic_name (string, nullable)
- category (string) - e.g., Antibiotics, Analgesics, etc.
- description (text)
- wholesale_price (decimal 12,2)
- suggested_retail_price (decimal 12,2, nullable)
- stock_quantity (integer)
- reorder_level (integer, default 10)
- unit (string) - e.g., tablets, bottles, boxes
- photo (string, nullable)
- state (string) - geo-lock to state
- city (string, nullable)
- status (enum: active, inactive, out_of_stock)
- created_at, updated_at
- Indexes: sales_rep_id, state, status, category
```

#### Migration 3: Create Doctor Storefront Inventory Table
```
File: 2025_10_14_000003_create_doctor_storefront_inventory_table.php
Columns:
- id (primary key)
- doctor_id (foreign key to users)
- marketplace_drug_id (foreign key to marketplace_drugs)
- wholesale_price (decimal 12,2) - locked at time of adding
- retail_price (decimal 12,2) - doctor's markup price
- markup_percentage (decimal 5,2)
- stock_quantity (integer) - virtual stock
- is_featured (boolean, default false)
- is_active (boolean, default true)
- created_at, updated_at
- Indexes: doctor_id, marketplace_drug_id
- Unique: doctor_id + marketplace_drug_id
```

#### Migration 4: Create Storefront Orders Table
```
File: 2025_10_14_000004_create_storefront_orders_table.php
Columns:
- id (primary key)
- order_number (string, unique) - e.g., STO-20251014-001
- patient_id (foreign key to users)
- doctor_id (foreign key to users)
- sales_rep_id (foreign key to users)
- total_amount (decimal 12,2)
- doctor_commission (decimal 12,2) - markup earned
- sales_rep_amount (decimal 12,2) - wholesale price
- payment_status (enum: pending, paid, refunded)
- order_status (enum: pending, confirmed, shipped, delivered, cancelled)
- delivery_address (text)
- delivery_state (string)
- delivery_city (string)
- delivery_phone (string)
- notes (text, nullable)
- created_at, updated_at
- Indexes: patient_id, doctor_id, sales_rep_id, order_status
```

#### Migration 5: Create Storefront Order Items Table
```
File: 2025_10_14_000005_create_storefront_order_items_table.php
Columns:
- id (primary key)
- order_id (foreign key to storefront_orders)
- doctor_inventory_id (foreign key to doctor_storefront_inventory)
- drug_name (string) - snapshot
- quantity (integer)
- unit_price (decimal 12,2) - retail price at time of order
- wholesale_price (decimal 12,2) - for commission calculation
- subtotal (decimal 12,2)
- created_at, updated_at
- Index: order_id
```

#### Migration 6: Create Sales Rep Commission Tracking Table
```
File: 2025_10_14_000006_create_sales_rep_commissions_table.php
Columns:
- id (primary key)
- sales_rep_id (foreign key to users)
- order_id (foreign key to storefront_orders)
- doctor_id (foreign key to users)
- amount (decimal 12,2)
- status (enum: pending, paid, cancelled)
- paid_at (timestamp, nullable)
- created_at, updated_at
- Indexes: sales_rep_id, status, order_id
```

#### Migration 7: Create Doctor Storefront Settings Table
```
File: 2025_10_14_000007_create_doctor_storefront_settings_table.php
Columns:
- id (primary key)
- doctor_id (foreign key to users, unique)
- storefront_name (string)
- storefront_logo (string, nullable)
- storefront_banner (string, nullable)
- primary_color (string, default #696cff)
- description (text, nullable)
- is_active (boolean, default true)
- created_at, updated_at
```

#### Migration 8: Create Product Reviews Table
```
File: 2025_10_14_000008_create_product_reviews_table.php
Columns:
- id (primary key)
- patient_id (foreign key to users)
- doctor_inventory_id (foreign key to doctor_storefront_inventory)
- order_id (foreign key to storefront_orders)
- rating (integer 1-5)
- review_text (text, nullable)
- created_at, updated_at
- Indexes: patient_id, doctor_inventory_id
```

#### Migration 9: Update Users Table for State/City
```
File: 2025_10_14_000009_add_location_to_users_table.php
- Add `state` string column (for all users)
- Add `city` string column (for all users)
- Index on state for geo-filtering
```

---

### **PHASE 2: Sales Rep Module** ✅ READY TO BUILD

#### 2.1 Authentication & Signup

**File: `resources/views/signup_sales_rep.blade.php`**
- Form fields: first_name, last_name, email, phone, password, company_name, company_license, state, city, address
- Ref code pattern: `MVSR` + 6 digits

**File: `app/Http/Controllers/loginController.php`** (Update)
- Add `signup_sales_rep()` method
- Insert with `sales_rep=1` flag

**File: `routes/web.php`** (Update)
- Add routes for `/signup-sales-rep`

#### 2.2 Sales Rep Dashboard Controller

**File: `app/Http/Controllers/dashboardSalesRepController.php`** (New)
```php
Methods:
- dashboard() - Main entry point with page switching
- uploadDrug() - Handle drug upload form
- editDrug() - Update drug details
- deleteDrug() - Soft delete (set status=inactive)
- viewOrders() - See orders from their drugs
- viewDoctors() - See which doctors stock their drugs
- viewAnalytics() - Sales statistics
- updateDeliveryStatus() - Mark orders as shipped/delivered
```

**File: `routes/web.php`** (Update)
```php
Route::get('/dashboard-sales-rep', 'dashboardSalesRepController@dashboard');
Route::post('/dashboard-sales-rep', 'dashboardSalesRepController@dashboard');
```

#### 2.3 Sales Rep Dashboard Views

**File: `resources/views/dashboard_sales_rep.blade.php`** (New)
- Master layout similar to doctor/pharmacy dashboards
- Gradient sidebar (use orange/red theme for differentiation)
- Sidebar menu items:
  - Dashboard (Home)
  - My Products
  - Upload Product
  - Orders
  - Doctors Network
  - Analytics
  - Profile

**File: `app/sales_rep_home.php`** (New)
- Overview cards: Total Products, Active Doctors, Total Orders, Revenue
- Recent orders table
- Low stock alerts
- Quick upload button

**File: `app/sales_rep_products.php`** (New)
- Product list with edit/delete actions
- Filters: category, status, stock level
- Bulk actions

**File: `app/sales_rep_upload.php`** (New)
- Drug upload form with image upload
- Fields: drug_name, generic_name, category, description, wholesale_price, suggested_retail_price, stock_quantity, unit, photo
- Auto-populate state/city from sales rep profile

**File: `app/sales_rep_orders.php`** (New)
- Orders table with filters
- Status: pending, confirmed, shipped, delivered
- Update delivery status buttons
- View order details modal

**File: `app/sales_rep_doctors.php`** (New)
- List of doctors who added their products
- Stats per doctor (total orders, revenue)
- Contact doctor button

**File: `app/sales_rep_analytics.php`** (New)
- Charts: Sales over time, Top products, Revenue by doctor
- Metrics: Total revenue, Average order value, Conversion rate

---

### **PHASE 3: Doctor Marketplace Module** ✅ READY TO BUILD

#### 3.1 Marketplace View (Doctor-Only)

**File: `app/doctor_marketplace.php`** (New)
- Grid/list view of marketplace drugs
- Filters:
  - Category dropdown
  - Price range slider
  - Sales rep filter
  - Stock status (in stock, low stock)
  - State filter (auto-set to doctor's state for geo-lock)
- Product cards showing:
  - Drug image
  - Drug name & generic name
  - Wholesale price
  - Suggested retail price
  - Stock quantity
  - Sales rep name & company
  - "Add to My Storefront" button
- Search bar
- Pagination

**File: `app/Http/Controllers/dashboardDoctorController.php`** (Update)
- Add case for `pg=marketplace` in dashboard method
- Add `addToStorefront()` method - handles adding drug to doctor inventory
- Add `removeFromStorefront()` method
- Add `updateStorefrontPrice()` method

#### 3.2 Doctor Storefront Management

**File: `app/doctor_storefront.php`** (New)
- Doctor's view of their storefront inventory
- Table/grid showing:
  - Drug name
  - Wholesale price (locked)
  - Retail price (editable)
  - Markup % (calculated)
  - Stock status
  - Actions: Edit price, Remove, Toggle featured
- Quick edit modal for pricing
- Bulk actions (remove multiple, discount multiple)
- "Preview Storefront" button (opens patient view in new tab)

**File: `app/doctor_storefront_settings.php`** (New)
- Storefront customization form:
  - Storefront name
  - Logo upload
  - Banner upload
  - Primary color picker
  - Description textarea
  - Toggle storefront active/inactive

#### 3.3 Update Doctor Dashboard Sidebar

**File: `resources/views/dashboard_doctor.blade.php`** (Update)
- Add "Marketplace" menu item (icon: bx-store)
- Add "My Storefront" menu item (icon: bx-shopping-bag)
- Add "Storefront Orders" menu item (icon: bx-package)

#### 3.4 Virtual Pharmacy Integration

**File: `resources/views/pharmacy/layout.blade.php`** (Update)
- Add "Shop" menu item in virtual pharmacy sidebar
- Links to doctor's storefront management

**File: `app/pharmacy_shop.php`** (New - for virtual pharmacy context)
- Same as `doctor_storefront.php` but within pharmacy module context
- Allows doctors to manage shop from virtual pharmacy section

---

### **PHASE 4: Patient Storefront View** ✅ READY TO BUILD

#### 4.1 Doctor Storefront Public View

**File: `app/patient_doctor_storefront.php`** (New)
- E-commerce style product grid
- Doctor branding at top (logo, banner, name, description)
- Product cards:
  - Drug image
  - Drug name
  - Price
  - Stock status badge
  - "Add to Cart" button
- Shopping cart icon with item count
- Search & category filters
- Prescription upload modal (for restricted drugs)

**File: `app/patient_storefront_cart.php`** (New)
- Cart items list
- Quantity adjustment
- Remove items
- Subtotal calculation
- "Proceed to Checkout" button

**File: `app/patient_storefront_checkout.php`** (New)
- Delivery address form (auto-populate from patient profile)
- State/city validation (must match sales rep's coverage)
- Order summary
- Payment method selection
- Place order button

#### 4.2 Patient Dashboard Updates

**File: `resources/views/patient/dashboard.blade.php`** (Update)
- Replace "Shop" menu item with "Doctor Storefronts"
- Add "My Orders" menu item

**File: `app/patient_storefronts_list.php`** (New)
- List of available doctor storefronts
- Filter by specialization
- Search doctors
- "Visit Storefront" buttons
- Featured doctors section

**File: `app/patient_orders.php`** (New)
- Order history table
- Order status tracking
- Reorder button
- View order details
- Leave review button (after delivery)

---

### **PHASE 5: Order Management & Fulfillment** ✅ READY TO BUILD

#### 5.1 Order Workflow Logic

**File: `app/Http/Controllers/dashboardController.php`** (Update)
```php
Methods to add:
- placeStorefrontOrder() - Create order from cart
  - Validate patient state matches sales rep state
  - Calculate commissions
  - Create order + order items
  - Deduct virtual stock from doctor inventory
  - Notify doctor, sales rep, patient
  - Clear cart

- viewStorefrontOrder() - Order details
- cancelStorefrontOrder() - Cancel order (refund, restore stock)
```

#### 5.2 Doctor Order Management

**File: `app/doctor_storefront_orders.php`** (New)
- Orders table with filters
- Columns: Order #, Patient, Date, Total, Status, Actions
- View order details modal
- Track order button (shows delivery status)
- Commission earned per order

#### 5.3 Sales Rep Order Fulfillment

**File: `app/sales_rep_orders.php`** (Already created in Phase 2)
- Update delivery status workflow:
  - Pending → Confirmed (sales rep confirms)
  - Confirmed → Shipped (sales rep ships)
  - Shipped → Delivered (sales rep confirms delivery)
- Upload delivery proof (photo)
- Add tracking number field

---

### **PHASE 6: Financial Management** ✅ READY TO BUILD

#### 6.1 Commission Tracking

**File: `app/doctor_earnings.php`** (New)
- Earnings dashboard
- Total earnings (markup from storefront sales)
- Earnings by period (daily, weekly, monthly)
- Withdrawal request button
- Transaction history

**File: `app/sales_rep_revenue.php`** (New)
- Revenue dashboard
- Total revenue (wholesale prices)
- Revenue by doctor
- Revenue by product
- Payout history

#### 6.2 Settlement System

**File: `app/Http/Controllers/FinancialController.php`** (New)
```php
Methods:
- calculateCommissions() - Auto-calculate on order delivery
- processDoctorPayout() - Handle doctor withdrawal
- processSalesRepPayout() - Handle sales rep payment
- generateInvoice() - PDF invoice generation
```

---

### **PHASE 7: Additional Features** ✅ READY TO BUILD

#### 7.1 Reviews & Ratings

**File: `app/patient_leave_review.php`** (New)
- Review form (after order delivered)
- Star rating (1-5)
- Review text
- Submit button

**File: `app/doctor_storefront.php`** (Update)
- Show average rating per product
- Display reviews

#### 7.2 Notifications System

**Update existing notifications table usage:**
- Sales rep: New order, Low stock alert
- Doctor: New order, Product added to marketplace
- Patient: Order confirmed, Order shipped, Order delivered

#### 7.3 Search & Filters

**File: `app/Http/Controllers/dashboardController.php`** (Update)
```php
Methods:
- searchDoctorStorefronts() - AJAX search
- filterMarketplaceDrugs() - AJAX filter
- searchStorefrontProducts() - AJAX search in patient view
```

#### 7.4 Analytics & Reports

**File: `app/sales_rep_analytics.php`** (Already in Phase 2)
**File: `app/doctor_storefront_analytics.php`** (New)
- Sales performance
- Top-selling products
- Customer demographics
- Revenue trends

---

### **PHASE 8: Geo-Locking Implementation** ✅ READY TO BUILD

#### 8.1 State-Based Filtering

**Logic in Controllers:**
```php
// When patient browses doctor storefronts
- Only show doctors in same state as patient

// When doctor browses marketplace
- Only show drugs from sales reps in same state as doctor

// When patient places order
- Validate: patient.state == doctor.state == sales_rep.state
- Reject order if mismatch
```

#### 8.2 Location Validation

**File: `app/Http/Controllers/dashboardController.php`** (Update)
```php
Method: validateGeoLocation()
- Check patient state
- Check doctor state
- Check sales rep state (via marketplace_drug)
- Return true/false
```

---

## 🗂️ File Structure Summary

### New Files to Create:

**Migrations (9 files):**
1. `2025_10_14_000001_add_sales_rep_to_users_table.php`
2. `2025_10_14_000002_create_marketplace_drugs_table.php`
3. `2025_10_14_000003_create_doctor_storefront_inventory_table.php`
4. `2025_10_14_000004_create_storefront_orders_table.php`
5. `2025_10_14_000005_create_storefront_order_items_table.php`
6. `2025_10_14_000006_create_sales_rep_commissions_table.php`
7. `2025_10_14_000007_create_doctor_storefront_settings_table.php`
8. `2025_10_14_000008_create_product_reviews_table.php`
9. `2025_10_14_000009_add_location_to_users_table.php`

**Controllers (2 new, 3 updated):**
- New: `dashboardSalesRepController.php`
- New: `FinancialController.php`
- Update: `loginController.php`
- Update: `dashboardController.php`
- Update: `dashboardDoctorController.php`

**Views (1 new dashboard, 1 signup):**
- `resources/views/dashboard_sales_rep.blade.php`
- `resources/views/signup_sales_rep.blade.php`

**Page Partials (15+ new files in `app/` directory):**
- Sales Rep: `sales_rep_home.php`, `sales_rep_products.php`, `sales_rep_upload.php`, `sales_rep_orders.php`, `sales_rep_doctors.php`, `sales_rep_analytics.php`
- Doctor: `doctor_marketplace.php`, `doctor_storefront.php`, `doctor_storefront_settings.php`, `doctor_storefront_orders.php`, `doctor_earnings.php`, `doctor_storefront_analytics.php`
- Patient: `patient_doctor_storefront.php`, `patient_storefront_cart.php`, `patient_storefront_checkout.php`, `patient_storefronts_list.php`, `patient_orders.php`, `patient_leave_review.php`
- Pharmacy: `pharmacy_shop.php`

**Routes:**
- Update `routes/web.php` with ~20 new routes

---

## 🎨 UI Theme Colors

**Sales Rep Dashboard:**
- Primary: `#ff6b35` (Orange)
- Secondary: `#f7931e` (Amber)
- Gradient: Orange to Amber

**Doctor Marketplace/Storefront:**
- Keep existing purple theme (`#696cff`)

**Patient Storefront View:**
- Use doctor's custom primary color from settings
- Fallback to purple if not set

---

## 🔐 Security Considerations

1. **Authentication Checks:**
   - Verify `sales_rep=1` flag before accessing sales rep dashboard
   - Verify `doctor=1` flag before accessing marketplace
   - Verify ownership before editing/deleting

2. **Geo-Lock Validation:**
   - Server-side validation on every order
   - Cannot be bypassed via frontend

3. **Price Integrity:**
   - Lock wholesale price when doctor adds to storefront
   - Prevent price manipulation during checkout

4. **Stock Management:**
   - Prevent negative stock
   - Atomic stock deduction (use DB transactions)

5. **SQL Injection Prevention:**
   - Use parameterized queries (already doing this)
   - Sanitize all inputs

---

## 📊 Database Relationships

```
users (sales_rep=1)
  ↓ has many
marketplace_drugs
  ↓ has many
doctor_storefront_inventory (doctor_id + marketplace_drug_id)
  ↓ has many
storefront_order_items
  ↓ belongs to
storefront_orders (patient_id + doctor_id + sales_rep_id)
  ↓ has one
sales_rep_commissions
```

---

## ✅ Testing Checklist

### Phase 1 Testing:
- [ ] Run all migrations successfully
- [ ] Verify table structures in database
- [ ] Check indexes are created

### Phase 2 Testing:
- [ ] Sales rep signup works
- [ ] Sales rep login redirects to correct dashboard
- [ ] Upload drug with image
- [ ] Edit drug details
- [ ] Delete/deactivate drug
- [ ] View orders list
- [ ] Update delivery status

### Phase 3 Testing:
- [ ] Doctor sees marketplace (geo-filtered)
- [ ] Add drug to storefront
- [ ] Set retail price with markup
- [ ] Remove drug from storefront
- [ ] Customize storefront settings
- [ ] Preview storefront as patient

### Phase 4 Testing:
- [ ] Patient sees list of doctor storefronts
- [ ] Visit doctor storefront
- [ ] Add products to cart
- [ ] Checkout with delivery address
- [ ] Geo-lock validation works (reject out-of-state orders)
- [ ] Place order successfully

### Phase 5 Testing:
- [ ] Doctor receives order notification
- [ ] Sales rep receives order notification
- [ ] Sales rep updates delivery status
- [ ] Patient sees order status updates
- [ ] Order completion workflow

### Phase 6 Testing:
- [ ] Commission calculated correctly
- [ ] Doctor sees earnings
- [ ] Sales rep sees revenue
- [ ] Payout requests work

### Phase 7 Testing:
- [ ] Patient leaves review after delivery
- [ ] Reviews display on storefront
- [ ] Notifications sent correctly
- [ ] Search and filters work

### Phase 8 Testing:
- [ ] Geo-locking prevents cross-state orders
- [ ] State filtering works in marketplace
- [ ] Location validation on checkout

---

## 🚀 Deployment Steps

1. **Backup database**
2. **Run migrations** (`php artisan migrate`)
3. **Clear caches** (`php artisan cache:clear`, `php artisan view:clear`)
4. **Test signup flow** (create test sales rep account)
5. **Upload test products**
6. **Test doctor marketplace**
7. **Test patient storefront**
8. **Test complete order flow**
9. **Monitor for errors**

---

## 📈 Success Metrics

- Number of sales reps onboarded
- Number of drugs in marketplace
- Number of doctors with active storefronts
- Number of patient orders
- Total GMV (Gross Merchandise Value)
- Average order value
- Conversion rate (storefront visits → orders)
- Doctor earnings
- Sales rep revenue

---

## 🎯 Next Steps

**Ready to proceed with Phase 1: Database Migrations**

Shall I begin creating the 9 migration files?
