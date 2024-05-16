<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface CanDelete {

    /**
     * @return bool
     */
    public function canDelete () : bool;

}
