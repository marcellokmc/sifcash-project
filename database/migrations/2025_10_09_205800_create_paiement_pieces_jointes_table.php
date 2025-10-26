<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiement_pieces_jointes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paiement_id')->constrained('paiements')->onDelete('cascade');
            $table->string('fichier');
            $table->string('type_document')->nullable();
            $table->timestamps();

            $table->index(['paiement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiement_pieces_jointes');
    }
};
