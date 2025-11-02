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
        // SQLite ne supporte pas MODIFY COLUMN, on saute en test
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        // Modifier le type ENUM pour ajouter 'success' et 'error'
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('info', 'warning', 'alert', 'email', 'anniversaire', 'success', 'error') DEFAULT 'info'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        // Retirer 'success' et 'error' du type ENUM
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('info', 'warning', 'alert', 'email', 'anniversaire') DEFAULT 'info'");
    }
};
