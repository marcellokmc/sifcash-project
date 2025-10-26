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
            Schema::create('penalite_retrait_anticipes', function (Blueprint $table) {
                $table->id();
                $table->decimal('pourcentage_capital_requis', 5, 2);
                $table->integer('duree_preavis_jours');
                $table->decimal('taux_penalite', 5, 2);
                $table->boolean('actif')->default(true);
                $table->timestamps();
                
                $table->index(['actif']);
            });
        }
    
        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('penalite_retrait_anticipes');
        }
    };
