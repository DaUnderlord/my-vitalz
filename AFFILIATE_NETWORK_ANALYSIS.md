# 🔗 AFFILIATE NETWORK MANAGEMENT - ANALYSIS & IMPLEMENTATION PLAN

## 📋 EXECUTIVE SUMMARY

**Status**: Analysis Complete - Ready for Implementation  
**Date**: November 1, 2025  
**Analyzed By**: Cascade AI  
**Scope**: Complete Affiliate Network System for Doctors, Patients, Pharmacies, Hospitals, and Guardians

---

## 🎯 CURRENT STATE ANALYSIS

### ✅ Already Implemented (Estimated 45% Complete)

#### Database Foundation (80%)
- ✅ Users table with role flags (doctor, pharmacy, hospital, sales_rep)
- ✅ Profile fields (specialization, city, state, address, public flag)
- ✅ Referral system (ref_code, referral fields)
- ✅ Patients table with approval flags
- ✅ Pharmacy networks table
- ✅ Notifications, appointments, prescriptions, vital_readings tables
- ✅ Marketplace and storefront tables
- ❌ Missing: Network invitations table
- ❌ Missing: Guardian management table
- ❌ Missing: Vitals preferences table
- ❌ Missing: Payment invoices/receipts tables

#### User Registration (70%)
- ✅ Doctor signup with specialization
- ✅ Patient signup with referral code
- ✅ Hospital, pharmacy, sales rep signup
- ❌ Missing: City/state selection during doctor signup
- ❌ Missing: Public/Private profile setting
- ❌ Missing: Guardian registration workflow

#### Network Management (40%)
- ✅ Basic patient-doctor linking
- ✅ Pharmacy network table structure
- ✅ Approval workflow flags
- ❌ Missing: Invitation system
- ❌ Missing: Network discovery
- ❌ Missing: Network isolation logic

#### Doctor Features (50%)
- ✅ Profile management
- ✅ Virtual pharmacy structure
- ✅ Prescription creation
- ✅ Patient vital monitoring
- ❌ Missing: Tiered discount system (5%-15%)
- ❌ Missing: Flagged vitals dashboard
- ❌ Missing: Virtual consultation links
- ❌ Missing: Network invitation messaging

#### Patient Features (30%)
- ✅ Vital readings recording
- ✅ Appointment booking
- ❌ Missing: Guardian management
- ❌ Missing: Vitals library selection
- ❌ Missing: Network isolation
- ❌ Missing: Doctor invitation workflow

#### Pharmacy Features (60%)
- ✅ Complete dashboard
- ✅ Inventory management
- ✅ E-prescription handling
- ❌ Missing: Patient invitation messaging
- ❌ Missing: Previous prescription reordering
- ❌ Missing: Credit/Escrow/Cash & Carry ordering

#### Payment System (20%)
- ✅ Basic cart functionality
- ❌ Missing: Payment gateway integration
- ❌ Missing: Invoice generation
- ❌ Missing: Third-party payment links
- ❌ Missing: Receipt generation

---

## 🚀 IMPLEMENTATION ROADMAP

### PHASE 1: Core Network Infrastructure (Priority: CRITICAL)

**New Database Tables Required:**
1. `network_invitations` - Track all invitation requests
2. `network_relationships` - Unified network connections
3. `guardians` - Patient guardian management
4. `patient_vitals_preferences` - Vitals monitoring settings
5. `doctor_virtual_pharmacy_settings` - Virtual pharmacy config
6. `consultation_sessions` - Virtual/physical consultations
7. `pharmacy_orders` - Orders from sales reps
8. `pharmacy_order_items` - Order line items
9. `payment_invoices` - Payment invoicing
10. `payment_receipts` - Payment receipts

**Users Table Updates:**
- Add `practice_location` field
- Add `practice_type` ENUM('public', 'private')
- Add `is_discoverable` flag
- Add `came_via_referral` flag
- Add `network_locked` flag

### PHASE 2: Doctor Network Features (Priority: HIGH)

**Registration Enhancement:**
- Add city/state dropdown selection
- Add primary practice location field
- Add Public/Private profile toggle
- Store practice_type and is_discoverable flags

**Network Management:**
- Search public doctors by specialty/location
- Send invitations to patients/pharmacies/hospitals
- Receive and approve/decline network requests
- Generate unique referral links
- View network connections dashboard

**Virtual Pharmacy:**
- Tiered discount system (5%-15% based on patient count)
- Automatic patient count tracking
- Prescription source selection
- Inventory population from marketplace
- Commission calculation

**Vital Monitoring:**
- Flagged vitals dashboard (abnormal readings)
- Automatic vital forwarding from patients
- One-click consultation invitation
- Virtual/physical consultation scheduling

### PHASE 3: Patient Network Features (Priority: HIGH)

**Registration Enhancement:**
- Detect referral link and extract referrer
- Store came_via_referral and network_locked flags
- Guardian registration during signup
- Vitals selection during onboarding

**Network Management:**
- Doctor search (only if not referred)
- Send doctor invitation by phone
- Request affiliation with public doctors
- Guardian management (add/edit/remove)
- Vitals library selection
- Enable/disable doctor access to vitals

**Dashboard Customization:**
- Display only selected vitals
- Grey out inactive vitals
- Quick guardian access
- Network isolation enforcement

### PHASE 4: Pharmacy Network Features (Priority: MEDIUM)

**Patient Invitation:**
- Send patient invitation messages
- Send doctor invitation messages
- Track invitation status
- Previous prescription reordering

**Ordering System:**
- Order from sales reps with payment method selection
- Credit order approval workflow
- Escrow payment holding
- Cash & carry instant payment
- Delivery confirmation

### PHASE 5: Hospital Network Features (Priority: MEDIUM)

**Network Management:**
- Patient invitation system
- Doctor affiliation requests
- Network management dashboard
- Pharmacy partnerships

### PHASE 6: Invitation & Messaging System (Priority: HIGH)

**Unified Invitation System:**
- Generate invitation links
- Send invitation emails/SMS
- Process invitation acceptance
- Track invitation clicks
- Message templates for all user types

**Notification Enhancement:**
- Network request notifications
- Invitation acceptance notifications
- Flagged vitals alerts
- Prescription ready notifications
- Payment received notifications

### PHASE 7: Payment System (Priority: HIGH)

**Payment Gateway Integration:**
- Invoice generation with unique numbers
- Payment link creation for third-party payers
- Payment gateway integration (Paystack/Flutterwave)
- Receipt generation and email delivery
- Payment history tracking
- Commission distribution

### PHASE 8: Guardian System (Priority: MEDIUM)

**Guardian Management:**
- Add guardian with permissions
- Guardian dashboard access
- Guardian payment capabilities
- Guardian appointment booking
- Guardian vital viewing
- Multiple guardians per patient
- Primary guardian designation

### PHASE 9: Advanced Features (Priority: LOW)

**Account Officer Assignment:**
- Assign account officers to clients
- Track client support interactions

**Doctor Performance Tracking:**
- Track invitation acceptance speed
- Track patient response time
- Calculate performance scores
- Prioritize high-performing doctors

**Automated Patient-Doctor Matching:**
- Match based on proximity
- Match based on specialty
- Match based on gender preference
- Cascade requests with 12-hour timeout

---

## 📊 IMPLEMENTATION PRIORITY MATRIX

### Must Have (Phase 1-3) - 4-6 weeks
1. Network invitations table and controller
2. Doctor public/private profile settings
3. Patient network isolation
4. Guardian management
5. Vitals preferences
6. Basic invitation messaging

### Should Have (Phase 4-7) - 4-6 weeks
1. Pharmacy ordering system
2. Hospital network features
3. Payment gateway integration
4. Invoice/receipt generation
5. Virtual consultation links
6. Doctor tiered discounts

### Nice to Have (Phase 8-9) - 2-4 weeks
1. Account officer assignment
2. Doctor performance tracking
3. Automated patient-doctor matching
4. Advanced analytics

---

## 🔧 TECHNICAL REQUIREMENTS

### New Controllers Needed:
1. `NetworkController.php` - Network management
2. `InvitationController.php` - Invitation system
3. `GuardianController.php` - Guardian management
4. `PaymentController.php` - Payment processing
5. `HospitalNetworkController.php` - Hospital features
6. `MatchingController.php` - Patient-doctor matching

### New Views Needed:
1. Doctor network views (invitations, my network, send invitation)
2. Patient network views (find doctor, my network, guardians, vitals preferences)
3. Pharmacy invitation views
4. Hospital network views
5. Payment views (invoice, receipt, payment link)
6. Guardian dashboard views

### Functions to Add:
1. `send_network_invitation()`
2. `create_network_relationship()`
3. `add_guardian()`
4. `update_vitals_preferences()`
5. `get_flagged_vitals()`
6. `calculate_doctor_discount_tier()`
7. `generate_payment_invoice()`
8. `generate_payment_receipt()`

---

## 🚨 CRITICAL IMPLEMENTATION RULES

### Network Isolation Rules:
1. IF patient.came_via_referral == TRUE THEN hide all search functionality
2. IF patient.network_locked == TRUE THEN only show network members from referrer
3. IF doctor.practice_type == 'private' THEN is_discoverable = FALSE
4. IF user registered via invitation link THEN auto-create network relationship

### Approval Workflow Rules:
1. All network requests require explicit approval
2. Declined requests can be re-sent after 30 days
3. Inactive relationships can be reactivated
4. Blocked relationships cannot be restored

### Payment Rules:
1. All payments must generate invoice before processing
2. All successful payments must generate receipt
3. Receipts must be emailed to payer and beneficiary
4. Commission distribution happens after payment confirmation

---

## ⏱️ ESTIMATED TIMELINE

**Total Implementation Time: 10-16 weeks**

- Phase 1: Core Infrastructure (1-2 weeks)
- Phase 2: Doctor Features (2-3 weeks)
- Phase 3: Patient Features (1-2 weeks)
- Phase 4: Pharmacy Features (1 week)
- Phase 5: Hospital Features (1 week)
- Phase 6: Invitation System (1 week)
- Phase 7: Payment System (2-3 weeks)
- Phase 8: Guardian System (1 week)
- Phase 9: Advanced Features (1-2 weeks)

---

## 📝 NEXT STEPS

1. **Review and approve this plan**
2. **Create all database migrations (Phase 1)**
3. **Implement core network infrastructure**
4. **Build doctor network features**
5. **Build patient network features**
6. **Integrate payment system**
7. **Test end-to-end workflows**
8. **Deploy to production**

---

## ✅ CONCLUSION

The MyVitalz platform has a solid foundation with approximately **45% of the affiliate network requirements already implemented**. The remaining **55% requires focused development** across 9 phases over 10-16 weeks.

**Key Strengths:**
- Database structure is well-designed
- User registration flows exist
- Basic network relationships are tracked
- Pharmacy module is well-developed

**Key Gaps:**
- No invitation/messaging system
- No network isolation enforcement
- No guardian management
- No payment invoicing/receipts
- No virtual consultation links
- No tiered discount system

**Recommendation:** Proceed with Phase 1 (Core Network Infrastructure) immediately, as it forms the foundation for all other features.
