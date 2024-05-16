<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class BasePolicy
{
    use HandlesAuthorization;

    /**
     * @var null
     */
    protected static $protectedModelName = null;

    /**
     * @var mixed|null
     */
    protected $protectedModel = null;

    /**
     * @return string
     */
    public function getProtectedModelName()
    {
        return '\\App\\Models\\' . static::$protectedModelName;
    }

    public function getProtectedModel()
    {
        $fqClassName = $this->getProtectedModelName();
        return new $fqClassName;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param User  $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->isAdministrator();
    }

    public function viewAnyOne(User $user)
    {
        if ($user->isSuperAdministrator()) {
            return true;
        }
        if ($user->isAdministrator()) {
            return true;
        }
        if ($user->isMechanic()) {
            return true;
        }
        if ($user->isEngineer()) {
            return true;
        }
        if ($user->isSeniorEngineer()) {
            return true;
        }
    }

    public function updateAnyOne(User $user)
    {
        if ($user->isSuperAdministrator()) {
            return true;
        }
        if ($user->isAdministrator()) {
            return true;
        }
        if ($user->isMechanic()) {
            return true;
        }
        if ($user->isEngineer()) {
            return true;
        }
        if ($user->isSeniorEngineer()) {
            return true;
        }
    }

    public function createAnyone(User $user)
    {
        if ($user->isSuperAdministrator()) {
            return true;
        }
        if ($user->isAdministrator()) {
            return true;
        }
        if ($user->isMechanic()) {
            return true;
        }
        if ($user->isEngineer()) {
            return true;
        }
        if ($user->isSeniorEngineer()) {
            return true;
        }
    }

    /**
     * @param User $user
     * @param  \App\Models\Basemodel|string  $model
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, $model)
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User     $user
     * @param  $model
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->isAdministrator();
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
        return $user->isAdministrator();
    }

    /**
     * @param User $user
     * @param      $model
     *
     * @return bool
     */
    public function isActive(User $user, $model)
    {
        if ($user->isAdministrator()) {
            return true;
        }
        if ($user->isPlaneWorker() && $model->active) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, $model)
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function destroy(User $user, $model)
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, $model)
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, $model)
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether only the Training Admin can View the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewTA(User $user)
    {
        return $user->isTrainingAdmin();
    }

    public function view_Admin_TA(User $user)
    {
        if ($user->isAdministrator()) {
            return true;
        }

        if ($user->isTrainingAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether only the Training Admin can Create the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function createTA(User $user)
    {
        return $user->isTrainingAdmin();
    }

    /**
     * Determine whether only the Training Admin can Save the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function saveTA(User $user)
    {
        return $user->isTrainingAdmin();
    }

    /**
     * Determine whether only the Training Admin can Edit the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function editTA(User $user)
    {
        return $user->isTrainingAdmin();
    }

    /**
     * Determine whether only the Training Admin can Update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateTA(User $user)
    {
        return $user->isTrainingAdmin();
    }

    /**
     * Determine whether only the Training Admin can Delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteTA(User $user)
    {
        return $user->isTrainingAdmin();
    }

    /**
     * Determine whether only the Training Admin or Training Officer or Training Manager can View the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewTA_TO_TM(User $user)
    {
        if ($user->isTrainingAdmin()) {
            return true;
        }
        if ($user->isTrainingOfficer()) {
            return true;
        }
        if ($user->isTrainingManager()) {
            return true;
        }
    }

    /**
     * Determine whether only the Training Admin or Training Officer or Training Manager can Create the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function createTA_TO_TM(User $user)
    {
        if ($user->isTrainingAdmin()) {
            return true;
        }
        if ($user->isTrainingOfficer()) {
            return true;
        }
        if ($user->isTrainingManager()) {
            return true;
        }
    }

    /**
     * Determine whether only the Training Admin or Training Officer or Training Manager can Save the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function saveTA_TO_TM(User $user)
    {
        if ($user->isTrainingAdmin()) {
            return true;
        }
        if ($user->isTrainingOfficer()) {
            return true;
        }
        if ($user->isTrainingManagerr()) {
            return true;
        }
    }

    /**
     * Determine whether only the Training Admin or Training Officer or Training Manager can Edit the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function editTA_TO_TM(User $user)
    {
        if ($user->isTrainingAdmin()) {
            return true;
        }
        if ($user->isTrainingOfficer()) {
            return true;
        }
        if ($user->isTrainingManager()) {
            return true;
        }
    }

    /**
     * Determine whether only the Training Admin or Training Officer or Training Manager can Update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateTA_TO_TM(User $user)
    {
        if ($user->isTrainingAdmin()) {
            return true;
        }
        if ($user->isTrainingOfficer()) {
            return true;
        }
        if ($user->isTrainingManager()) {
            return true;
        }
    }

    /**
     * Determine whether only the Training Admin or Training Officer or Training Manager can Delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteTA_TO_TM(User $user)
    {
        if ($user->isTrainingAdmin()) {
            return true;
        }
        if ($user->isTrainingOfficer()) {
            return true;
        }
        if ($user->isTrainingManager()) {
            return true;
        }
    }

    
    /**
     * Determine whether only the Training Admin or Training Officer or Training Manager can Delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Basemodel|string  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewTE_TMC(User $user)
    {
        if ($user->isTrainingEngineer()) {
            return true;
        }
        if ($user->isTrainingMechanic()) {
            return true;
        }
    }
}
