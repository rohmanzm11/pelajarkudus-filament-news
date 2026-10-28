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
        Schema::create('banners', function (Blueprint $table) {
    $table->id();
    $table->string('title');                       // nama internal + alt text
    $table->string('placement');                   // home_below_hero, post_top, post_bottom, sidebar
    $table->string('image');                       // gambar desktop
    $table->string('image_mobile')->nullable();    // opsional, rasio berbeda
    $table->string('url')->nullable();
    $table->boolean('open_in_new_tab')->default(true);
    $table->boolean('is_active')->default(true);
    $table->timestamp('starts_at')->nullable();
    $table->timestamp('ends_at')->nullable();
    $table->unsignedInteger('sort_order')->default(0);
    $table->unsignedBigInteger('clicks')->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
