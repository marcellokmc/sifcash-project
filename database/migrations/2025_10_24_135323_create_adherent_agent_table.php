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
        Schema::create('adherent_agent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adherent_id')->constrained('adherents')->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_principal')->default(false); // Agent principal/responsable
            $table->text('notes')->nullable(); // Notes sur l'affectation
            $table->timestamp('affecte_le')->useCurrent();
            $table->foreignId('affecte_par')->nullable()->constrained('users')->onDelete('set null'); // Qui a fait l'affectation
            $table->timestamps();
            
            // Un adhérent ne peut être affecté qu'une seule fois au même agent
            $table->unique(['adherent_id', 'agent_id']);
            
            // Index pour les recherches
            $table->index('adherent_id');
            $table->index('agent_id');
            $table->index('is_principal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adherent_agent');
    }
};
