<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('adherents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Rattachements agence et agent gestionnaire
            $table->foreignId('agence_id')->nullable()->constrained('agences')->onDelete('set null');
            $table->foreignId('agent_gestionnaire_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('membre_id')->unique()->comment('Format: SIFBF-000001');
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->string('lieu_naissance');
            $table->text('adresse');
            $table->string('telephone');
            $table->string('telephone_secondaire')->nullable();
            $table->string('email');
            // Contact d'urgence principal
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_prenoms')->nullable();
            $table->string('contact_urgence_lien_parente')->nullable();
            $table->string('contact_urgence_telephone');
            // Contact d'urgence secondaire
            $table->string('contact_urgence_secondaire_nom')->nullable();
            $table->string('contact_urgence_secondaire_prenoms')->nullable();
            $table->string('contact_urgence_secondaire_lien_parente')->nullable();
            $table->string('contact_urgence_secondaire_telephone')->nullable();
            $table->string('residence');
            $table->string('secteur_numero')->nullable();
            $table->string('profession_exercee')->nullable();
            $table->enum('situation_famille', ['marié', 'celibataire', 'veuf/veuve', 'divorcé']);
            $table->string('profession');
            $table->enum('statut_compte', ['actif', 'inactif', 'en_attente_de_verification', 'suspendu'])->default('en_attente_de_verification');
            $table->timestamp('date_activation')->nullable();

            // Suspension de compte
            $table->timestamp('suspended_at')->nullable();
            $table->string('suspension_reason')->nullable();
            $table->unsignedBigInteger('suspended_by')->nullable();
            $table->foreign('suspended_by')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();

            // Index
            $table->index('agence_id');
            $table->index('agent_gestionnaire_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('adherents');
    }
};
