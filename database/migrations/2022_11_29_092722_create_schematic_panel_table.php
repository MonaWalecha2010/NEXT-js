<?php

use App\Models\Schematic;
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
        Schema::create('panel_schematic', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Schematic::class)->constrained();
            $table->foreignIdFor(Panel::class)->constrained();
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
        Schema::dropIfExists('panel_schematic');
    }
};
