<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assesment extends Basemodel
{
    use HasFactory;

    public function tasks(){
        return $this->belongsTo( Task::class , 'task_id');
    }    

    
    public function assesment_checklist(){
        return $this->hasMany( AssesmentChecklist::class, 'assesment_id' );
    }
}
