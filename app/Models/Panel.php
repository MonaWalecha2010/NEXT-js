<?php

namespace App\Models;

use App\Traits\Noteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Panel extends Basemodel {

    use HasFactory, Noteable;

    /**
     * @var string[]
     */
    protected static array $validationRules = [];

    /**
     * @return HasOne
     */
    public function airframe()
    {
        return $this->belongsTo( Airframe::class );
    }

    /**
     * @return BelongsToMany
     */
    public function schematics()
    {
        return $this->belongsToMany( Schematic::class );
    }

    /**
     * @return HasMany
     */
    public function airframe_workpack_panels()
    {
        return $this->hasMany( AirframeWorkpackPanel::class );
    }

}
