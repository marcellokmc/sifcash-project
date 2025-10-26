<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('credits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('adherent_id')->constrained('adherents')->onDelete('cascade');
                $table->decimal('montant_demande', 12, 2);
                $table->integer('duree');
                $table->decimal('taux', 5, 2);
                $table->enum('periodicite', ['journalier', 'hebdomadaire', 'mensuel'])->nullable();
                $table->decimal('montant_accorde', 12, 2)->nullable();
                $table->decimal('frais_adhesion', 12, 2)->default(0);
                $table->decimal('frais_dossier', 12, 2)->default(0);
                $table->decimal('taux_penalite', 8, 2)->default(0);
                $table->enum('mode_penalite', ['fixe', 'pourcentage'])->default('pourcentage');
                $table->enum('type_credit', ['personnel', 'éducation', 'urgence']);
                $table->enum('statut', ['en_attente', 'approuvé', 'rejeté', 'desisté'])->default('en_attente');
                $table->enum('etat', ['brouillon','soumis','en_examen','approuve','rejete','contrat','actif','termine','defaut','annule'])->default('brouillon');
                $table->text('motif_rejet')->nullable();
                $table->date('date_demande');
                $table->date('date_validation')->nullable();
                $table->date('date_debut_remboursement')->nullable();
                $table->foreignId('validated_by_agent_id')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();

                $table->index(['adherent_id', 'statut']);
            });
        }
    
        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('credits');
        }
    };
