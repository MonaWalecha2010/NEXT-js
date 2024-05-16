<?php

namespace App\Models;

use App\Traits\Noteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WorkpackPanelTask extends Basemodel {

    use HasFactory, Noteable;

    /**
     * @var string[]
     */
    protected static array $validationRules = [
        'workpack_panel_id' => 'required|int',
        'user_id'           => 'required|integer',
        'user_code'         => 'required|integer',
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

    public function workpack()
    {
        return $this->belongsTo( Workpack::class );
    }

    /**
     * @return HasOne
     */
    public function workpack_panel()
    {
        return $this->belongsTo( WorkpackPanel::class );
    }

    /**
     * @return HasOne
     */
    public function workpack_panel_step()
    {
        return $this->belongsTo( WorkpackPanelStep::class );
    }


    /**
     * @return HasOne
     */
    public function workpack_panel_action()
    {
        return $this->belongsTo( WorkpackPanelAction::class );
    }


    /**
     * @return HasMany
     */
    public function workpack_panel_task_notes()
    {
        return $this->hasMany( WorkpackPanelTaskNote::class );
    }


    /**
     * Logic to check if user can perform specific task...
     *
     * @param User $user
     *
     * @return bool
     */
    /**
     * Logic to check if user can perform specific task...
     *
     * @param User $user
     *
     * @return bool
     */
    public function canPerformTask( User $user )
    {
        /* Here we need to check that the user is allowed to complete the relative Step. Only certain workers can complete certain steps */
        if( $user->isMechanic() )
        {
            if( in_array( $this->workpack_panel_step_id, WorkpackPanelStep::getStepsMechanicsCanPerform() ) ) {
                return true;
            }
            return false;
        }

        if( $user->isEngineer() ){
            if( in_array( $this->workpack_panel_step_id, WorkpackPanelStep::getStepsEngineersCanPerform() ) ) {
                return true;
            }
        }

        if( $user->isSeniorEngineer() ){
            if( in_array( $this->workpack_panel_step_id, WorkpackPanelStep::getStepsSeniorEngineersCanPerform() ) ) {
                return true;
            }
        }

        return false;
    }


    public function isStepCompleted()
    {
        return $this->workpack_panel_action->isFinalAction();
    }

    public function isFinalStep()
    {
        return $this->workpack_panel_step->isFinalStep();
    }

    /**
     * @return mixed
     */
    public function isFinalStepAndAction()
    {
        return ( $this->isFinalStep() && $this->isStepCompleted() );
    }


    /**
     * @param WorkpackPanel $workpackPanel
     *
     * @return void
     */
    public function setStepAndActionFromPanel( WorkpackPanel $workpackPanel )
    {
        $this->workpack_panel_action_id = $workpackPanel->workpack_panel_action_id;
        $this->workpack_panel_step_id = $workpackPanel->workpack_panel_step_id;
    }
}
