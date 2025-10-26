<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiement_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_id')->constrained('credits')->onDelete('cascade');
            $table->foreignId('echeance_credit_id')->nullable()->constrained('echeance_credits')->nullOnDelete();
            $table->date('date_paiement');
            $table->decimal('montant', 12, 2);
            $table->decimal('penalite', 12, 2)->default(0);
            $table->string('mode')->nullable(); // espece, mobile money, virement
            $table->string('reference')->nullable();
            $table->foreignId('received_by_agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('statut', ['en_attente','valide','rejete'])->default('valide');
            $table->text('motif_rejet')->nullable();
            $table->timestamps();

            $table->index(['credit_id','echeance_credit_id']);
            $table->index(['date_paiement']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiement_credits');
    }
};
