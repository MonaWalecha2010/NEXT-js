<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OwnerPolicy  extends BasePolicy
{
    /**
     * @var null
     */
    protected static $protectedModelName = 'Owner';
}
