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
        Schema::create('mission_recommendations', function (Blueprint $table) {
        $table->id();
      $table->foreignId('mission_id')->constrained()->cascadeOnDelete();
      $table->foreignId('machine_id')->constrained()->cascadeOnDelete();
      $table->foreignId('marque_id')->constrained()->cascadeOnDelete();
      $table->timestamps();

      $table->unique(['mission_id','machine_id']); // one marque per machine (simple)
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mission_recommendations');
    }
};
