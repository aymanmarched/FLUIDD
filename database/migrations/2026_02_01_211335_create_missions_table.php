<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration {
  public function up(): void {
    Schema::create('missions', function (Blueprint $table) {
      $table->id();

      $table->string('reference')->index();
      $table->enum('kind', ['entretien','remplacer'])->index();

      $table->foreignId('client_id')->constrained()->cascadeOnDelete();
      $table->foreignId('technicien_user_id')->constrained('users')->cascadeOnDelete();

      $table->enum('status', ['in_progress','completed','closed'])->default('in_progress')->index();
      $table->unsignedTinyInteger('current_step')->default(1); // 1..3

      // decisions (entretien)
      $table->boolean('will_fix')->nullable();              // step2 decision
      $table->text('cannot_fix_reason')->nullable();        // if will_fix = false
      $table->boolean('propose_remplacer')->nullable();     // if cannot fix
      $table->enum('proposal_status', ['none','pending','accepted','rejected'])->default('none');

      // decisions (remplacer)
      $table->boolean('will_install')->nullable();          // step2 decision
      $table->text('cannot_install_reason')->nullable();    // if will_install = false

      // payment
      $table->boolean('paid')->nullable();                  // last step
      $table->timestamps();

      $table->unique(['reference','kind']); // 1 mission per commande+type
    });
  }

  public function down(): void {
    Schema::dropIfExists('missions');
  }
};
