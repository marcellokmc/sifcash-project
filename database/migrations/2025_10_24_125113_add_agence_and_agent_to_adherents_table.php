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
        Schema::table('adherents', function (Blueprint $table) {
            // Agence de rattachement de l'adhérent
            $table->foreignId('agence_id')->nullable()->after('user_id')->constrained('agences')->onDelete('set null');
            
            // Agent gestionnaire responsable de ce compte adhérent
            $table->foreignId('agent_gestionnaire_id')->nullable()->after('agence_id')->constrained('users')->onDelete('set null');
            
            $table->index('agence_id');
            $table->index('agent_gestionnaire_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adherents', function (Blueprint $table) {
            $table->dropForeign(['agence_id']);
            $table->dropForeign(['agent_gestionnaire_id']);
            $table->dropColumn(['agence_id', 'agent_gestionnaire_id']);
        });
    }
};
