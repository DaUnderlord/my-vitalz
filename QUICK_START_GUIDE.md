# 🚀 MyVitalz Pharmacy Module - Quick Start Guide

## Get Started in 5 Minutes!

---

## ⚡ Prerequisites Check

Before starting, ensure you have:
- ✅ PHP 8.1+ installed
- ✅ MySQL/MariaDB running
- ✅ Composer installed
- ✅ Node.js & npm installed
- ✅ Laravel project already set up

---

## 📋 Installation Steps

### Step 1: Run Migrations (2 minutes)

Open your terminal in the project directory:

```bash
cd C:\Users\HP\Downloads\app
php artisan migrate
```

This creates 7 new tables:
- pharmacy_patients
- patient_vitals
- pharmacy_messages
- pharmacy_consultations
- prescription_medications
- pharmacy_settings
- Updates e_prescriptions table

**Expected Output:**
```
Migrating: 2025_01_01_000006_create_pharmacy_patients_table
Migrated:  2025_01_01_000006_create_pharmacy_patients_table (XX.XXms)
...
```

### Step 2: Clear Caches (30 seconds)

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

**Or visit these URLs in your browser:**
- http://localhost:8000/route-clear
- http://localhost:8000/config-cache
- http://localhost:8000/clear-cache
- http://localhost:8000/view-clear

### Step 3: Start the Server (10 seconds)

```bash
php artisan serve
```

**Expected Output:**
```
Starting Laravel development server: http://127.0.0.1:8000
```

### Step 4: Access Pharmacy Dashboard (1 minute)

1. **Create/Login as Pharmacy User:**
   - Visit: http://localhost:8000/signup-pharmacy
   - Or login if you already have a pharmacy account

2. **Access Dashboard:**
   - Visit: http://localhost:8000/dashboard-pharmacy
   - You should see the pharmacy dashboard with statistics

---

## 🎯 First-Time Setup

### Create Your First Inventory Item

1. Click **Inventory** in the sidebar
2. Click **Add Item** button
3. Fill in the form:
   - Medication Name: "Paracetamol 500mg"
   - Generic Name: "Acetaminophen"
   - Form: "Tablet"
   - Stock Quantity: 100
   - Reorder Level: 20
   - Retail Price: 500
4. Click **Save Item**

### Register Your First Patient

1. Click **Patient Monitoring** in the sidebar
2. Click **Register Patient** button
3. Fill in required fields:
   - Full Name
   - Date of Birth
   - Gender
   - Phone Number
4. Click **Register Patient**

### Add a Network Partner

1. Click **Network** in the sidebar
2. Enter partner email
3. Select partner type (Pharmacy/Hospital)
4. Click **Send Invite**

---

## 🔍 Feature Tour

### Dashboard (Home)
- View statistics: network members, prescriptions, inventory, revenue
- See recent prescriptions
- Check low stock alerts
- Quick action buttons

### E-Prescriptions
- Create multi-medication prescriptions
- Track status workflow
- Search and filter prescriptions
- Update prescription status

### Inventory Management
- Add/edit/delete medications
- View tiered pricing (retail/doctor/wholesale)
- Set discount policies
- Create clearance sales
- Request out-of-stock items

### Patient Monitoring
- Register patients
- Record vitals (BP, sugar, heart rate, etc.)
- View alerts for abnormal vitals
- Schedule consultations
- Track medication stock per patient

### Network Management
- Invite partners (pharmacies/hospitals)
- View network members
- Send messages to partners
- Remove partners

### Doctor Rewards
- View rewards statistics
- Track pending/paid rewards
- Mark rewards as paid
- Filter rewards history

### Settings
- Configure doctor markup percentage
- Set default delivery fee
- Update discount policy
- Link partners for OSR/clearance
- Copy virtual pharmacy link

### Messages
- Chat with network partners
- View conversation threads
- Send/receive messages
- Auto-refresh every 10 seconds

---

## 🐛 Troubleshooting

### Issue: "Class 'PharmacyController' not found"
**Solution:**
```bash
composer dump-autoload
```

### Issue: "Table doesn't exist"
**Solution:**
```bash
php artisan migrate:fresh
# Warning: This will delete all data!
```

### Issue: "View not found"
**Solution:**
Check that files exist in `resources/views/pharmacy/` directory

### Issue: "Route not found"
**Solution:**
```bash
php artisan route:clear
php artisan route:list | grep pharmacy
```

### Issue: "Unauthorized access"
**Solution:**
Ensure your user has `pharmacy = 1` in the users table:
```sql
UPDATE users SET pharmacy = 1 WHERE id = YOUR_USER_ID;
```

---

## 📱 Navigation Guide

### Sidebar Menu
- **Dashboard** - Statistics and overview
- **Patient Monitoring** - Patient management
- **E-Prescriptions** - Prescription workflow
- **Inventory** - Stock management
- **Network** - Partner management
- **Doctor Rewards** - Rewards tracking
- **Virtual Pharmacy Settings** - Configuration
- **Messages** - Communication

### URL Parameters
All pages use the `?pg=` parameter:
- `/dashboard-pharmacy` - Home
- `/dashboard-pharmacy?pg=monitoring` - Patient Monitoring
- `/dashboard-pharmacy?pg=prescriptions` - E-Prescriptions
- `/dashboard-pharmacy?pg=inventory` - Inventory
- `/dashboard-pharmacy?pg=network` - Network
- `/dashboard-pharmacy?pg=rewards` - Rewards
- `/dashboard-pharmacy?pg=settings` - Settings
- `/dashboard-pharmacy?pg=messages` - Messages

---

## 🎨 UI Components

### Modals
- Create Prescription
- Add Inventory Item
- Register Patient
- Record Vitals
- Schedule Consultation
- Clearance Sale
- Out of Stock Request
- Payment Confirmation

### Tables
- Recent Prescriptions
- Inventory Items
- Patient List
- Network Partners
- Rewards History
- Message Threads

### Forms
- All forms use AJAX for submission
- Real-time validation
- Success/error notifications
- No page reloads

---

## 🔑 Keyboard Shortcuts

- **Enter** in message input - Send message
- **Escape** - Close modals
- **Tab** - Navigate form fields

---

## 💾 Data Management

### Backup Database
```bash
mysqldump -u root -p database_name > backup.sql
```

### Restore Database
```bash
mysql -u root -p database_name < backup.sql
```

### Export Patient Data
Use the "Export History" button in Patient Monitoring

---

## 📊 Sample Data (Optional)

To test with sample data, you can manually insert:

```sql
-- Sample Inventory
INSERT INTO pharmacy_inventory (pharmacy_id, medication_name, generic_name, form, stock_quantity, reorder_level, retail_price, created_at, updated_at) 
VALUES (1, 'Paracetamol 500mg', 'Acetaminophen', 'Tablet', 100, 20, 500.00, NOW(), NOW());

-- Sample Patient
INSERT INTO pharmacy_patients (pharmacy_id, full_name, date_of_birth, gender, phone, created_at, updated_at) 
VALUES (1, 'John Doe', '1980-01-01', 'male', '+234-801-234-5678', NOW(), NOW());
```

---

## 🎓 Best Practices

### Daily Operations
1. Check dashboard for pending prescriptions
2. Review low stock alerts
3. Respond to messages from partners
4. Record patient vitals during visits
5. Update prescription statuses

### Weekly Tasks
1. Review inventory levels
2. Process pending rewards
3. Analyze sales trends
4. Update discount policies if needed
5. Backup database

### Monthly Tasks
1. Review network partners
2. Analyze monthly revenue
3. Update expired medications
4. Review patient compliance
5. Plan inventory restocking

---

## 🆘 Getting Help

### Documentation Files
- `PHARMACY_MODULE_IMPLEMENTATION.md` - Comprehensive guide
- `IMPLEMENTATION_SUMMARY.md` - Quick overview
- `PHARMACY_IMPLEMENTATION_COMPLETE.md` - Full details
- `QUICK_START_GUIDE.md` - This file

### Check Logs
```bash
tail -f storage/logs/laravel.log
```

### Debug Mode
In `.env` file:
```
APP_DEBUG=true
```
**Remember to set to `false` in production!**

---

## ✅ Success Indicators

You'll know everything is working when:
- ✅ Dashboard loads with statistics
- ✅ Can create prescriptions
- ✅ Can add inventory items
- ✅ Can register patients
- ✅ Can send messages
- ✅ No errors in browser console
- ✅ No errors in Laravel logs

---

## 🎉 You're Ready!

Congratulations! Your pharmacy module is now set up and ready to use.

### What's Next?
1. Explore all features
2. Customize settings
3. Add your inventory
4. Register patients
5. Start managing prescriptions

### Need More Help?
- Review the comprehensive documentation
- Check Laravel logs for errors
- Test each feature systematically
- Refer to the demo HTML for UI reference

---

**Happy Managing! 🚀**

*Last Updated: 2025-09-30*
*Version: 1.0.0*
