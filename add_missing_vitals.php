<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$patients = [23, 24, 25, 26, 27];
$vitals = [
    [4, '5'],   // Stress
    [6, '200'], // Lipids
    [7, '6.0'], // HbA1c
    [8, '80']   // IHRA
];

echo "Adding missing vitals to existing patients...\n";

foreach($patients as $patient_id){
    echo "Patient ID: $patient_id\n";
    foreach($vitals as $vital){
        $vital_id = $vital[0];
        $base_reading = $vital[1];
        
        // Add 31 days of readings
        for($day = 30; $day >= 0; $day--){
            $timestamp = time() - ($day * 86400);
            $date = date('Y-m-d H:i:s', $timestamp);
            
            // Add variation
            $reading = is_numeric($base_reading) ? ($base_reading + rand(-5, 5)) : $base_reading;
            
            DB::insert('
                INSERT INTO vital_readings (user, vitalz, reading, si_unit, date, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ', [$patient_id, $vital_id, $reading, '', $timestamp, $date, $date]);
        }
        echo "  - Added vital ID $vital_id\n";
    }
}

echo "\n✅ Successfully added missing vitals to all patients!\n";
