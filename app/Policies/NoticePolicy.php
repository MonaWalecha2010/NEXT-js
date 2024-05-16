<?php

namespace App\Policies;

use App\Models\User;

class NoticePolicy extends BasePolicy
{
     /**
     * @var null
     */
    protected static $protectedModelName = 'Notice';
}
