<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Table paiements
        DB::statement("ALTER TABLE paiements MODIFY COLUMN mode_paiement ENUM('mobile_money', 'virement', 'cheque', 'especes', 'orange_money', 'ligdicash')");
        
        // Table transactions_epargne
        DB::statement("ALTER TABLE transactions_epargne MODIFY COLUMN moyen_paiement ENUM('espece', 'cheque', 'virement', 'prelevement', 'carte', 'interet', 'autre', 'orange_money', 'ligdicash')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Table paiements (retour au précédent)
        DB::statement("ALTER TABLE paiements MODIFY COLUMN mode_paiement ENUM('mobile_money', 'virement', 'cheque', 'especes')");
        
        // Table transactions_epargne
        DB::statement("ALTER TABLE transactions_epargne MODIFY COLUMN moyen_paiement ENUM('espece', 'cheque', 'virement', 'prelevement', 'carte', 'interet', 'autre')");
    }
};
