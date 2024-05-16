<?php

namespace App\Models;

use App\Traits\Noteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WorkpackPanel extends Basemodel {

    use HasFactory, Noteable;

    /**
     * @var string[]
     */
    protected static array $validationRules = [];

    public const STATUS_INITIAL = 1;

    public const ACTION_INITIAL = 1;

    protected $attributes = [
        'workpack_panel_step_id' => self::STATUS_INITIAL,
        'workpack_panel_action_id' => self::ACTION_INITIAL,
    ];


    /**
     * @return HasOne
     */
    public function user()
    {
        return $this->belongsTo( User::class );
    }

    /**
     * @return BelongsTo
     */
    public function aeroplane()
    {
        return $this->belongsTo( Aeroplane::class );
    }

    /**
     * @return HasOne
     */
    public function panel()
    {
        return $this->belongsTo( Panel::class );
    }

    /**
     * @return HasOne
     */
    public function workpack()
    {
        return $this->belongsTo( Workpack::class );
    }

    /**
     * @return BelongsTo
     */
    public function workpack_panel_action()
    {
        return $this->belongsTo( WorkpackPanelAction::class );
    }

    /**
     * @return BelongsTo
     */
    public function workpack_panel_step()
    {
        return $this->belongsTo( WorkpackPanelStep::class );
    }

    /**
     * @return HasMany
     */
    public function workpack_panel_tasks()
    {
        return $this->hasMany( WorkpackPanelTask::class );
    }

    /**
     * @return HasMany
     */
    public function workpack_panel_task_notes()
    {
        return $this->hasMany( WorkpackPanelTaskNote::class );
    }


    /**
     * @return bool
     */
    public function doesTaskRequireACode()
    {
        if( $this->workpack_panel_action_id == WorkpackPanelAction::getFirstAction()->id ){
            return false;
        }
        return true;
    }


    /**
     * @return bool
     */
    public function canTakeOverPanel()
    {
        $blockingActions = [];
        $blockingActions[] = WorkpackPanelAction::getFirstAction()->id;
        $blockingActions[] = WorkpackPanelAction::getLastAction()->id;
        $getCurrentTask = $this->getLastTaskPerformedOnPanel();
        if( in_array( $getCurrentTask->workpack_panel_action_id, $blockingActions ) ){
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isFirstStep()
    {
        return $this->workpack_panel_step_id == WorkpackPanelStep::getFirstStep()->id;
    }


    /**
     * Has this panel had all Tasks completed?
     * @return bool
     */
    public function isCompleted()
    {
        return ( $this->workpack_panel_step->id == WorkpackPanelStep::getCompletedStep()->id ) && ( $this->workpack_panel_action->id == WorkpackPanelAction::getCompletedAction()->id );
    }

    public function setReopenedPanel()
    {
        $this->workpack_panel_action_id = WorkpackPanelAction::getFirstAction()->id;
        $this->workpack_panel_step_id = WorkpackPanelStep::getFirstStep()->id;
        $this->completed = 0;
    }

    /**
     * @return void
     */
    public function setStepCompleted()
    {
        // As long as this is not the first step we need to "release" the panel so no one currently owns it
        if( WorkpackPanelStep::getFirstStep()->id != $this->workpack_panel_step_id ){
            $this->user_id = 0;
        }
        $this->workpack_panel_action_id = WorkpackPanelAction::getFirstAction()->id;
        $this->workpack_panel_step_id = $this->workpack_panel_step->getNextStep()->id;
    }

    /**
     * @return void
     */
    public function setAllStepsCompleted()
    {
        $this->workpack_panel_action_id = WorkpackPanelAction::getLastAction()->id;
        $this->workpack_panel_step_id = WorkpackPanelStep::getLastStep()->id;
        $this->completed                = true;
    }


    /**
     * You can only move to the next status if the current action is Completed
     * @return void
     */
    public function setNextAvailableActionAndStep()
    {
        //Can we move to a new Step?
        if ( $this->workpack_panel_action_id == WorkpackPanelAction::getCompletedAction()->id )
        {
            $this->workpack_panel_action_id = $this->workpack_panel_action->getNextAction()->id;
            $this->workpack_panel_step_id = $this->workpack_panel_step->getNextStep()->id;
            return;
        }
        //Can we increment to a new Action?
        if ( $this->workpack_panel_action_id != WorkpackPanelAction::getCompletedAction()->id )
        {
            $this->workpack_panel_action_id = $this->workpack_panel_action->getNextAction()->id;
        }
    }

    public function setNextAvailableAction()
    {
        //Can we increment to a new Action?
        if ( $this->workpack_panel_action_id != WorkpackPanelAction::getCompletedAction()->id )
        {
            $this->workpack_panel_action_id = $this->workpack_panel_action->getNextAction()->id;
        }
    }


    public function setNextAvailableStep()
    {
        if ( $this->workpack_panel_action_id == WorkpackPanelAction::getCompletedAction()->id )
        {
            $this->workpack_panel_step_id = $this->workpack_panel_step->getNextStep()->id;
        }
    }

    public function canPanelBeAdddedToWorkpack()
    {
        $workpack = $this->workpack;
        $existing_workpack_panel = $workpack->workpack_panels()->where('panel_id', $this->panel_id)->get();
        if( $existing_workpack_panel->count() === 0 ){
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function canPanelBeDeletedFromWorkpack()
    {
        if ( ( $this->workpack_panel_action_id === WorkpackPanelStep::STEP_NOT_STARTED) && ( $this->workpack_panel_step_id === WorkpackPanelAction::ACTION_AVAILABLE ) )
        {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canPanelBeReaddedToWorkpack()
    {
        if ( ( $this->workpack_panel_action_id === WorkpackPanelStep::STEP_SEAL ) && ( $this->workpack_panel_step_id === WorkpackPanelAction::ACTION_COMPLETED ) )
        {
            return true;
        }

        return false;
    }


    /**
     * @return mixed
     */
    public function getLastTaskPerformedOnPanel()
    {
        return WorkpackPanelTask::where('workpack_panel_id', $this->id )->orderBy('id', 'DESC')->first();
    }



    /**
     * @return true
     */
    public function doesPanelHaveValidTaskHistory()
    {
        return true;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getDataForReportRow()
    {
        /**
         *
         * SELECT *
         * FROM workpack_panel_tasks
        * WHERE `workpack_panel_id` = 717
        * GROUP BY `workpack_panel_step_id`, `workpack_panel_action_id`
        * HAVING `workpack_panel_action_id` = 3
        * ORDER BY `workpack_panel_step_id` ASC, id DESC;
         *
         */

        $workpackPanelTasks   = WorkpackPanelTask::with( [
            'workpack_panel_action',
            'user'
        ])
            ->where( 'workpack_panel_id', $this->id )
            ->get();
        return $workpackPanelTasks;
    }

}
