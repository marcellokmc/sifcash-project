<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->text('motif')->nullable()->after('date_demande');
            $table->text('garanties')->nullable()->after('motif');
            $table->string('contract_path')->nullable()->after('validated_by_agent_id');
        });
    }

    public function down(): void
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->dropColumn(['motif', 'garanties', 'contract_path']);
        });
    }
};