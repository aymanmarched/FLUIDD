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
        Schema::create('client_service_selections', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('machine_id')->constrained('entretenir_mon_machines')->onDelete('cascade');
            $table->foreignId('type_id')->nullable()->constrained('type_equipements')->nullOnDelete(); 
            $table->timestamps();
        });

        // 2️⃣ BACKFILL OLD DATA
        $date = now()->format('Ymd');
        $counter = 100000;

        DB::table('clients')->orderBy('id')->each(function ($client) use (&$counter, $date) {
            $ref = "ENT-$date-" . $counter++;

            DB::table('client_service_selections')
                ->where('client_id', $client->id)
                ->whereNull('reference')
                ->update(['reference' => $ref]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_service_selections');
    }
};
