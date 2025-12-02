<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Cookie;
use Illuminate\Support\Facades\Hash;

class dashboardPharmacyController extends Controller
{
    
    function dashboard(Request $request){
        
        if(!$request->hasCookie('uid')){
          redirect()->to("/")->send();
       }
        
        $uid = Cookie::get('uid');
        $user =  DB::select('select * from users WHERE id='.$uid);
        
        if($user[0]->pharmacy != 1){
            redirect()->to("/")->send();
        }
        
        $pagename = "pharmacy_home";
        $a_type = "";
        $a_message = "";
        
        // Get page parameter
        $pg = $request->input('pg');
        if(!$pg) $pg = 'home';
        
        // Handle different pages
        switch($pg) {
            case 'network':
                $pagename = "pharmacy_network";
                break;
            case 'prescriptions':
                $pagename = "pharmacy_prescriptions";
                break;
            case 'inventory':
                $pagename = "pharmacy_inventory";
                break;
            case 'patients':
                $pagename = "pharmacy_patients";
                break;
            case 'monitoring':
                $pagename = "pharmacy_monitoring";
                break;
            case 'rewards':
                $pagename = "pharmacy_rewards";
                break;
            case 'profile':
                $pagename = "pharmacy_profile";
                break;
            default:
                $pagename = "pharmacy_home";
        }
        
        // Get pharmacy statistics
        $total_network_doctors = DB::select('select count(*) as count from pharmacy_networks WHERE pharmacy_id='.$uid.' AND member_type="doctor" AND status="active"');
        $total_network_patients = DB::select('select count(*) as count from pharmacy_networks WHERE pharmacy_id='.$uid.' AND member_type="patient" AND status="active"');
        $total_network_hospitals = DB::select('select count(*) as count from pharmacy_networks WHERE pharmacy_id='.$uid.' AND member_type="hospital" AND status="active"');
        $pending_prescriptions = DB::select('select count(*) as count from e_prescriptions WHERE pharmacy_id='.$uid.' AND status="pending"');
        $low_stock_items = DB::select('select count(*) as count from pharmacy_inventory WHERE pharmacy_id='.$uid.' AND stock_quantity <= reorder_level');
        
        // Get recent prescriptions
        $recent_prescriptions = DB::select('select * from e_prescriptions WHERE pharmacy_id='.$uid.' ORDER BY created_at DESC LIMIT 5');
        
        // Get notifications
        $notifications = DB::select('select * from notifications WHERE user_id='.$uid.' AND seen=0 ORDER BY date DESC');
        
        // Handle form submissions
        if($request->isMethod('post')){
            
            // Add network member
            if($request->input('action') == 'add_network_member'){
                $member_email = $this->sanitizeInput($request->input('member_email'));
                $member_type = $this->sanitizeInput($request->input('member_type'));
                
                // Check if user exists
                $member_user = DB::select('select * from users WHERE email="'.$member_email.'"');
                if($member_user){
                    // Check if already in network
                    $existing = DB::select('select * from pharmacy_networks WHERE pharmacy_id='.$uid.' AND member_id='.$member_user[0]->id);
                    if(!$existing){
                        DB::insert('insert into pharmacy_networks (pharmacy_id, member_id, member_type, status, created_at) values(?,?,?,?,?)',
                            [$uid, $member_user[0]->id, $member_type, 'active', date('Y-m-d H:i:s')]);
                        $a_type = "success";
                        $a_message = "Network member added successfully!";
                    } else {
                        $a_type = "warning";
                        $a_message = "User is already in your network!";
                    }
                } else {
                    $a_type = "warning";
                    $a_message = "User not found with that email!";
                }
            }
            
            // Add inventory item
            if($request->input('action') == 'add_inventory'){
                $medication_name = $this->sanitizeInput($request->input('medication_name'));
                $generic_name = $this->sanitizeInput($request->input('generic_name'));
                $manufacturer = $this->sanitizeInput($request->input('manufacturer'));
                $dosage = $this->sanitizeInput($request->input('dosage'));
                $form = $this->sanitizeInput($request->input('form'));
                $stock_quantity = intval($request->input('stock_quantity'));
                $reorder_level = intval($request->input('reorder_level'));
                $wholesale_price = floatval($request->input('wholesale_price'));
                $retail_price = floatval($request->input('retail_price'));
                $doctor_price = floatval($request->input('doctor_price'));
                
                DB::insert('insert into pharmacy_inventory (pharmacy_id, medication_name, generic_name, manufacturer, dosage, form, stock_quantity, reorder_level, wholesale_price, retail_price, doctor_price, created_at) values(?,?,?,?,?,?,?,?,?,?,?,?)',
                    [$uid, $medication_name, $generic_name, $manufacturer, $dosage, $form, $stock_quantity, $reorder_level, $wholesale_price, $retail_price, $doctor_price, date('Y-m-d H:i:s')]);
                
                $a_type = "success";
                $a_message = "Medication added to inventory successfully!";
            }
        }
        
        return view('/dashboard_pharmacy', [
            'pagename' => $pagename,
            'user' => $user,
            'notifications' => $notifications,
            'total_network_doctors' => $total_network_doctors[0]->count ?? 0,
            'total_network_patients' => $total_network_patients[0]->count ?? 0,
            'total_network_hospitals' => $total_network_hospitals[0]->count ?? 0,
            'pending_prescriptions' => $pending_prescriptions[0]->count ?? 0,
            'low_stock_items' => $low_stock_items[0]->count ?? 0,
            'recent_prescriptions' => $recent_prescriptions,
            'a_type' => $a_type,
            'a_message' => $a_message
        ]);
    }
    
    function sanitizeInput($input){
        $input = strip_tags(htmlspecialchars(trim($input)));
        return $input;
    }
}
