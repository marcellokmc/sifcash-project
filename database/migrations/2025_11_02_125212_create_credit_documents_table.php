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
        Schema::create('credit_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->string('chemin');
            $table->string('type');
            $table->unsignedBigInteger('taille');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_documents');
    }
};
