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
        // Modifier le type ENUM pour ajouter 'anniversaire'
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('info', 'warning', 'alert', 'email', 'anniversaire') DEFAULT 'info'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Retirer 'anniversaire' du type ENUM
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('info', 'warning', 'alert', 'email') DEFAULT 'info'");
    }
};
