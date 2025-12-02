<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestDoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test doctors in different states to test marketplace
        $testDoctors = [
            [
                'name' => 'Dr. John Doe',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'doctor@test.com',
                'phone' => '08011111111',
                'state' => 'Lagos',
                'city' => 'Ikeja',
                'specialization' => 'General Practice',
                'license_type' => 'MDCN',
            ],
            [
                'name' => 'Dr. Sarah Ahmed',
                'first_name' => 'Sarah',
                'last_name' => 'Ahmed',
                'email' => 'doctor.abuja@test.com',
                'phone' => '08022222222',
                'state' => 'Abuja',
                'city' => 'Wuse',
                'specialization' => 'Cardiology',
                'license_type' => 'MDCN',
            ],
            [
                'name' => 'Dr. Michael Okonkwo',
                'first_name' => 'Michael',
                'last_name' => 'Okonkwo',
                'email' => 'doctor.anambra@test.com',
                'phone' => '08033333333',
                'state' => 'Anambra',
                'city' => 'Onitsha',
                'specialization' => 'Pediatrics',
                'license_type' => 'MDCN',
            ],
        ];

        foreach ($testDoctors as $doctor) {
            $existingDoctor = DB::table('users')->where('email', $doctor['email'])->first();
            
            if (!$existingDoctor) {
                DB::table('users')->insert([
                    'name' => $doctor['name'],
                    'first_name' => $doctor['first_name'],
                    'last_name' => $doctor['last_name'],
                    'email' => $doctor['email'],
                    'password' => Hash::make('password123'),
                    'phone' => $doctor['phone'],
                    'state' => $doctor['state'],
                    'city' => $doctor['city'],
                    'specialization' => $doctor['specialization'],
                    'license_type' => $doctor['license_type'],
                    'doctor' => 1,
                    'pharmacy' => 0,
                    'hospital' => 0,
                    'sales_rep' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Test doctors created successfully!');
        $this->command->info("\nTest Doctor Credentials:");
        $this->command->info("Lagos Doctor - Email: doctor@test.com | Password: password123");
        $this->command->info("Abuja Doctor - Email: doctor.abuja@test.com | Password: password123");
        $this->command->info("Anambra Doctor - Email: doctor.anambra@test.com | Password: password123");
    }
}
