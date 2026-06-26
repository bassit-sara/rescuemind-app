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
        Schema::table('home_recoveries', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('landmark');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_recoveries', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
