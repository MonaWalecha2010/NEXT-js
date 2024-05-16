<?php

namespace App\Models;

use App\Traits\HasUpload;
use App\Traits\Noteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schematic extends Basemodel {

    use HasFactory, Noteable, HasUpload;

    protected $embedsvg = false;

    /**
     * @var string[]
     */
    protected static array $validationRules = [];


    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo( User::class );
    }

    /**
     * @return BelongsTo
     */
    public function airframe()
    {
        return $this->belongsTo( Airframe::class );
    }

    /**
     * @return BelongsToMany
     */
    public function panels()
    {
        return $this->belongsToMany( Panel::class, 'panel_schematic' )->withPivot( 'notes' )->using( SchematicPanel::class );
    }

    public function allowedAttachments()
    {
        return [
            "image/svg+xml",
        ];
    }

    public function canDelete() : bool
    {
        $panel_ids = $this->panels->pluck('id')->toArray();
        $workpackPanels = WorkpackPanel::whereIn('panel_id', $panel_ids)->get();
        if ( $workpackPanels->count() === 0 )
        {
            return true;
        }
        return false;
    }

    public function embedSvg()
    {
        $this->embedsvg = true;
    }

    /**
     * @return true
     */
    public function shouldEmbedSvg()
    {
        return (bool) $this->embedsvg;
    }


}
