<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeDocumentHistory extends BaseModel
{
    use HasFactory;


    public function notice(){        
      return  $this->belongsTo(Notice::class,'notice_id');        
    }
}
