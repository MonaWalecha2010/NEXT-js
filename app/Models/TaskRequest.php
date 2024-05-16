<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class TaskRequest extends Basemodel
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected static array $validationRules = [
        'task_id' => 'required',
        'files' => 'required',
    ];



     /**
     * @return HasOne
     */
    public function user()
    {
        return $this->belongsTo( User::class,'user_id');
    }

     /**
     * @return HasOne
     */
    public function task()
    {
        return $this->belongsTo( Task::class,'task_id');
    }

    public function task_request_history()
    {
        return $this->hasMany( TaskRequestHistory::class,'task_req_id');
    }

    public function task_request_attachments()
    {
        return $this->hasMany( File::class,'entity_id')->where('entity_name', 'TaskRequest');
    }

}
