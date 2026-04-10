<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('client_machine_marque_selections', function (Blueprint $table) {
            $table->boolean('is_submitted')->default(false)->after('reference');
            $table->timestamp('submitted_at')->nullable()->after('is_submitted');
        });
    }

    public function down(): void
    {
        Schema::table('client_machine_marque_selections', function (Blueprint $table) {
            $table->dropColumn(['is_submitted', 'submitted_at']);
        });
    }
};