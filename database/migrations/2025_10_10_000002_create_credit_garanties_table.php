<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credit_garanties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_id')->constrained('credits')->onDelete('cascade');
            $table->string('type'); // ex: bien, caution, depot
            $table->string('description')->nullable();
            $table->decimal('valeur_estimee', 12, 2)->nullable();
            $table->string('document_preuve_path')->nullable();
            $table->timestamps();

            $table->index(['credit_id','type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_garanties');
    }
};
