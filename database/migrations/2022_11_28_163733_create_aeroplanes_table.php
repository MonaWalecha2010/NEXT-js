<?php

use App\Models\Airframe;
use App\Models\Owner;
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
        Schema::create('aeroplanes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Airframe::class)->constrained();
            $table->foreignIdFor(Owner::class)->constrained();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->json('notes')->nullable();
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('aeroplane');
    }
};
