<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkpackPanelStep extends Basemodel {

    use HasFactory;

    public const STEP_NOT_STARTED = 1;

    public const STEP_REMOVAL = 2;

    public const STEP_INSPECT_CLEAR = 3;

    public const STEP_INSTALL = 4;

    public const STEP_INSPECT_INSTALL = 5;

    public const STEP_SEAL = 6;

    public const STEP_COMPLETE = 7;

    /**
     * @var string[]
     */
    protected static array $validationRules = [];


    /**
     * @return HasMany
     */
    public function workpack_panels()
    {
        return $this->hasMany( WorkpackPanel::class );
    }

    /**
     * @return HasMany
     */
    public function workpack_panel_tasks()
    {
        return $this->hasMany( WorkpackPanelTask::class );
    }


    /**
     * @return mixed
     */
    public static function getOrdered()
    {
        return self::orderBy('order', 'asc')->get();
    }


    /**
     * @return WorkpackPanelStep
     */
    public static function getFirstStep()
    {
        return WorkpackPanelStep::orderBy( 'order', 'asc' )->first();
    }


    /**
     * @return WorkpackPanelStep
     */
    public static function getLastStep()
    {
        return WorkpackPanelStep::orderBy( 'order', 'desc' )->first();
    }


    /**
     * @return WorkpackPanelStep
     */
    public function getNextStep()
    {
        $nextStep = self::where( 'order', '>', $this->order )->orderBy( 'order', 'asc' )->first();
        if ( $nextStep )
        {
            return $nextStep;
        }

        return self::getFirstStep();
    }


    /**
     * Get the Step that represents a Completed panel
     * @return WorkpackPanelStep
     */
    public static function getCompletedStep()
    {
        return self::getLastStep();
    }

    /**
     * @return bool
     */
    public function isFinalStep()
    {
        return $this->id === self::getCompletedStep()->id;
    }

    /**
     * Steps that Mechanics can perform...
     * @return array
     */
    public static function getStepsMechanicsCanPerform()
    {
        return [
            self::STEP_NOT_STARTED,
            self::STEP_REMOVAL,
            self::STEP_INSTALL,
            self::STEP_SEAL,
            self::STEP_COMPLETE
        ];
    }


    /**
     * Steps that Engineers can perform...
     * @return int[]
     */
    public static function getStepsEngineersCanPerform()
    {
        return [
            self::STEP_NOT_STARTED,
            self::STEP_REMOVAL,
            self::STEP_INSTALL,
            self::STEP_SEAL,
            self::STEP_COMPLETE,
            self::STEP_INSPECT_CLEAR,
            self::STEP_INSPECT_INSTALL
        ];
    }

    /**
     * Steps that Senior Engineers can perform...
     * @return array
     */
    public static function getStepsSeniorEngineersCanPerform()
    {
        return [
            self::STEP_NOT_STARTED,
            self::STEP_REMOVAL,
            self::STEP_INSTALL,
            self::STEP_SEAL,
            self::STEP_COMPLETE,
            self::STEP_INSPECT_CLEAR,
            self::STEP_INSPECT_INSTALL
        ];
    }

}
