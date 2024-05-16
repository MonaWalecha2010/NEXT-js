<?php

namespace App\Policies;

use App\Models\User;

class MarkReadNoticePolicy extends BasePolicy
{
    
    
    /**
     * @var null
     */
    protected static $protectedModelName = 'MarkReadNotice';
}
