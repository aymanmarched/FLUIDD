<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('entretenir_machine_technician', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technician_id')->constrained()->onDelete('cascade');
            $table->foreignId('machine_id')->constrained('entretenir_mon_machines')->onDelete('cascade');
      
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entretenir_machine_technician');
    }
};
