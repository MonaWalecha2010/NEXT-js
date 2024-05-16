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
        Schema::create('notice_document_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('notice_id')->nullable();
            $table->string('version')->nullable();
            $table->string('status')->nullable();
            $table->string('issued_by')->nullable();
            $table->string('notice_document')->nullable();
            $table->string('notice_document_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notice_document_histories');
    }
};
