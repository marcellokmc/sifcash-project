<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('adhesions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adherent_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('numero_adhesion')->unique()->comment('Format: ADH-000001');
            $table->decimal('montant_souscrit', 12, 2);
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->enum('statut', ['actif', 'clos', 'suspendu', 'en_attente_retrait', 'en_attente_activation'])->default('en_attente_activation');
            $table->boolean('renouvelable')->default(true);
            $table->decimal('solde_actuel', 12, 2)->default(0);
            $table->decimal('interets_cumules', 12, 2)->default(0);
            $table->decimal('montant_retrait', 12, 2)->default(0);
            $table->date('prochaine_echeance')->nullable();
            $table->integer('nombre_renouvellements')->default(0);
            $table->boolean('frais_ouverture_payes')->default(false);
            $table->text('motif_suspension')->nullable();
            $table->timestamp('date_activation')->nullable();
            $table->timestamp('date_cloture')->nullable();
            $table->foreignId('created_by_agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Index pour les performances
            $table->index(['adherent_id', 'statut']);
            $table->index(['plan_id', 'statut']);
            $table->index('date_debut');
            $table->index('date_fin');
        });
    }

    public function down()
    {
        Schema::dropIfExists('adhesions');
    }
};