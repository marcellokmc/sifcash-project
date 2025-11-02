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
        Schema::table('audits', function (Blueprint $table) {
            // Ajouter des champs manquants pour un audit complet
            $table->text('url')->nullable()->after('ip');
            $table->text('user_agent')->nullable()->after('url');
            $table->text('description')->nullable()->after('user_agent');
            $table->string('action_category')->nullable()->after('action'); // paiement, retrait, adhesion, affectation, etc.
            $table->unsignedBigInteger('target_user_id')->nullable()->after('model_id'); // utilisateur ciblé par l'action
            $table->foreign('target_user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('action_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->dropForeign(['target_user_id']);
            $table->dropIndex(['action_category']);
            $table->dropColumn(['url', 'user_agent', 'description', 'action_category', 'target_user_id']);
        });
    }
};
