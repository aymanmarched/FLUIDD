<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('entretenir_machine_type_equipement', function (Blueprint $table) {
            $table->id();

            $table->foreignId('machine_id')
                ->constrained('entretenir_mon_machines')
                ->onDelete('cascade');

            $table->foreignId('type_id')
                ->constrained('type_equipements')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entretenir_machine_type_equipement');
    }
};
