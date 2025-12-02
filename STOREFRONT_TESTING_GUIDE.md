# 🧪 Storefront Feature - Live Testing Guide

## 🚀 App is Running!

**Server URL:** http://127.0.0.1:8000

The Laravel development server is running and ready for testing.

---

## 🔐 Test Accounts Created

### **Test Doctors (3 accounts in different states)**

#### **1. Lagos Doctor**
```
Email: doctor@test.com
Password: password123
State: Lagos
City: Ikeja
Specialization: General Practice
```
**Expected Marketplace Products:** Products from Lagos sales reps (GlaxoSmithKline, May & Baker)

#### **2. Abuja Doctor**
```
Email: doctor.abuja@test.com
Password: password123
State: Abuja
City: Wuse
Specialization: Cardiology
```
**Expected Marketplace Products:** Products from Abuja sales reps (Pfizer Nigeria)

#### **3. Anambra Doctor**
```
Email: doctor.anambra@test.com
Password: password123
State: Anambra
City: Onitsha
Specialization: Pediatrics
```
**Expected Marketplace Products:** Products from Anambra sales reps (Emzor Pharmaceutical)

### **Sales Representatives (for reference)**
```
Email: chinedu.okafor@glaxopharm.com | Password: password123 | State: Lagos
Email: amina.bello@pfizernig.com | Password: password123 | State: Abuja
Email: emeka.nwosu@emzorpharm.com | Password: password123 | State: Anambra
```

---

## 📋 Step-by-Step Testing Procedure

### **Phase 1: Login & Dashboard Access**

1. **Open the app:** http://127.0.0.1:8000
2. **Login as Lagos Doctor:**
   - Email: `doctor@test.com`
   - Password: `password123`
3. **Verify redirect** to doctor dashboard
4. **Check sidebar menu** - should see:
   - Dashboard
   - Communities
   - Appointments
   - **Marketplace** ← (this is what we're testing)
   - **My Storefront** ← (this is what we're testing)
   - Patients
   - Prescriptions
   - Products
   - etc.

---

### **Phase 2: Browse Marketplace**

5. **Click "Marketplace"** in the sidebar
6. **Verify page loads** with:
   - ✅ Page title: "Pharmaceutical Marketplace"
   - ✅ Subtitle: "Browse wholesale drugs from pharmaceutical sales reps in Lagos"
   - ✅ 4 stat cards showing:
     - Available Products (should be > 0)
     - In My Storefront (starts at 0)
     - Sales Reps (should be > 0)
     - Your Location (shows "Lagos")

7. **Check product grid:**
   - ✅ Product cards display in grid layout
   - ✅ Each card shows:
     - Drug name (e.g., "Amoxicillin 500mg")
     - Generic name
     - Category badge (e.g., "Antibiotics")
     - Wholesale price (₦ amount)
     - Suggested retail price
     - Stock quantity
     - Company name (e.g., "GlaxoSmithKline Nigeria")
     - Location (city, state)
     - Description (truncated)
     - "Add to My Storefront" button

8. **Test filters:**
   - ✅ Search box - type "Paracetamol" and verify filtering
   - ✅ Category dropdown - select "Antibiotics"
   - ✅ Price range - select "₦0 - ₦1,000"
   - ✅ Availability - select "Not in My Storefront"
   - ✅ Sort - change to "Price: Low to High"

---

### **Phase 3: Add Product to Storefront**

9. **Select a product** (e.g., Paracetamol 500mg)
10. **Click "Add to My Storefront"** button
11. **Verify modal opens** with:
    - ✅ Modal title: "Add to My Storefront"
    - ✅ Product name (readonly)
    - ✅ Wholesale price (readonly, e.g., ₦500)
    - ✅ Retail price input (pre-filled with suggested price)
    - ✅ Markup display (shows amount and percentage)

12. **Test markup calculator:**
    - Change retail price to ₦800
    - Verify markup shows: "₦300 (60%)"
    - Change to ₦1,000
    - Verify markup shows: "₦500 (100%)"

13. **Set product details:**
    - Retail price: ₦800
    - Stock quantity: 100
    - Check "Featured" checkbox

14. **Click "Add to Storefront"** button
15. **Verify success:**
    - ✅ Success message appears: "Product added to your storefront successfully!"
    - ✅ Page refreshes
    - ✅ Product now shows "Already in Storefront" badge
    - ✅ "Add to Storefront" button is disabled
    - ✅ "In My Storefront" stat increased by 1

16. **Add 2-3 more products** to have multiple items in storefront

---

### **Phase 4: Manage Storefront**

17. **Click "My Storefront"** in the sidebar
18. **Verify storefront page loads** with:
    - ✅ Page title: "My Storefront"
    - ✅ Subtitle: "Manage your virtual pharmacy inventory"
    - ✅ "Add Products" button (links back to marketplace)
    - ✅ "Settings" button

19. **Check stats cards:**
    - ✅ Total Products (should match number added)
    - ✅ Active Products (all should be active by default)
    - ✅ Featured Products (should match what you featured)
    - ✅ Average Markup (calculated from all products)

20. **Verify product table/grid shows:**
    - ✅ All products you added from marketplace
    - ✅ Product name and generic name
    - ✅ Category
    - ✅ Wholesale price (your cost)
    - ✅ Retail price (what patients pay)
    - ✅ Markup percentage
    - ✅ Stock quantity
    - ✅ Status badges (Active, Featured)
    - ✅ Supplier info (sales rep company)
    - ✅ Action buttons (Edit, Toggle Active, Toggle Featured)

---

### **Phase 5: Edit Storefront Product**

21. **Click "Edit"** on one of your products
22. **Verify edit modal/form opens** with current values
23. **Change retail price** (e.g., from ₦800 to ₦900)
24. **Verify markup recalculates** automatically
25. **Change stock quantity** (e.g., to 150)
26. **Toggle "Featured"** checkbox
27. **Click "Update"** or "Save"
28. **Verify changes saved:**
    - ✅ Success message appears
    - ✅ Product shows new retail price
    - ✅ Markup percentage updated
    - ✅ Featured badge appears/disappears

---

### **Phase 6: Toggle Product Status**

29. **Toggle "Active" switch** on a product
30. **Verify:**
    - ✅ Status changes to "Inactive"
    - ✅ Active Products count decreases
    - ✅ Product grayed out or marked as inactive

31. **Toggle "Featured" switch** on a product
32. **Verify:**
    - ✅ Featured badge appears/disappears
    - ✅ Featured Products count updates
    - ✅ Product moves to top of list (if sorted by featured)

---

### **Phase 7: Duplicate Prevention Test**

33. **Go back to Marketplace**
34. **Find a product already in your storefront**
35. **Verify:**
    - ✅ Shows "Already in Storefront" badge
    - ✅ "Add to Storefront" button is disabled
    - ✅ Button text says "Already in Storefront"

36. **Try to add the same product** (button should be disabled)
37. **Verify:** Cannot add duplicate

---

### **Phase 8: Geographic Filtering Test**

38. **Logout** from Lagos doctor account
39. **Login as Abuja doctor:**
    - Email: `doctor.abuja@test.com`
    - Password: `password123`

40. **Go to Marketplace**
41. **Verify:**
    - ✅ Shows "Browse wholesale drugs from pharmaceutical sales reps in Abuja"
    - ✅ Different products appear (from Abuja sales reps)
    - ✅ Lagos products NOT visible
    - ✅ Your Location shows "Abuja"

42. **Add some products** to Abuja doctor's storefront
43. **Verify** they appear in Abuja doctor's storefront (not Lagos doctor's)

---

### **Phase 9: Empty State Test**

44. **Login as Anambra doctor:**
    - Email: `doctor.anambra@test.com`
    - Password: `password123`

45. **Go to My Storefront**
46. **Verify empty state:**
    - ✅ Shows message: "No products in your storefront yet"
    - ✅ Shows "Add Products" button
    - ✅ Stats show 0 for all counts

47. **Click "Add Products"** button
48. **Verify:** Redirects to marketplace

---

### **Phase 10: Search & Filter Advanced Test**

49. **In Marketplace, test combinations:**
    - Search "Amoxicillin" + Category "Antibiotics"
    - Price range "₦1,000 - ₦5,000" + Sort "Price: Low to High"
    - Availability "Not in My Storefront" + Category filter

50. **Verify:**
    - ✅ Results update dynamically
    - ✅ Filters work in combination
    - ✅ No JavaScript errors in console

---

## ✅ Expected Results Summary

### **Marketplace Page Should:**
- ✅ Display products from doctor's state only
- ✅ Show accurate product information
- ✅ Calculate markup in real-time
- ✅ Prevent duplicate additions
- ✅ Show visual indicators for added products
- ✅ Filter and search work correctly

### **Storefront Page Should:**
- ✅ Display all products doctor added
- ✅ Show accurate stats
- ✅ Allow editing retail prices
- ✅ Allow toggling active/featured status
- ✅ Preserve supplier information
- ✅ Calculate markup correctly

### **Business Logic Should:**
- ✅ Wholesale price cannot be changed by doctor
- ✅ Markup percentage auto-calculates
- ✅ Geographic filtering enforced
- ✅ Unique constraint prevents duplicates
- ✅ Virtual stock (no physical inventory required)

---

## 🐛 Common Issues & Solutions

### **Issue: No products in marketplace**
**Solution:** 
- Verify doctor has state set in profile
- Check if marketplace seeder ran successfully
- Run: `php artisan db:seed --class=MarketplaceSeeder`

### **Issue: Modal doesn't open**
**Solution:**
- Check browser console for JavaScript errors
- Verify Bootstrap JS is loaded
- Clear browser cache

### **Issue: Markup not calculating**
**Solution:**
- Ensure retail price field has a value
- Check JavaScript console for errors
- Verify `calculateMarkup()` function exists

### **Issue: Can't login**
**Solution:**
- Verify test doctors created: `php artisan db:seed --class=TestDoctorSeeder`
- Check email/password are correct
- Verify database connection

---

## 📸 Screenshots to Capture

For documentation, capture screenshots of:
1. ✅ Marketplace page with product grid
2. ✅ Add to Storefront modal
3. ✅ Storefront management page
4. ✅ Edit product modal
5. ✅ Stats dashboard
6. ✅ Product with "Already in Storefront" badge
7. ✅ Empty storefront state

---

## 🎯 Success Criteria

The storefront feature is **WORKING** if:
- ✅ Doctors can browse marketplace
- ✅ Products filter by doctor's state
- ✅ Doctors can add products to storefront
- ✅ Markup calculates correctly
- ✅ Doctors can manage storefront inventory
- ✅ Duplicates are prevented
- ✅ Active/Featured toggles work
- ✅ Stats update accurately

---

## 📊 Test Results Template

```
Test Date: _______________
Tester: _______________

Phase 1 - Login & Dashboard: ☐ PASS ☐ FAIL
Phase 2 - Browse Marketplace: ☐ PASS ☐ FAIL
Phase 3 - Add to Storefront: ☐ PASS ☐ FAIL
Phase 4 - Manage Storefront: ☐ PASS ☐ FAIL
Phase 5 - Edit Product: ☐ PASS ☐ FAIL
Phase 6 - Toggle Status: ☐ PASS ☐ FAIL
Phase 7 - Duplicate Prevention: ☐ PASS ☐ FAIL
Phase 8 - Geographic Filtering: ☐ PASS ☐ FAIL
Phase 9 - Empty State: ☐ PASS ☐ FAIL
Phase 10 - Advanced Filters: ☐ PASS ☐ FAIL

Overall Status: ☐ PASS ☐ FAIL

Notes:
_________________________________
_________________________________
_________________________________
```

---

## 🔧 Developer Tools

### **Browser Console Checks:**
- No JavaScript errors
- No 404 errors for assets
- AJAX requests complete successfully

### **Network Tab Checks:**
- Form submissions return 200 status
- Redirects work correctly
- No failed asset loads

### **Database Checks:**
```sql
-- Check marketplace products
SELECT COUNT(*) FROM marketplace_drugs WHERE state = 'Lagos';

-- Check doctor's storefront
SELECT COUNT(*) FROM doctor_storefront_inventory WHERE doctor_id = ?;

-- Verify no duplicates
SELECT marketplace_drug_id, COUNT(*) 
FROM doctor_storefront_inventory 
WHERE doctor_id = ? 
GROUP BY marketplace_drug_id 
HAVING COUNT(*) > 1;
```

---

## 🎉 Next Steps After Testing

If all tests pass:
1. ✅ Document any bugs found
2. ✅ Capture screenshots for documentation
3. ✅ Test on different browsers (Chrome, Firefox, Edge)
4. ✅ Test on mobile devices
5. ✅ Prepare for user acceptance testing
6. ✅ Deploy to staging environment

---

**Happy Testing! 🚀**

**Server:** http://127.0.0.1:8000  
**Status:** ✅ Running  
**Test Data:** ✅ Seeded  
**Ready:** ✅ Yes
