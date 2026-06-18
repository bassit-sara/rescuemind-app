<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->tinyInteger('level')->default(1); // 1=เฝ้าระวัง, 2=เตรียมอพยพ, 3=อพยพทันที
            $table->string('province')->nullable(); // null = ทั่วประเทศ
            $table->enum('disaster_type', ['flood', 'landslide', 'storm', 'wildfire', 'pm25', 'other'])->default('flood');
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
