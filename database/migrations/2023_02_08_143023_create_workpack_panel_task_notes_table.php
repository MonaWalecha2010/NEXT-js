<?php

use App\Models\WorkpackPanel;
use App\Models\WorkpackPanelTask;
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
        Schema::create('workpack_panel_task_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(WorkpackPanelTask::class)->constrained();
            $table->foreignIdFor(WorkpackPanel::class)->constrained();
            $table->text('note')->nullable(false);
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
        Schema::dropIfExists('workpack_panel_task_notes');
    }
};
