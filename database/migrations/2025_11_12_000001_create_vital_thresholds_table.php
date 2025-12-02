<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('vital_thresholds')) {
            Schema::create('vital_thresholds', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('doctor_id')->nullable(); // null = default/standard thresholds
                $table->unsignedBigInteger('vital_id'); // references allvitalz.id
                $table->string('vital_name'); // cached for performance
                $table->decimal('min_normal', 10, 2)->nullable();
                $table->decimal('max_normal', 10, 2)->nullable();
                $table->decimal('min_critical', 10, 2)->nullable(); // below this = critical
                $table->decimal('max_critical', 10, 2)->nullable(); // above this = critical
                $table->boolean('is_custom')->default(false); // true if doctor customized
                $table->timestamps();

                $table->index(['doctor_id', 'vital_id']);
                $table->index(['vital_id']);
            });
        }

        // Seed standard medical thresholds
        if (Schema::hasTable('vital_thresholds')) {
            $count = DB::table('vital_thresholds')->count();
            if ($count === 0) {
                DB::table('vital_thresholds')->insert([
                    // Heart Rate (60-100 bpm normal, <40 or >120 critical)
                    [
                        'doctor_id' => null,
                        'vital_id' => 1,
                        'vital_name' => 'Heart rate (ECG)',
                        'min_normal' => 60,
                        'max_normal' => 100,
                        'min_critical' => 40,
                        'max_critical' => 120,
                        'is_custom' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    // Blood Pressure Systolic (90-120 mmHg normal, <70 or >180 critical)
                    [
                        'doctor_id' => null,
                        'vital_id' => 2,
                        'vital_name' => 'Blood Pressure',
                        'min_normal' => 90,
                        'max_normal' => 120,
                        'min_critical' => 70,
                        'max_critical' => 180,
                        'is_custom' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    // Oxygen Saturation (95-100% normal, <90% critical)
                    [
                        'doctor_id' => null,
                        'vital_id' => 3,
                        'vital_name' => 'Oxygen Saturation',
                        'min_normal' => 95,
                        'max_normal' => 100,
                        'min_critical' => 90,
                        'max_critical' => null,
                        'is_custom' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    // Blood Glucose (70-140 mg/dL normal, <50 or >300 critical)
                    [
                        'doctor_id' => null,
                        'vital_id' => 5,
                        'vital_name' => 'Blood Glucose',
                        'min_normal' => 70,
                        'max_normal' => 140,
                        'min_critical' => 50,
                        'max_critical' => 300,
                        'is_custom' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    // Body Temperature (36.1-37.2°C normal, <35 or >39.5 critical)
                    [
                        'doctor_id' => null,
                        'vital_id' => 9,
                        'vital_name' => 'Body Temperature',
                        'min_normal' => 36.1,
                        'max_normal' => 37.2,
                        'min_critical' => 35,
                        'max_critical' => 39.5,
                        'is_custom' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vital_thresholds');
    }
};
