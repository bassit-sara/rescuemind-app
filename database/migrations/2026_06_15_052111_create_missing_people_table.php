<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('missing_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('photo')->nullable();
            $table->string('province')->nullable();
            $table->decimal('last_seen_lat', 10, 7)->nullable();
            $table->decimal('last_seen_lng', 10, 7)->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['missing', 'searching', 'found', 'safe'])->default('missing');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('missing_people');
    }
};
