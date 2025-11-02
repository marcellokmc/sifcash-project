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
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('action_by_user_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->string('action')->nullable()->after('type'); // ex: 'validation', 'rejet', 'approbation'
            $table->string('entity_type')->nullable()->after('action'); // ex: 'paiement', 'credit', 'retrait'
            $table->unsignedBigInteger('entity_id')->nullable()->after('entity_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['action_by_user_id']);
            $table->dropColumn(['action_by_user_id', 'action', 'entity_type', 'entity_id']);
        });
    }
};
