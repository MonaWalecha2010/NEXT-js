<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkpackPanelTaskPolicy extends BasePolicy
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
     * Determine whether the user can create models.
     *
     * @param User     $user
     * @param  $model
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user )
    {
        return $user->isPlaneWorker();
    }

    public function multicreate(User $user )
    {
        return $user->isPlaneWorker();
    }


}
