<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkpackPanelAction extends Basemodel {

    use HasFactory;

    /**
     * @var string[]
     */
    protected static array $validationRules = [];

    public const ACTION_AVAILABLE = 1;

    public const ACTION_INPROGRESS = 2;

    public const ACTION_COMPLETED = 3;

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


    public static function getOrdered()
    {
        return self::orderBy('order', 'asc')->get();
    }

    /**
     * @return mixed
     */
    public static function getFirstAction()
    {
        return self::orderBy( 'order', 'asc' )->first();
    }


    /**
     * @return WorkpackPanelAction
     */
    public static function getLastAction()
    {
        return self::orderBy( 'order', 'desc' )->first();
    }

    /**
     * @return WorkpackPanelAction
     */
    public function getNextAction()
    {
        $nextAction = self::where( 'order', '>', $this->order )->orderBy( 'order', 'asc' )->first();
        if ( $nextAction )
        {
            return $nextAction;
        }

        return self::getFirstAction();
    }

    /**
     * Get the Action that represents a Completed action
     * @return mixed
     */
    public static function getCompletedAction()
    {
        return self::getLastAction();
    }

    /**
     * @return bool
     */
    public function isFinalAction()
    {
        return $this->id === self::getCompletedAction()->id;
    }

}
