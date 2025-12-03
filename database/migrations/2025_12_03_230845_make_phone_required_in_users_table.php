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
        // Mettre à jour les enregistrements avec téléphone vide ou null
        DB::table('users')->whereNull('phone')->orWhere('phone', '')->update(['phone' => 'non_specifie']);
        
        Schema::table('users', function (Blueprint $table) {
            // Rendre le téléphone obligatoire
            $table->string('phone')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revenir à nullable
            $table->string('phone')->nullable()->change();
        });
    }
};
