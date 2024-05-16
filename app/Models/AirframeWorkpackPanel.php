<?php

namespace App\Models;

use App\Traits\Noteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AirframeWorkpackPanel extends Basemodel {

    use HasFactory, Noteable;

    /**
     * @var string[]
     */
    protected static array $validationRules = [];


    /**
     * @return HasOne
     */
    public function airframe_workpack()
    {
        return $this->belongsTo( AirframeWorkpack::class );
    }

    /**
     * @return HasOne
     */
    public function panel()
    {
        return $this->belongsTo( Panel::class );
    }

}
