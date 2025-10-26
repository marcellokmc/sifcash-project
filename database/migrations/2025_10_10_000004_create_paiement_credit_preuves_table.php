<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiement_credit_preuves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paiement_credit_id')->constrained('paiement_credits')->onDelete('cascade');
            $table->string('type')->nullable(); // reçu, photo, bordereau, autre
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->timestamps();

            $table->index('paiement_credit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiement_credit_preuves');
    }
};
