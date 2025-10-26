<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsEpargneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions_epargne', function (Blueprint $table) {
            $table->id();
            $table->foreignId('epargne_id')->constrained()->onDelete('cascade');
            $table->enum('type_operation', [
                'depot', 
                'retrait', 
                'interet', 
                'frais', 
                'virement', 
                'autre'
            ]);
            $table->decimal('montant', 15, 2);
            $table->dateTime('date_operation');
            $table->enum('moyen_paiement', [
                'espece',
                'cheque',
                'virement',
                'prelevement',
                'carte',
                'interet',
                'autre'
            ]);
            $table->string('reference', 100)->nullable();
            $table->text('notes')->nullable();
            $table->decimal('solde_apres_operation', 15, 2);
            $table->foreignId('auteur_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            // Index
            $table->index('epargne_id');
            $table->index('type_operation');
            $table->index('date_operation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions_epargne');
    }
}
