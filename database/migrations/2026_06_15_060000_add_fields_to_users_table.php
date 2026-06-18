<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add extra fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('province')->nullable()->after('phone');
            $table->string('district')->nullable()->after('province');
            $table->string('avatar')->nullable()->after('district');
            $table->boolean('is_active')->default(true)->after('avatar');
            $table->boolean('is_safe')->default(false)->after('is_active');
            $table->timestamp('safe_at')->nullable()->after('is_safe');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'province', 'district', 'avatar', 'is_active', 'is_safe', 'safe_at']);
        });
    }
};
