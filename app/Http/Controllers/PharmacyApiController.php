<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PharmacyApiController extends Controller
{
    // POST /api/pharmacy/clearance
    public function storeClearance(Request $request)
    {
        // Expect: uid, auth, items (json), note, partners (json of ids/types optional)
        if (!$request->uid || !$request->auth) {
            return response()->json(['status' => 'error', 'message' => 'Missing parameters']);
        }

        $uid = $this->sanitizeInput($request->uid);
        $auth = $this->sanitizeInput($request->auth);

        // Basic auth: verify auth token matches user
        $user = DB::select('select * from users WHERE id=' . intval($uid) . ' limit 1');
        if (empty($user) || ($user[0]->authen ?? null) !== $auth) {
            return response()->json(['status' => 'error', 'message' => 'unauthorized']);
        }
        if (($user[0]->pharmacy ?? 0) != 1) {
            return response()->json(['status' => 'error', 'message' => 'Only pharmacies can perform this action']);
        }

        $items = $request->input('items'); // array of {inventory_id, quantity, price}
        $note = $this->sanitizeInput($request->input('note') ?? '');
        $partners = $request->input('partners'); // optional

        if (!is_array($items) || count($items) === 0) {
            return response()->json(['status' => 'error', 'message' => 'No clearance items provided']);
        }

        $now = date('Y-m-d H:i:s');
        DB::beginTransaction();
        try {
            DB::insert(
                'insert into clearance_sales (pharmacy_id, items_json, partners_json, note, created_at) values (?,?,?,?,?)',
                [$uid, json_encode($items), json_encode($partners ?? []), $note, $now]
            );

            // Optionally adjust inventory quantities or mark clearance flag per item
            // Skipped here to avoid assumptions on inventory schema

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Clearance sale recorded']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to save clearance', 'error' => $e->getMessage()]);
        }
    }

    // GET /api/pharmacy/partners
    public function partners(Request $request)
    {
        // Expect: uid, auth
        if (!$request->uid || !$request->auth) {
            return response()->json(['status' => 'error', 'message' => 'Missing parameters']);
        }

        $uid = $this->sanitizeInput($request->uid);
        $auth = $this->sanitizeInput($request->auth);

        // Basic auth: verify auth token matches user
        $user = DB::select('select * from users WHERE id=' . intval($uid) . ' limit 1');
        if (empty($user) || ($user[0]->authen ?? null) !== $auth) {
            return response()->json(['status' => 'error', 'message' => 'unauthorized']);
        }
        if (($user[0]->pharmacy ?? 0) != 1) {
            return response()->json(['status' => 'error', 'message' => 'Only pharmacies can perform this action']);
        }

        // Pull partners from users table (doctors, hospitals, pharmacies) excluding self
        $rows = DB::select(
            'select id, name, email, doctor, hospital, pharmacy from users where (doctor=1 or hospital=1 or pharmacy=1) and id<>? order by id desc limit 200',
            [intval($uid)]
        );
        $partners = array_map(function ($r) {
            $type = 'partner';
            if (!empty($r->doctor)) $type = 'doctor';
            if (!empty($r->hospital)) $type = 'hospital';
            if (!empty($r->pharmacy) && empty($r->doctor) && empty($r->hospital)) $type = 'pharmacy';
            return [
                'id' => intval($r->id),
                'name' => $r->name ?: ($r->email ?: ('User #' . $r->id)),
                'type' => $type,
            ];
        }, $rows);

        return response()->json(['status' => 'success', 'partners' => $partners]);
    }

    // POST /api/pharmacy/osr
    public function storeOutOfStockRequest(Request $request)
    {
        // Expect: uid, auth, drug_name, dosage, quantity, partner_id (optional), partner_type (optional), note
        if (!$request->uid || !$request->auth) {
            return response()->json(['status' => 'error', 'message' => 'Missing parameters']);
        }

        $uid = $this->sanitizeInput($request->uid);
        $auth = $this->sanitizeInput($request->auth);

        $user = DB::select('select * from users WHERE id=' . intval($uid) . ' limit 1');
        if (empty($user) || ($user[0]->authen ?? null) !== $auth) {
            return response()->json(['status' => 'error', 'message' => 'unauthorized']);
        }
        if (($user[0]->pharmacy ?? 0) != 1) {
            return response()->json(['status' => 'error', 'message' => 'Only pharmacies can perform this action']);
        }

        $drug_name = $this->sanitizeInput($request->input('drug_name') ?? '');
        $dosage = $this->sanitizeInput($request->input('dosage') ?? '');
        $quantity = intval($request->input('quantity') ?? 0);
        $partner_id = intval($request->input('partner_id') ?? 0);
        $partner_type = $this->sanitizeInput($request->input('partner_type') ?? '');
        $note = $this->sanitizeInput($request->input('note') ?? '');

        if ($drug_name === '' || $quantity <= 0) {
            return response()->json(['status' => 'error', 'message' => 'Invalid drug name or quantity']);
        }

        $now = date('Y-m-d H:i:s');
        DB::beginTransaction();
        try {
            DB::insert(
                'insert into out_of_stock_requests (pharmacy_id, drug_name, dosage, quantity, partner_id, partner_type, note, status, created_at) values (?,?,?,?,?,?,?,?,?)',
                [$uid, $drug_name, $dosage, $quantity, $partner_id ?: null, $partner_type ?: null, $note, 'pending', $now]
            );

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Out of stock request created']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to create request', 'error' => $e->getMessage()]);
        }
    }

    // POST /api/pharmacy/message/send
    public function sendMessage(Request $request)
    {
        if (!$request->uid || !$request->auth) {
            return response()->json(['status' => 'error', 'message' => 'Missing parameters']);
        }

        $uid = $this->sanitizeInput($request->uid);
        $auth = $this->sanitizeInput($request->auth);

        $user = DB::select('select * from users WHERE id=' . intval($uid) . ' limit 1');
        if (empty($user) || ($user[0]->authen ?? null) !== $auth) {
            return response()->json(['status' => 'error', 'message' => 'unauthorized']);
        }

        $partnerId = intval($request->input('partner_id'));
        $partnerType = $this->sanitizeInput($request->input('partner_type'));
        $message = $this->sanitizeInput($request->input('message'));

        if (!$partnerId || !$partnerType || !$message) {
            return response()->json(['status' => 'error', 'message' => 'Missing required fields']);
        }

        $now = date('Y-m-d H:i:s');
        try {
            DB::insert(
                'insert into pharmacy_messages (pharmacy_id, partner_id, partner_type, sender_type, message, created_at, updated_at) values (?,?,?,?,?,?,?)',
                [$uid, $partnerId, $partnerType, 'pharmacy', $message, $now, $now]
            );
            return response()->json(['status' => 'success', 'message' => 'Message sent']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to send message', 'error' => $e->getMessage()]);
        }
    }

    // GET /api/pharmacy/messages/thread/{partnerId}/{partnerType}
    public function getThreadMessages($partnerId, $partnerType, Request $request)
    {
        if (!$request->uid || !$request->auth) {
            return response()->json(['status' => 'error', 'message' => 'Missing parameters']);
        }

        $uid = $this->sanitizeInput($request->uid);
        $auth = $this->sanitizeInput($request->auth);

        $user = DB::select('select * from users WHERE id=' . intval($uid) . ' limit 1');
        if (empty($user) || ($user[0]->authen ?? null) !== $auth) {
            return response()->json(['status' => 'error', 'message' => 'unauthorized']);
        }

        $messages = DB::select(
            'select * from pharmacy_messages WHERE pharmacy_id = ? AND partner_id = ? AND partner_type = ? ORDER BY created_at ASC',
            [intval($uid), intval($partnerId), $partnerType]
        );

        // Mark messages as read
        DB::update(
            'update pharmacy_messages set is_read = 1 WHERE pharmacy_id = ? AND partner_id = ? AND partner_type = ? AND sender_type = "partner"',
            [intval($uid), intval($partnerId), $partnerType]
        );

        return response()->json(['status' => 'success', 'messages' => $messages]);
    }

    // POST /api/pharmacy/reward/mark-paid
    public function markRewardPaid(Request $request)
    {
        if (!$request->uid || !$request->auth) {
            return response()->json(['status' => 'error', 'message' => 'Missing parameters']);
        }

        $uid = $this->sanitizeInput($request->uid);
        $auth = $this->sanitizeInput($request->auth);

        $user = DB::select('select * from users WHERE id=' . intval($uid) . ' limit 1');
        if (empty($user) || ($user[0]->authen ?? null) !== $auth) {
            return response()->json(['status' => 'error', 'message' => 'unauthorized']);
        }

        $rewardId = intval($request->input('reward_id'));
        $reference = $this->sanitizeInput($request->input('reference') ?? '');
        $notes = $this->sanitizeInput($request->input('notes') ?? '');

        if (!$rewardId) {
            return response()->json(['status' => 'error', 'message' => 'Missing reward ID']);
        }

        $now = date('Y-m-d H:i:s');
        try {
            DB::update(
                'update doctor_rewards set status = ?, payment_reference = ?, payment_notes = ?, paid_at = ?, updated_at = ? WHERE id = ? AND pharmacy_id = ?',
                ['paid', $reference, $notes, $now, $now, $rewardId, intval($uid)]
            );
            return response()->json(['status' => 'success', 'message' => 'Reward marked as paid']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to update reward', 'error' => $e->getMessage()]);
        }
    }

    // POST /api/pharmacy/patient/vitals
    public function recordVitals(Request $request)
    {
        if (!$request->uid || !$request->auth) {
            return response()->json(['status' => 'error', 'message' => 'Missing parameters']);
        }

        $uid = $this->sanitizeInput($request->uid);
        $auth = $this->sanitizeInput($request->auth);

        $user = DB::select('select * from users WHERE id=' . intval($uid) . ' limit 1');
        if (empty($user) || ($user[0]->authen ?? null) !== $auth) {
            return response()->json(['status' => 'error', 'message' => 'unauthorized']);
        }

        $patientId = intval($request->input('patient_id'));
        $bp = $this->sanitizeInput($request->input('blood_pressure') ?? '');
        $sugar = $this->sanitizeInput($request->input('sugar_level') ?? '');
        $hr = $this->sanitizeInput($request->input('heart_rate') ?? '');
        $temp = $this->sanitizeInput($request->input('temperature') ?? '');
        $weight = $this->sanitizeInput($request->input('weight') ?? '');
        $cholesterol = $this->sanitizeInput($request->input('cholesterol') ?? '');
        $hdl = $this->sanitizeInput($request->input('hdl') ?? '');
        $ldl = $this->sanitizeInput($request->input('ldl') ?? '');
        $notes = $this->sanitizeInput($request->input('notes') ?? '');

        if (!$patientId) {
            return response()->json(['status' => 'error', 'message' => 'Missing patient ID']);
        }

        $now = date('Y-m-d H:i:s');
        try {
            DB::insert(
                'insert into patient_vitals (pharmacy_patient_id, blood_pressure, sugar_level, heart_rate, temperature, weight, cholesterol, hdl, ldl, notes, recorded_at, created_at, updated_at) values (?,?,?,?,?,?,?,?,?,?,?,?,?)',
                [$patientId, $bp, $sugar, $hr, $temp, $weight, $cholesterol, $hdl, $ldl, $notes, $now, $now, $now]
            );
            return response()->json(['status' => 'success', 'message' => 'Vitals recorded successfully']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to record vitals', 'error' => $e->getMessage()]);
        }
    }

    // GET /api/pharmacy/patient/vitals-history/{id}
    public function getVitalsHistory($id, Request $request)
    {
        if (!$request->uid || !$request->auth) {
            return response()->json(['status' => 'error', 'message' => 'Missing parameters']);
        }

        $uid = $this->sanitizeInput($request->uid);
        $auth = $this->sanitizeInput($request->auth);

        $user = DB::select('select * from users WHERE id=' . intval($uid) . ' limit 1');
        if (empty($user) || ($user[0]->authen ?? null) !== $auth) {
            return response()->json(['status' => 'error', 'message' => 'unauthorized']);
        }

        $vitals = DB::select(
            'select * from patient_vitals WHERE pharmacy_patient_id = ? ORDER BY recorded_at DESC',
            [intval($id)]
        );

        return response()->json(['status' => 'success', 'vitals' => $vitals]);
    }

    private function sanitizeInput($input)
    {
        return is_string($input) ? strip_tags(htmlspecialchars(trim($input))) : $input;
    }
}
