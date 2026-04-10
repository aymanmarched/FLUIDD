<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Footer - Address
            $table->string('footer_address_line1')->nullable()->after('logo');
            $table->string('footer_address_line2')->nullable()->after('footer_address_line1');
            $table->string('footer_city')->nullable()->after('footer_address_line2');
            $table->string('footer_country')->nullable()->after('footer_city');

            // Footer - Google map embed URL (iframe src)
            $table->text('footer_map_embed_url')->nullable()->after('footer_country');

            // Footer - Contacts
            $table->string('footer_email')->nullable()->after('footer_map_embed_url');
            $table->string('footer_phone')->nullable()->after('footer_email');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'footer_address_line1',
                'footer_address_line2',
                'footer_city',
                'footer_country',
                'footer_map_embed_url',
                'footer_email',
                'footer_phone',
            ]);
        });
    }
};