<?php

namespace App\Policies;

use App\Models\Airframe;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class AirframePolicy extends BasePolicy
{
    /**
     * @var null
     */
    protected static $protectedModelName = 'Airframe';
}
