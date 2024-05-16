<?php

use App\Models\Aeroplane;
use App\Models\Panel;
use App\Models\Workpack;
use App\Models\WorkpackPanelAction;
use App\Models\WorkpackPanelStep;
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
        Schema::create('workpack_panels', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->foreignIdFor(Aeroplane::class)->constrained();
            $table->foreignIdFor(Workpack::class)->constrained();
            $table->foreignIdFor(Panel::class)->constrained();
            $table->foreignIdFor(WorkpackPanelStep::class)->constrained();
            $table->foreignIdFor(WorkpackPanelAction::class)->constrained();
            $table->integer('order')->default(0);
            $table->text('depends')->nullable();
            $table->text('description')->nullable();
            $table->boolean('completed')->default(0);
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
        Schema::dropIfExists('workpack_panel');
    }
};
