<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_role');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
