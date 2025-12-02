<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Cookie;
use App\functions; 
use Illuminate\Support\Facades\Hash;
//use App\base;



class dashboardHospitalController extends Controller
{
	//public $secret_key = 'sk_test_bd7c6cd4cde9c6838585c6c5f5c375b353fcfce6';
	
	 function dashboard(Request $request){
         if(Cookie::get('uid')==""){
           redirect()->to("/")->send();
        }else{
            $uid = Cookie::get('uid');
            $user =  DB::select('select * from users WHERE id='.$uid);
         }
          $pg=$request->input('pg');
         $a_type="";
         $a_message="";
         
         $allvitalz =  DB::select('select * from allvitalz');
         
         if($request->input('a_type')){
             $a_type = $this->sanitizeInput($request->input('a_type'));
         $a_message = $this->sanitizeInput($request->input('a_message'));
         }
         
         if($request->input('vitalz')){
            $vitalz = $this->sanitizeInput($request->input('vitalz'));
            $reading = $this->sanitizeInput($request->input('vital_reading'));
             
            Functions::save_vital_reading($uid, $vitalz, $reading);
            $a_type="success";
            $a_message="Reading saved successfully!";
             redirect()->to("/dashboard-doctor?pg=".$pg."&a_type=".$a_type."&a_message=".$a_message)->send();
         }
         
         //add product to shop
         if($request->input('product_name')){
            $product_name = $this->sanitizeInput($request->input('product_name'));
            $product_price = $this->sanitizeInput($request->input('product_price'));
            $product_description = $this->sanitizeInput($request->input('product_description'));
            $product_category = $this->sanitizeInput($request->input('product_category'));
             
             $file = $request->file('product_image');
			$target_dir = public_path('assets/products');
			$filename = $this->imageUpload($file, $target_dir);
		
             
            Functions::add_product($uid, $product_name, $product_price, $product_description, $filename, $product_category);
            $a_type="success";
            $a_message="Product saved successfully!";
             redirect()->to("/dashboard-doctor?pg=".$pg."&a_type=".$a_type."&a_message=".$a_message)->send();
         }
         
         //edit product in shop
         if($request->input('product_nameed')){
            $pid = $this->sanitizeInput($request->input('pid'));
            $product_name = $this->sanitizeInput($request->input('product_nameed'));
            $product_price = $this->sanitizeInput($request->input('product_priceed'));
            $product_description = $this->sanitizeInput($request->input('product_descriptioned'));
            $product_category = $this->sanitizeInput($request->input('product_categoryed'));
             
             $prd =  DB::select('select * from products WHERE id='.$pid.' AND user='.$uid); 
             
             if($prd[0]->product_ref==NULL){
             $filename = $prd[0]->photo;
                 
                 if($request->file('product_imageed')){
             $file = $request->file('product_imageed');
			$target_dir = public_path('assets/products');
			$filename = $this->imageUpload($file, $target_dir);
                 }
             
            Functions::edit_product($uid, $pid, $product_name, $product_price, $product_description, $filename, $product_category);
            $a_type="success";
            $a_message="Product edited successfully!";
             redirect()->to("/dashboard-doctor?pg=store&a_type=".$a_type."&a_message=".$a_message)->send();
             }else{
                 Functions::edit_product_ref($uid, $pid, $product_price);
                 $a_type="success";
            $a_message="Product edited successfully!";
             redirect()->to("/dashboard-doctor?pg=store&a_type=".$a_type."&a_message=".$a_message)->send();
             }
         }
         
         //add product from store
         if($request->input('product_ref')){
            $product_ref = $this->sanitizeInput($request->input('product_ref'));
            $product_price = $this->sanitizeInput($request->input('product_price'));
            
            
            Functions::add_store_product($uid, $product_ref, $product_price);
            $a_type="success";
            $a_message="Product saved successfully!";
             redirect()->to("/dashboard-doctor?pg=".$pg."&a_type=".$a_type."&a_message=".$a_message)->send();
         }
         
          
         //approve affliate request
         if($request->input('approve_affliate')){
            $req_id = $this->sanitizeInput($request->input('approve_affliate'));
             
            Functions::doctor_approve_request($uid, $req_id);
            $a_type="success";
            $a_message="Request Approved!";
             redirect()->to("/dashboard-doctor?pg=".$pg."&a_type=".$a_type."&a_message=".$a_message)->send();
         }
         
         //decline affliate request
         if($request->input('decline_affliate')){
            $req_id = $this->sanitizeInput($request->input('decline_affliate'));
             
            Functions::doctor_decline_request($uid, $req_id);
            $a_type="warning";
            $a_message="Request Declined!";
             redirect()->to("/dashboard-doctor?pg=".$pg."&a_type=".$a_type."&a_message=".$a_message)->send();
         }
         
         
         //update profile picture
         if($request->file('upload_profile')){
         
			$file = $request->file('upload_profile');
			$target_dir = public_path('assets/images');
			$filename = $this->imageUpload($file, $target_dir);
		
        
            DB::update('UPDATE users SET photo=? WHERE id=?', [$filename, $uid]);
            
             $a_type="success";
            $a_message = "Profile photo updated successfully!";
            redirect()->to("/dashboard-doctor?pg=".$pg."&a_type=".$a_type."&a_message=".$a_message)->send();
        }
         
         //update profile details
         if(isset($request->first_name)){
         $first_name= $this->sanitizeInput($request->first_name);
         $last_name= $this->sanitizeInput($request->last_name);
         $phone= $this->sanitizeInput($request->phone);
         $about= $this->sanitizeInput($request->about);
         $address= $this->sanitizeInput($request->address);
         $state= $this->sanitizeInput($request->state);
         $country= $this->sanitizeInput($request->country);
         $profile_status= $this->sanitizeInput($request->profile_status);
            if($profile_status==""){
                 $profile_status=NULL;
            }
        
            DB::update('UPDATE users SET first_name=?, last_name=?, phone=?, about=?, address=?, state=?, country=?, public=?   WHERE id=?', [$first_name, $last_name, $phone, $about, $address, $state, $country, $profile_status, $uid]);
            
             $a_type="success";
            $a_message  = "Profile updated successfully!";
            redirect()->to("/dashboard-doctor?pg=".$pg."&a_type=".$a_type."&a_message=".$a_message)->send();
        }
        
         //create support ticket
         if(isset($request->support_subject)){
         $subject= $this->sanitizeInput($request->support_subject);
         $priority= $this->sanitizeInput($request->support_priority);
         $description= $this->sanitizeInput($request->support_description);
             $ticket_id = "SPDT".$uid.mt_rand(100000, 999999);
             $status = "open";
         
             Functions::add_support($uid, $ticket_id, $subject, $description, $priority, $status);
             
             $a_type="success";
            $a_message  = "Support ticket created successfully, you will get a response shortly.";
            redirect()->to("/dashboard-doctor?pg=".$pg."&a_type=".$a_type."&a_message=".$a_message)->send();
        }
        
          
          //affilate requests
         $my_requests =  DB::select('select * from patients WHERE hospital='.$uid.' AND hospital_approve IS NULL');
         $my_patients =  DB::select('select * from patients WHERE hospital='.$uid.' AND hospital_approve=1 AND user_approve=1');
         $my_doctors =  DB::select('select * from patients WHERE hospital='.$uid.' AND hospital_approve =1 AND doctor_approve=1');
         $my_pharmacy =  DB::select('select * from patients WHERE hospital='.$uid.' AND hospital_approve =1 AND pharmacy_approve=1');
         
        $request_details = [];
        $patients_details = [];
        $pharmacy_details = [];
        $doctor_details = [];
         
         foreach($my_requests as $pat){
             if(!empty($pat->user)){
             $user_requests =  DB::select('select * from users WHERE id='.$pat->user);
             array_push($request_details, $user_requests[0]);
                 
             }else if(!empty($pat->pharmacy)){
             $user_requests =  DB::select('select * from users WHERE id='.$pat->pharmacy);
             array_push($request_details, $user_requests[0]);
                 
             }else if(!empty($pat->doctor)){
             $user_requests =  DB::select('select * from users WHERE id='.$pat->doctor);
             array_push($request_details, $user_requests[0]);
             }
         }
         
         foreach($my_patients as $pat){
             $user_requests =  DB::select('select * from users WHERE id='.$pat->user);
             array_push($patients_details, $user_requests[0]);
         }
         
         foreach($my_pharmacy as $pat){
             $user_requests =  DB::select('select * from users WHERE id='.$pat->pharmacy);
             array_push($pharmacy_details, $user_requests[0]);
         }
         
         foreach($my_doctors as $pat){
             $user_requests =  DB::select('select * from users WHERE id='.$pat->doctor);
             array_push($doctor_details, $user_requests[0]);
         }
        
         
         $my_products=  DB::select('select * from products WHERE user='.$uid);
         $my_tickets=  DB::select('select * from support WHERE user='.$uid);
         $my_referrals=  DB::select('select * from users WHERE referral='.$uid);
         $my_notifications=  DB::select('select * from notifications WHERE seen IS NULL AND user='.$uid);
         $pending_appointments =  DB::select('select * from appointments WHERE end_time > '.time().' AND doctor='.$uid);
         
         $product_ref = array();
         
         foreach($my_products as $pat){
             if($pat->product_ref==NULL){
                 $product_ref[]= array();
             }else{
             $prdt =  DB::select('select * from products WHERE id='.$pat->product_ref);
             $product_ref[] = $prdt[0];
             }
         }
        
        switch($pg){
                
            case "profile":  
        return view('/dashboard_hospital', ['pagename'=>'profile_doctors', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message,  'notifications'=>$my_notifications ]);
                break; 
            
            case "messages":
                $all_notifications = DB::select('select * from notifications WHERE (user = ? OR user_id = ?) ORDER BY id DESC', [$uid, $uid]);
                return view('/dashboard_hospital', ['pagename' => 'hospital_messages', 'user' => $user, 'allvitalz' => $allvitalz, 'a_type' => $a_type, 'a_message' => $a_message, 'notifications' => $my_notifications, 'all_notifications' => $all_notifications]);
                break; 
                  
            case "patient_details":  
            $ptidr = $request->input('ptid');
                
            $pat_user =  DB::select('select * from users WHERE ref_code="'.$ptidr.'"'); 
            $ptid = $pat_user[0]->id;
                
         $heart_rate_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 1');
         $blood_pressure_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 2');
         $oxygen_saturation_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 3');
         $stress_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 4');
         $blood_glucose_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 5');
         $lipids_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 6');
         $hba1c_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 7');
         $ihra_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 8');
         $body_temperature_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 9');
                
        return view('/dashboard_hospital', ['pagename'=>'patient_details', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'heart_rate_readings'=>$heart_rate_readings, 'blood_pressure_readings'=>$blood_pressure_readings, 'oxygen_saturation_readings'=>$oxygen_saturation_readings, 'stress_readings'=>$stress_readings, 'blood_glucose_readings'=>$blood_glucose_readings, 'lipids_readings'=>$lipids_readings, 'hba1c_readings'=>$hba1c_readings, 'ihra_readings'=>$ihra_readings, 'body_temperature_readings'=>$body_temperature_readings, 'pat_user'=>$pat_user, 'notifications'=>$my_notifications  ]);
                break; 
                
               
            case "appointment_details":  
            $ptidr = $this->sanitizeInput($request->input('aptid'));
                
            $appointment =  DB::select('select * from appointments WHERE doctor='.$uid.' AND id='.$ptidr); 
           
            $pat_user =  DB::select('select * from users WHERE id='.$appointment[0]->user); 
            
                
                      
        return view('/dashboard_hospital', ['pagename'=>'appointment_details', 'user'=>$user, 'a_type'=>$a_type, 'a_message'=>$a_message, 'pat_user'=>$pat_user, 'notifications'=>$my_notifications, 'appointment'=>$appointment  ]);
                break; 
                
            case "patient_reading_history":  
            $ptidr = $this->sanitizeInput($request->input('ptid'));
                
            $pat_user =  DB::select('select * from users WHERE ref_code="'.$ptidr.'"'); 
            $ptid = $pat_user[0]->id;
                
         $heart_rate_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 1');
         $blood_pressure_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 2');
         $oxygen_saturation_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 3');
         $stress_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 4');
         $blood_glucose_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 5');
         $lipids_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 6');
         $hba1c_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 7');
         $ihra_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 8');
         $body_temperature_readings =  DB::select('select * from vital_readings WHERE user='.$ptid.' AND vitalz = 9');
                
        return view('/dashboard_hospital', ['pagename'=>'patient_reading_history', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'heart_rate_readings'=>$heart_rate_readings, 'blood_pressure_readings'=>$blood_pressure_readings, 'oxygen_saturation_readings'=>$oxygen_saturation_readings, 'stress_readings'=>$stress_readings, 'blood_glucose_readings'=>$blood_glucose_readings, 'lipids_readings'=>$lipids_readings, 'hba1c_readings'=>$hba1c_readings, 'ihra_readings'=>$ihra_readings, 'body_temperature_readings'=>$body_temperature_readings, 'pat_user'=>$pat_user, 'notifications'=>$my_notifications  ]);
                break; 
            
            case "patient_medications_all":  
            $ptidr = $this->sanitizeInput($request->input('ptid'));
                
            $pat_user =  DB::select('select * from users WHERE ref_code="'.$ptidr.'"'); 
            $ptid = $pat_user[0]->id;
                
         $medications =  DB::select('select * from prescriptions WHERE user='.$ptid);
                
                $pres_days = array();
                $pres_by_days = array();
                
                foreach($medications as $med){
                    $pdays = date("d-M-Y", $med->date);
                    $pres_days[] = $pdays;
                    $pres_by_days[$pdays][]=$med;
                }
                
                $pres_days_sort = array_unique($pres_days);
                
        return view('/dashboard_hospital', ['pagename'=>'patient_medications_all', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message,  'medications'=>$medications, 'pat_user'=>$pat_user, 'notifications'=>$my_notifications, 'pres_days'=>$pres_days_sort, 'pres_by_days'=>$pres_by_days]);
                break; 
             
            case "patient_medications":  
            $ptidr = $this->sanitizeInput($request->input('ptid'));
            $pscid = $this->sanitizeInput($request->input('pscid'));
                
            $pat_user =  DB::select('select * from users WHERE ref_code="'.$ptidr.'"'); 
            $ptid = $pat_user[0]->id;
                
         $medications =  DB::select('select * from prescriptions WHERE user='.$ptid);
                
                $pres_days = array();
                $pres_by_days = array();
                $doctors = array();
                $last_compliance = array();
                
                foreach($medications as $med){
                    $pdays = date("d-M-Y", $med->date);
                    $pres_days[] = $pdays;
                    $pres_by_days[$pdays][]=$med;
                    $doctors[$pdays][] =  DB::select('select * from users WHERE id="'.$med->doctor.'"'); 
                    $last_compliance[$pdays][] =  DB::select('select * from medications WHERE user='.$ptid.' AND prescription_id='.$med->id); 
                }
                
                $pres_days_sort = array_unique($pres_days);
                
        return view('/dashboard_hospital', ['pagename'=>'patient_medications', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message,  'medications'=>$pres_by_days[$pscid], 'pat_user'=>$pat_user, 'notifications'=>$my_notifications, 'pres_days'=>$pres_days_sort, "doctors"=>$doctors[$pscid], 'compliance'=>$last_compliance[$pscid]]);
                break; 
           
                  
            case "affiliates":  
        return view('/dashboard_hospital', ['pagename'=>'affiliate_doctors', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message,  'my_requests'=>$my_requests, 'my_patients'=>$my_patients, 'my_pharmacy'=>$my_pharmacy, 'request_details'=>$request_details, 'patients_details'=>$patients_details, 'pharmacy_details'=>$pharmacy_details, 'notifications'=>$my_notifications  ]);
                break; 
                
            case "referrals":  
        return view('/dashboard_hospital', ['pagename'=>'referrals', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'notifications'=>$my_notifications, 'my_referrals'=>$my_referrals  ]);
                break; 
                
            case "store":  
        return view('/dashboard_hospital', ['pagename'=>'myproducts', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message,  'my_products'=>$my_products, 'notifications'=>$my_notifications, 'product_ref'=>$product_ref ]);
                break; 
                
            case "editproduct":  
                $pdid = $this->sanitizeInput($request->input('pd'));
                
            $my_products =  DB::select('select * from products WHERE id='.$pdid.' AND user='.$uid); 
                
                $product_ref = array();
         
         foreach($my_products as $pat){
             if($pat->product_ref==NULL){
                 $product_ref[]= array();
             }else{
             $prdt =  DB::select('select * from products WHERE id='.$pat->product_ref);
             $product_ref[] = $prdt[0];
             }
         }
        
                
        return view('/dashboard_hospital', ['pagename'=>'editproduct', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'my_products'=>$my_products, 'notifications'=>$my_notifications, 'product_ref'=>$product_ref ]);
                break; 
                
            case "support":  
        return view('/dashboard_hospital', ['pagename'=>'support', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message,  'my_tickets'=>$my_tickets, 'notifications'=>$my_notifications ]);
                break; 
                
                
            case "support_details": 
                $tkid = $this->sanitizeInput($request->input('tkid'));
                
            $my_tickets =  DB::select('select * from support WHERE ticket_id="'.$tkid.'"'); 
            $my_tickets_replies =  DB::select('select * from support_replies WHERE support_id="'.$tkid.'"'); 
            
                
        return view('/dashboard_hospital', ['pagename'=>'support_details', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message,  'my_tickets'=>$my_tickets, 'my_tickets_replies'=>$my_tickets_replies, 'notifications'=>$my_notifications ]);
                break; 
                
            case "readings":  
        return view('/dashboard_hospital', ['pagename'=>'readings', 'user'=>$user, 'allvitalz'=>$allvitalz, 'heart_rate_readings'=>$heart_rate_readings, 'blood_pressure_readings'=>$blood_pressure_readings, 'oxygen_saturation_readings'=>$oxygen_saturation_readings, 'stress_readings'=>$stress_readings, 'blood_glucose_readings'=>$blood_glucose_readings, 'lipids_readings'=>$lipids_readings, 'hba1c_readings'=>$hba1c_readings, 'ihra_readings'=>$ihra_readings, 'body_temperature_readings'=>$body_temperature_readings, 'a_type'=>$a_type, 'a_message'=>$a_message, 'appointment_schedule'=>$appointment_schedule, 'notifications'=>$my_notifications  ]);
                break; 
                
                
            case "medications":  
        return view('/dashboard_hospital', ['pagename'=>'medications', 'user'=>$user, 'allvitalz'=>$allvitalz,  'a_type'=>$a_type, 'a_message'=>$a_message,  'notifications'=>$my_notifications  ]);
                break; 
                
            case "medication_hospital":  
                $ptidr = $this->sanitizeInput($request->input('ptid'));
                $pscid = $this->sanitizeInput($request->input('pscid'));
                
            $pat_user =  DB::select('select * from users WHERE ref_code="'.$ptidr.'"'); 
            $ptid = $pat_user[0]->id;
                
                $taken_history =  DB::select('select * from medications WHERE user='.$ptid.' AND prescription_id='.$pscid);
                
                $prescription =  DB::select('select * from prescriptions WHERE id='.$pscid);
                
                $morning= array();
                $afternoon= array();
                $night= array();
                $days= array();
                
                foreach($taken_history as $taken){
                    
                    $dt = date("d-M-Y", strtotime($taken->date));
                    
                    $days[]=$dt;
                    
                    if($taken->period_taken == "Morning"){
                        $morning[$dt]=date("h:ia", strtotime($taken->date));
                    }else if($taken->period_taken == "Afternoon"){
                        $afternoon[$dt]=date("h:ia", strtotime($taken->date));
                    }else if($taken->period_taken == "Night"){
                        $night[$dt]=date("h:ia", strtotime($taken->date));
                    }
                }
                $days = array_merge(array_unique($days));
         
        return view('/dashboard_hospital', ['pagename'=>'medication_history', 'user'=>$user, 'allvitalz'=>$allvitalz,  'a_type'=>$a_type, 'a_message'=>$a_message,  'notifications'=>$my_notifications, "morning"=>$morning, "afternoon"=>$afternoon, "night"=>$night, "days"=>$days, "pat_user"=>$pat_user, "prescription"=>$prescription[0] ]);
                break; 
                
            default:
                 return view('/dashboard_hospital', ['pagename'=>'hospital_patients', 'user'=>$user, 'a_type'=>$a_type, 'a_message'=>$a_message, 'my_requests'=>$my_requests, 'my_patients'=>$my_patients, 'my_doctors'=>$my_doctors, 'my_pharmacy'=>$my_pharmacy, 'request_details'=>$request_details, 'patients_details'=>$patients_details, 'pharmacy_details'=>$pharmacy_details, 'doctor_details'=>$doctor_details, 'notifications'=>$my_notifications  ]);
        }
     }
    
    function search_patients(Request $request){
        if(Cookie::get('uid')==""){
           exit();
        }else{
            $uid = Cookie::get('uid');
         }
        
        if($request->input('pname')){
             $pname = $this->sanitizeInput($request->input('pname'));

            $user =  DB::select('select * from users WHERE doctor IS NULL AND hospital IS NULL AND pharmacy IS NULL AND phone="'.$pname.'"');
            
            $list = '<ul class="list-unstyled mb-0">';
            for($i=0; $i<count($user); $i++){
                $my_requests = DB::select('select * from patients WHERE patient='.$user[$i]->id.' AND user='.$uid.' AND hospital_approve=1');
                
                if($user[0]->photo!=""){
                 $pic = "../assets/images/".$user[$i]->photo;
                }else{ 
                    $pic = "../assets/img/avatars/user.png"; 
                    } 
                
                $list .= ' <li class="mb-3">
                <div class="d-flex align-items-start">
                  <div class="d-flex align-items-start">
                    <div class="avatar me-3">
                      <img src="'.$pic.'" alt="Avatar" class="rounded-circle">
                    </div>
                    <div class="me-2">
                      <h6 class="mt-2">'.$user[$i]->first_name.' '.$user[$i]->last_name.'</h6>
                    </div>
                  </div>
                  <div class="ms-auto" id="btn_'.$user[$i]->ref_code.'">';
                if(empty($my_requests)){
                  $list .= '  <button class="btn btn-label-primary btn-icon btn-sm"  onclick="add_patient(\''.$user[$i]->ref_code.'\', \'btn_'.$user[$i]->ref_code.'\')"><i class="bx bx-plus"></i> Add</button>';
                }else{
                    $list .= 'Request sent';
                }
                $list .= '
                  </div>
                </div>
              </li>';
            }
            $list .='</ul>';
            
            if(empty($user)){
            $list = "No patient found!";
            }
            
            return $list;
         }
    }
    
    
    function search_doctors(Request $request){
        if(Cookie::get('uid')==""){
           exit();
        }else{
            $uid = Cookie::get('uid');
         }
        
        if($request->input('pname')){
             $pname = $this->sanitizeInput($request->input('pname'));

            $user =  DB::select('select * from users WHERE doctor=1 AND hospital IS NULL AND pharmacy IS NULL AND phone="'.$pname.'"');
            
            $list = '<ul class="list-unstyled mb-0">';
            for($i=0; $i<count($user); $i++){
                $my_requests = DB::select('select * from patients WHERE (user='.$uid.' AND doctor='.$user[$i]->id.' AND  hospital_approve=1) OR (hospital='.$uid.' AND user='.$user[$i]->id.' AND doctor_approve=1');
                
                if($user[0]->photo!=""){
                 $pic = "../assets/images/".$user[$i]->photo;
                }else{ 
                    $pic = "../assets/img/avatars/user.png"; 
                    } 
                
                $list .= ' <li class="mb-3">
                <div class="d-flex align-items-start">
                  <div class="d-flex align-items-start">
                    <div class="avatar me-3">
                      <img src="'.$pic.'" alt="Avatar" class="rounded-circle">
                    </div>
                    <div class="me-2">
                      <h6 class="mt-2">'.$user[$i]->first_name.' '.$user[$i]->last_name.'</h6>
                    </div>
                  </div>
                  <div class="ms-auto" id="btn_'.$user[$i]->ref_code.'">';
                if(empty($my_requests)){
                  $list .= '  <button class="btn btn-label-primary btn-icon btn-sm"  onclick="add_doctor(\''.$user[$i]->ref_code.'\', \'btn_'.$user[$i]->ref_code.'\')"><i class="bx bx-plus"></i> Add</button>';
                }else{
                    $list .= 'Request sent';
                }
                $list .= '
                  </div>
                </div>
              </li>';
            }
            $list .='</ul>';
            
            if(empty($user)){
            $list = "No Doctor found!";
            }
            
            return $list;
         }
    }
    
    
    function search_products(Request $request){
        if(Cookie::get('uid')==""){
           exit();
        }else{
            $uid = Cookie::get('uid');
         }
        
        if($request->input('product_search')){
             $pname = $this->sanitizeInput($request->input('product_search'));
          
            $product =  DB::select('select * from products WHERE hide IS NULL AND name LIKE "'.$pname.'%"');
            
            $list = '';
            foreach($product as $pdt){
               
                $list .= '<input type="radio" name="ref_p" onclick="select_suggested_product(\''.$pdt->id.'\', \''.$pdt->name.'\')"> <img src="assets/products/'.$pdt->photo.'" width="30px" height="30px"> '.$pdt->name.'<hr>';
            }
            
            if(empty($product)){
            $list = "No product found!<hr>";
            }
            
            return $list;
         }
    }
    
    function add_patients(Request $request){
         if(Cookie::get('uid')==""){
           exit();
        }else{
            $uid = Cookie::get('uid');
         }
        
        if($request->input('pcode')){
            $ref_code = $this->sanitizeInput($request->input('pcode'));
            $patient =  DB::select('select * from users WHERE ref_code="'.$ref_code.'"');
            Functions::add_patienth($uid, $patient[0]->id);
            $description="You have a new hospital request"; 
            $link = "?pg=affiliates";
            Functions::notify($patient[0]->id, $description, $link);
        }
    }
    
    
    function add_doctors(Request $request){
         if(Cookie::get('uid')==""){
           exit();
        }else{
            $uid = Cookie::get('uid');
         }
        
        if($request->input('pcode')){
            $ref_code = $this->sanitizeInput($request->input('pcode'));
            $doctor =  DB::select('select * from users WHERE ref_code="'.$ref_code.'"');
            Functions::add_doctorh($uid, $doctor[0]->id);
            $description="You have a new hospital request"; 
            $link = "?pg=affiliates";
            Functions::notify($doctor[0]->id, $description, $link);
            
        }
    }
     
      
    function check_compliance(Request $request){
         $ptidr = $this->sanitizeInput($request->input('ptid'));
                $pscid = $this->sanitizeInput($request->input('pscid'));
                
            $pat_user =  DB::select('select * from users WHERE ref_code="'.$ptidr.'"'); 
            $ptid = $pat_user[0]->id;
                
                $taken_history =  DB::select('select * from medications WHERE user='.$ptid.' AND prescription_id='.$pscid);
                
                $prescription =  DB::select('select * from prescriptions WHERE id='.$pscid);
                
                $morning= array();
                $afternoon= array();
                $night= array();
                $days= array();
                 
        $med = '<h4>'.$prescription[0]->drug_name.'</h4>
        <table class="table table-hover">
                   <thead>
                          <th>Date</th>
                          <th>Morning</th>
                          <th>Afternoon</th>
                          <th>Night</th>
                          </thead>
                    <tbody class="table-border-bottom-0">
                        ';
        
                foreach($taken_history as $taken){
                    
                    $dt = date("d-M-Y", strtotime($taken->date));
                    
                    $days[]=$dt;
                    
                    if($taken->period_taken == "Morning"){
                        $morning[$dt]=date("h:ia", strtotime($taken->date));
                    }else if($taken->period_taken == "Afternoon"){
                        $afternoon[$dt]=date("h:ia", strtotime($taken->date));
                    }else if($taken->period_taken == "Night"){
                        $night[$dt]=date("h:ia", strtotime($taken->date));
                    }
                }
                $days = array_merge(array_unique($days));
        
       
                        if(!empty($days)){
                            for($i=count($days)-1; $i>=0; $i--){
                        
                     $med .= ' <tr>
                        <td><strong>'. $days[$i].'</strong></td>
                        <td>';
                                if(empty($morning[$days[$i]])){$med .= "--"; }else{ $med .=$morning[$days[$i]]; }
                                
                           $med .= '      </td>
                        <td>';
                               if(empty($afternoon[$days[$i]])){$med .= "--"; }else{ $med .=$afternoon[$days[$i]]; }
                                
                                $med .='</td>
                        <td>'; 
                                    if(empty($night[$days[$i]])){$med .= "--"; }else{ $med .= $night[$days[$i]]; }
                                
                                $med .='</td>
                        
                      </tr>';
                     
                            }
                        }else{
                               
                       $med .='<tr> <td>No records</td></tr>';
                       
                        }
        
                        $med .='
                    </tbody>
                  </table>';
        return $med;
    }
  
    function seen_notification(Request $request){
         if(Cookie::get('uid')==""){
           exit();
        }else{
            $uid = Cookie::get('uid');
         }
        
        if($request->input('not_id')){
            $noti_id = $this->sanitizeInput($request->input('not_id'));
            DB::select('UPDATE notifications SET seen=? WHERE id=? AND user=?', ['1', $noti_id, $uid]);
        }
    }
       
  
      
	// function to upload images
function imageUpload($file, $target_dir){
  
 // Allowed extensions
 $extensions_arr = array("jpg","jpeg","png","gif");
$newfilename="";
 // Check extension
 if( in_array($file->getClientOriginalExtension(),$extensions_arr) ){
 
 
$newfilename = round(microtime(true)) .mt_rand(1, 999).'.' . $file->getClientOriginalExtension();
  
  // Upload file
  $file->move($target_dir, $newfilename);

	}
	return $newfilename;
}
	
	// function to upload images
function imageUploadDoc($file, $target_dir){
  
 // Allowed extensions
 $extensions_arr = array("jpg","jpeg","png","gif","doc", "docx", "pdf");
$newfilename="";
 // Check extension
 if( in_array($file->getClientOriginalExtension(),$extensions_arr) ){
 
 
$newfilename = round(microtime(true)) .mt_rand(1, 999).'.' . $file->getClientOriginalExtension();
  
  // Upload file
  $file->move($target_dir, $newfilename);

	}
	return $newfilename;
}
	
    	
function sanitizeInput($input){
	//sanitze an input
	//include("connect.php");
	$input = strip_tags(htmlspecialchars(trim($input)));
	//$input = mysqli_real_escape_string($link, $input);
	return $input;
}
    
    
function curPageURL() {
 $pageURL = 'http';
// if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
    
    //../../myaccount.myvitalz.ai/assets/images
}
