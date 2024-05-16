<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class AeroplanePolicy extends BasePolicy
{
    /**
     * @var null
     */
    protected static $protectedModelName = 'Aeroplane';

}
