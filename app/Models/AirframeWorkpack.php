<?php

namespace App\Models;

use App\Traits\HasFiles;
use App\Traits\Noteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AirframeWorkpack extends Basemodel {

    use HasFactory, HasFiles, Noteable;


    /**
     * @var string[]
     */
    protected static array $validationRules = [
        'id'          => 'required|int',
        'airframe_id' => 'required|int',
        'name'        => 'required|max:255',
    ];


    /**
     * @return BelongsTo
     */
    public function airframe()
    {
        return $this->belongsTo( Airframe::class );
    }

    /**
     * @return HasOne
     */
    public function user()
    {
        return $this->belongsTo( User::class );
    }

    /**
     * @return HasMany
     */
    public function airframe_workpack_panels()
    {
        return $this->hasMany( AirframeWorkpackPanel::class );
    }
}
