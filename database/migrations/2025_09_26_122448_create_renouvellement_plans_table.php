<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('renouvellements_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adhesion_id')->constrained()->onDelete('cascade');
            $table->decimal('montant_souscrit', 12, 2);
            $table->date('date_renouvellement');
            $table->date('date_debut_periode');
            $table->date('date_fin_periode');
            $table->decimal('taux_interet_applique', 5, 2);
            $table->decimal('interets_generes', 12, 2)->default(0);
            $table->enum('statut', ['actif', 'termine', 'annule'])->default('actif');
            $table->text('commentaire')->nullable();
            $table->foreignId('effectue_par_agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Index pour les performances
            $table->index(['adhesion_id', 'date_renouvellement']);
            $table->index('date_debut_periode');
            $table->index('date_fin_periode');
        });
    }

    public function down()
    {
        Schema::dropIfExists('renouvellements_plans');
    }
};