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
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->string('start_number')->nullable();
            $table->string('start_period')->nullable();
            $table->string('start_scope')->nullable();
            $table->string('stop_number')->nullable();
            $table->string('stop_period')->nullable();
            $table->string('stop_scope')->nullable();
            $table->string('repeat_number')->nullable();
            $table->string('repeat_period')->nullable();
            $table->integer('status')->nullable();
            $table->boolean('for_mandatory')->nullable();
            $table->timestamps();
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
