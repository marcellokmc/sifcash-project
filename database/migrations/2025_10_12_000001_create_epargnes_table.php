<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpargnesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epargnes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adherent_id')->constrained()->onDelete('cascade');
            $table->string('numero_compte', 20)->unique();
            $table->enum('type_epargne', [
                'epargne_ordinaire',
                'epargne_jeune',
                'epargne_logement',
                'epargne_retraite',
                'epargne_scolaire'
            ]);
            $table->decimal('montant_initial', 15, 2);
            $table->decimal('solde_actuel', 15, 2)->default(0);
            $table->decimal('taux_interet', 5, 2);
            $table->decimal('interet_cumule', 15, 2)->default(0);
            $table->date('date_ouverture');
            $table->dateTime('date_derniere_operation')->nullable();
            $table->date('dernier_calcul_interets')->nullable();
            $table->enum('statut', ['actif', 'inactif', 'bloque', 'cloture'])->default('actif');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Index
            $table->index('adherent_id');
            $table->index('type_epargne');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('epargnes');
    }
}
