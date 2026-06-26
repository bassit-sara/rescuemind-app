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
        Schema::create('custom_assessments', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // e.g. 'sleep_quality', 'stress_test'
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('time_estimate')->nullable(); // e.g. '~3 นาที'
            $table->string('icon')->nullable(); // e.g. 'o-moon'
            $table->string('color_theme')->default('indigo'); // e.g. 'indigo', 'orange', 'pink'
            $table->json('questions'); // The array of question strings
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_assessments');
    }
};
