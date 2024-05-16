<?php

use App\Models\Airframe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panels', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Airframe::class)->constrained();
            $table->string('name')->nullable( false );
            $table->text('description')->nullable();
            $table->json('notes')->nullable();
            $table->timestamps();
            $table->unique(['airframe_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('panel');
    }
};
