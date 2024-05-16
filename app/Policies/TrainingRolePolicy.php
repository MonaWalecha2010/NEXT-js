<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TrainingRole;
use Illuminate\Auth\Access\HandlesAuthorization;


class TrainingRolePolicy extends BasePolicy
{
    
    /**
     * @var null
     */
    protected static $protectedModelName = 'TrainingRole';
}
