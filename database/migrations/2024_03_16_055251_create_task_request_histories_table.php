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
        Schema::create('task_request_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('task_req_id')->nullable();
            $table->longText('assesmant_result_data')->nullable();
            $table->string('task_req_result')->nullable();
            $table->longText('task_req_comment')->nullable();
            $table->string('previous_expiry')->nullable();
            $table->string('completed_date')->nullable();
            $table->string('modified_date')->nullable();
            $table->string('completed_by')->nullable();
            $table->longText('task_req_document')->nullable();
            $table->longText('task_req_document_name')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_request_histories');
    }
};
