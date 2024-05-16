<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkpackStat extends Model {

    use HasFactory;

    /**
     * @var string[]
     */
    protected static array $validationRules = [];

    protected $fillable = [
        'id',
        'name',
        'completed',
        'panels_completed',
        'panels_total',
        'users',
        'current_users',
        'workers',
    ];

    public function getUsers()
    {
        if ( ! empty( $this->users ) )
        {
            $user_ids = explode( ',', $this->users );

            return User::findMany( $user_ids );
        }

        return null;
    }

    public function getCurrentUsers()
    {
        if ( ! empty( $this->users ) )
        {
            $user_ids = explode( ',', $this->current_users );

            return User::findMany( $user_ids );
        }

        return null;
    }

}
