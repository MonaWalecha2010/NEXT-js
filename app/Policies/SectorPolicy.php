<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Sector;
use Illuminate\Auth\Access\HandlesAuthorization;

class SectorPolicy extends BasePolicy
{
    
    /**
     * @var null
     */
    protected static $protectedModelName = 'Sector';
}
