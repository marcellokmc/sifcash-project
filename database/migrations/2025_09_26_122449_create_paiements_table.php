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
            Schema::create('paiements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('adhesion_id')->constrained('adhesions')->onDelete('cascade');
                $table->foreignId('adherent_id')->constrained('adherents')->onDelete('cascade');
                $table->decimal('montant', 12, 2);
                $table->enum('categorie', ['ouverture', 'cotisation', 'credit', 'autre'])->default('autre');
                $table->enum('mode_paiement', ['mobile_money', 'virement', 'cheque', 'especes']);
                $table->string('preuve');
                $table->string('reference_paiement')->nullable();
                $table->string('numero_compte_beneficiaire')->nullable();
                $table->string('banque_emetteur')->nullable();
                $table->string('reference_cheque')->nullable();
                $table->enum('statut', ['brouillon', 'soumis', 'validé', 'rejeté', 'expiré'])->default('brouillon');
                $table->text('motif_rejet')->nullable();
                $table->timestamp('date_soumission');
                $table->timestamp('date_validation')->nullable();
                $table->foreignId('validated_by_agent_id')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();

                $table->index(['adherent_id', 'adhesion_id']);
                $table->index(['statut', 'mode_paiement']);
                $table->index(['categorie']);
            });
        }
    
        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('paiements');
        }
    };
