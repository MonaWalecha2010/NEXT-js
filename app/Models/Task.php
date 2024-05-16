<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TrainingRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Validator;


class Task extends Basemodel
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected static array $validationRules = [
        'task_number'           => 'required|unique:tasks,task_number',
        'title'                 => 'required|max:255',
        'short_name'            => 'required|max:255',
        'revalidation_type'     => 'required',
        'validity_number'       => 'required',
        'validity_period'       => 'required',
        'sector_id'             => 'required',
        'training_role_ids'     => 'required', 
        'sections'              => 'required',
    ];


    // public static function validate(array $data)
    // {
    //     return Validator::make($data, static::$validationRules);
    //     if ($validator->fails()) {
    //         return $validator->errors();
    //     }

    //     return true;
    // }
      
        
    public function getValidationRules()
    {
        $rules = self::$validationRules;
        // https://laravel.com/docs/9.x/validation#rule-unique
        if ( ! empty( $this->id ) )
        {
            $rules[ 'task_number' ] .= ',' . $this->id;
        }

        return $rules;
    }
    
    /**
     * @return HasMany
     */

    public function assesments()
    {
        return $this->hasMany(Assesment::class,);
    }

    public function assesments_checklist()
    {
        return $this->hasMany(AssesmentChecklist::class,);
    }


    public function sector(){
        return $this->belongsTo( Sector::class , 'sector_id');
    }

   
    

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($task) {
            $roles = explode(',', $task->roles);
            $roleIds = TrainingRole::whereIn('id', $roles)->pluck('id');
            $task->roles()->sync($roleIds);
        });


        // Validator::extend('allSectionsRequired', function ($attribute, $value, $parameters, $validator) {
        //     foreach ($value as $index => $section) {
        //         if (is_null($section) && empty($section)) {
        //             $validator->errors()->add($attribute, "Section Titles Required for all the Selected Sections.");
        //         }
        //     }
        // });
    }

    public function roles()
    {
        return $this->belongsToMany(TrainingRole::class, 'task_role');
    }

    public function task_status()
    {
        return $this->hasMany(TaskRequest::class);
    }

    public function taskRequests()
    {
        return $this->hasMany(TaskRequest::class);
    }

    // public function trainingRoles()
    // {
    //     return $this->belongsToMany(TrainingRole::class)->withPivot('additional_field');
    // }
    

}
