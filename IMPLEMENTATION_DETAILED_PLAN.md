# 🔗 AFFILIATE NETWORK - DETAILED IMPLEMENTATION PLAN

## 📋 PHASE-BY-PHASE BREAKDOWN

---

## PHASE 1: DATABASE MIGRATIONS (Week 1-2)

### Migration 1: Network Invitations Table
**File:** `database/migrations/2025_11_01_000001_create_network_invitations_table.php`

```php
Schema::create('network_invitations', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('sender_id');
    $table->enum('sender_type', ['doctor', 'patient', 'pharmacy', 'hospital']);
    $table->unsignedBigInteger('recipient_id')->nullable();
    $table->string('recipient_email')->nullable();
    $table->string('recipient_phone', 20)->nullable();
    $table->enum('recipient_type', ['doctor', 'patient', 'pharmacy', 'hospital']);
    $table->text('invitation_message');
    $table->string('invitation_token', 100)->unique();
    $table->enum('status', ['pending', 'accepted', 'declined', 'expired'])->default('pending');
    $table->timestamp('expires_at')->nullable();
    $table->timestamp('accepted_at')->nullable();
    $table->timestamps();
    
    $table->index(['recipient_email', 'status']);
    $table->index(['recipient_phone', 'status']);
    $table->index(['sender_id', 'sender_type']);
    $table->index('invitation_token');
});
```

### Migration 2: Network Relationships Table
**File:** `database/migrations/2025_11_01_000002_create_network_relationships_table.php`

```php
Schema::create('network_relationships', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('initiator_id');
    $table->enum('initiator_type', ['doctor', 'patient', 'pharmacy', 'hospital']);
    $table->unsignedBigInteger('connected_id');
    $table->enum('connected_type', ['doctor', 'patient', 'pharmacy', 'hospital']);
    $table->string('relationship_type', 50); // 'doctor-patient', 'pharmacy-patient', etc.
    $table->enum('status', ['pending', 'active', 'inactive', 'blocked'])->default('active');
    $table->enum('initiated_via', ['invitation', 'search', 'referral_link', 'admin_match']);
    $table->string('referral_code', 50)->nullable();
    $table->timestamps();
    
    $table->unique(['initiator_id', 'initiator_type', 'connected_id', 'connected_type'], 'unique_relationship');
    $table->index(['initiator_id', 'initiator_type']);
    $table->index(['connected_id', 'connected_type']);
    $table->index('status');
});
```

### Migration 3: Guardians Table
**File:** `database/migrations/2025_11_01_000003_create_guardians_table.php`

```php
Schema::create('guardians', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('patient_id');
    $table->string('guardian_name');
    $table->string('guardian_email')->nullable();
    $table->string('guardian_phone', 20);
    $table->string('relationship', 100); // 'parent', 'spouse', 'child', etc.
    $table->boolean('can_view_vitals')->default(true);
    $table->boolean('can_make_payments')->default(false);
    $table->boolean('can_book_appointments')->default(false);
    $table->boolean('is_primary')->default(false);
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
    
    $table->index('patient_id');
    $table->index('guardian_phone');
    $table->index('guardian_email');
});
```

### Migration 4: Patient Vitals Preferences
**File:** `database/migrations/2025_11_01_000004_create_patient_vitals_preferences_table.php`

```php
Schema::create('patient_vitals_preferences', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('patient_id');
    $table->string('vital_name', 100);
    $table->boolean('is_monitored')->default(true);
    $table->boolean('show_on_dashboard')->default(true);
    $table->boolean('share_with_doctors')->default(true);
    $table->decimal('alert_threshold_high', 10, 2)->nullable();
    $table->decimal('alert_threshold_low', 10, 2)->nullable();
    $table->timestamps();
    
    $table->unique(['patient_id', 'vital_name']);
    $table->index('patient_id');
});
```

### Migration 5: Doctor Virtual Pharmacy Settings
**File:** `database/migrations/2025_11_01_000005_create_doctor_virtual_pharmacy_settings_table.php`

```php
Schema::create('doctor_virtual_pharmacy_settings', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('doctor_id')->unique();
    $table->enum('plan_type', ['regular', 'platinum'])->default('regular');
    $table->enum('discount_tier', ['5', '7.5', '10', '12.5', '15'])->default('5');
    $table->integer('patient_count')->default(0);
    $table->boolean('is_active')->default(false);
    $table->decimal('consultation_fee', 10, 2)->nullable();
    $table->timestamps();
    
    $table->index('doctor_id');
});
```

### Migration 6: Consultation Sessions
**File:** `database/migrations/2025_11_01_000006_create_consultation_sessions_table.php`

```php
Schema::create('consultation_sessions', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('appointment_id');
    $table->unsignedBigInteger('doctor_id');
    $table->unsignedBigInteger('patient_id');
    $table->enum('session_type', ['physical', 'virtual']);
    $table->string('meeting_link', 500)->nullable();
    $table->string('meeting_platform', 50)->nullable(); // 'vonage', 'zoom', 'whatsapp', 'meet'
    $table->text('physical_address')->nullable();
    $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
    $table->timestamp('started_at')->nullable();
    $table->timestamp('ended_at')->nullable();
    $table->timestamps();
    
    $table->index('appointment_id');
    $table->index('doctor_id');
    $table->index('patient_id');
});
```

### Migration 7: Pharmacy Orders
**File:** `database/migrations/2025_11_01_000007_create_pharmacy_orders_table.php`

```php
Schema::create('pharmacy_orders', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('pharmacy_id');
    $table->unsignedBigInteger('sales_rep_id');
    $table->string('order_number', 50)->unique();
    $table->enum('payment_method', ['credit', 'escrow', 'cash_and_carry']);
    $table->enum('payment_status', ['pending', 'paid', 'delivered', 'confirmed', 'disputed'])->default('pending');
    $table->decimal('total_amount', 10, 2);
    $table->boolean('rep_approved')->default(false);
    $table->timestamp('rep_approved_at')->nullable();
    $table->boolean('delivery_confirmed')->default(false);
    $table->timestamp('delivery_confirmed_at')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();
    
    $table->index('pharmacy_id');
    $table->index('sales_rep_id');
    $table->index('payment_status');
});
```

### Migration 8: Pharmacy Order Items
**File:** `database/migrations/2025_11_01_000008_create_pharmacy_order_items_table.php`

```php
Schema::create('pharmacy_order_items', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('order_id');
    $table->unsignedBigInteger('drug_id');
    $table->integer('quantity');
    $table->decimal('unit_price', 10, 2);
    $table->decimal('total_price', 10, 2);
    $table->timestamps();
    
    $table->index('order_id');
    $table->foreign('order_id')->references('id')->on('pharmacy_orders')->onDelete('cascade');
});
```

### Migration 9: Payment Invoices
**File:** `database/migrations/2025_11_01_000009_create_payment_invoices_table.php`

```php
Schema::create('payment_invoices', function (Blueprint $table) {
    $table->id();
    $table->string('invoice_number', 50)->unique();
    $table->unsignedBigInteger('payer_id')->nullable();
    $table->string('payer_email')->nullable();
    $table->unsignedBigInteger('beneficiary_id');
    $table->enum('beneficiary_type', ['doctor', 'pharmacy', 'hospital', 'sales_rep']);
    $table->enum('invoice_type', ['consultation', 'medication', 'device', 'subscription']);
    $table->decimal('amount', 10, 2);
    $table->text('description');
    $table->string('payment_link', 500)->nullable();
    $table->enum('status', ['pending', 'paid', 'cancelled', 'refunded'])->default('pending');
    $table->timestamp('paid_at')->nullable();
    $table->timestamps();
    
    $table->index('payer_id');
    $table->index(['beneficiary_id', 'beneficiary_type']);
    $table->index('status');
});
```

### Migration 10: Payment Receipts
**File:** `database/migrations/2025_11_01_000010_create_payment_receipts_table.php`

```php
Schema::create('payment_receipts', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('invoice_id');
    $table->string('receipt_number', 50)->unique();
    $table->string('transaction_reference');
    $table->string('payment_method', 50);
    $table->decimal('amount_paid', 10, 2);
    $table->string('payer_name');
    $table->string('payer_email')->nullable();
    $table->string('receipt_url', 500)->nullable();
    $table->timestamps();
    
    $table->index('invoice_id');
    $table->foreign('invoice_id')->references('id')->on('payment_invoices')->onDelete('cascade');
});
```

### Migration 11: Update Users Table
**File:** `database/migrations/2025_11_01_000011_add_network_fields_to_users_table.php`

```php
Schema::table('users', function (Blueprint $table) {
    if (!Schema::hasColumn('users', 'practice_location')) {
        $table->string('practice_location')->nullable()->after('city');
    }
    if (!Schema::hasColumn('users', 'practice_type')) {
        $table->enum('practice_type', ['public', 'private'])->default('public')->after('public');
    }
    if (!Schema::hasColumn('users', 'is_discoverable')) {
        $table->boolean('is_discoverable')->default(true)->after('practice_type');
    }
    if (!Schema::hasColumn('users', 'came_via_referral')) {
        $table->boolean('came_via_referral')->default(false)->after('referral');
    }
    if (!Schema::hasColumn('users', 'network_locked')) {
        $table->boolean('network_locked')->default(false)->after('came_via_referral');
    }
});
```

---

## PHASE 2: NETWORK CONTROLLER (Week 2-3)

### NetworkController.php
**File:** `app/Http/Controllers/NetworkController.php`

**Key Methods:**

1. **searchPublicDoctors()** - Search for discoverable doctors
2. **searchPublicHospitals()** - Search for discoverable hospitals
3. **sendInvitation()** - Send network invitation
4. **receiveRequest()** - Handle incoming network request
5. **approveRequest()** - Approve connection
6. **declineRequest()** - Decline connection
7. **viewMyNetwork()** - View all connections
8. **generateInvitationLink()** - Create unique referral link
9. **removeFromNetwork()** - Remove connection
10. **blockConnection()** - Block user

---

## PHASE 3: INVITATION CONTROLLER (Week 3-4)

### InvitationController.php
**File:** `app/Http/Controllers/InvitationController.php`

**Key Methods:**

1. **generateInvitation()** - Create invitation record
2. **sendInvitationEmail()** - Email invitation
3. **sendInvitationSMS()** - SMS invitation
4. **acceptInvitation()** - Process acceptance
5. **trackInvitationClick()** - Analytics
6. **getInvitationStatus()** - Check status
7. **resendInvitation()** - Resend expired invitation

**Message Templates:**

```php
private $messageTemplates = [
    'doctor_to_patient' => 'Hello, this is Dr {name} from {city}. I now monitor and manage my patients @Virtual Clinic from MyVitalz. If you\'d like me to monitor your {vitals}, you can reach me easily by joining my network. Click: {link}',
    
    'doctor_to_pharmacy' => 'Hello {pharmacy_name}. This is Dr {name}, a {specialty} from {city}. I invite you to join my network on MyVitalz so I can patronize your store and help you manage your patients. Click: {link}',
    
    'patient_to_doctor' => 'Hello Dr {name}. This is your patient {patient_name}. Will you like to monitor and manage my {vitals} on Virtual Clinic from MyVitalz? Please click: {link}',
    
    'pharmacy_to_patient' => 'Hello, this is {pharmacy_name} in {city}. We are inviting you to join our network on Virtual Clinic to help you monitor your vitals and replenish your routine drugs. Click: {link}',
    
    'pharmacy_to_doctor' => 'Hi Dr {name}, this is {pharmacy_name} in {city}. We are seeking to join your network so we can refer patients to you or you buy drugs from us. Please click: {link}',
    
    'hospital_to_patient' => 'Hello, this is {hospital_name} in {city}. We are inviting you as our patient to join our network on Virtual Clinic to monitor your vitals. Click: {link}'
];
```

---

## PHASE 4: GUARDIAN CONTROLLER (Week 4)

### GuardianController.php
**File:** `app/Http/Controllers/GuardianController.php`

**Key Methods:**

1. **index()** - List all guardians
2. **store()** - Add new guardian
3. **update()** - Update guardian
4. **destroy()** - Remove guardian
5. **dashboard()** - Guardian dashboard view
6. **makePayment()** - Guardian payment
7. **bookAppointment()** - Guardian booking
8. **viewVitals()** - Guardian vital access

---

## PHASE 5: PAYMENT CONTROLLER (Week 5-6)

### PaymentController.php
**File:** `app/Http/Controllers/PaymentController.php`

**Key Methods:**

1. **generateInvoice()** - Create invoice
2. **createPaymentLink()** - Generate payment link
3. **processPayment()** - Handle payment
4. **generateReceipt()** - Create receipt
5. **sendReceiptEmail()** - Email receipt
6. **viewInvoice()** - Display invoice
7. **downloadReceipt()** - PDF receipt
8. **paymentHistory()** - Transaction history

---

## PHASE 6: FUNCTIONS ADDITIONS (Week 6)

### Add to app/functions.php

```php
// Network invitation
public static function send_network_invitation($sender_id, $sender_type, $recipient_email, $recipient_type, $message) {
    $token = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', strtotime('+7 days'));
    
    DB::insert('INSERT INTO network_invitations (sender_id, sender_type, recipient_email, recipient_type, invitation_message, invitation_token, expires_at, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?)', 
        [$sender_id, $sender_type, $recipient_email, $recipient_type, $message, $token, $expires_at, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
    
    return $token;
}

// Create network relationship
public static function create_network_relationship($initiator_id, $initiator_type, $connected_id, $connected_type, $relationship_type, $initiated_via, $referral_code = null) {
    DB::insert('INSERT INTO network_relationships (initiator_id, initiator_type, connected_id, connected_type, relationship_type, initiated_via, referral_code, status, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?)',
        [$initiator_id, $initiator_type, $connected_id, $connected_type, $relationship_type, $initiated_via, $referral_code, 'active', date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
}

// Add guardian
public static function add_guardian($patient_id, $guardian_name, $guardian_phone, $guardian_email, $relationship, $permissions) {
    DB::insert('INSERT INTO guardians (patient_id, guardian_name, guardian_phone, guardian_email, relationship, can_view_vitals, can_make_payments, can_book_appointments, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?)',
        [$patient_id, $guardian_name, $guardian_phone, $guardian_email, $relationship, $permissions['view_vitals'], $permissions['make_payments'], $permissions['book_appointments'], date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
}

// Update vitals preferences
public static function update_vitals_preferences($patient_id, $vital_name, $is_monitored, $show_on_dashboard, $share_with_doctors) {
    $existing = DB::select('SELECT * FROM patient_vitals_preferences WHERE patient_id = ? AND vital_name = ?', [$patient_id, $vital_name]);
    
    if (empty($existing)) {
        DB::insert('INSERT INTO patient_vitals_preferences (patient_id, vital_name, is_monitored, show_on_dashboard, share_with_doctors, created_at, updated_at) VALUES (?,?,?,?,?,?,?)',
            [$patient_id, $vital_name, $is_monitored, $show_on_dashboard, $share_with_doctors, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
    } else {
        DB::update('UPDATE patient_vitals_preferences SET is_monitored = ?, show_on_dashboard = ?, share_with_doctors = ?, updated_at = ? WHERE patient_id = ? AND vital_name = ?',
            [$is_monitored, $show_on_dashboard, $share_with_doctors, date('Y-m-d H:i:s'), $patient_id, $vital_name]);
    }
}

// Get flagged vitals for doctor
public static function get_flagged_vitals($doctor_id) {
    // Get all patients in doctor's network
    $patients = DB::select('SELECT connected_id FROM network_relationships WHERE initiator_id = ? AND initiator_type = ? AND connected_type = ? AND status = ?',
        [$doctor_id, 'doctor', 'patient', 'active']);
    
    $patient_ids = array_column($patients, 'connected_id');
    
    if (empty($patient_ids)) {
        return [];
    }
    
    // Get latest vitals for these patients with abnormal readings
    $placeholders = implode(',', array_fill(0, count($patient_ids), '?'));
    
    return DB::select("SELECT vr.*, u.first_name, u.last_name, u.phone, av.normal_range_low, av.normal_range_high 
        FROM vital_readings vr
        JOIN users u ON vr.user = u.id
        JOIN allvitalz av ON vr.vitalz = av.id
        WHERE vr.user IN ($placeholders)
        AND (CAST(vr.reading AS DECIMAL(10,2)) < av.normal_range_low OR CAST(vr.reading AS DECIMAL(10,2)) > av.normal_range_high)
        ORDER BY vr.date DESC", $patient_ids);
}

// Calculate doctor discount tier
public static function calculate_doctor_discount_tier($doctor_id) {
    $patient_count = DB::selectOne('SELECT COUNT(*) as count FROM network_relationships WHERE initiator_id = ? AND initiator_type = ? AND connected_type = ? AND status = ?',
        [$doctor_id, 'doctor', 'patient', 'active'])->count;
    
    if ($patient_count < 10) {
        $tier = '5';
    } elseif ($patient_count < 40) {
        $tier = '7.5';
    } elseif ($patient_count < 70) {
        $tier = '10';
    } elseif ($patient_count < 100) {
        $tier = '12.5';
    } else {
        $tier = '15';
    }
    
    // Update doctor's virtual pharmacy settings
    DB::update('UPDATE doctor_virtual_pharmacy_settings SET discount_tier = ?, patient_count = ?, updated_at = ? WHERE doctor_id = ?',
        [$tier, $patient_count, date('Y-m-d H:i:s'), $doctor_id]);
    
    return $tier;
}

// Generate payment invoice
public static function generate_payment_invoice($beneficiary_id, $beneficiary_type, $invoice_type, $amount, $description, $payer_email = null) {
    $invoice_number = 'INV-' . strtoupper($beneficiary_type) . '-' . date('Ymd') . '-' . mt_rand(1000, 9999);
    
    DB::insert('INSERT INTO payment_invoices (invoice_number, beneficiary_id, beneficiary_type, invoice_type, amount, description, payer_email, status, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?)',
        [$invoice_number, $beneficiary_id, $beneficiary_type, $invoice_type, $amount, $description, $payer_email, 'pending', date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
    
    return $invoice_number;
}

// Generate payment receipt
public static function generate_payment_receipt($invoice_id, $transaction_ref, $payment_method, $amount_paid, $payer_name, $payer_email) {
    $receipt_number = 'RCP-' . date('Ymd') . '-' . mt_rand(1000, 9999);
    
    DB::insert('INSERT INTO payment_receipts (invoice_id, receipt_number, transaction_reference, payment_method, amount_paid, payer_name, payer_email, created_at) VALUES (?,?,?,?,?,?,?,?)',
        [$invoice_id, $receipt_number, $transaction_ref, $payment_method, $amount_paid, $payer_name, $payer_email, date('Y-m-d H:i:s')]);
    
    // Update invoice status
    DB::update('UPDATE payment_invoices SET status = ?, paid_at = ?, updated_at = ? WHERE id = ?',
        ['paid', date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $invoice_id]);
    
    return $receipt_number;
}
```

---

## PHASE 7: ROUTES CONFIGURATION (Week 6)

### Add to routes/web.php

```php
// Network Management Routes
Route::prefix('network')->middleware('auth')->group(function() {
    Route::get('/search-doctors', 'NetworkController@searchPublicDoctors');
    Route::get('/search-hospitals', 'NetworkController@searchPublicHospitals');
    Route::post('/send-invitation', 'NetworkController@sendInvitation');
    Route::post('/send-request', 'NetworkController@receiveRequest');
    Route::post('/approve/{id}', 'NetworkController@approveRequest');
    Route::post('/decline/{id}', 'NetworkController@declineRequest');
    Route::get('/my-network', 'NetworkController@viewMyNetwork');
    Route::post('/remove/{id}', 'NetworkController@removeFromNetwork');
    Route::post('/block/{id}', 'NetworkController@blockConnection');
});

// Invitation Routes
Route::prefix('invitation')->group(function() {
    Route::get('/accept/{token}', 'InvitationController@acceptInvitation');
    Route::post('/generate', 'InvitationController@generateInvitation')->middleware('auth');
    Route::post('/send-email', 'InvitationController@sendInvitationEmail')->middleware('auth');
    Route::post('/send-sms', 'InvitationController@sendInvitationSMS')->middleware('auth');
    Route::post('/resend/{id}', 'InvitationController@resendInvitation')->middleware('auth');
});

// Guardian Routes
Route::prefix('guardian')->middleware('auth')->group(function() {
    Route::get('/manage', 'GuardianController@index');
    Route::post('/add', 'GuardianController@store');
    Route::put('/update/{id}', 'GuardianController@update');
    Route::delete('/remove/{id}', 'GuardianController@destroy');
    Route::get('/dashboard/{id}', 'GuardianController@dashboard');
    Route::post('/payment', 'GuardianController@makePayment');
    Route::post('/book-appointment', 'GuardianController@bookAppointment');
});

// Payment Routes
Route::prefix('payment')->middleware('auth')->group(function() {
    Route::post('/invoice/generate', 'PaymentController@generateInvoice');
    Route::get('/invoice/{number}', 'PaymentController@viewInvoice');
    Route::post('/process', 'PaymentController@processPayment');
    Route::get('/receipt/{number}', 'PaymentController@viewReceipt');
    Route::get('/receipt/{number}/download', 'PaymentController@downloadReceipt');
    Route::get('/history', 'PaymentController@paymentHistory');
});

// Vitals Preferences Routes
Route::prefix('vitals')->middleware('auth')->group(function() {
    Route::get('/preferences', 'PatientController@vitalsPreferences');
    Route::post('/preferences/update', 'PatientController@updateVitalsPreferences');
    Route::get('/flagged', 'DoctorController@flaggedVitals');
});
```

---

## TESTING CHECKLIST

### Network Invitation Flow
- [ ] Doctor sends invitation to patient via email
- [ ] Patient receives email with invitation link
- [ ] Patient clicks link and registers
- [ ] Network relationship is auto-created
- [ ] Patient cannot see search functionality (network locked)

### Doctor Virtual Pharmacy
- [ ] Doctor activates virtual pharmacy
- [ ] Discount tier updates based on patient count
- [ ] Doctor prescribes from virtual pharmacy
- [ ] Patient receives prescription
- [ ] Payment is processed with commission split

### Guardian Management
- [ ] Patient adds guardian during registration
- [ ] Guardian receives access credentials
- [ ] Guardian can view patient vitals
- [ ] Guardian can make payments
- [ ] Guardian can book appointments

### Payment System
- [ ] Invoice is generated for consultation
- [ ] Payment link is sent to third party
- [ ] Payment is processed successfully
- [ ] Receipt is generated and emailed
- [ ] Payment history is recorded

---

## DEPLOYMENT CHECKLIST

- [ ] Run all migrations in sequence
- [ ] Seed initial data (vitals library, specializations)
- [ ] Configure email service (SMTP/SendGrid)
- [ ] Configure SMS service (Twilio/Termii)
- [ ] Configure payment gateway (Paystack/Flutterwave)
- [ ] Test all invitation flows
- [ ] Test payment processing
- [ ] Test network isolation
- [ ] Update documentation
- [ ] Train account officers

---

**END OF DETAILED IMPLEMENTATION PLAN**
