<?php

namespace App\Traits;

trait HasValidationRules {


    /**
     * Get any validation rules defined for this model
     * @return array
     */
    public function getValidationRules()
    {
        return static::$validationRules;
    }

}
