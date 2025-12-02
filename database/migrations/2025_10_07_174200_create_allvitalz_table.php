<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('allvitalz')) {
            Schema::create('allvitalz', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();
                // comma-separated units, e.g. "bpm,mmHg,%"
                $table->string('si_unit')->nullable();
                $table->timestamps();
            });
        }

        // Seed common vitals if table is empty
        if (Schema::hasTable('allvitalz')) {
            $count = DB::table('allvitalz')->count();
            if ($count === 0) {
                DB::table('allvitalz')->insert([
                    ['name' => 'Heart rate (ECG)', 'si_unit' => 'bpm', 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Blood Pressure', 'si_unit' => 'mmHg', 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Oxygen Saturation', 'si_unit' => '%', 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Stress', 'si_unit' => '', 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Blood Glucose', 'si_unit' => 'mg/dL,mmol/L', 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Lipids', 'si_unit' => 'mg/dL,mmol/L', 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'HbA1c', 'si_unit' => '%', 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'IHRA', 'si_unit' => '', 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Body Temperature', 'si_unit' => '°C,°F', 'created_at' => now(), 'updated_at' => now()],
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('allvitalz');
    }
};
