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
        Schema::create('sectors', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->integer('order_number')->default(0);
            $table->boolean('status')->default(1);
            $table->integer('user_id_created_by')->nullable();
            $table->string('fname_created_by')->nullable();
            $table->string('lname_created_by')->nullable();
            $table->integer('user_id_modified_by')->nullable();
            $table->string('fname_modified_by')->nullable();
            $table->string('lname_modified_by')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sectors');
    }
};
