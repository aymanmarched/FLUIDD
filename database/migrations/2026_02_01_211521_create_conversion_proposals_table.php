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
        Schema::create('conversion_proposals', function (Blueprint $table) {
              $table->id();

      $table->foreignId('mission_id')->constrained()->cascadeOnDelete();
      $table->foreignId('client_id')->constrained()->cascadeOnDelete();

      $table->string('old_reference')->index();
      $table->string('new_reference')->nullable()->index();

      $table->enum('status', ['pending','accepted','rejected'])->default('pending')->index();
      $table->string('token', 64)->unique(); // secure accept link

      $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversion_proposals');
    }
};
