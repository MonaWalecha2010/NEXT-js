<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssesmentChecklistPolicy extends BasePolicy
{
     /**
     * @var null
     */
    protected static $protectedModelName = 'AssesmentChecklist';
}
