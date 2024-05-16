<?php

namespace App\Policies;

use App\Models\User;

class TaskRequestAssesmentChecklistPolicy extends BasePolicy
{
    
    
    /**
     * @var null
     */
    protected static $protectedModelName = 'TaskRequestAssesmentChecklist';
}
