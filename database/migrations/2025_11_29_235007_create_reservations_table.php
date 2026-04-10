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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('reference')->nullable();
            $table->date('date_souhaite')->nullable();
            $table->time('hour')->nullable();
            $table->timestamps();

            $table->unique(['date_souhaite', 'hour']);
        });
                // 2️⃣ BACKFILL OLD DATA
$date = now()->format('Ymd');
$counter = 100000;

DB::table('clients')->orderBy('id')->each(function ($client) use (&$counter, $date) {
    $ref = "ENT-$date-" . $counter++;


    DB::table('reservations')
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
        Schema::dropIfExists('reservations');
    }
};
