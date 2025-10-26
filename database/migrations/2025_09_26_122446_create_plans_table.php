<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration
    {
        public function up()
        {
            Schema::create('plans', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->text('description')->nullable();
                $table->enum('periodicite', ['journalier', 'hebdomadaire', 'mensuel', 'trimestriel', 'annuel']);
                $table->decimal('montant_min', 10, 2)->nullable();
                $table->decimal('montant_max', 10, 2)->nullable();
                $table->decimal('taux_interet', 5, 2)->default(0);
                $table->integer('duree_min_jours')->default(30);
                $table->integer('duree_max_jours')->nullable();
                $table->decimal('frais_adhesion', 10, 2)->default(0);
                $table->decimal('frais_retrait', 10, 2)->default(0);
                $table->decimal('frais_dossier', 12, 2)->default(0);
                $table->decimal('frais_entretien', 12, 2)->default(0);
                $table->boolean('actif')->default(true);
                $table->integer('ordre_affichage')->default(0);
                $table->json('conditions')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        public function down()
        {
            Schema::dropIfExists('plans');
        }
    };