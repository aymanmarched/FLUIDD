<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->foreignId('ville_id')->nullable()->constrained('villes')->nullOnDelete();
            $table->text('adresse')->nullable();
            $table->string('location')->nullable(); // GPS string lat,lng
            $table->string('sms_token')->nullable();
            $table->string('sms_verification_code')->nullable();
            $table->string('sms_verification_reference')->nullable();
            $table->timestamp('sms_verification_expires_at')->nullable();
            $table->timestamp('sms_verified_at')->nullable();
            $table->string('password_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
