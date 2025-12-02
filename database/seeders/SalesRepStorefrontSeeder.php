<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesRepStorefrontSeeder extends Seeder
{
    public function run()
    {
        // Get all sales reps
        $sales_reps = DB::table('users')->where('sales_rep', 1)->get();
        
        if($sales_reps->isEmpty()){
            echo "No sales reps found. Please create sales rep accounts first.\n";
            return;
        }
        
        $companies = [
            [
                'company_name' => 'GlaxoSmithKline Nigeria',
                'tagline' => 'Leading pharmaceutical innovation',
                'description' => 'GlaxoSmithKline is a global healthcare company committed to improving the quality of human life by enabling people to do more, feel better and live longer.',
                'primary_color' => '#FF6900',
                'secondary_color' => '#E55D00',
                'phone' => '+234 803 456 7890',
                'email' => 'info@gsk.com.ng',
                'website' => 'www.gsk.com.ng'
            ],
            [
                'company_name' => 'May & Baker Nigeria',
                'tagline' => 'Healthcare solutions you can trust',
                'description' => 'May & Baker Nigeria Plc is a leading pharmaceutical and chemical company in Nigeria, providing quality healthcare products for over 60 years.',
                'primary_color' => '#0066CC',
                'secondary_color' => '#0052A3',
                'phone' => '+234 802 345 6789',
                'email' => 'contact@maybaker.com',
                'website' => 'www.mayandbakerng.com'
            ],
            [
                'company_name' => 'Fidson Healthcare',
                'tagline' => 'Your health, our priority',
                'description' => 'Fidson Healthcare Plc is a leading indigenous pharmaceutical company in Nigeria, manufacturing and marketing quality pharmaceutical products.',
                'primary_color' => '#00A651',
                'secondary_color' => '#008542',
                'phone' => '+234 805 234 5678',
                'email' => 'info@fidson.com',
                'website' => 'www.fidsonhealthcare.com'
            ],
            [
                'company_name' => 'Emzor Pharmaceutical',
                'tagline' => 'Quality healthcare for all',
                'description' => 'Emzor Pharmaceutical Industries Limited is one of the leading pharmaceutical companies in Nigeria with a strong commitment to quality.',
                'primary_color' => '#C8102E',
                'secondary_color' => '#A00D25',
                'phone' => '+234 806 123 4567',
                'email' => 'sales@emzor.com',
                'website' => 'www.emzorpharm.com'
            ]
        ];
        
        foreach($sales_reps as $index => $rep){
            $company = $companies[$index % count($companies)];
            
            DB::table('users')
                ->where('id', $rep->id)
                ->update([
                    'company_name' => $company['company_name'],
                    'storefront_tagline' => $company['tagline'],
                    'storefront_description' => $company['description'],
                    'storefront_primary_color' => $company['primary_color'],
                    'storefront_secondary_color' => $company['secondary_color'],
                    'storefront_phone' => $company['phone'],
                    'storefront_email' => $company['email'],
                    'storefront_website' => $company['website'],
                    'storefront_active' => 1,
                    'updated_at' => now()
                ]);
            
            echo "Updated storefront for: {$company['company_name']}\n";
        }
        
        echo "\n✅ Successfully updated " . count($sales_reps) . " sales rep storefronts!\n";
        echo "\nNote: Logo and banner images need to be uploaded manually to /assets/storefronts/\n";
        echo "Or you can add placeholder images using the storefront settings page.\n";
    }
}
