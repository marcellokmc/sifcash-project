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
        DB::statement("ALTER TABLE paiements MODIFY COLUMN mode_paiement ENUM('mobile_money', 'virement', 'cheque', 'espece', 'orange_money', 'moov_money', 'ligdicash')");
    }

    public function down()
    {
        DB::statement("ALTER TABLE paiements MODIFY COLUMN mode_paiement ENUM('mobile_money', 'virement', 'cheque', 'espece', 'orange_money', 'ligdicash')");
    }
};
