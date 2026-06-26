<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('community_needs', function (Blueprint $table) {
            $table->string('progress')->default('pending')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('community_needs', function (Blueprint $table) {
            $table->dropColumn('progress');
        });
    }
};
