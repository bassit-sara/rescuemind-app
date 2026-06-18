<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relief_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['shelter', 'hospital', 'food', 'parking'])->default('shelter');
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('capacity')->default(0);
            $table->integer('current_occupancy')->default(0);
            $table->integer('available_beds')->default(0);
            $table->boolean('has_icu')->default(false);
            $table->integer('ambulance_count')->default(0);
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relief_points');
    }
};
