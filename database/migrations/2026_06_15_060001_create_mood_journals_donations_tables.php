<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mood_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('mood')->default(3); // 1=very bad, 2=bad, 3=neutral, 4=good, 5=very good
            $table->text('note')->nullable();
            $table->date('logged_date');
            $table->timestamps();

            $table->unique(['user_id', 'logged_date']);
        });

        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->decimal('sentiment_score', 4, 2)->nullable(); // -1.0 to 1.0
            $table->string('sentiment_label')->nullable(); // positive, neutral, negative
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('recipient_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('item_type'); // food, water, medicine, clothing, money
            $table->integer('quantity')->default(1);
            $table->string('unit')->nullable();
            $table->text('description')->nullable();
            $table->string('province')->nullable();
            $table->enum('status', ['available', 'matched', 'delivered'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
        Schema::dropIfExists('journals');
        Schema::dropIfExists('mood_logs');
    }
};
