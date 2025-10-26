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
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->enum('type_transaction', ['paiement', 'retrait', 'credit']);
                $table->string('reference')->unique();
                $table->decimal('montant', 12, 2);
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('adherent_id')->nullable()->constrained('adherents')->onDelete('set null');
                $table->foreignId('adhesion_id')->nullable()->constrained('adhesions')->onDelete('set null');
                $table->foreignId('credit_id')->nullable()->constrained('credits')->onDelete('set null');
                $table->enum('statut', ['en_attente', 'validé', 'rejeté'])->default('en_attente');
                $table->timestamps();

                $table->index(['type_transaction', 'statut']);
                $table->index(['adherent_id', 'adhesion_id']);
            });
        }
    
        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('transactions');
        }
    };
