# 🔍 CURRENT APP STRUCTURE ANALYSIS

**Date:** November 1, 2025, 7:20 PM  
**Purpose:** Understand existing structure before implementing network isolation

---

## 📊 DATABASE SCHEMA ANALYSIS

### **users** Table (Current Structure)

#### Core Fields:
- `id` - Primary key
- `name` - Full name
- `email` - Unique email
- `password` - Hashed password
- `remember_token`
- `created_at`, `updated_at`

#### User Type Flags:
- `doctor` - Boolean (1 = doctor, 0 = not)
- `pharmacy` - Boolean (1 = pharmacy, 0 = not)
- `hospital` - Boolean (1 = hospital, 0 = not)
- `sales_rep` - Boolean (1 = sales rep, 0 = not)
- **Note:** Patient = all flags are 0

#### Profile Fields:
- `first_name`, `last_name`
- `phone` - Phone number
- `photo` - Profile picture
- `about` - Bio/description
- `address`, `state`, `country`
- `city` - Added in later migration
- `practice_location` - Added in later migration

#### Doctor-Specific Fields:
- `specialization` - Doctor's specialty
- `license_type` - Medical license type

#### Existing Network Fields:
- `ref_code` - Unique referral code (e.g., "MV123456")
- `referral` - ID of user who referred them
- `public` - Visibility flag (nullable, 1=public, null/0=private)

#### Other Fields:
- `authen` - Legacy authentication field
- `date` - Registration date (formatted string)

### **patients** Table (Existing Relationship System)

**Purpose:** Tracks relationships between users (doctor-patient, pharmacy-patient, etc.)

#### Fields:
- `id` - Primary key
- `user` - Patient user ID
- `patient` - Alternative patient ID reference
- `doctor` - Doctor user ID (if linked to doctor)
- `hospital` - Hospital user ID (if linked to hospital)
- `pharmacy` - Pharmacy user ID (if linked to pharmacy)

#### Approval Flags:
- `user_approve` - 1=approved, 2=declined, null=pending
- `doctor_approve` - 1=approved, 2=declined, null=pending
- `hospital_approve` - 1=approved, 2=declined, null=pending
- `pharmacy_approve` - 1=approved, 2=declined, null=pending

**Current Logic:**
- When a patient connects with a doctor, a record is created
- Both parties must approve (approve=1) for active relationship
- This is used in existing controllers to check relationships

### **pharmacy_networks** Table

**Purpose:** Pharmacy-specific network management

#### Fields:
- `id`
- `pharmacy_id` - Pharmacy owner
- `member_id` - Member user ID
- `member_type` - Enum: 'doctor', 'hospital', 'patient'
- `status` - Enum: 'pending', 'active', 'inactive'
- `invited_at`, `joined_at`
- `created_at`, `updated_at`

**Unique Constraint:** pharmacy_id + member_id + member_type

---

## 🔍 REGISTRATION FLOW ANALYSIS

### Current Registration Process (loginController.php)

#### Patient Registration:
```php
function signup_patient(Request $request) {
    // 1. Collect: email, first_name, last_name, phone, referral
    // 2. Generate ref_code: "MV" + random 6 digits
    // 3. Check if referral code exists
    // 4. Store referral as user ID (not code!)
    // 5. Insert into users table
    // 6. Redirect to login
}
```

**Key Findings:**
- ✅ Already captures `referral` field (who referred them)
- ✅ Generates unique `ref_code` for each user
- ❌ Does NOT track HOW they registered (direct vs link)
- ❌ Does NOT lock them into a network
- ❌ Does NOT differentiate registration source

#### Doctor Registration:
```php
function signup_doctor(Request $request) {
    // Similar to patient but sets doctor=1
    // Also captures: specialization, license_type
}
```

#### Pharmacy/Hospital Registration:
- Similar pattern
- Sets respective flag (pharmacy=1 or hospital=1)

---

## 🎯 WHAT EXISTS vs WHAT'S NEEDED

### ✅ What Already Exists:

1. **User Type System**
   - Boolean flags for doctor, pharmacy, hospital
   - Patients = all flags false

2. **Referral Tracking**
   - `ref_code` - Unique code per user
   - `referral` - ID of who referred them
   - Already being captured during registration!

3. **Relationship System**
   - `patients` table tracks connections
   - Approval workflow (both parties approve)
   - Used in existing controllers

4. **Pharmacy Networks**
   - Separate table for pharmacy-specific networks
   - Member type tracking
   - Status management

5. **Public/Private Flag**
   - `public` field exists in users table
   - Used in doctor controller

### ❌ What's Missing (Critical):

1. **Registration Source Tracking**
   - Need to know: direct signup vs affiliate link
   - Need to know: which provider's link (if affiliate)
   - Need to differentiate for network isolation

2. **Network Lock Mechanism**
   - No field to indicate "locked to network"
   - No enforcement of isolation
   - Search is available to everyone

3. **Affiliate Link System**
   - No unique link generation per provider
   - No tracking of link clicks/conversions
   - No way to embed provider ID in link

4. **Search Visibility Control**
   - No logic to hide search for affiliate users
   - No filtering by public_profile
   - Everyone can search everyone

5. **Store Visibility Control**
   - No restriction on which stores users see
   - All products visible to all users
   - No network-based filtering

6. **Commission Tracking**
   - No system to track purchases through network
   - No lifetime earnings calculation
   - No provider attribution

---

## 💡 KEY INSIGHTS

### 1. **Referral System Exists But Incomplete**
The app already has:
- `ref_code` generation
- `referral` field to track who referred
- BUT: It's not being used for network isolation!

### 2. **patients Table is the Relationship Core**
- This table already tracks doctor-patient, pharmacy-patient relationships
- Approval workflow exists
- We can BUILD ON THIS instead of creating new tables!

### 3. **Multiple Network Systems**
- `patients` table for general relationships
- `pharmacy_networks` table for pharmacy-specific
- Need to UNIFY or CLARIFY which to use

### 4. **Public Flag Exists**
- `public` field in users table
- Already being used in doctor controller
- Just need to ENFORCE it in search

---

## 🎯 IMPLEMENTATION STRATEGY

### Phase 1: Extend Existing Structure (Don't Rebuild)

#### Add to `users` Table:
```sql
- registration_source VARCHAR (direct, doctor_link, pharmacy_link, hospital_link, patient_link)
- affiliate_provider_id BIGINT (ID of provider who brought them)
- network_locked BOOLEAN (can they search other providers?)
- can_search_providers BOOLEAN (redundant with network_locked, but explicit)
```

#### Create `affiliate_links` Table:
```sql
- id
- provider_id (user who owns the link)
- provider_type (doctor, pharmacy, hospital)
- link_code (unique tracking code)
- link_url (full URL with code)
- clicks (counter)
- registrations (counter)
- active_users (counter)
- created_at, updated_at
```

#### Create `network_transactions` Table:
```sql
- id
- provider_id (who gets commission)
- user_id (who made purchase)
- transaction_type (drug_purchase, consultation, device)
- amount
- commission_amount
- commission_percentage
- status (pending, paid)
- created_at, updated_at
```

### Phase 2: Modify Registration Flow

#### Update loginController.php:
1. Check if URL has affiliate code parameter
2. If yes:
   - Look up provider from affiliate_links table
   - Set registration_source
   - Set affiliate_provider_id
   - Set network_locked = true
   - Create relationship in patients table
3. If no:
   - Set registration_source = 'direct'
   - Set network_locked = false
   - Allow search

### Phase 3: Implement Search Control

#### In Controllers:
```php
// Check if user can search
if ($user->network_locked == 1) {
    // Hide search, show only network providers
} else {
    // Show search, filter by public_profile
}
```

### Phase 4: Store Visibility

#### Filter Products:
```php
// Get user's network providers
$network_providers = DB::select('
    SELECT doctor, pharmacy, hospital 
    FROM patients 
    WHERE user = ? AND doctor_approve = 1
', [$user_id]);

// Show only products from these providers
```

---

## 🚨 CRITICAL DECISIONS NEEDED

### Decision 1: Use `patients` Table or Create New?
**Recommendation:** USE `patients` table
- Already exists and working
- Has approval workflow
- Used throughout app
- Just add network_locked logic on top

### Decision 2: Unified Network or Separate Tables?
**Current State:**
- `patients` table for general relationships
- `pharmacy_networks` table for pharmacy-specific

**Recommendation:** Keep both, clarify usage
- `patients` = relationship approval
- `pharmacy_networks` = pharmacy-specific features
- Add similar tables if needed for doctor/hospital networks

### Decision 3: Referral vs Affiliate?
**Current State:**
- `referral` field stores ID of referrer
- Used during registration

**Recommendation:** Enhance, don't replace
- Keep `referral` for general referrals
- Add `affiliate_provider_id` for network lock
- They can be the same or different

---

## 📋 IMPLEMENTATION CHECKLIST

### Database Changes:
- [ ] Add fields to users table (registration_source, affiliate_provider_id, network_locked)
- [ ] Create affiliate_links table
- [ ] Create network_transactions table (for commissions)
- [ ] Add indexes for performance

### Controller Changes:
- [ ] Update loginController signup methods
- [ ] Add affiliate link detection logic
- [ ] Update dashboardController search methods
- [ ] Update product/store controllers for visibility

### New Features:
- [ ] Affiliate link generation system
- [ ] Link tracking (clicks, conversions)
- [ ] Search visibility control
- [ ] Store visibility control
- [ ] Commission calculation system

### Testing:
- [ ] Test direct registration (no lock)
- [ ] Test affiliate registration (locked)
- [ ] Test search visibility
- [ ] Test store visibility
- [ ] Test relationship approval flow

---

## 🎯 NEXT STEPS

1. **Create Migration Files** (3 new tables + users updates)
2. **Update loginController** (detect affiliate links)
3. **Create AffiliateController** (link generation, tracking)
4. **Update Search Logic** (visibility control)
5. **Update Store Logic** (network-based filtering)
6. **Test End-to-End** (complete user journey)

---

**Analysis Complete!** ✅  
**Ready to implement network isolation system** 🚀

**Key Takeaway:** We can build on existing structure (patients table, referral system) rather than starting from scratch. This will be faster and maintain compatibility with existing features.
