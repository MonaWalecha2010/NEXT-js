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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('task_number')->unique();
            $table->string('title')->unique();
            $table->string('short_name')->nullable();
            $table->longText('description')->nullable();
            $table->string('external_id')->nullable();
            $table->integer('sector_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('revalidation_type')->nullable();
            $table->integer('validity_number')->nullable();
            $table->string('validity_period')->nullable();
            $table->longText('document')->nullable();
            $table->boolean('is_mandatory')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
