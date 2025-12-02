<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Cookie;
use Illuminate\Support\Facades\Hash;

class dashboardSalesRepController extends Controller
{
    
    function dashboard(Request $request){
        
        if(!$request->hasCookie('uid')){
          redirect()->to("/")->send();
       }
        
        $uid = Cookie::get('uid');
        $user =  DB::select('select * from users WHERE id='.$uid);
        
        if($user[0]->sales_rep != 1){
            redirect()->to("/")->send();
        }
        
        $pagename = "sales_rep_home";
        $a_type = "";
        $a_message = "";
        
        // Get page parameter
        $pg = $request->input('pg');
        if(!$pg) $pg = 'home';
        
        // Handle different pages
        switch($pg) {
            case 'products':
                $pagename = "sales_rep_products";
                break;
            case 'upload':
                $pagename = "sales_rep_upload";
                break;
            case 'orders':
                $pagename = "sales_rep_orders";
                break;
            case 'doctors':
                $pagename = "sales_rep_doctors";
                break;
            case 'analytics':
                $pagename = "sales_rep_analytics";
                break;
            case 'payout':
                $pagename = "sales_rep_payout";
                break;
            case 'profile':
                $pagename = "sales_rep_profile";
                break;
            default:
                $pagename = "sales_rep_home";
        }
        
        // Get sales rep statistics
        $total_products = DB::select('select count(*) as count from marketplace_drugs WHERE sales_rep_id='.$uid);
        $active_products = DB::select('select count(*) as count from marketplace_drugs WHERE sales_rep_id='.$uid.' AND status="active"');
        $low_stock_products = DB::select('select count(*) as count from marketplace_drugs WHERE sales_rep_id='.$uid.' AND stock_quantity <= reorder_level');
        
        // Get total doctors who added products
        $total_doctors = DB::select('select count(DISTINCT doctor_id) as count from doctor_storefront_inventory dsi 
            INNER JOIN marketplace_drugs md ON dsi.marketplace_drug_id = md.id 
            WHERE md.sales_rep_id='.$uid);
        
        // Get total orders
        $total_orders = DB::select('select count(*) as count from storefront_orders WHERE sales_rep_id='.$uid);
        $pending_orders = DB::select('select count(*) as count from storefront_orders WHERE sales_rep_id='.$uid.' AND order_status="pending"');
        
        // Get total revenue
        $total_revenue = DB::select('select COALESCE(SUM(sales_rep_amount), 0) as total from storefront_orders WHERE sales_rep_id='.$uid.' AND payment_status="paid"');
        
        // Get recent orders
        $recent_orders = DB::select('select so.*, u.first_name, u.last_name from storefront_orders so 
            INNER JOIN users u ON so.patient_id = u.id 
            WHERE so.sales_rep_id='.$uid.' ORDER BY so.created_at DESC LIMIT 10');
        
        // Get all products for products page
        $all_products = DB::select('select * from marketplace_drugs WHERE sales_rep_id='.$uid.' ORDER BY created_at DESC');
        
        // Get notifications
        $notifications = DB::select('select * from notifications WHERE user_id='.$uid.' AND seen=0 ORDER BY date DESC');
        
        // Handle form submissions
        if($request->isMethod('post')){
            
            // Upload profile photo
            if($request->hasFile('upload_profile')){
                $file = $request->file('upload_profile');
                $filename = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('assets/images'), $filename);
                DB::update('update users set photo=?, updated_at=? where id=?', [$filename, date('Y-m-d H:i:s'), $uid]);
                $a_type = "success";
                $a_message = "Profile photo updated successfully!";
                redirect()->to("/dashboard-sales-rep?pg=profile&a_type=".$a_type."&a_message=".$a_message)->send();
            }
            
            // Update general profile (company/contact)
            if($request->input('company_name')){
                $company_name = $this->sanitizeInput($request->input('company_name'));
                $company_email = $this->sanitizeInput($request->input('company_email'));
                $state = $this->sanitizeInput($request->input('state'));
                $city = $this->sanitizeInput($request->input('city'));
                $address = $this->sanitizeInput($request->input('address'));
                $phone = $this->sanitizeInput($request->input('phone'));
                $website = $this->sanitizeInput($request->input('website'));
                
                DB::update('update users set company_name=?, company_email=?, state=?, city=?, address=?, phone=?, website=?, updated_at=? where id=?',
                    [$company_name, $company_email, $state, $city, $address, $phone, $website, date('Y-m-d H:i:s'), $uid]);
                
                $a_type = "success";
                $a_message = "Profile updated successfully!";
                redirect()->to("/dashboard-sales-rep?pg=profile&a_type=".$a_type."&a_message=".$a_message)->send();
            }
            
            // Update bank details
            if($request->input('action') == 'update_bank_details'){
                $bank_name = $this->sanitizeInput($request->input('bank_name'));
                $account_number = $this->sanitizeInput($request->input('account_number'));
                $account_name = $this->sanitizeInput($request->input('account_name'));
                
                DB::update('update users set bank_name=?, account_number=?, account_name=?, updated_at=? where id=?',
                    [$bank_name, $account_number, $account_name, date('Y-m-d H:i:s'), $uid]);
                
                $a_type = "success";
                $a_message = "Bank details updated successfully!";
                redirect()->to("/dashboard-sales-rep?pg=profile&a_type=".$a_type."&a_message=".$a_message)->send();
            }
            
            // Upload new drug
            if($request->input('action') == 'upload_drug'){
                $drug_name = $this->sanitizeInput($request->input('drug_name'));
                $generic_name = $this->sanitizeInput($request->input('generic_name'));
                $category = $this->sanitizeInput($request->input('category'));
                $description = $this->sanitizeInput($request->input('description'));
                $wholesale_price = $this->sanitizeInput($request->input('wholesale_price'));
                $suggested_retail_price = $this->sanitizeInput($request->input('suggested_retail_price'));
                $stock_quantity = $this->sanitizeInput($request->input('stock_quantity'));
                $reorder_level = $this->sanitizeInput($request->input('reorder_level'));
                $unit = $this->sanitizeInput($request->input('unit'));
                
                // Handle photo upload
                $photo = null;
                if($request->hasFile('photo')){
                    $file = $request->file('photo');
                    $filename = time().'_'.$file->getClientOriginalName();
                    $file->move(public_path('assets/products'), $filename);
                    $photo = $filename;
                }
                
                DB::insert('insert into marketplace_drugs (sales_rep_id, drug_name, generic_name, category, description, wholesale_price, suggested_retail_price, stock_quantity, reorder_level, unit, photo, state, city, status, created_at, updated_at) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                    [$uid, $drug_name, $generic_name, $category, $description, $wholesale_price, $suggested_retail_price, $stock_quantity, $reorder_level, $unit, $photo, $user[0]->state, $user[0]->city, 'active', date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
                
                $a_type = "success";
                $a_message = "Product uploaded successfully!";
                redirect()->to("/dashboard-sales-rep?pg=products&a_type=".$a_type."&a_message=".$a_message)->send();
            }
            
            // Update drug
            if($request->input('action') == 'update_drug'){
                $drug_id = $this->sanitizeInput($request->input('drug_id'));
                $drug_name = $this->sanitizeInput($request->input('drug_name'));
                $generic_name = $this->sanitizeInput($request->input('generic_name'));
                $category = $this->sanitizeInput($request->input('category'));
                $description = $this->sanitizeInput($request->input('description'));
                $wholesale_price = $this->sanitizeInput($request->input('wholesale_price'));
                $suggested_retail_price = $this->sanitizeInput($request->input('suggested_retail_price'));
                $stock_quantity = $this->sanitizeInput($request->input('stock_quantity'));
                $reorder_level = $this->sanitizeInput($request->input('reorder_level'));
                $unit = $this->sanitizeInput($request->input('unit'));
                
                // Handle photo upload
                if($request->hasFile('photo')){
                    $file = $request->file('photo');
                    $filename = time().'_'.$file->getClientOriginalName();
                    $file->move(public_path('assets/products'), $filename);
                    
                    DB::update('update marketplace_drugs set drug_name=?, generic_name=?, category=?, description=?, wholesale_price=?, suggested_retail_price=?, stock_quantity=?, reorder_level=?, unit=?, photo=?, updated_at=? where id=? and sales_rep_id=?',
                        [$drug_name, $generic_name, $category, $description, $wholesale_price, $suggested_retail_price, $stock_quantity, $reorder_level, $unit, $filename, date('Y-m-d H:i:s'), $drug_id, $uid]);
                } else {
                    DB::update('update marketplace_drugs set drug_name=?, generic_name=?, category=?, description=?, wholesale_price=?, suggested_retail_price=?, stock_quantity=?, reorder_level=?, unit=?, updated_at=? where id=? and sales_rep_id=?',
                        [$drug_name, $generic_name, $category, $description, $wholesale_price, $suggested_retail_price, $stock_quantity, $reorder_level, $unit, date('Y-m-d H:i:s'), $drug_id, $uid]);
                }
                
                $a_type = "success";
                $a_message = "Product updated successfully!";
                redirect()->to("/dashboard-sales-rep?pg=products&a_type=".$a_type."&a_message=".$a_message)->send();
            }
            
            // Delete/Deactivate drug
            if($request->input('action') == 'delete_drug'){
                $drug_id = $this->sanitizeInput($request->input('drug_id'));
                
                DB::update('update marketplace_drugs set status=? where id=? and sales_rep_id=?',
                    ['inactive', $drug_id, $uid]);
                
                $a_type = "success";
                $a_message = "Product deactivated successfully!";
                redirect()->to("/dashboard-sales-rep?pg=products&a_type=".$a_type."&a_message=".$a_message)->send();
            }
            
            // Update order status
            if($request->input('action') == 'update_order_status'){
                $order_id = $this->sanitizeInput($request->input('order_id'));
                $new_status = $this->sanitizeInput($request->input('new_status'));
                
                DB::update('update storefront_orders set order_status=?, updated_at=? where id=? and sales_rep_id=?',
                    [$new_status, date('Y-m-d H:i:s'), $order_id, $uid]);
                
                // If order is delivered, mark commission as paid
                if($new_status == 'delivered'){
                    DB::update('update sales_rep_commissions set status=?, paid_at=? where order_id=?',
                        ['paid', date('Y-m-d H:i:s'), $order_id]);
                }
                
                $a_type = "success";
                $a_message = "Order status updated successfully!";
                redirect()->to("/dashboard-sales-rep?pg=orders&a_type=".$a_type."&a_message=".$a_message)->send();
            }
            
            // Request payout
            if($request->input('action') == 'request_payout'){
                $amount = $this->sanitizeInput($request->input('amount'));
                $bank_name = $this->sanitizeInput($request->input('bank_name'));
                $account_number = $this->sanitizeInput($request->input('account_number'));
                $account_name = $this->sanitizeInput($request->input('account_name'));
                
                DB::insert('insert into payout_requests (user_id, amount, bank_name, account_number, account_name, status, created_at, updated_at) values(?,?,?,?,?,?,?,?)',
                    [$uid, $amount, $bank_name, $account_number, $account_name, 'pending', date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
                
                $a_type = "success";
                $a_message = "Payout request submitted successfully!";
                redirect()->to("/dashboard-sales-rep?pg=payout&a_type=".$a_type."&a_message=".$a_message)->send();
            }
        }
        
        // Pass alert messages from URL
        if($request->input('a_type')){
            $a_type = $this->sanitizeInput($request->input('a_type'));
            $a_message = $this->sanitizeInput($request->input('a_message'));
        }
        
        return view('dashboard_sales_rep', [
            'pagename' => $pagename,
            'user' => $user,
            'total_products' => $total_products[0]->count ?? 0,
            'active_products' => $active_products[0]->count ?? 0,
            'low_stock_products' => $low_stock_products[0]->count ?? 0,
            'total_doctors' => $total_doctors[0]->count ?? 0,
            'total_orders' => $total_orders[0]->count ?? 0,
            'pending_orders' => $pending_orders[0]->count ?? 0,
            'total_revenue' => $total_revenue[0]->total ?? 0,
            'recent_orders' => $recent_orders,
            'all_products' => $all_products,
            'notifications' => $notifications,
            'a_type' => $a_type,
            'a_message' => $a_message
        ]);
    }
    
    function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
