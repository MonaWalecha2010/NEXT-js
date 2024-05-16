<?php

namespace App\Policies;

use App\Models\User;

class FilePolicy  extends BasePolicy
{
    /**
     * @var null
     */
    protected static $protectedModelName = 'File';

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

}
