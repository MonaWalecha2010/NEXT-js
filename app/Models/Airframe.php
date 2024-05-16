<?php

namespace App\Models;

use App\Traits\Noteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Airframe extends Basemodel {

    use HasFactory, Noteable;


    /**
     * @var string[]
     */
    protected static array $validationRules = [
        'name'     => 'required|max:255',
        'revision' => 'max:255',
        'active'   => 'required|bool',
    ];

    /**
     * @return HasMany
     */
    public function aeroplanes()
    {
        return $this->hasMany( Aeroplane::class );
    }

    /**
     * @return HasMany
     */
    public function panels()
    {
        return $this->hasMany( Panel::class );
    }

    /**
     * @return HasMany
     */
    public function airframe_workpacks()
    {
        return $this->hasMany( AirframeWorkpack::class );
    }

    /**
     * @return HasMany
     */
    public function schematics()
    {
        return $this->hasMany( Schematic::class );
    }

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany( User::class )->withPivot('qualification')->withTimestamps();;
    }


    public function getFullname()
    {
        $hasRevision = (boolean) $this->revision;

        return ( $hasRevision ) ? "{$this->name} ({$this->revision} )" : $this->name;
    }


    /**
     * @return bool
     */
    public function canDelete() : bool
    {
        if (  !$this->hasUsers() && ! $this->hasAeroplanes() && ! $this->hasSchematics() && ! $this->hasWorkpackPanelTasks() )
        {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function hasAeroplanes(): bool
    {
        $aeroplanes = $this->aeroplanes;
        if ( $aeroplanes->count() > 0 )
        {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function hasSchematics(): bool
    {
        $schematics = $this->schematics;
        if ( $schematics->count() > 0 )
        {
            return true;
        }

        return false;
    }

    /**
     * @param $aeroplanes
     *
     * @return bool
     */
    protected function hasWorkpackPanelTasks(): bool
    {
        $aeroplanes = $this->aeroplanes;
        if ( $aeroplanes->count() > 0 )
        {
            $aeroplane_ids      = $aeroplanes->pluck( 'id' )->toArray();
            $workpackPanelTasks = WorkpackPanelTask::whereIn( 'aeroplane_id', $aeroplane_ids )->get();
            if ( $workpackPanelTasks->count() > 0 )
            {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function hasUsers()
    {
        return $this->users->count() > 0;
    }


}
