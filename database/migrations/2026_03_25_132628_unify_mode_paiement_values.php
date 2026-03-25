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
    public function up()
    {
        // On change d'abord les données
        DB::statement("UPDATE paiements SET mode_paiement = 'especes' WHERE mode_paiement = 'especes'"); // Pas utile ici car on veut juste être sûr qu'on peut migrer
        
        // On modifie l'enum pour inclure 'espece'
        DB::statement("ALTER TABLE paiements MODIFY COLUMN mode_paiement ENUM('mobile_money', 'virement', 'cheque', 'espece', 'especes', 'orange_money', 'ligdicash')");
        
        // On migre les données
        DB::statement("UPDATE paiements SET mode_paiement = 'espece' WHERE mode_paiement = 'especes'");
        
        // On enlève 'especes'
        DB::statement("ALTER TABLE paiements MODIFY COLUMN mode_paiement ENUM('mobile_money', 'virement', 'cheque', 'espece', 'orange_money', 'ligdicash')");
    }

    public function down()
    {
        DB::statement("ALTER TABLE paiements MODIFY COLUMN mode_paiement ENUM('mobile_money', 'virement', 'cheque', 'espece', 'especes', 'orange_money', 'ligdicash')");
        DB::statement("UPDATE paiements SET mode_paiement = 'especes' WHERE mode_paiement = 'espece'");
        DB::statement("ALTER TABLE paiements MODIFY COLUMN mode_paiement ENUM('mobile_money', 'virement', 'cheque', 'especes', 'orange_money', 'ligdicash')");
    }
};
