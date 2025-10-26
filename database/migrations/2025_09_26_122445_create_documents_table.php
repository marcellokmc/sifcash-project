<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adherent_id')->constrained()->onDelete('cascade');
            $table->foreignId('type_document_id')->constrained()->onDelete('cascade');
            $table->string('fichier_recto');
            $table->string('fichier_verso')->nullable();
            $table->enum('statut', ['soumis', 'validé', 'rejeté'])->default('soumis');
            $table->text('commentaire')->nullable();
            $table->integer('version')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};