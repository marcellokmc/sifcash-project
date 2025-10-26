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
            Schema::create('condition_eligibilite_credits', function (Blueprint $table) {
                $table->id();
                $table->enum('type_cotisation_requise', ['journalier', 'hebdomadaire', 'mensuel']);
                $table->integer('duree_minimum_anciennete');
                $table->decimal('montant_epargne_minimum', 12, 2)->nullable();
                $table->boolean('actif')->default(true);
                $table->timestamps();
                
                // Index explicite (évite le nom auto-généré trop long sur MySQL)
                $table->index(['type_cotisation_requise', 'actif'], 'cond_elig_credits_tc_actif_idx');
            });
        }
    
        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('condition_eligibilite_credits');
        }
    };
