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
            Schema::create('audits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('action');
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
                $table->json('ancienne_valeur')->nullable();
                $table->json('nouvelle_valeur')->nullable();
                $table->string('ip');
                $table->timestamp('created_at');

                $table->index(['user_id', 'model_type', 'model_id']);
                $table->index('created_at');
            });
        }
    
        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('audits');
        }
    };
