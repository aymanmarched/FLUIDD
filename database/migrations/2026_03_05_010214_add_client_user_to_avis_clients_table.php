<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('avis_clients', function (Blueprint $table) {
            if (!Schema::hasColumn('avis_clients', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->index()->after('id');
            }
            if (!Schema::hasColumn('avis_clients', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->index()->after('client_id');
            }

            // optional: 1 avis per client
            // $table->unique('client_id');

            // FK (optional but recommended)
            // if your tables are named clients/users
            $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('avis_clients', function (Blueprint $table) {
            // drop FK first
            try { $table->dropForeign(['client_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['user_id']); } catch (\Throwable $e) {}

            if (Schema::hasColumn('avis_clients', 'client_id')) $table->dropColumn('client_id');
            if (Schema::hasColumn('avis_clients', 'user_id')) $table->dropColumn('user_id');
        });
    }
};