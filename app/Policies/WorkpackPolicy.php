<?php

namespace App\Policies;

use App\Models\Schematic;
use App\Models\User;
use App\Models\Workpack;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkpackPolicy extends BasePolicy
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
     * Determine whether the user can view any workpacks.
     *
     * @param User  $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->isAuthenticatedUser();
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

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, $model)
    {
        return ( $user->isAdministrator() && !$model->isCompleted() );
    }


    public function destroy(User $user, $model)
    {
        return ( $user->isAdministrator() && !$model->isCompleted() );
    }


    /**
     * @param User $user
     *
     * @return bool
     */
    public function addWorkpackPanel( User $user )
    {
        return $user->isAdministrator();
    }


    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Schematic  $schematic
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function workpackSchematic(User $user, Workpack $workpack, Schematic $schematic)
    {
        return $user->isAuthenticatedUser();
    }

    /**
     * @param  \App\Models\User  $user
     * @param Workpack $workpack
     *
     * @return bool
     */
    public function updatePanels( User $user, Workpack $workpack )
    {
        return ( $user->isAuthenticatedUser() && !$workpack->isCompleted() );
    }

    /**
     * @param  \App\Models\User  $user
     * @param Workpack $workpack
     *
     * @return bool
     */
    public function complete( User $user, Workpack $workpack )
    {
        return $user->isSeniorEngineer();
    }


    /**
     * @param  \App\Models\User  $user
     * @param Workpack $workpack
     *
     * @return bool
     */
    public function report( User $user, Workpack $workpack )
    {
        return $user->isAuthenticatedUser();
    }

}
