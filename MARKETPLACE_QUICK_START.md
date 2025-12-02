# 🚀 MyVitalz Marketplace - Quick Start Guide

## 🎯 What is the Marketplace?

The **Global Pharmaceutical Marketplace** is a B2B platform where:
- **Sales Reps** from drug companies upload products with wholesale prices
- **Doctors** browse products in their state and add them to virtual storefronts
- **Patients** buy from doctor storefronts (not directly from marketplace)

---

## 🔐 Test Credentials

### Sales Representatives (6 available)
```
Email: chinedu.okafor@glaxopharm.com     | Company: GlaxoSmithKline Nigeria (Lagos)
Email: amina.bello@pfizernig.com         | Company: Pfizer Nigeria (Abuja)
Email: seun.adeyemi@mayandbakerng.com    | Company: May & Baker Nigeria (Lagos)
Email: fatima.ibrahim@novartisng.com     | Company: Novartis Nigeria (Kano)
Email: emeka.nwosu@emzorpharm.com        | Company: Emzor Pharmaceutical (Anambra)
Email: blessing.okoro@fidsonng.com       | Company: Fidson Healthcare (Ogun)

Password: password123
```

### Products Available
- **33 pharmaceutical products** across 10 categories
- **100+ marketplace entries** (products distributed across states)
- Categories: Antibiotics, Analgesics, Cardiovascular, Antidiabetics, Antimalarials, Gastrointestinal, Respiratory, Vitamins, Antifungals, Antivirals, Dermatology

---

## 📍 How to Access

### For Doctors:
1. Login to doctor dashboard: `/dashboard-doctor`
2. Click **"Marketplace"** in the sidebar (store icon)
3. Or navigate to: `/dashboard-doctor?pg=marketplace`

### For Sales Reps:
1. Login to sales rep dashboard: `/dashboard-sales-rep`
2. Upload products to marketplace
3. Manage inventory and pricing

---

## ✅ Features Verified

### ✓ Database Schema
- `marketplace_drugs` table - stores products from sales reps
- `doctor_storefront_inventory` table - tracks products doctors added
- Proper foreign keys and indexes
- Unique constraint prevents duplicate additions

### ✓ Geographic Filtering
- Doctors only see products from their state
- Prevents irrelevant product listings
- Requires doctor profile to have state set

### ✓ Product Browsing
- Grid view with product cards
- Shows: name, generic name, category, prices, stock, supplier
- Visual indicators for products already in storefront
- Placeholder images (ready for actual product photos)

### ✓ Search & Filters
- Text search by drug name
- Category dropdown filter
- Price range filters (₦0-1K, ₦1K-5K, ₦5K-10K, ₦10K+)
- Availability filter (in/not in storefront)
- Sort options (newest, price, name)

### ✓ Add to Storefront
- Modal form with product details
- Wholesale price (readonly - from marketplace)
- Retail price input (doctor sets selling price)
- Real-time markup calculator
- Virtual stock quantity
- Featured product toggle
- Form validation and error handling

### ✓ Storefront Management
- View all products in doctor's storefront
- Edit retail prices
- Toggle active/inactive status
- Toggle featured products
- Stats dashboard (total, active, featured counts)
- Link back to marketplace to add more products

### ✓ Business Logic
- Automatic markup percentage calculation
- Formula: `((retail - wholesale) / wholesale) * 100`
- Suggested retail price pre-fills (or 30% markup)
- Prevents duplicate product additions
- Maintains supplier information for reference

---

## 🧪 Testing Steps

### Test 1: Browse Marketplace
```
1. Login as doctor
2. Ensure profile has "state" field set (e.g., Lagos)
3. Click "Marketplace" in sidebar
4. Verify products from your state appear
5. Check product cards show all details
```

### Test 2: Add Product to Storefront
```
1. In marketplace, click "Add to My Storefront" on any product
2. Modal opens with product details
3. Wholesale price is pre-filled (readonly)
4. Enter retail price (e.g., ₦2,000 for ₦1,500 wholesale)
5. Watch markup calculate automatically (33.3%)
6. Set stock quantity (e.g., 100)
7. Check "Featured" if desired
8. Click "Add to Storefront"
9. Success message appears
10. Product now shows "Already in Storefront" badge
```

### Test 3: Manage Storefront
```
1. Click "My Storefront" in sidebar
2. See all products you added
3. Stats show: Total Products, Active Products, Featured Products
4. Click edit on any product
5. Change retail price
6. Toggle active/inactive
7. Toggle featured
8. Verify changes save
```

### Test 4: Search & Filter
```
1. In marketplace, use search box to find "Paracetamol"
2. Select "Analgesics" from category dropdown
3. Select price range "₦0 - ₦1,000"
4. Select "Not in My Storefront" from availability
5. Change sort to "Price: Low to High"
6. Verify results update accordingly
```

### Test 5: Geographic Filtering
```
1. Login as doctor with state = "Lagos"
2. View marketplace - see Lagos products
3. Update profile to state = "Abuja"
4. Refresh marketplace - see different products
5. Remove state from profile
6. See warning message with link to update profile
```

---

## 📊 Sample Data Overview

### Product Price Ranges
- **Budget:** ₦500 - ₦1,500 (Paracetamol, Metronidazole, Folic Acid)
- **Mid-range:** ₦1,500 - ₦3,500 (Antibiotics, Analgesics, Cardiovascular)
- **Premium:** ₦3,500 - ₦8,500 (Tramadol, Insulin, Artesunate)

### Suggested Markup
- Most products: 40-60% markup
- Example: ₦1,200 wholesale → ₦1,800 retail (50% markup)

### Stock Levels
- High stock: 500-1000 units (common drugs like Paracetamol)
- Medium stock: 250-500 units (antibiotics, vitamins)
- Low stock: 100-250 units (specialty drugs, injectables)

---

## 🔧 Technical Details

### File Locations
```
Database Migrations:
- database/migrations/2025_10_14_000002_create_marketplace_drugs_table.php
- database/migrations/2025_10_14_000003_create_doctor_storefront_inventory_table.php

Seeder:
- database/seeders/MarketplaceSeeder.php

Views:
- app/doctor_marketplace.php (marketplace browsing page)
- app/doctor_storefront.php (storefront management page)

Controller:
- app/Http/Controllers/dashboardDoctorController.php
  - add_to_storefront action (line 97-112)
  - update_storefront_product action (line 115-135)
  - toggle_featured action (line 138-146)
  - toggle_active action (line 149-157)

Dashboard:
- resources/views/dashboard_doctor.blade.php
  - Marketplace menu item (line 357-362)
  - Storefront menu item (line 363-368)
```

### Database Queries
```sql
-- Get marketplace products for doctor's state
SELECT md.*, u.company_name,
  (SELECT COUNT(*) FROM doctor_storefront_inventory 
   WHERE marketplace_drug_id = md.id AND doctor_id = ?) as in_my_storefront
FROM marketplace_drugs md
INNER JOIN users u ON md.sales_rep_id = u.id
WHERE md.state = ? AND md.status = 'active' AND md.stock_quantity > 0
ORDER BY md.created_at DESC

-- Get doctor's storefront inventory
SELECT dsi.*, md.drug_name, md.generic_name, md.category, 
       u.company_name
FROM doctor_storefront_inventory dsi
INNER JOIN marketplace_drugs md ON dsi.marketplace_drug_id = md.id
INNER JOIN users u ON md.sales_rep_id = u.id
WHERE dsi.doctor_id = ?
ORDER BY dsi.is_featured DESC, dsi.created_at DESC
```

---

## 🎨 UI/UX Features

### Color Scheme
- Primary: `#696cff` (purple-blue)
- Success: `#28a745` (green)
- Info: `#17a2b8` (cyan)
- Warning: `#ffc107` (yellow)

### Icons (Boxicons)
- Marketplace: `bx bx-store`
- Storefront: `bx bx-store-alt`
- Add: `bx bx-plus`
- Settings: `bx bx-cog`
- Package: `bx bx-package`
- Star (featured): `bx bx-star`
- Check: `bx bx-check`

### Responsive Design
- Grid: 4 columns (desktop), 3 columns (tablet), 2 columns (mobile)
- Cards: Hover effects with shadow
- Modal: Centered, responsive
- Stats: 4-column grid on desktop, stacks on mobile

---

## ⚡ Performance Notes

- **Indexing:** State, category, status columns indexed for fast filtering
- **Pagination:** Not implemented yet (consider for 100+ products)
- **Caching:** No caching yet (consider for production)
- **Images:** Placeholder system ready for actual uploads

---

## 🐛 Troubleshooting

### "No Products Available"
**Cause:** Doctor's state doesn't match any marketplace products
**Solution:** 
1. Check doctor's profile has state set
2. Verify state matches available products (Lagos, Abuja, Kano, etc.)
3. Run seeder again if needed: `php artisan db:seed --class=MarketplaceSeeder`

### "Location Required" Warning
**Cause:** Doctor profile missing state field
**Solution:** Click "Update Profile" link and set state

### Product Already in Storefront
**Cause:** Unique constraint prevents duplicates
**Solution:** This is expected behavior - edit existing storefront item instead

### Markup Not Calculating
**Cause:** JavaScript not loaded or retail price empty
**Solution:** Ensure retail price has value, check browser console for errors

---

## 📈 Next Steps

### Immediate
- [ ] Test all features with real user accounts
- [ ] Add product images for better visual appeal
- [ ] Test on mobile devices

### Short-term
- [ ] Implement patient-facing storefront views
- [ ] Add order placement workflow
- [ ] Create sales analytics dashboard

### Long-term
- [ ] Sales rep commission system
- [ ] Stock synchronization
- [ ] Notification system
- [ ] Product reviews/ratings
- [ ] Bulk operations

---

## 📞 Support

For issues or questions:
1. Check `MARKETPLACE_REVIEW.md` for detailed documentation
2. Review controller logic in `dashboardDoctorController.php`
3. Inspect database tables: `marketplace_drugs`, `doctor_storefront_inventory`
4. Check browser console for JavaScript errors

---

**Last Updated:** October 28, 2025  
**Status:** ✅ Fully Functional  
**Test Data:** ✅ Seeded Successfully
