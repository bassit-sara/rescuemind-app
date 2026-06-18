<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sos_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('address')->nullable();
            $table->string('province')->nullable();
            $table->integer('num_people')->default(1);
            $table->string('water_level')->nullable(); // e.g. knee, waist, chest
            $table->boolean('has_elderly')->default(false);
            $table->boolean('has_children')->default(false);
            $table->boolean('has_bedridden')->default(false);
            $table->boolean('has_pregnant')->default(false);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'assigned', 'in_progress', 'resolved', 'safe'])->default('pending');
            $table->enum('priority', ['critical', 'high', 'medium', 'low'])->default('medium');
            $table->foreignId('officer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('volunteer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sos_requests');
    }
};
