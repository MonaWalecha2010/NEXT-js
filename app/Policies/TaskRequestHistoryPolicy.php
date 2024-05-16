<?php

namespace App\Policies;

use App\Models\User;

class TaskRequestHistoryPolicy extends BasePolicy
{
    
    
    /**
     * @var null
     */
    protected static $protectedModelName = 'TaskRequestHistory';
}
