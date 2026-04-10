<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_social_links', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('site_setting_id')->nullable(); // optional
            $table->string('name');          // ex: Facebook
            $table->string('url');           // ex: https://facebook.com/adil
            $table->string('color')->nullable(); // ex: #1877F2
            $table->longText('icon_svg')->nullable(); // svg code
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true);

            $table->timestamps();

            $table->index(['site_setting_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_social_links');
    }
};