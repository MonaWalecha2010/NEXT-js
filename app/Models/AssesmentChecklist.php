<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssesmentChecklist extends BaseModel
{
    use HasFactory;

protected $fillable = ['task_id', 'assesment_id','assigned_check_num', 'check_value','status'];




}
