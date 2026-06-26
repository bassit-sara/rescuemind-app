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
        Schema::create('mt3_donations', function (Blueprint $table) {
            $table->id();
            $table->string('donor')->nullable();
            $table->string('phone')->nullable();
            $table->text('items')->nullable();
            $table->string('tracking_no')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mt3_donations');
    }
};
