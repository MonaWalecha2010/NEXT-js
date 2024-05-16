<?php

namespace App\Policies;

use App\Models\AirframeWorkpack;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AirframeWorkpackPolicy extends BasePolicy
{
    /**
     * @var null
     */
    protected static $protectedModelName = 'AirframeWorkpack';

}
