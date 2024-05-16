<?php

namespace App\Models;

use App\Traits\Noteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SchematicPanel extends Pivot {

    use HasFactory, Noteable;

    /**
     * @var string[]
     */
    protected static array $validationRules = [];

    /***
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var string
     */
    protected $table = 'panel_schematic';

    /**
     * @return HasOne
     */
    public function panel(): HasOne
    {
        return $this->belongsTo( Panel::class );
    }

    /**
     * @return HasOne
     */
    public function schematic(): HasOne
    {
        return $this->belongsTo( Schematic::class );
    }
}
