<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntityPolicy  extends BasePolicy
{
    use HandlesAuthorization;
}
