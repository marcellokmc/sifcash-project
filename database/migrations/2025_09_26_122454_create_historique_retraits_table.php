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
            Schema::create('historique_retraits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('demande_retrait_id')->constrained('demande_retraits')->onDelete('cascade');
                $table->foreignId('adherent_id')->constrained('adherents')->onDelete('cascade');
                $table->foreignId('adhesion_id')->constrained('adhesions')->onDelete('cascade');
                $table->decimal('montant_retire', 12, 2);
                $table->enum('mode_retrait', ['mobile_money', 'virement', 'cheque', 'especes']);
                $table->json('informations_retrait');
                $table->timestamp('date_retrait');
                $table->timestamps();

                $table->index(['adherent_id', 'adhesion_id']);
            });
        }
    
        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('historique_retraits');
        }
    };
