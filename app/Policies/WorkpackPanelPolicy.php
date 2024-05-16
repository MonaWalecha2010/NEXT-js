<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkpackPanelPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * @param User $user
     * @param  \App\Models\Basemodel|string  $model
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, $model )
    {
        return $user->isAuthenticatedUser();
    }

    public function create(User $user )
    {
        return $user->isAuthenticatedUser();
    }

}
