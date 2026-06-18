<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hazard_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['flood', 'landslide', 'road_blocked', 'bridge_damaged', 'power_outage', 'fire', 'other'])->default('flood');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('province')->nullable();
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'verified', 'resolved'])->default('pending');
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hazard_reports');
    }
};
