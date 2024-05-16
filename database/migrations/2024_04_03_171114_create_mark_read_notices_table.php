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
        Schema::create('mark_read_notices', function (Blueprint $table) {
            $table->id();
            $table->integer('notice_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('status')->nullable();
            $table->string('last_version_read')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mark_read_notices');
    }
};
