<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('risk_areas', function (Blueprint $table) {
            $table->id();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('subdistrict')->nullable();
            $table->string('area_name'); // e.g. "ต.หนองหอย"
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            
            // Raw scores (0-100)
            $table->integer('rain_score')->default(0);
            $table->integer('water_score')->default(0);
            $table->integer('sos_count')->default(0);
            $table->integer('missing_count')->default(0);
            $table->integer('hazard_count')->default(0);
            
            // Calculated fields
            $table->integer('risk_score')->default(0);
            $table->string('risk_level')->default('safe'); // safe, watch, warning, critical
            $table->string('prediction_text')->nullable(); // e.g. "คาดการณ์น้ำท่วมหนักภายใน 2 ชั่วโมง"
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_areas');
    }
};
