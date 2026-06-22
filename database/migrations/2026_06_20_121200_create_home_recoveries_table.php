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
        Schema::create('home_recoveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('landmark')->nullable();
            $table->boolean('need_cleaning')->default(false);
            $table->boolean('need_electric')->default(false);
            $table->boolean('need_plumbing')->default(false);
            $table->boolean('need_structure')->default(false);
            $table->text('additional_details')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_recoveries');
    }
};
