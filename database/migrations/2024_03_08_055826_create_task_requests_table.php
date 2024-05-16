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
        Schema::create('task_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable(); 
            $table->integer('task_id')->nullable();
            $table->integer('training_role_id')->nullable(); 
            $table->string('status')->nullable(); 
            $table->string('display_status')->nullable(); 
            $table->string('request_status')->nullable();
            $table->string('approval_status')->nullable(); 
            $table->longText('document')->nullable();
            $table->string('valid_upto')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_requests');
    }
};
