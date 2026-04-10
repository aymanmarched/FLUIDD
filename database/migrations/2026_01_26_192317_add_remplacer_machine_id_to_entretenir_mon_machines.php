<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('entretenir_mon_machines', function (Blueprint $table) {
            $table->foreignId('remplacer_machine_id')
                ->nullable()
                ->after('id')
                ->constrained('machines')
                ->nullOnDelete()
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('entretenir_mon_machines', function (Blueprint $table) {
            $table->dropConstrainedForeignId('remplacer_machine_id');
        });
    }
};
