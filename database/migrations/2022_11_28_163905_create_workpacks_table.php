<?php

use App\Models\Aeroplane;
use App\Models\Airframe;
use App\Models\User;
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
        Schema::create('workpacks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Airframe::class)->constrained();
            $table->foreignIdFor(Aeroplane::class)->constrained()->nullable();
            $table->string('name');
            $table->text('description');
            $table->json('notes')->nullable();
            $table->boolean('active')->default(0);
            $table->boolean('completed')->default(0);
            $table->timestamps();
            $table->unique(['aeroplane_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workpack');
    }
};
