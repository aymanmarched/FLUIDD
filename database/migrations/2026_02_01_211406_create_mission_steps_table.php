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
        Schema::create('mission_steps', function (Blueprint $table) {
          $table->id();
      $table->foreignId('mission_id')->constrained()->cascadeOnDelete();

      $table->unsignedTinyInteger('step_no'); // 1..3
      $table->text('comment')->nullable();

      $table->string('media_path')->nullable(); // stored in public disk
      $table->enum('media_type', ['photo','video'])->nullable();

      $table->timestamps();

      $table->unique(['mission_id','step_no']); // one record per step (simple)
   });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mission_steps');
    }
};
