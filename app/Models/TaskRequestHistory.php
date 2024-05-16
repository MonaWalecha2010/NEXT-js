<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskRequestHistory extends BaseModel
{
    use HasFactory;


    public function taskRequest(){        
      return  $this->belongsTo(TaskRequest::class,'task_req_id');        
    }
}
