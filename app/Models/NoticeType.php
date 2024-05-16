<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeType extends Basemodel {

    use HasFactory;

    protected static array $validationRules = [
        'name' => 'required|unique:notice_types,name',
    ];

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

    public function notice(){
        return $this->hasMany(Notice::class);
    }

    
}
