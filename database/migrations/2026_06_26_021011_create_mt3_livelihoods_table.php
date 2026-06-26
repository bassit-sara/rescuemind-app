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
        Schema::create('mt3_livelihoods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('business_type');
            $table->string('business_type_other')->nullable();
            $table->string('location');
            $table->text('damage_details');
            $table->decimal('damage_value', 12, 2)->nullable();
            $table->text('needs')->nullable();
            $table->string('status')->default('รอตรวจสอบ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mt3_livelihoods');
    }
};
