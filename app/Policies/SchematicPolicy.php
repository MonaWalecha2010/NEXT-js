<?php

namespace App\Policies;

use App\Models\Schematic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchematicPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * @var null
     */
    protected static $protectedModelName = 'Schematic';


//    /**
//     * Determine whether the user can view any models.
//     *
//     * @param  \App\Models\User  $user
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function viewAny(User $user)
//    {
//        return $user->isAdministrator();
//    }
//
//    /**
//     * Determine whether the user can view the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\Schematic  $schematic
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function view(User $user, Schematic $schematic)
//    {
//        return $user->isAuthenticatedUser();
//    }

//    /**
//     * Determine whether the user can create models.
//     *
//     * @param  \App\Models\User  $user
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function create(User $user)
//    {
//        return $user->isAdministrator();
//    }

//    /**
//     * Determine whether the user can update the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\Basemodel  $schematic
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function update(User $user, Basemodel $schematic)
//    {
//        return $user->isAdministrator();
//    }
//
//    /**
//     * Determine whether the user can delete the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\Schematic  $schematic
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function delete(User $user, Schematic $schematic)
//    {
//        return $user->isAdministrator();
//    }
//
//    /**
//     * Determine whether the user can restore the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\Schematic  $schematic
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function restore(User $user, Schematic $schematic)
//    {
//        return $user->isAdministrator();
//    }
//
//    /**
//     * Determine whether the user can permanently delete the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\Schematic  $schematic
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function forceDelete(User $user, Schematic $schematic)
//    {
//        return $user->isAdministrator();
//    }
}
