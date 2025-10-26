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
            Schema::create('echeance_credits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('credit_id')->constrained('credits')->onDelete('cascade');
                $table->integer('numero_echeance');
                $table->date('date_echeance');
                $table->decimal('montant_attendu', 12, 2);
                $table->decimal('montant_paye', 12, 2)->default(0);
                $table->decimal('penalite_appliquee', 12, 2)->default(0);
                $table->enum('statut', ['en_attente', 'payé', 'en_retard', 'impayé'])->default('en_attente');
                $table->date('date_paiement')->nullable();
                $table->timestamps();

                $table->index(['credit_id', 'statut']);
                $table->index(['credit_id', 'numero_echeance']);
                $table->index('date_echeance');
            });
        }
    
        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('echeance_credits');
        }
    };
