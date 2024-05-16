<?php

use App\Models\AirframeWorkpack;
use App\Models\Panel;
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
        Schema::create('airframe_workpack_panels', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AirframeWorkpack::class)->constrained();
            $table->foreignIdFor(Panel::class)->constrained();
            $table->integer('order')->default(0);
            $table->text('depends')->nullable();
            $table->text('description')->nullable();
            $table->json('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airframe_workpack_panels');
    }
};
