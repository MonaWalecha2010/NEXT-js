<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy extends BasePolicy
{
    
    /**
     * @var null
     */
    protected static $protectedModelName = 'Task';
}
