# ⚡ Quick Storefront Test - 5 Minute Checklist

## 🚀 App Running
**URL:** http://127.0.0.1:8000

---

## ✅ Quick Test (5 minutes)

### **1. Login** (30 seconds)
```
Email: doctor@test.com
Password: password123
```
- [ ] Login successful
- [ ] Redirected to doctor dashboard

---

### **2. Access Marketplace** (30 seconds)
- [ ] Click "Marketplace" in sidebar
- [ ] Page loads with products
- [ ] See stat cards (Available Products, In My Storefront, Sales Reps, Location)
- [ ] Products displayed in grid

---

### **3. Add Product** (2 minutes)
- [ ] Click "Add to My Storefront" on any product
- [ ] Modal opens
- [ ] See wholesale price (readonly)
- [ ] Enter retail price (e.g., increase by 50%)
- [ ] Watch markup calculate automatically
- [ ] Set stock quantity (e.g., 100)
- [ ] Click "Add to Storefront"
- [ ] Success message appears
- [ ] Product now shows "Already in Storefront" badge

---

### **4. View Storefront** (1 minute)
- [ ] Click "My Storefront" in sidebar
- [ ] See product you just added
- [ ] Stats show: 1 Total Product, 1 Active, Featured count
- [ ] Product details match what you entered

---

### **5. Edit Product** (1 minute)
- [ ] Click edit/settings on the product
- [ ] Change retail price
- [ ] Markup updates automatically
- [ ] Save changes
- [ ] Success message appears
- [ ] New price displayed

---

## 🎯 Result

If all checkboxes are checked: **✅ STOREFRONT IS WORKING!**

If any failed: Check `STOREFRONT_TESTING_GUIDE.md` for detailed troubleshooting

---

## 📱 Test Accounts

### **Lagos Doctor**
- Email: `doctor@test.com`
- Password: `password123`
- Will see Lagos products

### **Abuja Doctor**
- Email: `doctor.abuja@test.com`
- Password: `password123`
- Will see Abuja products (different from Lagos)

### **Anambra Doctor**
- Email: `doctor.anambra@test.com`
- Password: `password123`
- Will see Anambra products

---

## 🔍 What to Look For

### **Marketplace Page:**
✅ Products from doctor's state only  
✅ Wholesale & retail prices shown  
✅ "Add to Storefront" buttons  
✅ Search and filter options  

### **Add Modal:**
✅ Product details pre-filled  
✅ Retail price input  
✅ Real-time markup calculator  
✅ Stock quantity input  

### **Storefront Page:**
✅ All added products listed  
✅ Accurate stats  
✅ Edit/toggle options  
✅ Supplier information  

---

## 🐛 Quick Troubleshooting

**No products showing?**
→ Check doctor's state is set in profile

**Modal won't open?**
→ Check browser console for errors

**Can't login?**
→ Verify password is `password123`

**Markup not calculating?**
→ Enter a retail price value

---

## 📊 Expected Data

- **33 products** across 10 categories
- **6 sales reps** from pharmaceutical companies
- **10 states** covered (Lagos, Abuja, Kano, etc.)
- Products range from **₦500 to ₦8,500** wholesale

---

**Server:** http://127.0.0.1:8000  
**Status:** ✅ RUNNING  
**Ready to Test:** ✅ YES
