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
        Schema::create('task_request_assesments', function (Blueprint $table) {
            $table->id();
            $table->string('task_id')->nullable();
            $table->string('task_request_id')->nullable();
            $table->string('assesment_id')->nullable();
            $table->string('revalidation_type')->nullable();
            $table->string('status')->nullable();
            $table->longText('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_request_assesments');
    }
};
