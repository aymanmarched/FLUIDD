// database/migrations/2026_02_04_000000_create_payments_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('client_id');
            $table->string('reference');
            $table->string('kind'); // entretien | remplacer

            $table->decimal('amount_original', 10, 2)->default(0);
            $table->unsignedTinyInteger('discount_percent')->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0);

            $table->string('status')->default('paid'); // paid
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->unique(['reference', 'kind']); // one payment per commande
            $table->index(['client_id', 'reference', 'kind']);

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
