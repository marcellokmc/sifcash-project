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
        Schema::table('adhesions', function (Blueprint $table) {
            $table->enum('type_cloture', ['retrait_anticipé', 'arrivee_terme'])
                ->nullable()
                ->after('date_cloture');
            $table->index('type_cloture');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adhesions', function (Blueprint $table) {
            $table->dropIndex(['type_cloture']);
            $table->dropColumn('type_cloture');
        });
    }
};
