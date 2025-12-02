<?php

namespace App\Helpers;

use DB;
use Illuminate\Support\Facades\Mail;

class NotificationHelper
{
    /**
     * Send email notification
     */
    public static function sendEmail($to, $subject, $message, $from_name = 'MyVitalz')
    {
        try {
            $headers = "From: " . $from_name . " <noreply@myvitalz.com>\r\n";
            $headers .= "Reply-To: support@myvitalz.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            
            $html_message = self::getEmailTemplate($subject, $message);
            
            return mail($to, $subject, $html_message, $headers);
        } catch (\Exception $e) {
            error_log("Email send failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send SMS notification
     */
    public static function sendSMS($phone, $message)
    {
        // Integration with SMS provider (e.g., Twilio, Termii, etc.)
        // For now, we'll log it
        try {
            // Example: Termii API integration
            $api_key = env('TERMII_API_KEY', '');
            $sender_id = env('TERMII_SENDER_ID', 'MyVitalz');
            
            if(empty($api_key)){
                error_log("SMS not sent - API key not configured");
                return false;
            }
            
            $data = [
                'to' => $phone,
                'from' => $sender_id,
                'sms' => $message,
                'type' => 'plain',
                'channel' => 'generic',
                'api_key' => $api_key
            ];
            
            $ch = curl_init('https://api.ng.termii.com/api/sms/send');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            return json_decode($response, true);
        } catch (\Exception $e) {
            error_log("SMS send failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send order notification to all parties
     */
    public static function sendOrderNotifications($order_id)
    {
        $order = DB::select('select * from storefront_orders where id='.$order_id);
        if(empty($order)) return false;
        
        $order = $order[0];
        
        // Get user details
        $patient = DB::select('select * from users where id='.$order->patient_id)[0];
        $doctor = DB::select('select * from users where id='.$order->doctor_id)[0];
        $sales_rep = DB::select('select * from users where id='.$order->sales_rep_id)[0];
        
        // Send to patient
        self::sendEmail(
            $patient->email,
            'Order Confirmation - '.$order->order_number,
            'Your order has been placed successfully! Order #'.$order->order_number.' - Total: ₦'.number_format($order->total_amount, 2)
        );
        
        self::sendSMS(
            $patient->phone,
            'MyVitalz: Your order #'.$order->order_number.' has been placed. Total: ₦'.number_format($order->total_amount, 2)
        );
        
        // Send to doctor
        self::sendEmail(
            $doctor->email,
            'New Order Received - '.$order->order_number,
            'You have received a new order from '.$patient->first_name.' '.$patient->last_name.'. Order #'.$order->order_number
        );
        
        // Send to sales rep
        self::sendEmail(
            $sales_rep->email,
            'New Order to Fulfill - '.$order->order_number,
            'You have a new order to fulfill for Dr. '.$doctor->first_name.' '.$doctor->last_name.'. Order #'.$order->order_number
        );
        
        return true;
    }
    
    /**
     * Send order status update notification
     */
    public static function sendOrderStatusUpdate($order_id, $new_status)
    {
        $order = DB::select('select * from storefront_orders where id='.$order_id);
        if(empty($order)) return false;
        
        $order = $order[0];
        $patient = DB::select('select * from users where id='.$order->patient_id)[0];
        
        $status_messages = [
            'confirmed' => 'Your order has been confirmed and is being prepared.',
            'shipped' => 'Your order has been shipped and is on the way!',
            'delivered' => 'Your order has been delivered. Thank you for shopping with us!',
            'cancelled' => 'Your order has been cancelled.'
        ];
        
        $message = $status_messages[$new_status] ?? 'Your order status has been updated.';
        
        self::sendEmail(
            $patient->email,
            'Order Update - '.$order->order_number,
            $message.' Order #'.$order->order_number
        );
        
        self::sendSMS(
            $patient->phone,
            'MyVitalz: Order #'.$order->order_number.' - '.$message
        );
        
        return true;
    }
    
    /**
     * Get email template
     */
    private static function getEmailTemplate($subject, $message)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>'.$subject.'</title>
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
            <div style="background: linear-gradient(135deg, #696cff 0%, #5a67d8 100%); padding: 20px; text-align: center; border-radius: 10px 10px 0 0;">
                <h1 style="color: white; margin: 0;">MyVitalz</h1>
                <p style="color: white; margin: 5px 0 0 0;">Healthcare Marketplace</p>
            </div>
            <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
                <h2 style="color: #696cff; margin-top: 0;">'.$subject.'</h2>
                <p style="font-size: 16px;">'.$message.'</p>
                <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
                <p style="font-size: 14px; color: #666;">
                    Thank you for using MyVitalz!<br>
                    <a href="https://myvitalz.com" style="color: #696cff; text-decoration: none;">Visit our website</a>
                </p>
                <p style="font-size: 12px; color: #999; margin-top: 20px;">
                    This is an automated message. Please do not reply to this email.
                </p>
            </div>
        </body>
        </html>
        ';
    }
}
