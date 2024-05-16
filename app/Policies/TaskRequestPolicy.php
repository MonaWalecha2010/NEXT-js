<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TaskRequest;
use Illuminate\Auth\Access\HandlesAuthorization;


class TaskRequestPolicy extends BasePolicy
{
    /**
     * @var null
     */ protected static $protectedModelName = 'TaskRequest';

}
