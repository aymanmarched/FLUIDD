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
        Schema::create('garanties', function (Blueprint $table) {
            $table->id();

            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone');
            $table->string('email')->nullable();

            $table->foreignId('ville_id')->constrained()->cascadeOnDelete();
            $table->string('adresse')->nullable();

            $table->foreignId('machine_id')->constrained()->cascadeOnDelete();
            $table->foreignId('marque_id')->constrained()->cascadeOnDelete();

            $table->string('machine_series'); // 🔥 VERY IMPORTANT

            $table->timestamp('date_garante');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garanties');
    }
};
