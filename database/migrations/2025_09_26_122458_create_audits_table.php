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
                $table->string('action_category')->nullable();
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
                $table->unsignedBigInteger('target_user_id')->nullable();
                $table->json('ancienne_valeur')->nullable();
                $table->json('nouvelle_valeur')->nullable();
                $table->string('ip');
                $table->text('url')->nullable();
                $table->text('user_agent')->nullable();
                $table->text('description')->nullable();
                $table->timestamp('created_at');

                $table->foreign('target_user_id')->references('id')->on('users')->onDelete('set null');
                $table->index(['user_id', 'model_type', 'model_id']);
                $table->index('created_at');
                $table->index('action_category');
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
