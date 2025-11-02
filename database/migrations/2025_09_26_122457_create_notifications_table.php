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
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('action_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('titre');
                $table->text('message');
                $table->boolean('lu')->default(false);
                $table->enum('type', ['info', 'warning', 'alert', 'email', 'anniversaire', 'success', 'error'])->default('info');
                $table->string('action')->nullable();
                $table->string('entity_type')->nullable();
                $table->unsignedBigInteger('entity_id')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'lu']);
                $table->index('type');
            });
        }
    
        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('notifications');
        }
    };
