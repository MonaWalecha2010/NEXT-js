<?php

namespace App\Models;

use App\Traits\CanAttachUploads;
use App\Traits\HasFiles;
use App\Traits\HasValidationRules;
use App\Traits\Paginatable;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\CanDelete;

class Basemodel extends Model implements CanDelete {

    use Paginatable, HasValidationRules, CanAttachUploads, HasFiles;

    /**
     * @var array
     */
    protected static array $validationRules = [];

    /**
     * @return false
     */
    public function canDelete() : bool
    {
        return false;
    }

}
