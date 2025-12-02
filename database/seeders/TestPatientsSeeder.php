<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestPatientsSeeder extends Seeder
{
    public function run()
    {
        // Get doctor ID from command line argument or use the most recent doctor
        $doctor_id = $this->command->ask('Enter Doctor ID (or press Enter to use most recent doctor)');
        
        if(!$doctor_id){
            $doctor = DB::table('users')->where('doctor', 1)->orderBy('id', 'desc')->first();
            if(!$doctor){
                echo "No doctor found. Please create a doctor account first.\n";
                return;
            }
            $doctor_id = $doctor->id;
        }
        
        // Verify doctor exists
        $doctor = DB::table('users')->where('id', $doctor_id)->where('doctor', 1)->first();
        if(!$doctor){
            echo "Doctor ID {$doctor_id} not found or is not a doctor account.\n";
            return;
        }
        
        echo "Creating test patients for Doctor: {$doctor->first_name} {$doctor->last_name} (ID: {$doctor_id})\n";
        
        // Create 5 test patients
        $test_patients = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@test.com',
                'phone' => '+1234567890',
                'gender' => 'male',
                'age' => 45,
                'vitals' => [
                    ['vital_id' => 1, 'reading' => '125', 'status' => 'high'],      // Heart Rate (high)
                    ['vital_id' => 2, 'reading' => '145/95', 'status' => 'high'],   // Blood Pressure (high)
                    ['vital_id' => 3, 'reading' => '96', 'status' => 'normal'],     // Oxygen Saturation
                    ['vital_id' => 4, 'reading' => '7', 'status' => 'high'],        // Stress (high)
                    ['vital_id' => 5, 'reading' => '165', 'status' => 'high'],      // Blood Glucose (high)
                    ['vital_id' => 6, 'reading' => '245', 'status' => 'high'],      // Lipids (high)
                    ['vital_id' => 7, 'reading' => '7.2', 'status' => 'high'],      // HbA1c (high)
                    ['vital_id' => 8, 'reading' => '85', 'status' => 'normal'],     // IHRA
                    ['vital_id' => 9, 'reading' => '37.8', 'status' => 'normal'],   // Temperature
                ]
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@test.com',
                'phone' => '+1234567891',
                'gender' => 'female',
                'age' => 32,
                'vitals' => [
                    ['vital_id' => 1, 'reading' => '35', 'status' => 'critical'],   // Heart Rate (critical low)
                    ['vital_id' => 2, 'reading' => '65/40', 'status' => 'critical'], // Blood Pressure (critical low)
                    ['vital_id' => 3, 'reading' => '88', 'status' => 'critical'],   // Oxygen Saturation (critical)
                    ['vital_id' => 4, 'reading' => '9', 'status' => 'critical'],    // Stress (critical high)
                    ['vital_id' => 5, 'reading' => '85', 'status' => 'normal'],     // Blood Glucose
                    ['vital_id' => 6, 'reading' => '180', 'status' => 'normal'],    // Lipids
                    ['vital_id' => 7, 'reading' => '5.5', 'status' => 'normal'],    // HbA1c
                    ['vital_id' => 8, 'reading' => '75', 'status' => 'normal'],     // IHRA
                    ['vital_id' => 9, 'reading' => '36.5', 'status' => 'normal'],   // Temperature
                ]
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Davis',
                'email' => 'michael.davis@test.com',
                'phone' => '+1234567892',
                'gender' => 'male',
                'age' => 58,
                'vitals' => [
                    ['vital_id' => 1, 'reading' => '75', 'status' => 'normal'],     // Heart Rate
                    ['vital_id' => 2, 'reading' => '118/78', 'status' => 'normal'], // Blood Pressure
                    ['vital_id' => 3, 'reading' => '98', 'status' => 'normal'],     // Oxygen Saturation
                    ['vital_id' => 4, 'reading' => '4', 'status' => 'normal'],      // Stress
                    ['vital_id' => 5, 'reading' => '105', 'status' => 'normal'],    // Blood Glucose
                    ['vital_id' => 6, 'reading' => '190', 'status' => 'normal'],    // Lipids
                    ['vital_id' => 7, 'reading' => '5.8', 'status' => 'normal'],    // HbA1c
                    ['vital_id' => 8, 'reading' => '80', 'status' => 'normal'],     // IHRA
                    ['vital_id' => 9, 'reading' => '36.8', 'status' => 'normal'],   // Temperature
                ]
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Wilson',
                'email' => 'emily.wilson@test.com',
                'phone' => '+1234567893',
                'gender' => 'female',
                'age' => 67,
                'vitals' => [
                    ['vital_id' => 1, 'reading' => '55', 'status' => 'low'],        // Heart Rate (low)
                    ['vital_id' => 2, 'reading' => '155/98', 'status' => 'high'],   // Blood Pressure (high)
                    ['vital_id' => 3, 'reading' => '94', 'status' => 'low'],        // Oxygen Saturation (low)
                    ['vital_id' => 4, 'reading' => '8', 'status' => 'high'],        // Stress (high)
                    ['vital_id' => 5, 'reading' => '320', 'status' => 'critical'],  // Blood Glucose (critical high)
                    ['vital_id' => 6, 'reading' => '280', 'status' => 'critical'],  // Lipids (critical high)
                    ['vital_id' => 7, 'reading' => '9.5', 'status' => 'critical'],  // HbA1c (critical high)
                    ['vital_id' => 8, 'reading' => '95', 'status' => 'high'],       // IHRA (high)
                    ['vital_id' => 9, 'reading' => '38.2', 'status' => 'high'],     // Temperature (high)
                ]
            ],
            [
                'first_name' => 'Robert',
                'last_name' => 'Brown',
                'email' => 'robert.brown@test.com',
                'phone' => '+1234567894',
                'gender' => 'male',
                'age' => 41,
                'vitals' => [
                    ['vital_id' => 1, 'reading' => '82', 'status' => 'normal'],     // Heart Rate
                    ['vital_id' => 2, 'reading' => '125/82', 'status' => 'normal'], // Blood Pressure
                    ['vital_id' => 3, 'reading' => '97', 'status' => 'normal'],     // Oxygen Saturation
                    ['vital_id' => 4, 'reading' => '5', 'status' => 'normal'],      // Stress
                    ['vital_id' => 5, 'reading' => '92', 'status' => 'normal'],     // Blood Glucose
                    ['vital_id' => 6, 'reading' => '195', 'status' => 'normal'],    // Lipids
                    ['vital_id' => 7, 'reading' => '5.6', 'status' => 'normal'],    // HbA1c
                    ['vital_id' => 8, 'reading' => '78', 'status' => 'normal'],     // IHRA
                    ['vital_id' => 9, 'reading' => '37.1', 'status' => 'normal'],   // Temperature
                ]
            ],
        ];
        
        foreach($test_patients as $patient_data){
            // Create user account for patient
            $user_id = DB::table('users')->insertGetId([
                'first_name' => $patient_data['first_name'],
                'last_name' => $patient_data['last_name'],
                'email' => $patient_data['email'],
                'phone' => $patient_data['phone'],
                'password' => Hash::make('password123'),
                'name' => $patient_data['first_name'] . ' ' . $patient_data['last_name'],
                'doctor' => 0,
                'pharmacy' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            echo "Created patient: {$patient_data['first_name']} {$patient_data['last_name']} (ID: {$user_id})\n";
            
            // Create patient-doctor relationship
            DB::table('patients')->insert([
                'user' => $user_id,
                'doctor' => $doctor_id,
                'doctor_approve' => 1,
                'user_approve' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Create vital readings for the past 30 days
            for($day = 30; $day >= 0; $day--){
                $date = now()->subDays($day);
                
                foreach($patient_data['vitals'] as $vital){
                    // Add some variation to readings over time
                    $base_reading = $vital['reading'];
                    $variation = rand(-5, 5);
                    
                    // Handle blood pressure format
                    if(strpos($base_reading, '/') !== false){
                        list($systolic, $diastolic) = explode('/', $base_reading);
                        $reading = ($systolic + $variation) . '/' . ($diastolic + rand(-3, 3));
                    } else {
                        $reading = (float)$base_reading + ($variation * 0.5);
                    }
                    
                    // Get SI unit
                    $vital_info = DB::table('allvitalz')->where('id', $vital['vital_id'])->first();
                    
                    DB::table('vital_readings')->insert([
                        'user' => $user_id,
                        'vitalz' => $vital['vital_id'],
                        'reading' => (string)$reading,
                        'si_unit' => $vital_info->si_unit ?? '',
                        'date' => $date->timestamp,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                }
            }
            
            echo "  - Created 30 days of vital readings\n";
        }
        
        echo "\n✅ Test data created successfully!\n";
        echo "Test patient credentials:\n";
        echo "  Email: john.smith@test.com (or any other test patient email)\n";
        echo "  Password: password123\n\n";
        echo "Patient statuses:\n";
        echo "  - John Smith: HIGH RISK (high heart rate, BP, glucose)\n";
        echo "  - Sarah Johnson: CRITICAL (critically low heart rate, BP, oxygen)\n";
        echo "  - Michael Davis: NORMAL (all vitals normal)\n";
        echo "  - Emily Wilson: HIGH RISK (critical glucose, high BP)\n";
        echo "  - Robert Brown: NORMAL (all vitals normal)\n";
    }
}
