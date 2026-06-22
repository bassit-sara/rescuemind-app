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
        Schema::create('mental_articles', function (Blueprint $table) {
            $table->id();
            $table->string('category')->index(); // 'mental', 'physical', 'prevention'
            $table->string('title');
            $table->text('desc')->nullable();
            $table->string('read_time')->nullable();
            $table->string('author')->nullable();
            $table->string('icon')->nullable();
            $table->string('video_url')->nullable();
            $table->longText('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mental_articles');
    }
};
