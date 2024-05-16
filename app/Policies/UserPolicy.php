<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends BasePolicy
{
    /**
     * @var null
     */
    protected static $protectedModelName = 'User';


    //    /**
    //     * Determine whether the user can validate their code
    //     *
    //     * @param  \App\Models\User  $user
    //     * @return \Illuminate\Auth\Access\Response|bool
    //     */
    public function checkCode( User $user )
    {
        return $user->isAuthenticatedUser();
    }

}
