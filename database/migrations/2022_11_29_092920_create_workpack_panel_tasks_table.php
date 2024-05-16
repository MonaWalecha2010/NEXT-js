<?php

use App\Models\Aeroplane;
use App\Models\User;
use App\Models\Workpack;
use App\Models\WorkpackPanel;
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
        Schema::create('workpack_panel_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Aeroplane::class)->constrained();
            $table->foreignIdFor(Workpack::class)->constrained();
            $table->foreignIdFor(WorkpackPanel::class)->constrained();
            $table->foreignIdFor(WorkpackPanelStep::class)->constrained()->default(1);
            $table->foreignIdFor(WorkpackPanelAction::class)->constrained()->default(1);
            $table->string('user_code', 10);
            $table->json('notes')->nullable();
            $table->date('created_at');
            $table->date('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workpack_panel_task');
    }
};
