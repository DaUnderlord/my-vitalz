<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nigerian States for geographic distribution
        $states = ['Lagos', 'Abuja', 'Kano', 'Rivers', 'Oyo', 'Kaduna', 'Enugu', 'Delta', 'Anambra', 'Ogun'];
        
        // Create Sales Representatives from different pharmaceutical companies
        $salesReps = [
            [
                'first_name' => 'Chinedu',
                'last_name' => 'Okafor',
                'email' => 'chinedu.okafor@glaxopharm.com',
                'company_name' => 'GlaxoSmithKline Nigeria',
                'state' => 'Lagos',
                'city' => 'Ikeja',
                'phone' => '08012345678',
            ],
            [
                'first_name' => 'Amina',
                'last_name' => 'Bello',
                'email' => 'amina.bello@pfizernig.com',
                'company_name' => 'Pfizer Nigeria',
                'state' => 'Abuja',
                'city' => 'Wuse',
                'phone' => '08023456789',
            ],
            [
                'first_name' => 'Oluwaseun',
                'last_name' => 'Adeyemi',
                'email' => 'seun.adeyemi@mayandbakerng.com',
                'company_name' => 'May & Baker Nigeria',
                'state' => 'Lagos',
                'city' => 'Oshodi',
                'phone' => '08034567890',
            ],
            [
                'first_name' => 'Fatima',
                'last_name' => 'Ibrahim',
                'email' => 'fatima.ibrahim@novartisng.com',
                'company_name' => 'Novartis Nigeria',
                'state' => 'Kano',
                'city' => 'Kano Municipal',
                'phone' => '08045678901',
            ],
            [
                'first_name' => 'Emeka',
                'last_name' => 'Nwosu',
                'email' => 'emeka.nwosu@emzorpharm.com',
                'company_name' => 'Emzor Pharmaceutical',
                'state' => 'Anambra',
                'city' => 'Onitsha',
                'phone' => '08056789012',
            ],
            [
                'first_name' => 'Blessing',
                'last_name' => 'Okoro',
                'email' => 'blessing.okoro@fidsonng.com',
                'company_name' => 'Fidson Healthcare',
                'state' => 'Ogun',
                'city' => 'Sagamu',
                'phone' => '08067890123',
            ],
        ];

        $salesRepIds = [];
        foreach ($salesReps as $rep) {
            $existingRep = DB::table('users')->where('email', $rep['email'])->first();
            
            if (!$existingRep) {
                $repId = DB::table('users')->insertGetId([
                    'name' => $rep['first_name'] . ' ' . $rep['last_name'],
                    'first_name' => $rep['first_name'],
                    'last_name' => $rep['last_name'],
                    'email' => $rep['email'],
                    'password' => Hash::make('password123'),
                    'company_name' => $rep['company_name'],
                    'state' => $rep['state'],
                    'city' => $rep['city'],
                    'phone' => $rep['phone'],
                    'sales_rep' => 1,
                    'doctor' => 0,
                    'pharmacy' => 0,
                    'hospital' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $salesRepIds[] = ['id' => $repId, 'state' => $rep['state'], 'company' => $rep['company_name']];
            } else {
                $salesRepIds[] = ['id' => $existingRep->id, 'state' => $rep['state'], 'company' => $rep['company_name']];
            }
        }

        // Comprehensive pharmaceutical products database
        $products = [
            // Antibiotics
            ['drug_name' => 'Amoxicillin 500mg', 'generic_name' => 'Amoxicillin', 'category' => 'Antibiotics', 'description' => 'Broad-spectrum antibiotic for bacterial infections', 'wholesale_price' => 1200, 'suggested_retail_price' => 1800, 'stock_quantity' => 500, 'unit' => 'capsules'],
            ['drug_name' => 'Ciprofloxacin 500mg', 'generic_name' => 'Ciprofloxacin', 'category' => 'Antibiotics', 'description' => 'Fluoroquinolone antibiotic for various infections', 'wholesale_price' => 2500, 'suggested_retail_price' => 3500, 'stock_quantity' => 300, 'unit' => 'tablets'],
            ['drug_name' => 'Azithromycin 250mg', 'generic_name' => 'Azithromycin', 'category' => 'Antibiotics', 'description' => 'Macrolide antibiotic for respiratory infections', 'wholesale_price' => 3000, 'suggested_retail_price' => 4200, 'stock_quantity' => 250, 'unit' => 'tablets'],
            ['drug_name' => 'Metronidazole 400mg', 'generic_name' => 'Metronidazole', 'category' => 'Antibiotics', 'description' => 'Antibiotic for anaerobic bacterial and protozoal infections', 'wholesale_price' => 800, 'suggested_retail_price' => 1200, 'stock_quantity' => 600, 'unit' => 'tablets'],
            
            // Analgesics & Anti-inflammatory
            ['drug_name' => 'Paracetamol 500mg', 'generic_name' => 'Paracetamol', 'category' => 'Analgesics', 'description' => 'Pain reliever and fever reducer', 'wholesale_price' => 500, 'suggested_retail_price' => 800, 'stock_quantity' => 1000, 'unit' => 'tablets'],
            ['drug_name' => 'Ibuprofen 400mg', 'generic_name' => 'Ibuprofen', 'category' => 'Analgesics', 'description' => 'NSAID for pain, inflammation and fever', 'wholesale_price' => 1000, 'suggested_retail_price' => 1500, 'stock_quantity' => 800, 'unit' => 'tablets'],
            ['drug_name' => 'Diclofenac 50mg', 'generic_name' => 'Diclofenac Sodium', 'category' => 'Analgesics', 'description' => 'Anti-inflammatory for arthritis and pain', 'wholesale_price' => 1500, 'suggested_retail_price' => 2200, 'stock_quantity' => 400, 'unit' => 'tablets'],
            ['drug_name' => 'Tramadol 50mg', 'generic_name' => 'Tramadol HCl', 'category' => 'Analgesics', 'description' => 'Opioid analgesic for moderate to severe pain', 'wholesale_price' => 3500, 'suggested_retail_price' => 5000, 'stock_quantity' => 200, 'unit' => 'capsules'],
            
            // Antihypertensives
            ['drug_name' => 'Amlodipine 5mg', 'generic_name' => 'Amlodipine', 'category' => 'Cardiovascular', 'description' => 'Calcium channel blocker for hypertension', 'wholesale_price' => 2000, 'suggested_retail_price' => 3000, 'stock_quantity' => 500, 'unit' => 'tablets'],
            ['drug_name' => 'Lisinopril 10mg', 'generic_name' => 'Lisinopril', 'category' => 'Cardiovascular', 'description' => 'ACE inhibitor for high blood pressure', 'wholesale_price' => 2500, 'suggested_retail_price' => 3800, 'stock_quantity' => 400, 'unit' => 'tablets'],
            ['drug_name' => 'Losartan 50mg', 'generic_name' => 'Losartan Potassium', 'category' => 'Cardiovascular', 'description' => 'ARB for hypertension and heart failure', 'wholesale_price' => 3000, 'suggested_retail_price' => 4500, 'stock_quantity' => 350, 'unit' => 'tablets'],
            ['drug_name' => 'Atenolol 50mg', 'generic_name' => 'Atenolol', 'category' => 'Cardiovascular', 'description' => 'Beta-blocker for hypertension and angina', 'wholesale_price' => 1800, 'suggested_retail_price' => 2700, 'stock_quantity' => 450, 'unit' => 'tablets'],
            
            // Antidiabetics
            ['drug_name' => 'Metformin 500mg', 'generic_name' => 'Metformin HCl', 'category' => 'Antidiabetics', 'description' => 'First-line treatment for type 2 diabetes', 'wholesale_price' => 1500, 'suggested_retail_price' => 2300, 'stock_quantity' => 700, 'unit' => 'tablets'],
            ['drug_name' => 'Glibenclamide 5mg', 'generic_name' => 'Glibenclamide', 'category' => 'Antidiabetics', 'description' => 'Sulfonylurea for type 2 diabetes', 'wholesale_price' => 1200, 'suggested_retail_price' => 1800, 'stock_quantity' => 500, 'unit' => 'tablets'],
            ['drug_name' => 'Insulin Glargine 100IU/ml', 'generic_name' => 'Insulin Glargine', 'category' => 'Antidiabetics', 'description' => 'Long-acting insulin for diabetes management', 'wholesale_price' => 8500, 'suggested_retail_price' => 12000, 'stock_quantity' => 100, 'unit' => 'vials'],
            
            // Antimalarials
            ['drug_name' => 'Artemether-Lumefantrine', 'generic_name' => 'Artemether/Lumefantrine', 'category' => 'Antimalarials', 'description' => 'ACT for uncomplicated malaria', 'wholesale_price' => 2500, 'suggested_retail_price' => 3500, 'stock_quantity' => 600, 'unit' => 'tablets'],
            ['drug_name' => 'Artesunate Injection', 'generic_name' => 'Artesunate', 'category' => 'Antimalarials', 'description' => 'Injectable for severe malaria', 'wholesale_price' => 4000, 'suggested_retail_price' => 6000, 'stock_quantity' => 200, 'unit' => 'ampoules'],
            ['drug_name' => 'Chloroquine 250mg', 'generic_name' => 'Chloroquine Phosphate', 'category' => 'Antimalarials', 'description' => 'Antimalarial and anti-inflammatory', 'wholesale_price' => 800, 'suggested_retail_price' => 1200, 'stock_quantity' => 500, 'unit' => 'tablets'],
            
            // Gastrointestinal
            ['drug_name' => 'Omeprazole 20mg', 'generic_name' => 'Omeprazole', 'category' => 'Gastrointestinal', 'description' => 'Proton pump inhibitor for acid reflux', 'wholesale_price' => 2000, 'suggested_retail_price' => 3000, 'stock_quantity' => 400, 'unit' => 'capsules'],
            ['drug_name' => 'Ranitidine 150mg', 'generic_name' => 'Ranitidine', 'category' => 'Gastrointestinal', 'description' => 'H2 blocker for ulcers and heartburn', 'wholesale_price' => 1200, 'suggested_retail_price' => 1800, 'stock_quantity' => 500, 'unit' => 'tablets'],
            ['drug_name' => 'Loperamide 2mg', 'generic_name' => 'Loperamide HCl', 'category' => 'Gastrointestinal', 'description' => 'Anti-diarrheal medication', 'wholesale_price' => 1500, 'suggested_retail_price' => 2200, 'stock_quantity' => 300, 'unit' => 'capsules'],
            
            // Respiratory
            ['drug_name' => 'Salbutamol Inhaler', 'generic_name' => 'Salbutamol', 'category' => 'Respiratory', 'description' => 'Bronchodilator for asthma and COPD', 'wholesale_price' => 3500, 'suggested_retail_price' => 5000, 'stock_quantity' => 150, 'unit' => 'inhalers'],
            ['drug_name' => 'Cetirizine 10mg', 'generic_name' => 'Cetirizine HCl', 'category' => 'Respiratory', 'description' => 'Antihistamine for allergies', 'wholesale_price' => 1000, 'suggested_retail_price' => 1500, 'stock_quantity' => 600, 'unit' => 'tablets'],
            ['drug_name' => 'Prednisolone 5mg', 'generic_name' => 'Prednisolone', 'category' => 'Respiratory', 'description' => 'Corticosteroid for inflammation', 'wholesale_price' => 1800, 'suggested_retail_price' => 2700, 'stock_quantity' => 400, 'unit' => 'tablets'],
            
            // Vitamins & Supplements
            ['drug_name' => 'Multivitamin Complex', 'generic_name' => 'Multivitamin', 'category' => 'Vitamins', 'description' => 'Complete vitamin and mineral supplement', 'wholesale_price' => 2500, 'suggested_retail_price' => 3800, 'stock_quantity' => 500, 'unit' => 'tablets'],
            ['drug_name' => 'Vitamin C 1000mg', 'generic_name' => 'Ascorbic Acid', 'category' => 'Vitamins', 'description' => 'Immune support and antioxidant', 'wholesale_price' => 1500, 'suggested_retail_price' => 2200, 'stock_quantity' => 700, 'unit' => 'tablets'],
            ['drug_name' => 'Calcium + Vitamin D3', 'generic_name' => 'Calcium/Cholecalciferol', 'category' => 'Vitamins', 'description' => 'Bone health supplement', 'wholesale_price' => 3000, 'suggested_retail_price' => 4500, 'stock_quantity' => 400, 'unit' => 'tablets'],
            ['drug_name' => 'Folic Acid 5mg', 'generic_name' => 'Folic Acid', 'category' => 'Vitamins', 'description' => 'Essential for pregnancy and blood health', 'wholesale_price' => 800, 'suggested_retail_price' => 1200, 'stock_quantity' => 800, 'unit' => 'tablets'],
            
            // Antifungals
            ['drug_name' => 'Fluconazole 150mg', 'generic_name' => 'Fluconazole', 'category' => 'Antifungals', 'description' => 'Antifungal for candidiasis', 'wholesale_price' => 2500, 'suggested_retail_price' => 3800, 'stock_quantity' => 300, 'unit' => 'capsules'],
            ['drug_name' => 'Clotrimazole Cream', 'generic_name' => 'Clotrimazole', 'category' => 'Antifungals', 'description' => 'Topical antifungal for skin infections', 'wholesale_price' => 1500, 'suggested_retail_price' => 2200, 'stock_quantity' => 400, 'unit' => 'tubes'],
            
            // Antivirals
            ['drug_name' => 'Acyclovir 400mg', 'generic_name' => 'Acyclovir', 'category' => 'Antivirals', 'description' => 'Antiviral for herpes infections', 'wholesale_price' => 3000, 'suggested_retail_price' => 4500, 'stock_quantity' => 250, 'unit' => 'tablets'],
            
            // Dermatology
            ['drug_name' => 'Hydrocortisone Cream 1%', 'generic_name' => 'Hydrocortisone', 'category' => 'Dermatology', 'description' => 'Topical steroid for skin inflammation', 'wholesale_price' => 1200, 'suggested_retail_price' => 1800, 'stock_quantity' => 500, 'unit' => 'tubes'],
            ['drug_name' => 'Benzoyl Peroxide Gel', 'generic_name' => 'Benzoyl Peroxide', 'category' => 'Dermatology', 'description' => 'Acne treatment gel', 'wholesale_price' => 2000, 'suggested_retail_price' => 3000, 'stock_quantity' => 300, 'unit' => 'tubes'],
        ];

        // Distribute products across sales reps and states
        foreach ($products as $product) {
            // Each product is available in multiple states through different sales reps
            $statesForProduct = array_rand(array_flip($states), rand(2, 4));
            
            foreach ($statesForProduct as $state) {
                // Find sales reps in this state
                $repsInState = array_filter($salesRepIds, function($rep) use ($state) {
                    return $rep['state'] === $state;
                });
                
                if (empty($repsInState)) {
                    // If no rep in this state, assign to a random rep but use this state
                    $randomRep = $salesRepIds[array_rand($salesRepIds)];
                    $repId = $randomRep['id'];
                } else {
                    $randomRep = $repsInState[array_rand($repsInState)];
                    $repId = $randomRep['id'];
                }
                
                // Get cities for the state
                $cities = [
                    'Lagos' => ['Ikeja', 'Victoria Island', 'Lekki', 'Surulere', 'Yaba'],
                    'Abuja' => ['Wuse', 'Garki', 'Maitama', 'Asokoro', 'Gwarinpa'],
                    'Kano' => ['Kano Municipal', 'Fagge', 'Nassarawa', 'Gwale'],
                    'Rivers' => ['Port Harcourt', 'Obio-Akpor', 'Eleme'],
                    'Oyo' => ['Ibadan', 'Ogbomoso', 'Oyo'],
                    'Kaduna' => ['Kaduna North', 'Kaduna South', 'Zaria'],
                    'Enugu' => ['Enugu North', 'Enugu South', 'Nsukka'],
                    'Delta' => ['Warri', 'Asaba', 'Sapele'],
                    'Anambra' => ['Onitsha', 'Awka', 'Nnewi'],
                    'Ogun' => ['Abeokuta', 'Sagamu', 'Ijebu-Ode'],
                ];
                
                $city = $cities[$state][array_rand($cities[$state])];
                
                // Check if this product already exists for this rep and state
                $exists = DB::table('marketplace_drugs')
                    ->where('sales_rep_id', $repId)
                    ->where('drug_name', $product['drug_name'])
                    ->where('state', $state)
                    ->exists();
                
                if (!$exists) {
                    DB::table('marketplace_drugs')->insert([
                        'sales_rep_id' => $repId,
                        'drug_name' => $product['drug_name'],
                        'generic_name' => $product['generic_name'],
                        'category' => $product['category'],
                        'description' => $product['description'],
                        'wholesale_price' => $product['wholesale_price'],
                        'suggested_retail_price' => $product['suggested_retail_price'],
                        'stock_quantity' => $product['stock_quantity'],
                        'reorder_level' => 10,
                        'unit' => $product['unit'],
                        'photo' => null,
                        'state' => $state,
                        'city' => $city,
                        'status' => 'active',
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now()->subDays(rand(0, 10)),
                    ]);
                }
            }
        }

        $this->command->info('Marketplace seeded successfully!');
        $this->command->info('Created ' . count($salesReps) . ' sales representatives');
        $this->command->info('Created marketplace products across ' . count($states) . ' states');
        $this->command->info("\nSales Rep Login Credentials:");
        $this->command->info("Email: Any of the sales rep emails above");
        $this->command->info("Password: password123");
    }
}
