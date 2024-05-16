<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AirframeUser extends Pivot {

    use HasFactory;


    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;


    /**
     * @return HasMany
     */
    public function airframes()
    {
        return $this->hasMany( Airframe::class );
    }

    public function users()
    {
        return $this->hasMany( Airframe::class );
    }
}
