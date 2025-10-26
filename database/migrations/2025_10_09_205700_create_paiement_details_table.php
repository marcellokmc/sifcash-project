<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiement_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paiement_id')->constrained('paiements')->onDelete('cascade');
            $table->enum('type_frais', ['dossier', 'entretien', 'cotisation']);
            $table->decimal('montant', 12, 2);
            $table->timestamps();

            $table->index(['paiement_id', 'type_frais']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiement_details');
    }
};
