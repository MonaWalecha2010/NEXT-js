<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy extends BasePolicy
{
    
    /**
     * @var null
     */
    protected static $protectedModelName = 'Notification';
}
