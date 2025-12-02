<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Cookie;

class PharmacyController extends Controller
{
    private function sanitizeInput($input)
    {
        return is_string($input) ? strip_tags(htmlspecialchars(trim($input))) : $input;
    }
    
    private function checkAuth(Request $request)
    {
        if (!$request->hasCookie('uid')) {
            return redirect()->to("/")->send();
        }
        
        $uid = Cookie::get('uid');
        $user = DB::select('select * from users WHERE id=' . intval($uid));
        
        // Allow both doctors and pharmacies to access (pharmacy module is now doctor module)
        if (empty($user) || ($user[0]->pharmacy != 1 && $user[0]->doctor != 1)) {
            return redirect()->to("/")->send();
        }
        
        return $user[0];
    }
    
    public function dashboard(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        // Get page parameter - default to monitoring (vitals monitoring)
        $pg = $request->input('pg', 'monitoring');
        
        // Route to appropriate page
        switch($pg) {
            case 'network':
                return $this->network($request);
            case 'prescriptions':
                return $this->prescriptions($request);
            case 'inventory':
                return $this->inventory($request);
            case 'monitoring':
                return $this->monitoring($request);
            case 'rewards':
                return $this->rewards($request);
            case 'settings':
                return $this->settings($request);
            case 'messages':
                return $this->messages($request);
            case 'profile':
                return $this->profile($request);
            case 'appointments':
                return $this->appointments($request);
            case 'appointment-details':
                return $this->appointmentDetails($request);
            case 'patient-details':
                return $this->patientDetails($request);
            case 'patient-reading-history':
                return $this->patientReadingHistory($request);
            case 'patient-medications':
                return $this->patientMedications($request);
            case 'new-prescription':
                return $this->newPrescription($request);
            case 'edit-prescription':
                return $this->editPrescription($request);
            case 'affiliates':
                return $this->affiliates($request);
            case 'marketplace':
                return $this->marketplace($request);
            case 'storefront':
                return $this->storefront($request);
            case 'storefront-settings':
                return $this->storefrontSettings($request);
            case 'patient-vitals':
                return $this->patientVitals($request);
            case 'thresholds':
                return $this->thresholds($request);
            case 'storefront-products':
                return $this->storefrontProducts($request);
            case 'store':
                return $this->store($request);
            case 'support':
                return $this->support($request);
            case 'support-details':
                return $this->supportDetails($request);
            case 'referrals':
                return $this->referrals($request);
            case 'readings':
                return $this->readings($request);
            default:
                // Home page
                break;
        }
        
        // Get pharmacy settings
        $settings = DB::select('select * from pharmacy_settings WHERE pharmacy_id=' . $uid);
        if (empty($settings)) {
            // Create default settings
            DB::insert('insert into pharmacy_settings (pharmacy_id, created_at, updated_at) values(?,?,?)',
                [$uid, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
            $settings = DB::select('select * from pharmacy_settings WHERE pharmacy_id=' . $uid);
        }
        
        // Get statistics
        $stats = [
            'network_members' => DB::select('select count(*) as count from pharmacy_networks WHERE pharmacy_id=' . $uid . ' AND status="active"')[0]->count ?? 0,
            'pending_prescriptions' => DB::select('select count(*) as count from e_prescriptions WHERE pharmacy_id=' . $uid . ' AND status="pending"')[0]->count ?? 0,
            'inventory_items' => DB::select('select count(*) as count from pharmacy_inventory WHERE pharmacy_id=' . $uid . ' AND status="active"')[0]->count ?? 0,
            'low_stock_items' => DB::select('select count(*) as count from pharmacy_inventory WHERE pharmacy_id=' . $uid . ' AND stock_quantity <= reorder_level AND status="active"')[0]->count ?? 0,
            'monthly_revenue' => DB::select('select COALESCE(SUM(total_amount), 0) as total from e_prescriptions WHERE pharmacy_id=' . $uid . ' AND status="delivered" AND MONTH(delivered_at) = MONTH(CURRENT_DATE())')[0]->total ?? 0,
        ];
        
        // Get recent prescriptions
        $recent_prescriptions = DB::select('
            SELECT ep.*, 
                   u_patient.name as patient_name,
                   u_doctor.name as doctor_name
            FROM e_prescriptions ep
            LEFT JOIN users u_patient ON ep.patient_id = u_patient.id
            LEFT JOIN users u_doctor ON ep.doctor_id = u_doctor.id
            WHERE ep.pharmacy_id = ?
            ORDER BY ep.created_at DESC
            LIMIT 10
        ', [$uid]);
        
        // Get low stock alerts
        $low_stock_alerts = DB::select('
            SELECT * FROM pharmacy_inventory 
            WHERE pharmacy_id = ? 
            AND stock_quantity <= reorder_level 
            AND status = "active"
            ORDER BY stock_quantity ASC
            LIMIT 5
        ', [$uid]);
        
        // Get notifications
        $notifications = DB::select('select * from notifications WHERE user_id=' . $uid . ' AND seen=0 ORDER BY date DESC LIMIT 10');
        
        return view('pharmacy.home', [
            'user' => $user,
            'settings' => $settings[0],
            'stats' => $stats,
            'recent_prescriptions' => $recent_prescriptions,
            'low_stock_alerts' => $low_stock_alerts,
            'notifications' => $notifications,
            'page' => 'home'
        ]);
    }
    
    public function network(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        // Get network members
        $network_members = DB::select('
            SELECT pn.*, u.name, u.email, u.phone, u.doctor, u.hospital, u.pharmacy
            FROM pharmacy_networks pn
            LEFT JOIN users u ON pn.member_id = u.id
            WHERE pn.pharmacy_id = ?
            ORDER BY pn.created_at DESC
        ', [$uid]);
        
        return view('pharmacy.network', [
            'user' => $user,
            'network_members' => $network_members,
            'page' => 'network'
        ]);
    }
    
    public function prescriptions(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        $status_filter = $request->input('status', 'all');
        $search = $request->input('search', '');
        
        // Build query
        $query = '
            SELECT ep.*, 
                   u_patient.name as patient_name,
                   u_doctor.name as doctor_name,
                   (SELECT COUNT(*) FROM prescription_medications WHERE prescription_id = ep.id) as medication_count
            FROM e_prescriptions ep
            LEFT JOIN users u_patient ON ep.patient_id = u_patient.id
            LEFT JOIN users u_doctor ON ep.doctor_id = u_doctor.id
            WHERE ep.pharmacy_id = ?
        ';
        
        $params = [$uid];
        
        if ($status_filter != 'all') {
            $query .= ' AND ep.status = ?';
            $params[] = $status_filter;
        }
        
        if ($search) {
            $query .= ' AND (u_patient.name LIKE ? OR ep.prescription_id LIKE ? OR u_doctor.name LIKE ?)';
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $query .= ' ORDER BY ep.created_at DESC';
        
        $prescriptions = DB::select($query, $params);
        
        // Get medications for each prescription
        foreach ($prescriptions as $rx) {
            $rx->medications = DB::select('SELECT * FROM prescription_medications WHERE prescription_id = ?', [$rx->id]);
        }
        
        return view('pharmacy.prescriptions', [
            'user' => $user,
            'prescriptions' => $prescriptions,
            'status_filter' => $status_filter,
            'search' => $search,
            'page' => 'prescriptions'
        ]);
    }
    
    public function inventory(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        $search = $request->input('search', '');
        $stock_filter = $request->input('stock_filter', 'all');
        
        // Get pharmacy settings for discount calculations
        $settings = DB::select('select * from pharmacy_settings WHERE pharmacy_id=' . $uid)[0];
        
        // Build query
        $query = 'SELECT * FROM pharmacy_inventory WHERE pharmacy_id = ?';
        $params = [$uid];
        
        if ($search) {
            $query .= ' AND (medication_name LIKE ? OR generic_name LIKE ?)';
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($stock_filter == 'low') {
            $query .= ' AND stock_quantity > 0 AND stock_quantity <= reorder_level';
        } elseif ($stock_filter == 'out') {
            $query .= ' AND stock_quantity = 0';
        }
        
        $query .= ' ORDER BY medication_name ASC';
        
        $inventory = DB::select($query, $params);
        
        return view('pharmacy.inventory', [
            'user' => $user,
            'inventory' => $inventory,
            'settings' => $settings,
            'search' => $search,
            'stock_filter' => $stock_filter,
            'page' => 'inventory'
        ]);
    }
    
    public function monitoring(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        $filter = $request->input('filter', 'all');
        $search = $request->input('search', '');
        
        // Get patients with their latest vitals
        $query = '
            SELECT pp.*,
                   (SELECT blood_pressure FROM patient_vitals WHERE pharmacy_patient_id = pp.id ORDER BY recorded_at DESC LIMIT 1) as last_bp,
                   (SELECT sugar_level FROM patient_vitals WHERE pharmacy_patient_id = pp.id ORDER BY recorded_at DESC LIMIT 1) as last_sugar,
                   (SELECT heart_rate FROM patient_vitals WHERE pharmacy_patient_id = pp.id ORDER BY recorded_at DESC LIMIT 1) as last_heart_rate,
                   (SELECT temperature FROM patient_vitals WHERE pharmacy_patient_id = pp.id ORDER BY recorded_at DESC LIMIT 1) as last_temperature,
                   (SELECT weight FROM patient_vitals WHERE pharmacy_patient_id = pp.id ORDER BY recorded_at DESC LIMIT 1) as last_weight
            FROM pharmacy_patients pp
            WHERE pp.pharmacy_id = ? AND pp.status = "active"
        ';
        
        $params = [$uid];
        
        if ($search) {
            $query .= ' AND (pp.full_name LIKE ? OR pp.primary_condition LIKE ?)';
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $query .= ' ORDER BY pp.full_name ASC';
        
        $patients = DB::select($query, $params);
        
        // Get medication monitoring for each patient
        foreach ($patients as $patient) {
            $patient->monitoring = DB::select('
                SELECT * FROM medication_monitoring 
                WHERE patient_id = ? 
                ORDER BY next_refill_due ASC 
                LIMIT 1
            ', [$patient->patient_id ?? 0]);
            
            // Determine alerts
            $patient->alerts = [];
            if ($patient->last_bp) {
                $bp_parts = explode('/', $patient->last_bp);
                if (count($bp_parts) == 2 && (intval($bp_parts[0]) > 140 || intval($bp_parts[1]) > 90)) {
                    $patient->alerts[] = 'Abnormal BP';
                }
            }
            if ($patient->last_sugar && intval($patient->last_sugar) > 180) {
                $patient->alerts[] = 'Abnormal Sugar';
            }
            if (!empty($patient->monitoring) && $patient->monitoring[0]->remaining_quantity < 5) {
                $patient->alerts[] = 'Low stock';
            }
        }
        
        // Apply filter
        if ($filter != 'all') {
            $patients = array_filter($patients, function($p) use ($filter) {
                if ($filter == 'low_stock') {
                    return in_array('Low stock', $p->alerts);
                } elseif ($filter == 'abnormal_vitals') {
                    return in_array('Abnormal BP', $p->alerts) || in_array('Abnormal Sugar', $p->alerts);
                } elseif ($filter == 'non_compliant') {
                    return !empty($p->monitoring) && $p->monitoring[0]->compliance_status == 'poor';
                }
                return true;
            });
        }
        
        return view('pharmacy.monitoring', [
            'user' => $user,
            'patients' => $patients,
            'filter' => $filter,
            'search' => $search,
            'page' => 'monitoring'
        ]);
    }
    
    public function rewards(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        $filter = $request->input('filter', 'all');
        
        // Get rewards
        $query = '
            SELECT dr.*, u.name as doctor_name, ep.prescription_id
            FROM doctor_rewards dr
            LEFT JOIN users u ON dr.doctor_id = u.id
            LEFT JOIN e_prescriptions ep ON dr.prescription_id = ep.id
            WHERE dr.pharmacy_id = ?
        ';
        
        $params = [$uid];
        
        if ($filter != 'all') {
            $query .= ' AND dr.status = ?';
            $params[] = $filter;
        }
        
        $query .= ' ORDER BY dr.created_at DESC';
        
        $rewards = DB::select($query, $params);
        
        // Calculate totals
        $total_rewards = DB::select('SELECT COALESCE(SUM(reward_amount), 0) as total FROM doctor_rewards WHERE pharmacy_id = ?', [$uid])[0]->total ?? 0;
        $pending_payout = DB::select('SELECT COALESCE(SUM(reward_amount), 0) as total FROM doctor_rewards WHERE pharmacy_id = ? AND status = "pending"', [$uid])[0]->total ?? 0;
        $paid_out = DB::select('SELECT COALESCE(SUM(reward_amount), 0) as total FROM doctor_rewards WHERE pharmacy_id = ? AND status = "paid"', [$uid])[0]->total ?? 0;
        $doctor_count = DB::select('SELECT COUNT(DISTINCT doctor_id) as count FROM doctor_rewards WHERE pharmacy_id = ?', [$uid])[0]->count ?? 0;
        
        return view('pharmacy.rewards', [
            'user' => $user,
            'rewards' => $rewards,
            'filter' => $filter,
            'total_rewards' => $total_rewards,
            'pending_payout' => $pending_payout,
            'paid_out' => $paid_out,
            'doctor_count' => $doctor_count,
            'page' => 'rewards'
        ]);
    }
    
    public function settings(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        // Get settings
        $settings = DB::select('select * from pharmacy_settings WHERE pharmacy_id=' . $uid)[0];
        
        // Get network partners for virtual pharmacy
        $partners = DB::select('
            SELECT pn.*, u.name, u.email
            FROM pharmacy_networks pn
            LEFT JOIN users u ON pn.member_id = u.id
            WHERE pn.pharmacy_id = ? AND pn.status = "active"
            AND pn.member_type IN ("pharmacy", "hospital")
            ORDER BY u.name ASC
        ', [$uid]);
        
        return view('pharmacy.settings', [
            'user' => $user,
            'settings' => $settings,
            'partners' => $partners,
            'page' => 'settings'
        ]);
    }
    
    public function messages(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        // Get message threads
        $threads = DB::select('
            SELECT 
                pm.partner_id,
                pm.partner_type,
                u.name as partner_name,
                MAX(pm.created_at) as last_message_time,
                SUM(CASE WHEN pm.is_read = 0 AND pm.sender_type = "partner" THEN 1 ELSE 0 END) as unread_count
            FROM pharmacy_messages pm
            LEFT JOIN users u ON pm.partner_id = u.id
            WHERE pm.pharmacy_id = ?
            GROUP BY pm.partner_id, pm.partner_type, u.name
            ORDER BY last_message_time DESC
        ', [$uid]);
        
        return view('pharmacy.messages', [
            'user' => $user,
            'threads' => $threads,
            'page' => 'messages'
        ]);
    }
    
    // AJAX Actions
    public function addNetworkMember(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        // Accept either email or phone as identifier
        $identifier = $this->sanitizeInput($request->input('email') ?: $request->input('identifier'));
        $type = $this->sanitizeInput($request->input('type'));

        if (empty($identifier) || empty($type)) {
            return response()->json(['status' => 'error', 'message' => 'Identifier and type are required']);
        }

        // Determine lookup by email or phone
        $member = [];
        if (strpos($identifier, '@') !== false) {
            // Email path
            $member = DB::select('select * from users WHERE email = ?', [$identifier]);
        } else {
            // Phone path - normalize to digits only for comparison
            $digits = preg_replace('/\D+/', '', $identifier);
            // Try normalized phone without symbols, exact, and without leading zero
            $member = DB::select("select * from users WHERE REPLACE(REPLACE(REPLACE(phone, '+', ''), '-', ''), ' ', '') = ? OR phone = ? OR phone = ?", [
                $digits,
                $identifier,
                ltrim($identifier, '0')
            ]);
        }
        
        if (empty($member)) {
            return response()->json(['status' => 'error', 'message' => 'User not found by email/phone']);
        }
        
        // Check if already in network
        $existing = DB::select('select * from pharmacy_networks WHERE pharmacy_id = ? AND member_id = ?', [$uid, $member[0]->id]);
        
        if (!empty($existing)) {
            return response()->json(['status' => 'error', 'message' => 'Already in network']);
        }
        
        // Add to network
        DB::insert('insert into pharmacy_networks (pharmacy_id, member_id, member_type, status, created_at, updated_at) values(?,?,?,?,?,?)',
            [$uid, $member[0]->id, $type, 'active', date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);

        // Send in-app message notification to the partner thread
        try {
            DB::insert('insert into pharmacy_messages (pharmacy_id, partner_id, partner_type, sender_type, message, is_read, created_at, updated_at) values (?,?,?,?,?,?,?,?)', [
                $uid,
                $member[0]->id,
                $type,
                'pharmacy',
                'You have been added to my network. Let\'s collaborate!',
                0,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s')
            ]);
        } catch (\Throwable $e) {
            // If messaging table differs, fail silently for now
        }
        
        // Prepare partner details for client redirect
        $partner_name = $member[0]->name ?? trim(($member[0]->first_name ?? '') . ' ' . ($member[0]->last_name ?? ''));
        return response()->json([
            'status' => 'success',
            'message' => 'Member added to network',
            'partner_id' => $member[0]->id,
            'partner_type' => $type,
            'partner_name' => $partner_name
        ]);
    }
    
    public function registerPatient(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        $data = [
            'full_name' => $this->sanitizeInput($request->input('name')),
            'date_of_birth' => $this->sanitizeInput($request->input('dob')),
            'gender' => $this->sanitizeInput($request->input('gender')),
            'phone' => $this->sanitizeInput($request->input('phone')),
            'email' => $this->sanitizeInput($request->input('email')),
            'address' => $this->sanitizeInput($request->input('address')),
            'primary_condition' => $this->sanitizeInput($request->input('condition')),
            'allergies' => $this->sanitizeInput($request->input('allergies')),
            'emergency_contact_name' => $this->sanitizeInput($request->input('emergency_name')),
            'emergency_contact_phone' => $this->sanitizeInput($request->input('emergency_phone')),
        ];
        
        DB::insert('insert into pharmacy_patients (pharmacy_id, full_name, date_of_birth, gender, phone, email, address, primary_condition, allergies, emergency_contact_name, emergency_contact_phone, created_at, updated_at) values(?,?,?,?,?,?,?,?,?,?,?,?,?)',
            [$uid, $data['full_name'], $data['date_of_birth'], $data['gender'], $data['phone'], $data['email'], $data['address'], $data['primary_condition'], $data['allergies'], $data['emergency_contact_name'], $data['emergency_contact_phone'], date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        
        return response()->json(['status' => 'success', 'message' => 'Patient registered successfully']);
    }
    
    public function saveSettings(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        $markup = floatval($request->input('markup'));
        $delivery_fee = floatval($request->input('delivery_fee'));
        $logo = $this->sanitizeInput($request->input('logo'));
        
        DB::update('update pharmacy_settings set doctor_markup_percentage = ?, default_delivery_fee = ?, storefront_logo_url = ?, updated_at = ? WHERE pharmacy_id = ?',
            [$markup, $delivery_fee, $logo, date('Y-m-d H:i:s'), $uid]);
        
        return response()->json(['status' => 'success', 'message' => 'Settings saved']);
    }
    
    public function updateDiscountPolicy(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        $doctor_discount = floatval($request->input('doctor_discount'));
        $wholesale_discount = floatval($request->input('wholesale_discount'));
        
        DB::update('update pharmacy_settings set doctor_discount_percentage = ?, wholesale_discount_percentage = ?, updated_at = ? WHERE pharmacy_id = ?',
            [$doctor_discount, $wholesale_discount, date('Y-m-d H:i:s'), $uid]);
        
        return response()->json(['status' => 'success', 'message' => 'Discount policy updated']);
    }

    // Allow doctors to access their Virtual Pharmacy landing
    public function doctorVirtual(Request $request)
    {
        if (!$request->hasCookie('uid')) {
            return redirect()->to('/')->send();
        }
        $uid = Cookie::get('uid');
        $user = DB::select('select * from users WHERE id=' . intval($uid));
        if (empty($user)) {
            return redirect()->to('/')->send();
        }

        // Ensure user is a doctor
        if (empty($user[0]->doctor)) {
            return redirect()->to('/')->send();
        }

        // If the doctor already has a pharmacy account, send them to full pharmacy dashboard
        if (!empty($user[0]->pharmacy)) {
            return redirect()->to('/dashboard-pharmacy')->send();
        }

        // Build a virtual pharmacy link (read-only storefront or future public link)
        $virtualLink = 'https://myvitalz.ai/pharmacy/' . $user[0]->id;

        return view('pharmacy.doctor_virtual', [
            'user' => $user[0],
            'virtual_link' => $virtualLink,
        ]);
    }

    // POST: upgrade a doctor account to include pharmacy role
    public function doctorUpgrade(Request $request)
    {
        if (!$request->hasCookie('uid')) {
            return redirect()->to('/')->send();
        }
        $uid = Cookie::get('uid');
        $user = DB::select('select * from users WHERE id=' . intval($uid));
        if (empty($user)) {
            return redirect()->to('/')->send();
        }
        // Ensure doctor
        if (empty($user[0]->doctor)) {
            return redirect()->to('/')->send();
        }

        // Grant pharmacy flag if not already
        if (empty($user[0]->pharmacy)) {
            DB::update('UPDATE users SET pharmacy = 1 WHERE id = ?', [intval($uid)]);
        }

        // Ensure pharmacy_settings exists
        $exists = DB::select('select * from pharmacy_settings WHERE pharmacy_id = ?', [intval($uid)]);
        if (empty($exists)) {
            DB::insert('insert into pharmacy_settings (pharmacy_id, created_at, updated_at) values(?,?,?)',
                [intval($uid), date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        }

        return redirect()->to('/dashboard-pharmacy')->send();
    }

    // POST: Doctor creates a prescription from Virtual Pharmacy
    public function doctorCreatePrescription(Request $request)
    {
        // Auth: must be logged in and be a doctor
        if (!$request->hasCookie('uid')) {
            return redirect()->to('/')->send();
        }
        $doctorId = intval(Cookie::get('uid'));
        $doctor = DB::select('select * from users WHERE id = ?', [$doctorId]);
        if (empty($doctor) || empty($doctor[0]->doctor)) {
            return redirect()->to('/')->send();
        }

        // Inputs
        $patientIdentifier = $this->sanitizeInput($request->input('patient'));
        $medicationName = $this->sanitizeInput($request->input('medication_name'));
        $dosage = $this->sanitizeInput($request->input('dosage'));
        $frequency = $this->sanitizeInput($request->input('frequency'));
        $duration = $this->sanitizeInput($request->input('duration'));
        $quantity = intval($request->input('quantity')) ?: 1;
        $unitPrice = floatval($request->input('unit_price')) ?: 0.0;

        if (empty($patientIdentifier) || empty($medicationName) || empty($dosage) || empty($frequency) || empty($duration)) {
            return redirect()->to('/doctor/virtual-pharmacy?a_type=warning&a_message=' . urlencode('Please fill all required fields'));
        }

        // Resolve patient by email or phone
        $patient = [];
        if (strpos($patientIdentifier, '@') !== false) {
            $patient = DB::select('select * from users WHERE email = ?', [$patientIdentifier]);
        } else {
            $digits = preg_replace('/\D+/', '', $patientIdentifier);
            $patient = DB::select("select * from users WHERE REPLACE(REPLACE(REPLACE(phone, '+', ''), '-', ''), ' ', '') = ? OR phone = ? OR phone = ?", [
                $digits,
                $patientIdentifier,
                ltrim($patientIdentifier, '0')
            ]);
        }

        if (empty($patient)) {
            return redirect()->to('/doctor/virtual-pharmacy?a_type=warning&a_message=' . urlencode('Patient not found by email/phone'));
        }

        $patientId = intval($patient[0]->id);

        // Insert into e_prescriptions (pharmacy_id may be null for virtual flow)
        $total = $unitPrice * max(1, $quantity);
        DB::insert('insert into e_prescriptions (patient_id, doctor_id, pharmacy_id, status, total_amount, created_at, updated_at) values (?,?,?,?,?,?,?)', [
            $patientId,
            $doctorId,
            null,
            'pending',
            $total,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);

        // Get the new prescription id
        $rx = DB::select('select id from e_prescriptions WHERE patient_id = ? AND doctor_id = ? ORDER BY id DESC LIMIT 1', [$patientId, $doctorId]);
        $rxId = $rx[0]->id ?? null;

        if ($rxId) {
            // Insert medication line
            DB::insert('insert into prescription_medications (prescription_id, medication_name, dosage, frequency, duration, quantity, unit_price, created_at, updated_at) values (?,?,?,?,?,?,?, ?, ?)', [
                $rxId,
                $medicationName,
                $dosage,
                $frequency,
                $duration,
                $quantity,
                $unitPrice,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s')
            ]);
        }

        // Optional: notify patient via notifications table if available (best-effort, ignore failure)
        try {
            DB::insert('insert into notifications (user, description, link, date) values (?,?,?,?)', [
                $patientId,
                'You have a new prescription',
                '/dashboard?pg=rx',
                time()
            ]);
        } catch (\Throwable $e) {
            // ignore
        }

        return redirect()->to('/doctor/virtual-pharmacy?a_type=success&a_message=' . urlencode('Prescription created successfully'));
    }
    
    // ==================== PHASE 1: PROFILE MANAGEMENT ====================
    
    public function profile(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        // Handle profile update
        if($request->isMethod('post') && $request->input('action') == 'update_profile'){
            $first_name = $this->sanitizeInput($request->input('first_name'));
            $last_name = $this->sanitizeInput($request->input('last_name'));
            $phone = $this->sanitizeInput($request->input('phone'));
            $address = $this->sanitizeInput($request->input('address'));
            $about = $this->sanitizeInput($request->input('about'));
            $state = $this->sanitizeInput($request->input('state'));
            $city = $this->sanitizeInput($request->input('city'));
            $country = $this->sanitizeInput($request->input('country'));
            $practice_location = $this->sanitizeInput($request->input('practice_location'));
            $public = $this->sanitizeInput($request->input('profile_status'));
            if($public === null || $public === ''){ $public = null; }

            DB::update('update users set first_name=?, last_name=?, phone=?, address=?, about=?, state=?, city=?, country=?, practice_location=?, public=?, updated_at=? where id=?',
                [$first_name, $last_name, $phone, $address, $about, $state, $city, $country, $practice_location, $public, date('Y-m-d H:i:s'), $uid]);

            return redirect()->to("/dashboard-pharmacy?pg=profile&a_type=success&a_message=Profile updated successfully!");
        }
        
        // Handle profile photo upload
        if($request->hasFile('upload_profile')){
            $file = $request->file('upload_profile');
            $target_dir = public_path('assets/images');
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($target_dir, $filename);
            
            DB::update('UPDATE users SET photo=? WHERE id=?', [$filename, $uid]);
            
            return redirect()->to("/dashboard-pharmacy?pg=profile&a_type=success&a_message=Profile photo updated successfully!");
        }
        
        $allvitalz = DB::select('select * from allvitalz');
        $notifications = DB::select('select * from notifications WHERE user_id=' . $uid . ' AND seen=0 ORDER BY date DESC LIMIT 10');
        
        return view('pharmacy.profile', [
            'user' => $user,
            'allvitalz' => $allvitalz,
            'notifications' => $notifications,
            'page' => 'profile',
            'a_type' => $request->input('a_type', ''),
            'a_message' => $request->input('a_message', '')
        ]);
    }
    
    // ==================== PHASE 1: APPOINTMENTS SYSTEM ====================
    
    public function appointments(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        // Handle appointment actions
        if($request->isMethod('post')){
            // Accept appointment
            if($request->input('accept_appointment')){
                $appointment_id = $this->sanitizeInput($request->input('accept_appointment'));
                $address = $this->sanitizeInput($request->input('address'));
                $cost = $this->sanitizeInput($request->input('cost'));
                
                DB::update('UPDATE appointments SET doc_accept=?, cost=?, address=? WHERE id=? AND doctor=?', 
                    ["1", $cost, $address, $appointment_id, $uid]);
                
                return redirect()->to("/dashboard-pharmacy?pg=appointments&a_type=success&a_message=Appointment Accepted!");
            }
            
            // Reject appointment
            if($request->input('reject_appointment')){
                $appointment_id = $this->sanitizeInput($request->input('reject_appointment'));
                
                DB::update('UPDATE appointments SET doc_accept=?, cost=?, address=? WHERE id=? AND doctor=?', 
                    ["3", NULL, NULL, $appointment_id, $uid]);
                
                return redirect()->to("/dashboard-pharmacy?pg=appointments&a_type=warning&a_message=Appointment Rejected!");
            }
            
            // Reschedule appointment
            if($request->input('reschedule_appointment')){
                $appointment_id = $this->sanitizeInput($request->input('reschedule_appointment'));
                $address = $this->sanitizeInput($request->input('address'));
                $cost = $this->sanitizeInput($request->input('cost'));
                $appointment_time = $this->sanitizeInput($request->input('appointment_time_reschedule'));
                $appointment_date = $this->sanitizeInput($request->input('appointment_date_reschedule'));
                
                $start_time = strtotime($appointment_date." ".$appointment_time);
                $end_time = strtotime("+1 hour", $start_time);
                $day = date("D", strtotime($appointment_date));
                
                DB::update('UPDATE appointments SET day=?, date=?, start_time=?, end_time=?, doc_accept=?, cost=?, address=? WHERE id=? AND doctor=?', 
                    [$day, $appointment_date, $start_time, $end_time, "2", $cost, $address, $appointment_id, $uid]);
                
                return redirect()->to("/dashboard-pharmacy?pg=appointments&a_type=success&a_message=Appointment Rescheduled!");
            }
        }
        
        // Get appointments
        $pending_appointments = DB::select('select * from appointments WHERE end_time > '.time().' AND doctor='.$uid);
        
        $appointment_user = [];
        foreach($pending_appointments as $pat){
            if(!empty($pat->user)){
                $appoint_user_details = DB::select('select * from users WHERE id='.$pat->user);
                array_push($appointment_user, $appoint_user_details[0]);
            } else {
                $appoint_user_details = array();
                array_push($appointment_user, $appoint_user_details);
            }
        }
        
        $notifications = DB::select('select * from notifications WHERE user_id=' . $uid . ' AND seen=0 ORDER BY date DESC LIMIT 10');
        
        return view('pharmacy.appointments', [
            'user' => $user,
            'pending_appointments' => $pending_appointments,
            'appointment_user' => $appointment_user,
            'notifications' => $notifications,
            'page' => 'appointments',
            'a_type' => $request->input('a_type', ''),
            'a_message' => $request->input('a_message', '')
        ]);
    }
    
    public function appointmentDetails(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        $aptid = $this->sanitizeInput($request->input('aptid'));
        $appointment = DB::select('select * from appointments WHERE doctor='.$uid.' AND id='.$aptid);
        
        if(empty($appointment)){
            return redirect()->to("/dashboard-pharmacy?pg=appointments&a_type=error&a_message=Appointment not found!");
        }
        
        $pat_user = DB::select('select * from users WHERE id='.$appointment[0]->user);
        $notifications = DB::select('select * from notifications WHERE user_id=' . $uid . ' AND seen=0 ORDER BY date DESC LIMIT 10');
        
        return view('pharmacy.appointment_details', [
            'user' => $user,
            'appointment' => $appointment[0],
            'pat_user' => $pat_user[0] ?? null,
            'notifications' => $notifications,
            'page' => 'appointments',
            'a_type' => $request->input('a_type', ''),
            'a_message' => $request->input('a_message', '')
        ]);
    }
    
    // ==================== PHASE 1: PATIENT DETAILS ====================
    
    public function patientDetails(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        $ptidr = $request->input('ptid');
        $pat_user = DB::select('select * from users WHERE ref_code="'.$ptidr.'"');
        
        if(empty($pat_user)){
            return redirect()->to("/dashboard-pharmacy?pg=monitoring&a_type=error&a_message=Patient not found!");
        }
        
        $ptid = $pat_user[0]->id;
        
        // Get all vitals for this patient
        $heart_rate_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 1 ORDER BY date DESC');
        $blood_pressure_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 2 ORDER BY date DESC');
        $oxygen_saturation_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 3 ORDER BY date DESC');
        $stress_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 4 ORDER BY date DESC');
        $blood_glucose_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 5 ORDER BY date DESC');
        $lipids_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 6 ORDER BY date DESC');
        $hba1c_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 7 ORDER BY date DESC');
        $ihra_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 8 ORDER BY date DESC');
        $body_temperature_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 9 ORDER BY date DESC');
        
        $allvitalz = DB::select('select * from allvitalz');
        $notifications = DB::select('select * from notifications WHERE user_id=' . $uid . ' AND seen=0 ORDER BY date DESC LIMIT 10');
        
        return view('pharmacy.patient_details', [
            'user' => $user,
            'pat_user' => $pat_user[0],
            'allvitalz' => $allvitalz,
            'heart_rate_readings' => $heart_rate_readings,
            'blood_pressure_readings' => $blood_pressure_readings,
            'oxygen_saturation_readings' => $oxygen_saturation_readings,
            'stress_readings' => $stress_readings,
            'blood_glucose_readings' => $blood_glucose_readings,
            'lipids_readings' => $lipids_readings,
            'hba1c_readings' => $hba1c_readings,
            'ihra_readings' => $ihra_readings,
            'body_temperature_readings' => $body_temperature_readings,
            'notifications' => $notifications,
            'page' => 'monitoring',
            'a_type' => $request->input('a_type', ''),
            'a_message' => $request->input('a_message', '')
        ]);
    }
    
    public function patientReadingHistory(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        $ptidr = $this->sanitizeInput($request->input('ptid'));
        $pat_user = DB::select('select * from users WHERE ref_code="'.$ptidr.'"');
        
        if(empty($pat_user)){
            return redirect()->to("/dashboard-pharmacy?pg=monitoring&a_type=error&a_message=Patient not found!");
        }
        
        $ptid = $pat_user[0]->id;
        
        // Get all vitals for this patient
        $heart_rate_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 1 ORDER BY date DESC');
        $blood_pressure_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 2 ORDER BY date DESC');
        $oxygen_saturation_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 3 ORDER BY date DESC');
        $stress_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 4 ORDER BY date DESC');
        $blood_glucose_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 5 ORDER BY date DESC');
        $lipids_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 6 ORDER BY date DESC');
        $hba1c_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 7 ORDER BY date DESC');
        $ihra_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 8 ORDER BY date DESC');
        $body_temperature_readings = DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 9 ORDER BY date DESC');
        
        $allvitalz = DB::select('select * from allvitalz');
        $notifications = DB::select('select * from notifications WHERE user_id=' . $uid . ' AND seen=0 ORDER BY date DESC LIMIT 10');
        
        return view('pharmacy.patient_reading_history', [
            'user' => $user,
            'pat_user' => $pat_user[0],
            'allvitalz' => $allvitalz,
            'heart_rate_readings' => $heart_rate_readings,
            'blood_pressure_readings' => $blood_pressure_readings,
            'oxygen_saturation_readings' => $oxygen_saturation_readings,
            'stress_readings' => $stress_readings,
            'blood_glucose_readings' => $blood_glucose_readings,
            'lipids_readings' => $lipids_readings,
            'hba1c_readings' => $hba1c_readings,
            'ihra_readings' => $ihra_readings,
            'body_temperature_readings' => $body_temperature_readings,
            'notifications' => $notifications,
            'page' => 'monitoring',
            'a_type' => $request->input('a_type', ''),
            'a_message' => $request->input('a_message', '')
        ]);
    }
    
    public function patientMedications(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        $ptidr = $this->sanitizeInput($request->input('ptid'));
        $pat_user = DB::select('select * from users WHERE ref_code="'.$ptidr.'"');
        
        if(empty($pat_user)){
            return redirect()->to("/dashboard-pharmacy?pg=monitoring&a_type=error&a_message=Patient not found!");
        }
        
        $ptid = $pat_user[0]->id;
        $medications = DB::select('select * from prescriptions WHERE user='.$ptid.' ORDER BY date DESC');
        
        $allvitalz = DB::select('select * from allvitalz');
        $notifications = DB::select('select * from notifications WHERE user_id=' . $uid . ' AND seen=0 ORDER BY date DESC LIMIT 10');
        
        return view('pharmacy.patient_medications', [
            'user' => $user,
            'pat_user' => $pat_user[0],
            'medications' => $medications,
            'allvitalz' => $allvitalz,
            'notifications' => $notifications,
            'page' => 'monitoring',
            'a_type' => $request->input('a_type', ''),
            'a_message' => $request->input('a_message', '')
        ]);
    }
    
    // ==================== PHASE 2: PRESCRIPTION MANAGEMENT ====================
    
    public function newPrescription(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        // Handle prescription creation
        if($request->isMethod('post') && $request->input('drug_name')){
            $ptidr = $this->sanitizeInput($request->input('ptid'));
            $pat_user = DB::select('select * from users WHERE ref_code="'.$ptidr.'"');
            
            if(empty($pat_user)){
                return redirect()->to("/dashboard-pharmacy?pg=monitoring&a_type=error&a_message=Patient not found!");
            }
            
            $ptid = $pat_user[0]->id;
            
            // Insert multiple drugs
            for($i=0; $i<count($request->input('drug_name')); $i++){
                $drug_name = $this->sanitizeInput($request->input('drug_name')[$i]);
                $drug_type = $this->sanitizeInput($request->input('drug_type')[$i]);
                $quantity = $this->sanitizeInput($request->input('quantity')[$i]);
                $dosage = $this->sanitizeInput($request->input('dosage')[$i]);
                $frequency = $this->sanitizeInput($request->input('frequency')[$i]);
                $additional = $this->sanitizeInput($request->input('additional')[$i]);
                
                $dt = time();
                DB::insert('INSERT INTO prescriptions(user, doctor, drug_name, drug_type, duration, dosage, frequency, additional_info, date) VALUES (?,?,?,?,?,?,?,?,?)', 
                    [$ptid, $uid, $drug_name, $drug_type, $quantity, $dosage, $frequency, $additional, $dt]);
            }
            
            // Notify patient
            $description = "You have a new prescription";
            $link = "?pg=medications";
            DB::insert('INSERT INTO notifications(user_id, title, message, date, created_at, updated_at) VALUES (?,?,?,?,?,?)', 
                [$ptid, 'New Prescription', $description, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
            
            return redirect()->to("/dashboard-pharmacy?pg=patient-medications&ptid=".$ptidr."&a_type=success&a_message=Prescription sent successfully!");
        }
        
        $ptidr = $this->sanitizeInput($request->input('ptid'));
        $pat_user = DB::select('select * from users WHERE ref_code="'.$ptidr.'"');
        
        if(empty($pat_user)){
            return redirect()->to("/dashboard-pharmacy?pg=monitoring&a_type=error&a_message=Patient not found!");
        }
        
        $allvitalz = DB::select('select * from allvitalz');
        $notifications = DB::select('select * from notifications WHERE user_id=' . $uid . ' AND seen=0 ORDER BY date DESC LIMIT 10');
        
        return view('pharmacy.new_prescription', [
            'user' => $user,
            'pat_user' => $pat_user[0],
            'allvitalz' => $allvitalz,
            'notifications' => $notifications,
            'page' => 'prescriptions',
            'a_type' => $request->input('a_type', ''),
            'a_message' => $request->input('a_message', '')
        ]);
    }
    
    public function editPrescription(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        // Handle prescription update
        if($request->isMethod('post') && $request->input('drug_nameed')){
            $ptidr = $this->sanitizeInput($request->input('ptid'));
            $pscid = $this->sanitizeInput($request->input('pres_id'));
            $pat_user = DB::select('select * from users WHERE ref_code="'.$ptidr.'"');
            
            if(empty($pat_user)){
                return redirect()->to("/dashboard-pharmacy?pg=monitoring&a_type=error&a_message=Patient not found!");
            }
            
            $ptid = $pat_user[0]->id;
            
            $drug_name = $this->sanitizeInput($request->input('drug_nameed'));
            $drug_type = $this->sanitizeInput($request->input('drug_typeed'));
            $duration = $this->sanitizeInput($request->input('durationed'));
            $dosage = $this->sanitizeInput($request->input('dosageed'));
            $frequency = $this->sanitizeInput($request->input('frequencyed'));
            $additional = $this->sanitizeInput($request->input('additionaled'));
            
            DB::update('UPDATE prescriptions SET drug_name=?, drug_type=?, duration=?, dosage=?, frequency=?, additional_info=? WHERE id=? AND user=? AND doctor=?', 
                [$drug_name, $drug_type, $duration, $dosage, $frequency, $additional, $pscid, $ptid, $uid]);
            
            return redirect()->to("/dashboard-pharmacy?pg=edit-prescription&ptid=".$ptidr."&pscid=".$pscid."&a_type=success&a_message=Prescription edited successfully!");
        }
        
        $ptidr = $this->sanitizeInput($request->input('ptid'));
        $mdid = $this->sanitizeInput($request->input('pscid'));
        $pat_user = DB::select('select * from users WHERE ref_code="'.$ptidr.'"');
        
        if(empty($pat_user)){
            return redirect()->to("/dashboard-pharmacy?pg=monitoring&a_type=error&a_message=Patient not found!");
        }
        
        $ptid = $pat_user[0]->id;
        $medications = DB::select('select * from prescriptions WHERE user='.$ptid.' AND id='.$mdid);
        
        if(empty($medications)){
            return redirect()->to("/dashboard-pharmacy?pg=patient-medications&ptid=".$ptidr."&a_type=error&a_message=Prescription not found!");
        }
        
        $allvitalz = DB::select('select * from allvitalz');
        $notifications = DB::select('select * from notifications WHERE user_id=' . $uid . ' AND seen=0 ORDER BY date DESC LIMIT 10');
        
        return view('pharmacy.edit_prescription', [
            'user' => $user,
            'pat_user' => $pat_user[0],
            'medication' => $medications[0],
            'allvitalz' => $allvitalz,
            'notifications' => $notifications,
            'page' => 'prescriptions',
            'a_type' => $request->input('a_type', ''),
            'a_message' => $request->input('a_message', '')
        ]);
    }
    
    // ==================== PHASE 2: AFFILIATE NETWORK ====================
    
    public function affiliates(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        // Handle affiliate approval/decline
        if($request->isMethod('post')){
            if($request->input('approve_affliate')){
                $req_id = $this->sanitizeInput($request->input('approve_affliate'));
                DB::update('UPDATE patients SET doctor_approve=? WHERE doctor=? AND id=?', ["1", $uid, $req_id]);
                return redirect()->to("/dashboard-pharmacy?pg=affiliates&a_type=success&a_message=Request Approved!");
            }
            
            if($request->input('decline_affliate')){
                $req_id = $this->sanitizeInput($request->input('decline_affliate'));
                DB::update('UPDATE patients SET doctor_approve=? WHERE doctor=? AND id=?', ["2", $uid, $req_id]);
                return redirect()->to("/dashboard-pharmacy?pg=affiliates&a_type=warning&a_message=Request Declined!");
            }
        }
        
        // Get affiliate requests and members
        $my_requests = DB::select('select * from patients WHERE doctor='.$uid.' AND doctor_approve IS NULL');
        $my_patients = DB::select('select * from patients WHERE doctor='.$uid.' AND doctor_approve=1 AND user_approve=1');
        $my_hospital = DB::select('select * from patients WHERE doctor='.$uid.' AND doctor_approve =1 AND hospital_approve=1');
        $my_pharmacy = DB::select('select * from patients WHERE doctor='.$uid.' AND doctor_approve =1 AND pharmacy_approve=1');
        
        $request_details = [];
        $patients_details = [];
        $pharmacy_details = [];
        $hospital_details = [];
        
        foreach($my_requests as $pat){
            if(!empty($pat->user)){
                $user_requests = DB::select('select * from users WHERE id='.$pat->user);
                array_push($request_details, $user_requests[0]);
            }else if(!empty($pat->pharmacy)){
                $user_requests = DB::select('select * from users WHERE id='.$pat->pharmacy);
                array_push($request_details, $user_requests[0]);
            }else if(!empty($pat->hospital)){
                $user_requests = DB::select('select * from users WHERE id='.$pat->hospital);
                array_push($request_details, $user_requests[0]);
            }
        }
        
        foreach($my_patients as $pat){
            $user_requests = DB::select('select * from users WHERE id='.$pat->user);
            array_push($patients_details, $user_requests[0]);
        }
        
        foreach($my_pharmacy as $pat){
            $user_requests = DB::select('select * from users WHERE id='.$pat->pharmacy);
            array_push($pharmacy_details, $user_requests[0]);
        }
        
        foreach($my_hospital as $pat){
            $user_requests = DB::select('select * from users WHERE id='.$pat->hospital);
            array_push($hospital_details, $user_requests[0]);
        }
        
        $notifications = DB::select('select * from notifications WHERE user_id=' . $uid . ' AND seen=0 ORDER BY date DESC LIMIT 10');
        
        return view('pharmacy.affiliates', [
            'user' => $user,
            'my_requests' => $my_requests,
            'my_patients' => $my_patients,
            'my_hospital' => $my_hospital,
            'my_pharmacy' => $my_pharmacy,
            'request_details' => $request_details,
            'patients_details' => $patients_details,
            'pharmacy_details' => $pharmacy_details,
            'hospital_details' => $hospital_details,
            'notifications' => $notifications,
            'page' => 'network',
            'a_type' => $request->input('a_type', ''),
            'a_message' => $request->input('a_message', '')
        ]);
    }
    
    // ==================== STUB METHODS (TO BE IMPLEMENTED IN LATER PHASES) ====================
    
    public function marketplace(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        // Handle POST actions
        if($request->isMethod('post')){
            $action = $request->input('action');
            
            if($action == 'add_to_storefront'){
                $marketplace_drug_id = (int)$request->input('marketplace_drug_id');
                $wholesale_price = (float)$request->input('wholesale_price');
                $retail_price = (float)$request->input('retail_price');
                $stock_quantity = (int)$request->input('stock_quantity');
                $is_featured = $request->input('is_featured') ? 1 : 0;
                
                // Calculate markup percentage
                $markup_percentage = $wholesale_price > 0 ? (($retail_price - $wholesale_price) / $wholesale_price) * 100 : 0;
                
                // Check if already in storefront
                $existing = DB::select('SELECT id FROM doctor_storefront_inventory WHERE doctor_id = ? AND marketplace_drug_id = ?', [$uid, $marketplace_drug_id]);
                
                if(empty($existing)){
                    DB::insert('INSERT INTO doctor_storefront_inventory (doctor_id, marketplace_drug_id, wholesale_price, retail_price, markup_percentage, stock_quantity, is_featured, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, ?)',
                        [$uid, $marketplace_drug_id, $wholesale_price, $retail_price, $markup_percentage, $stock_quantity, $is_featured, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
                    
                    return redirect()->to('/dashboard-pharmacy?pg=marketplace&success=1')->send();
                } else {
                    return redirect()->to('/dashboard-pharmacy?pg=marketplace&error=already_exists')->send();
                }
            }
        }
        
        return view('pharmacy.marketplace', ['user' => $user, 'page' => 'marketplace']);
    }
    
    public function storefront(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        // Handle POST actions
        if($request->isMethod('post')){
            $action = $request->input('action');
            
            if($action == 'update_storefront_product'){
                $inventory_id = (int)$request->input('inventory_id');
                $retail_price = (float)$request->input('retail_price');
                $stock_quantity = (int)$request->input('stock_quantity');
                
                // Get wholesale price to calculate markup
                $product = DB::select('SELECT wholesale_price FROM doctor_storefront_inventory WHERE id = ? AND doctor_id = ?', [$inventory_id, $uid]);
                
                if(!empty($product)){
                    $wholesale_price = $product[0]->wholesale_price;
                    $markup_percentage = $wholesale_price > 0 ? (($retail_price - $wholesale_price) / $wholesale_price) * 100 : 0;
                    
                    DB::update('UPDATE doctor_storefront_inventory SET retail_price = ?, stock_quantity = ?, markup_percentage = ?, updated_at = ? WHERE id = ? AND doctor_id = ?',
                        [$retail_price, $stock_quantity, $markup_percentage, date('Y-m-d H:i:s'), $inventory_id, $uid]);
                }
                
                return redirect()->to('/dashboard-pharmacy?pg=storefront&success=1')->send();
            }
            
            if($action == 'toggle_featured'){
                $inventory_id = (int)$request->input('inventory_id');
                $is_featured = (int)$request->input('is_featured');
                
                DB::update('UPDATE doctor_storefront_inventory SET is_featured = ?, updated_at = ? WHERE id = ? AND doctor_id = ?',
                    [$is_featured, date('Y-m-d H:i:s'), $inventory_id, $uid]);
                
                return redirect()->to('/dashboard-pharmacy?pg=storefront')->send();
            }
            
            if($action == 'toggle_active'){
                $inventory_id = (int)$request->input('inventory_id');
                $is_active = (int)$request->input('is_active');
                
                DB::update('UPDATE doctor_storefront_inventory SET is_active = ?, updated_at = ? WHERE id = ? AND doctor_id = ?',
                    [$is_active, date('Y-m-d H:i:s'), $inventory_id, $uid]);
                
                return redirect()->to('/dashboard-pharmacy?pg=storefront')->send();
            }
            
            if($action == 'remove_from_storefront'){
                $inventory_id = (int)$request->input('inventory_id');
                
                DB::delete('DELETE FROM doctor_storefront_inventory WHERE id = ? AND doctor_id = ?', [$inventory_id, $uid]);
                
                return redirect()->to('/dashboard-pharmacy?pg=storefront&removed=1')->send();
            }
        }
        
        return view('pharmacy.storefront', ['user' => $user, 'page' => 'storefront']);
    }
    
    public function storefrontSettings(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        // Handle POST actions
        if($request->isMethod('post')){
            $action = $request->input('action');
            
            if($action == 'update_storefront_settings'){
                $storefront_name = $this->sanitizeInput($request->input('storefront_name'));
                $description = $this->sanitizeInput($request->input('description'));
                $primary_color = $this->sanitizeInput($request->input('primary_color'));
                $is_active = $request->input('is_active') ? 1 : 0;
                
                // Check if settings exist
                $existing = DB::select('SELECT id FROM doctor_storefront_settings WHERE doctor_id = ?', [$uid]);
                
                if(empty($existing)){
                    DB::insert('INSERT INTO doctor_storefront_settings (doctor_id, storefront_name, description, primary_color, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)',
                        [$uid, $storefront_name, $description, $primary_color, $is_active, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
                } else {
                    DB::update('UPDATE doctor_storefront_settings SET storefront_name = ?, description = ?, primary_color = ?, is_active = ?, updated_at = ? WHERE doctor_id = ?',
                        [$storefront_name, $description, $primary_color, $is_active, date('Y-m-d H:i:s'), $uid]);
                }
                
                return redirect()->to('/dashboard-pharmacy?pg=storefront-settings&success=1')->send();
            }
        }
        
        return view('pharmacy.storefront_settings', ['user' => $user, 'page' => 'storefront']);
    }
    
    public function store(Request $request)
    {
        $user = $this->checkAuth($request);
        return view('pharmacy.store', ['user' => $user, 'page' => 'store']);
    }
    
    public function support(Request $request)
    {
        $user = $this->checkAuth($request);
        return view('pharmacy.support', ['user' => $user, 'page' => 'support']);
    }
    
    public function supportDetails(Request $request)
    {
        $user = $this->checkAuth($request);
        return view('pharmacy.support_details', ['user' => $user, 'page' => 'support']);
    }
    
    public function referrals(Request $request)
    {
        $user = $this->checkAuth($request);
        return view('pharmacy.referrals', ['user' => $user, 'page' => 'referrals']);
    }
    
    public function readings(Request $request)
    {
        $user = $this->checkAuth($request);
        return view('pharmacy.readings', ['user' => $user, 'page' => 'readings']);
    }
    
    public function patientVitals(Request $request)
    {
        $user = $this->checkAuth($request);
        $uid = $user->id;
        
        // Handle POST actions
        if($request->isMethod('post')){
            $action = $request->input('action');
            $patient_id = (int)$request->input('patient_id');
            
            // Verify patient belongs to this doctor
            $relationship = DB::select('
                SELECT * FROM patients 
                WHERE doctor = ? AND user = ? AND doctor_approve = 1 AND user_approve = 1
            ', [$uid, $patient_id]);
            
            if(empty($relationship)){
                return redirect()->to('/dashboard-pharmacy?pg=monitoring&error=access_denied')->send();
            }
            
            if($action == 'create_prescription'){
                $drug_name = $this->sanitizeInput($request->input('drug_name'));
                $drug_type = $this->sanitizeInput($request->input('drug_type'));
                $dosage = $this->sanitizeInput($request->input('dosage'));
                $frequency = $this->sanitizeInput($request->input('frequency'));
                $duration = $this->sanitizeInput($request->input('duration'));
                $additional_info = $this->sanitizeInput($request->input('additional_info'));
                
                // Insert prescription
                DB::insert('
                    INSERT INTO prescriptions (user, doctor, drug_name, drug_type, dosage, frequency, duration, additional_info, date, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ', [$patient_id, $uid, $drug_name, $drug_type, $dosage, $frequency, $duration, $additional_info, time(), date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
                
                // Send notification to patient
                DB::insert('
                    INSERT INTO notifications (user_id, title, message, seen, date, created_at, updated_at)
                    VALUES (?, ?, ?, 0, ?, ?, ?)
                ', [
                    $patient_id,
                    'New Prescription',
                    'Your doctor has prescribed '.$drug_name.'. Dosage: '.$dosage.', Frequency: '.$frequency.', Duration: '.$duration,
                    date('d-M-Y'),
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s')
                ]);
                
                return redirect()->to('/dashboard-pharmacy?pg=patient-vitals&patient_id='.$patient_id.'&success=prescription_created')->send();
            }
            
            if($action == 'create_appointment'){
                $appointment_date = $request->input('appointment_date');
                $appointment_time = $request->input('appointment_time');
                $channel = $this->sanitizeInput($request->input('channel'));
                $duration = (int)$request->input('duration', 30);
                $address = $this->sanitizeInput($request->input('address'));
                $reason = $this->sanitizeInput($request->input('reason'));
                
                // Calculate timestamps
                $datetime = $appointment_date.' '.$appointment_time;
                $start_time = strtotime($datetime);
                $end_time = $start_time + ($duration * 60);
                
                // Get day name
                $day = date('D', $start_time);
                
                // Insert appointment
                DB::insert('
                    INSERT INTO appointments (user, doctor, day, date, start_time, end_time, channel, status, booking_date, doc_accept, address, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ', [
                    $patient_id,
                    $uid,
                    $day,
                    $appointment_date,
                    $start_time,
                    $end_time,
                    $channel,
                    'pending',
                    date('d-M-Y'),
                    1, // Doctor created, so auto-accepted
                    $address,
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s')
                ]);
                
                // Send notification to patient
                DB::insert('
                    INSERT INTO notifications (user_id, title, message, seen, date, created_at, updated_at)
                    VALUES (?, ?, ?, 0, ?, ?, ?)
                ', [
                    $patient_id,
                    'Appointment Scheduled',
                    'Your doctor has scheduled an appointment on '.date('M d, Y', $start_time).' at '.date('h:i A', $start_time).'. Channel: '.ucfirst($channel).($reason ? '. Reason: '.$reason : ''),
                    date('d-M-Y'),
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s')
                ]);
                
                return redirect()->to('/dashboard-pharmacy?pg=patient-vitals&patient_id='.$patient_id.'&success=appointment_created')->send();
            }
            
            if($action == 'send_message'){
                $title = $this->sanitizeInput($request->input('title', 'Message from Doctor'));
                $message = $this->sanitizeInput($request->input('message'));
                
                // Send notification
                DB::insert('
                    INSERT INTO notifications (user_id, title, message, seen, date, created_at, updated_at)
                    VALUES (?, ?, ?, 0, ?, ?, ?)
                ', [
                    $patient_id,
                    $title,
                    $message,
                    date('d-M-Y'),
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s')
                ]);
                
                return redirect()->to('/dashboard-pharmacy?pg=patient-vitals&patient_id='.$patient_id.'&success=message_sent')->send();
            }
            
            if($action == 'send_alert'){
                $alert_type = $this->sanitizeInput($request->input('alert_type'));
                $message = $this->sanitizeInput($request->input('message'));
                $action_required = $this->sanitizeInput($request->input('action_required'));
                
                $full_message = '🚨 URGENT ALERT: '.$message;
                if($action_required){
                    $full_message .= ' | ACTION REQUIRED: '.$action_required;
                }
                
                // Send high-priority notification
                DB::insert('
                    INSERT INTO notifications (user_id, title, message, seen, date, created_at, updated_at)
                    VALUES (?, ?, ?, 0, ?, ?, ?)
                ', [
                    $patient_id,
                    '🚨 URGENT: '.ucwords(str_replace('_', ' ', $alert_type)),
                    $full_message,
                    date('d-M-Y'),
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s')
                ]);
                
                return redirect()->to('/dashboard-pharmacy?pg=patient-vitals&patient_id='.$patient_id.'&success=alert_sent')->send();
            }
        }
        
        return view('pharmacy.patient_vitals', ['user' => $user, 'page' => 'patient-vitals']);
    }
    
    public function thresholds(Request $request)
    {
        $user = $this->checkAuth($request);
        return view('pharmacy.thresholds', ['user' => $user, 'page' => 'monitoring']);
    }
    
    public function storefrontProducts(Request $request)
    {
        $user = $this->checkAuth($request);
        return view('pharmacy.storefront_products', ['user' => $user, 'page' => 'marketplace']);
    }
}
