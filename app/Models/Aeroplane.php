<?php

namespace App\Models;

use App\Traits\Noteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Aeroplane extends Basemodel {

    use HasFactory, Noteable;


    /**
     * @var string[]
     */
    protected static array $validationRules = [
        'name'   => 'required|max:255',
        'airframe_id'  => 'required|exists:airframes,id',
        'owner_id'  => '|required|exists:owners,id',
        'active' => 'required|bool',
    ];

    /**
     * @return HasOne
     */
    public function airframe()
    {
        return $this->belongsTo( Airframe::class );
    }

    /**
     * @return HasOne
     */
    public function owner()
    {
        return $this->belongsTo( Owner::class );
    }

    /**
     * @return HasMany
     */
    public function workpacks()
    {
        return $this->hasMany( Workpack::class );
    }

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
     * @return bool
     */
    public function canCompleteWorkpack()
    {
        return false;
    }


    /**
     * @return bool
     */
    public function canDelete() : bool
    {
        if ( ! $this->hasWorkpacks() && ! $this->hasWorkPackPanels() )
        {
            return true;
        }

        return false;
    }

    public function canChangeAirframe() : bool
    {
        if ( ! $this->hasWorkpacks() && ! $this->hasWorkPackPanels() )
        {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function hasWorkpacks(): bool
    {
        if ( $this->workpacks->count() > 0 )
        {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function hasWorkPackPanels(): bool
    {
        // Are there any Workpack Panel Tasks?
        if ( $this->workpack_panel_tasks->count() > 0 )
        {
            return true;
        }

        return false;
    }

}
