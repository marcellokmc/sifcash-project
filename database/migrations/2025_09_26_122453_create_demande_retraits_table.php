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
            Schema::create('demande_retraits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('adherent_id')->constrained('adherents')->onDelete('cascade');
                $table->foreignId('adhesion_id')->constrained('adhesions')->onDelete('cascade');
                $table->enum('type_retrait', ['a_terme', 'anticipe']);
                $table->decimal('montant_demande', 12, 2);
                $table->enum('mode_retrait', ['mobile_money', 'virement', 'cheque', 'especes']);
                $table->json('informations_retrait');
                $table->enum('statut', ['soumis', 'approuve', 'rejete'])->default('soumis');
                $table->text('motif_rejet')->nullable();
                $table->timestamp('date_demande');
                $table->timestamp('date_validation')->nullable();
                $table->foreignId('validated_by_agent_id')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();

                $table->index(['adherent_id', 'adhesion_id']);
                $table->index(['statut', 'type_retrait']);
            });
        }
    
        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('demande_retraits');
        }
    };
