<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Cookie;
//use App\Paystack; 
use Illuminate\Support\Facades\Hash;
//use App\base;



class loginController extends Controller
{
	//public $secret_key = 'sk_test_bd7c6cd4cde9c6838585c6c5f5c375b353fcfce6';
	
	 function signup(Request $request){
         
           $pg=$request->input('pg');
        
       
        return view('/signup', ['pagename'=>'profile' ]);
        
     }
    
    function signup_patient(Request $request){
         $rtn ="";
           if(isset($request->email) && $request->isMethod('post')){
         $password = Hash::make($request->password);
         $email= $this->sanitizeInput($request->email);
         $first_name= $this->sanitizeInput($request->first_name);
         $last_name= $this->sanitizeInput($request->last_name);
         $phone= $this->sanitizeInput($request->phone);
         $referral= $this->sanitizeInput($request->referral);
         
         // Check for affiliate link code
         $affiliateCode = $this->sanitizeInput($request->input('ref', ''));
         
        $dt = date("d-M-Y");
        $ref_code ="MV".mt_rand(000000, 999999);
        
        $emailcheck =  DB::select('select * from users WHERE email="'.$email.'"');    
        $refcheck =  DB::select('select * from users WHERE ref_code="'.$referral.'"'); 
               
               if($refcheck){
                   $ref_id = $refcheck[0]->id;
               }else{
                   $ref_id = "";
               }
               
               if($emailcheck){
                $rtn="Email already exist!";
            }else{
             // Determine registration source and network lock
             $registrationSource = 'direct';
             $affiliateProviderId = null;
             $networkLocked = 0;
             
             // Check if this is an affiliate registration
             if (!empty($affiliateCode)) {
                 $affiliateLink = DB::select('select * from affiliate_links WHERE link_code=? AND is_active=1', [$affiliateCode]);
                 if (!empty($affiliateLink)) {
                     $registrationSource = $affiliateLink[0]->provider_type . '_link';
                     $affiliateProviderId = $affiliateLink[0]->provider_id;
                     $networkLocked = 1;
                 }
             }
             
             DB::insert('insert into users (first_name, last_name, name, phone, email, password, authen, ref_code, referral, date, registration_source, affiliate_provider_id, network_locked) values(?,?,?,?,?,?,?,?,?,?,?,?,?)',[
                $first_name,
                $last_name,
                $first_name.' '.$last_name,
                $phone,
                $email,
                $password,
                $password,
                $ref_code,
                $ref_id,
                $dt,
                $registrationSource,
                $affiliateProviderId,
                $networkLocked
             ]);
             
             // Get the new user ID
             $newUser = DB::select('select id from users WHERE email=?', [$email]);
             $newUserId = $newUser[0]->id;
             
             // Process affiliate registration if applicable
             if (!empty($affiliateCode) && !empty($affiliateProviderId)) {
                 // Use NetworkHelper to complete affiliate registration
                 require_once app_path('Helpers/NetworkHelper.php');
                 \App\Helpers\NetworkHelper::processAffiliateRegistration($affiliateCode, $newUserId, 'patient');
             }
                   
                   redirect()->to("/?reg=1")->send();
               }
           }
        
       
        return view('/signup_patient', ['rtn'=>$rtn ]);
        
     }
    
    function signup_doctor(Request $request){
         
            $rtn ="";
           if(isset($request->email) && $request->isMethod('post')){
         $password = Hash::make($request->password);
         $email= $this->sanitizeInput($request->email);
         $first_name= $this->sanitizeInput($request->first_name);
         $last_name= $this->sanitizeInput($request->last_name);
         $phone= $this->sanitizeInput($request->phone);
         $referral= $this->sanitizeInput($request->referral);
         $specialization= $this->sanitizeInput($request->specialization);
         $license_type= $this->sanitizeInput($request->license_type);
         
         // Check for affiliate link code
         $affiliateCode = $this->sanitizeInput($request->input('ref', ''));
         
        $dt = date("d-M-Y");
        $ref_code ="MVD".mt_rand(000000, 999999);
        
        $emailcheck =  DB::select('select * from users WHERE email="'.$email.'"');    
        $refcheck =  DB::select('select * from users WHERE ref_code="'.$referral.'"'); 
               
               if($refcheck){
                   $ref_id = $refcheck[0]->id;
               }else{
                   $ref_id = "";
               }
               
               if($emailcheck){
                $rtn="Email already exist!";
            }else{
             // Determine registration source and network lock
             $registrationSource = 'direct';
             $affiliateProviderId = null;
             $networkLocked = 0;
             
             // Check if this is an affiliate registration
             if (!empty($affiliateCode)) {
                 $affiliateLink = DB::select('select * from affiliate_links WHERE link_code=? AND is_active=1', [$affiliateCode]);
                 if (!empty($affiliateLink)) {
                     $registrationSource = $affiliateLink[0]->provider_type . '_link';
                     $affiliateProviderId = $affiliateLink[0]->provider_id;
                     $networkLocked = 1;
                 }
             }
             
             DB::insert('insert into users (first_name, last_name, name, phone, email, password, authen, ref_code, referral, date, doctor, specialization, license_type, registration_source, affiliate_provider_id, network_locked) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',[
                $first_name,
                $last_name,
                $first_name.' '.$last_name,
                $phone,
                $email,
                $password,
                $password,
                $ref_code,
                $ref_id,
                $dt,
                1,
                $specialization,
                $license_type,
                $registrationSource,
                $affiliateProviderId,
                $networkLocked
             ]);
             
             // Get the new user ID
             $newUser = DB::select('select id from users WHERE email=?', [$email]);
             $newUserId = $newUser[0]->id;
             
             // Process affiliate registration if applicable
             if (!empty($affiliateCode) && !empty($affiliateProviderId)) {
                 require_once app_path('Helpers/NetworkHelper.php');
                 \App\Helpers\NetworkHelper::processAffiliateRegistration($affiliateCode, $newUserId, 'doctor');
             }
                   
                   redirect()->to("/?reg=1")->send();
               }
           }
        
       
        return view('/signup_doctor', ['rtn'=>$rtn ]);
        
     }
    
    function signup_hospital(Request $request){
         
             $rtn ="";
           if(isset($request->email) && $request->isMethod('post')){
         $password = Hash::make($request->password);
         $email= $this->sanitizeInput($request->email);
         $first_name= $this->sanitizeInput($request->first_name);
         $last_name= $this->sanitizeInput($request->last_name);
         $phone= $this->sanitizeInput($request->phone);
        $dt = date("d-M-Y");
        $ref_code ="MVH".mt_rand(000000, 999999);
        
        $emailcheck =  DB::select('select * from users WHERE email="'.$email.'"');    
               
               if($emailcheck){
                $rtn="Email already exist!";
            }else{
             DB::insert('insert into users (first_name, last_name, name, phone, email, password, authen, ref_code, hospital, date) values(?,?,?,?,?,?,?,?,?,?)',[
                $first_name,
                $last_name,
                $first_name.' '.$last_name,
                $phone,
                $email,
                $password,
                $password,
                $ref_code,
                "1",
                $dt
             ]);
                   
                   redirect()->to("/?reg=1")->send();
               }
           }
        
        
       
        return view('/signup_hospital', ['rtn'=>$rtn ]);
        
     }
    
    function signup_pharmacy(Request $request){
         
             $rtn ="";
           if(isset($request->email) && $request->isMethod('post')){
         $password = Hash::make($request->password);
         $email= $this->sanitizeInput($request->email);
         $first_name= $this->sanitizeInput($request->first_name);
         $last_name= $this->sanitizeInput($request->last_name);
         $phone= $this->sanitizeInput($request->phone);
        $dt = date("d-M-Y");
        $ref_code ="MVP".mt_rand(000000, 999999);
        
        $emailcheck =  DB::select('select * from users WHERE email="'.$email.'"');    
               
               if($emailcheck){
                $rtn="Email already exist!";
            }else{
             DB::insert('insert into users (first_name, last_name, name, phone, email, password, authen, ref_code, pharmacy, date) values(?,?,?,?,?,?,?,?,?,?)',[
                $first_name,
                $last_name,
                $first_name.' '.$last_name,
                $phone,
                $email,
                $password,
                $password,
                $ref_code,
                "1",
                $dt
             ]);
                   
                   redirect()->to("/?reg=1")->send();
               }
           }
        
        
       
        return view('/signup_pharmacy', ['rtn'=>$rtn ]);
        
     }
    
    function signup_sales_rep(Request $request){
         
             $rtn ="";
           if(isset($request->email) && $request->isMethod('post')){
         $password = Hash::make($request->password);
         $email= $this->sanitizeInput($request->email);
         $first_name= $this->sanitizeInput($request->first_name);
         $last_name= $this->sanitizeInput($request->last_name);
         $phone= $this->sanitizeInput($request->phone);
         $company_name= $this->sanitizeInput($request->company_name);
         $company_license= $this->sanitizeInput($request->company_license);
         $state= $this->sanitizeInput($request->state);
         $city= $this->sanitizeInput($request->city);
         $address= $this->sanitizeInput($request->address);
        $dt = date("d-M-Y");
        $ref_code ="MVSR".mt_rand(000000, 999999);
        
        $emailcheck =  DB::select('select * from users WHERE email="'.$email.'"');    
               
               if($emailcheck){
                $rtn="Email already exist!";
            }else{
             DB::insert('insert into users (first_name, last_name, name, phone, email, password, authen, ref_code, sales_rep, company_name, company_license, state, city, address, date) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',[
                $first_name,
                $last_name,
                $first_name.' '.$last_name,
                $phone,
                $email,
                $password,
                $password,
                $ref_code,
                "1",
                $company_name,
                $company_license,
                $state,
                $city,
                $address,
                $dt
             ]);
                   
                   redirect()->to("/?reg=1")->send();
               }
           }
        
        
       
        return view('/signup_sales_rep', ['rtn'=>$rtn ]);
        
     }
    
	 function login(Request $request){
         $reg ="";
         $rtn ="";
        if($request->input('reg')){
               $reg = 1;
           }
         
           // Only auto-redirect if user has cookie AND it's not a registration redirect AND not switching roles
           // Check if 'role' parameter exists - if it does, user is switching login forms, don't auto-redirect
           if($request->cookie('uid') && !$request->input('reg') && !$request->input('role')){
			 $uid = Cookie::get('uid');
            $user =  DB::select('select * from users WHERE id='.$uid);
            
            if(!empty($user)){
               if($user[0]->doctor==1){
                  redirect()->to("/dashboard-pharmacy")->send();
               }else if($user[0]->hospital==1){
                  redirect()->to("/dashboard-hospital")->send();
               }else if($user[0]->pharmacy==1){
                  redirect()->to("/dashboard-pharmacy")->send();
               }else if($user[0]->sales_rep==1){
                  redirect()->to("/dashboard-sales-rep")->send();
               }else {
              
				 redirect()->to("/dashboard")->send();
               }
            }
       }
       
       
       if(isset($request->email) && $request->isMethod('post')){
        $user = DB::select('select * from users WHERE email="'.$this->sanitizeInput($request->email).'"');
        $login_as = $this->sanitizeInput($request->input('login_as')); // Get the role user is trying to login as
        
           if($user){
        if (Hash::check($request->password, $user[0]->password)) {
            
            // Verify user has the correct role they're trying to login as
            $role_valid = false;
            $redirect_url = '/dashboard'; // Default for patients
            
            if($login_as === 'doctor' && $user[0]->doctor == 1){
                $role_valid = true;
                $redirect_url = '/dashboard-pharmacy';
            }else if($login_as === 'hospital' && $user[0]->hospital == 1){
                $role_valid = true;
                $redirect_url = '/dashboard-hospital';
            }else if($login_as === 'pharmacy' && $user[0]->pharmacy == 1){
                $role_valid = true;
                $redirect_url = '/dashboard-pharmacy';
            }else if($login_as === 'patient' && $user[0]->doctor != 1 && $user[0]->hospital != 1 && $user[0]->pharmacy != 1 && $user[0]->sales_rep != 1){
                $role_valid = true;
                $redirect_url = '/dashboard';
            }else if($user[0]->sales_rep == 1){
                // Sales rep can login from any form
                $role_valid = true;
                $redirect_url = '/dashboard-sales-rep';
            }
            
            if(!$role_valid){
                $rtn = "This account is not registered as a ".$login_as.". Please select the correct account type.";
            }else{
                // The passwords match and role is correct
                $response = new Response("<script>
                        window.location.href = '".$redirect_url."';
                    </script>");
                $response->withCookie(cookie('uid', $user[0]->id, 120));
                $response->withCookie(cookie('authen', $user[0]->authen, 120));
                return $response;
            }
            
        }else{
            $rtn = "Invalid Username or Password";
            
        }
       
       }else{
           $rtn = "Invalid Username or Password";
       }
       }
        
       
        return view('/index', ['reg'=>$reg, 'rtn'=>$rtn]);
        
     }
       
    
function sendSMS($nms, $message){
	$curl = curl_init();
    if(preg_match("~^0\d+$~", $nms)){
     // Yes
       $nms = "234".str_replace(",","",number_format($nms));
}
    
    $url ="https://api.ng.termii.com/api/sms/send";

$data = array("api_key" => "TLEaIGMCVrwVz1MCv78ojnWcdjl3XJieqcuinpmTP0kkV0X4sehQ4OiFkN7YtU", "to" => $nms,  "from" => "N-Alert",
"sms" => $message,  "type" => "plain",  "channel" => "dnd" );

    $post_data="";
//url-ify the data for the POST
foreach($data as $key=>$value) { $post_data .= $key.'='.$value.'&'; }
rtrim($post_data, '&');
    
//$post_data = json_encode($data);

//open connection
$curl = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($curl,CURLOPT_URL, $url);
curl_setopt($curl,CURLOPT_POST, count($data));
curl_setopt($curl,CURLOPT_POSTFIELDS, $post_data);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
// Set so curl_exec returns the result instead of outputting it.
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// Get the response and close the channel.
$response = curl_exec($curl);
curl_close($curl);

return $response;
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
    
    
function randomCode($length) {
	//we hope to use this function generate referral codes in referrals.inc.php
    $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));

$key='';
    for($i=0; $i < $length; $i++) {
        $key .= $pool[mt_rand(0, count($pool) - 1)];
    }
    return $key;
}
	
	
function sanitizeInput($input){
	//sanitze an input
	//include("connect.php");
	$input = strip_tags(htmlspecialchars(trim($input)));
	//$input = mysqli_real_escape_string($link, $input);
	return $input;
}
    
    function logout(Request $request){
        
        if(!$request->hasCookie('uid')){
          redirect()->to("/")->send();
       }
        
        $response = new Response("<script>
                    window.location.href = '/';
                </script>");
        $cookie = \Cookie::forget('uid');
      $response->withCookie($cookie);
      return $response;
        
    }
    
 function getIp(){
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip); // just to be safe
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                    return $ip;
                }
            }
        }
    }
    return request()->ip(); // it will return server ip when no client ip found
}
	
    
}
