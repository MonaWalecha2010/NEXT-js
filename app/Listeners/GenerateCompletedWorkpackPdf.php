<?php

namespace App\Listeners;

use App\Events\WorkpackCompleted;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class GenerateCompletedWorkpackPdf
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
     * @param  \App\Events\WorkpackCompleted $event
     *
     * @return void
     */
    public function handle(WorkpackCompleted $event)
    {
        $workpack = $event->workpack;
        //Email all SuperAdmins and Admins
        $users = User::whereIn('role_id', [User::SUPERADMIN_ROLE, User::ADMIN_ROLE ] )->get();
        $workpackCompleted = new \App\Mail\WorkpackCompleted( $workpack );
        // Ref https://laravel.com/docs/9.x/mail#looping-over-recipients
        $to = false;
        $cc = [];
        foreach ( $users as $user ) {
            $workpackCompleted->setUser( $user );
            if( $to === false ) {
                $to = $user->email;
            } else {
                $cc[] = $user->email;
            }
        }

        Mail::to( $to )
            ->cc($cc )
            ->send( $workpackCompleted );
    }
}
