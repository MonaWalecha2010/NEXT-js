<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sector;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Notification extends Basemodel
{
    use HasFactory;

    protected $with = ['notificationSectors'];
     /**
     * @var string[]
     */
    protected static array $validationRules = [
        'title' => 'required',
        'notification_sectors' => 'required',
        'content' => 'required',
        'start_number' => 'required', 
        'start_period' => 'required', 
        'start_scope' => 'required', 
        'stop_number' => 'required', 
        'stop_period' => 'required', 
        'stop_scope' => 'required', 
        'repeat_number' => 'required', 
        'repeat_period' => 'required', 

    ];

    public function notificationSectors()
    {
        return $this->belongsToMany(Sector::class, 'notification_sector');
    }
}
