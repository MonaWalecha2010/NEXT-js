<?php

namespace App\Policies;

use App\Models\User;

class AssesmentPolicy extends BasePolicy
{
    /**
     * @var null
     */
    protected static $protectedModelName = 'Assesment';
}
