<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Cookie;
//use App\Paystack; 
use Illuminate\Support\Facades\Hash;
//use App\base;



class loginControllerApi extends Controller
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
             DB::insert('insert into users (first_name, last_name, phone, email, password, authen, ref_code, referral, date) values(?,?,?,?,?,?,?,?,?)',[$first_name, $last_name, $phone, $email, $password, $password, $ref_code, $ref_id, $dt]);
                   
                  // redirect()->to("/?reg=1")->send();
                    return response()->json(array('status'=>'success', 'reg'=>'1', 'rtn'=>$rtn));
               }
           }
        
       
       // return view('/signup_patient', ['rtn'=>$rtn ]);
        return response()->json(array('status'=>'success', 'rtn'=>$rtn));
        
     }
    
    function signup_doctor(Request $request){
         
            $rtn ="";
           if(isset($request->email) && $request->isMethod('post')){
         $password = Hash::make($request->password);
         $email= $this->sanitizeInput($request->email);
         $first_name= $this->sanitizeInput($request->first_name);
         $last_name= $this->sanitizeInput($request->last_name);
         $phone= $this->sanitizeInput($request->phone);
         $license= $this->sanitizeInput($request->license);
         $specialization= $this->sanitizeInput($request->specialization);
        $dt = date("d-M-Y");
        $ref_code ="MVD".mt_rand(000000, 999999);
        
        $emailcheck =  DB::select('select * from users WHERE email="'.$email.'"');    
               
               if($emailcheck){
                $rtn="Email already exist!";
            }else{
             DB::insert('insert into users (first_name, last_name, phone, email, password, authen, ref_code, license_type, specialization, doctor, date) values(?,?,?,?,?,?,?,?,?,?,?)',[$first_name, $last_name, $phone, $email, $password, $password, $ref_code, $license, $specialization, "1", $dt]);
                   
                //   redirect()->to("/?reg=1")->send();
                    return response()->json(array('status'=>'success', 'reg'=>'1', 'rtn'=>$rtn));
               }
           }
        
       
       // return view('/signup_doctor', ['rtn'=>$rtn ]);
        return response()->json(array('status'=>'success', 'rtn'=>$rtn));
        
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
             DB::insert('insert into users (first_name, last_name, phone, email, password, authen, ref_code, hospital, date) values(?,?,?,?,?,?,?,?,?)',[$first_name, $last_name, $phone, $email, $password, $password, $ref_code, "1", $dt]);
                   
                 //  redirect()->to("/?reg=1")->send();
                   return response()->json(array('status'=>'success', 'reg'=>'1', 'rtn'=>$rtn));
               }
           }
        
        
       
       // return view('/signup_hospital', ['rtn'=>$rtn ]);
        return response()->json(array('status'=>'success', 'rtn'=>$rtn));
        
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
             DB::insert('insert into users (first_name, last_name, phone, email, password, authen, ref_code, pharmacy, date) values(?,?,?,?,?,?,?,?,?)',[$first_name, $last_name, $phone, $email, $password, $password, $ref_code, "1", $dt]);
                   
                 //  redirect()->to("/?reg=1")->send();
                   return response()->json(array('status'=>'success', 'reg'=>'1', 'rtn'=>$rtn));
               }
           }
        
        
       
       // return view('/signup_pharmacy', ['rtn'=>$rtn ]);
        return response()->json(array('status'=>'success', 'rtn'=>$rtn));
        
     }
    
	 function login(Request $request){
         $reg ="";
         $rtn ="";
        if($request->input('reg')){
               $reg = 1;
           }
         
//           if($request->cookie('uid')){
//			 $uid = Cookie::get('uid');
//            $user =  DB::select('select * from users WHERE id='.$uid);
//            
//               if($user[0]->doctor==1){
//                  redirect()->to("/dashboard-doctor")->send();
//               }else if($user[0]->hospital==1){
//                 redirect()->to("/dashboard-hospital")->send();
//               }else if($user[0]->pharmacy==1){
//             
//               }else {
//              
//				 redirect()->to("/dashboard")->send();
//               }
//       }
       
       
       if(isset($request->email) && $request->isMethod('post')){
        $user = DB::select('select * from users WHERE email="'.$this->sanitizeInput($request->email).'"');
        
           if($user){
        if (Hash::check($request->password, $user[0]->password)) {
            
   // The passwords match...
            $response = new Response("<script>
                    window.location.href = '/';
                </script>");
      $response->withCookie(cookie('uid', $user[0]->id, 120));
     // return $response;
            return response()->json(array('status'=>'success', 'user'=>$user, 'rtn'=>$rtn));
            
        }else{
            $rtn = "Invalid Username or Password";
            
        }
       
       }else{
           $rtn = "Invalid Username or Password";
       }
       }
        
       
       // return view('/index', ['reg'=>$reg, 'rtn'=>$rtn]);
         return response()->json(array('status'=>'success', 'reg'=>$reg, 'rtn'=>$rtn));
        
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
