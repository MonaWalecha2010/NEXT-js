<?php

namespace App\Models;

use App\Traits\Noteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Owner extends Basemodel {

    use HasFactory, Noteable;


    /**
     * @var string[]
     */
    protected static array $validationRules = [
        'name'   => 'required|max:255|unique:owners,name',
        'active' => 'required|bool',
        'email'  => 'required|email',
        'email'  => 'required',
    ];

    /**
     * @return array
     */
    public function getValidationRules()
    {
        $rules = self::$validationRules;
        // https://laravel.com/docs/9.x/validation#rule-unique
        if ( ! empty( $this->id ) )
        {
            $rules[ 'name' ] .= ',' . $this->id;
        }

        return $rules;
    }

    /**
     * @return HasMany
     */
    public function aeroplanes()
    {
        return $this->hasMany( Aeroplane::class );
    }

    /**
     * @return false|void
     */
    public function canDelete() : bool
    {
        return !$this->hasAeroplanes();
    }

    /**
     * @return bool
     */
    protected function hasAeroplanes(): bool
    {
        if ( $this->aeroplanes->count() > 0 )
        {
            return true;
        }

        return false;
    }

}
