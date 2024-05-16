<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sector extends Basemodel {

    use HasFactory;

    /**
     * @var string[]
     */
    protected static array $validationRules = [
        'title' => 'required|max:255|unique:sectors,title',
        'order_number' => 'required|numeric|unique:sectors,order_number',
        'fname_created_by' => 'required',
        'lname_created_by' => 'required', 
    ];

    public function getValidationRules()
    {
        $rules = self::$validationRules;
        // https://laravel.com/docs/9.x/validation#rule-unique
        if ( ! empty( $this->id ) )
        {
            $rules[ 'title' ] .= ',' . $this->id;
            $rules[ 'order_number' ] .= ',' . $this->id;
        }

        return $rules;
    }

     /**
     * @return HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class );
    }

    /**
     * @return false|void
     */
    public function canDelete() : bool
    {
        return !$this->hasTasks();
    }

    /**
     * @return bool
     */
    protected function hasTasks(): bool
    {
        if ( $this->tasks->count() > 0 )
        {
            return true;
        }

        return false;
    }

}
