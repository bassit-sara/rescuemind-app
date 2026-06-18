<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mental_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['phq9', 'gad7', 'ptsd'])->default('phq9');
            $table->json('answers')->nullable();
            $table->integer('score')->default(0);
            $table->string('severity')->nullable(); // minimal, mild, moderate, severe
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mental_assessments');
    }
};
