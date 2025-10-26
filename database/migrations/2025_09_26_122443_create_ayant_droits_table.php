<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ayants_droit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adherent_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance')->nullable();
            $table->string('lien_parente');
            $table->string('contact')->nullable();
            $table->enum('type_beneficiaire', ['vie', 'deces']);
            $table->enum('statut_validation', ['en_attente', 'validé', 'rejeté'])->default('en_attente');
            $table->text('motif_rejet')->nullable();
            $table->foreignId('validated_by_agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ayants_droit');
    }
};