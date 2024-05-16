<?php

namespace App\Listeners;

use App\Events\UserActivated;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Mail;

class SendUserActiveNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserActivated  $event
     * @return void
     */
    public function handle( UserActivated $event)
    {
        $user = $event->user;
        try
        {
            if( $user->active ){
                $userCreated = new \App\Mail\UserActivated( $user );
                Mail::to( $user->email )->send( $userCreated );
            }
        }
        catch ( \Exception $ex )
        {
            $action = "Class: " . __CLASS__ . " - Method: " . __METHOD__;
            Helper::logError( $ex, $ex->getMessage(), $action, 'critical' );
        }
    }
}
