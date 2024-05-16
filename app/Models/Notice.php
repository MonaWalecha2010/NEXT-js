<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Basemodel {
    use HasFactory;

    protected static array $validationRules = [
        'title' => 'required',
        'number' => 'required|unique:notices,number',
        'notice_type' => 'required',
        'training_role_ids'=>'required',
        // 'files' => 'required',
    ];

    public function getValidationRules()
    {
        $rules = self::$validationRules;
        // https://laravel.com/docs/9.x/validation#rule-unique
        if ( ! empty( $this->id ) )
        {
            $rules[ 'number' ] .= ',' . $this->id;
        }

        return $rules;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($notice) {
            $roles = explode(',', $notice->roles);
            $roleIds = TrainingRole::whereIn('id', $roles)->pluck('id');
            $notice->roles()->sync($roleIds);
        });
    }

    public function roles()
    {
        return $this->belongsToMany(TrainingRole::class, 'notice_role');
    }

    public function notice_documents_history()
    {
        return $this->hasMany( NoticeDocumentHistory::class,'notice_id');
    }

    public function markReadStatus()
    {
        return $this->hasMany( MarkReadNotice::class,'notice_id');
    }

    public function notice_type(){
        return $this->belongsTo(NoticeType::class, 'notice_type_id');
    }
}
