<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logs_connexions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address');
            $table->text('user_agent')->nullable();
            $table->enum('action', ['login', 'logout', 'failed_login']);
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('logs_connexions');
    }
};