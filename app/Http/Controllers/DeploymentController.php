<?php

namespace App\Http\Controllers;

use App\Mail\SendingTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DeploymentController extends Controller
{

    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function checkSecurityKey( Request $request )
    {
        $security_key = config('jetworks145.deployment.key');
        if( !is_null( $security_key ) ){
            if( ($request->has('key') ) && ( $security_key == $request->get('key') ) ){
                return true;
            }
        }
        return false;
    }

    public function clearcache( Request $request )
    {
        if( !$this->checkSecurityKey( $request) ){
            return "Nope";
        }
        Artisan::call('cache:clear');
        return 'Application cache has been cleared';
    }

    public function clearroutes( Request $request )
    {
        if( !$this->checkSecurityKey( $request) ){
            return "Nope";
        }
        Artisan::call('route:cache');
        return 'Routes cache has been cleared';
    }

    public function clearconfig( Request $request )
    {
        if( !$this->checkSecurityKey( $request) ){
            return "Nope";
        }
        Artisan::call('config:cache');
        return 'Config cache has been cleared';
    }

    public function clearview( Request $request )
    {
        if( !$this->checkSecurityKey( $request) ){
            return "Nope";
        }
        Artisan::call('view:clear');
        return 'View cache has been cleared';
    }

    public function clearbackend( Request $request )
    {
        if( !$this->checkSecurityKey( $request) ){
            return "Nope";
        }
        Artisan::call('config:cache');
        Artisan::call('cache:clear');
        Artisan::call('route:cache');
        Artisan::call('view:clear');
        return 'All backend caches cleared';
    }

    public function clearfrontend( Request $request )
    {
        if( !$this->checkSecurityKey( $request) ){
            return "Nope";
        }
        $appPath = base_path();
        $cmd = "cd {$appPath} && npm run build 2>&1";
        $output = shell_exec( $cmd );
        return 'Frontend Javascript / CSS cache has been rebuilt';
    }

    public function emailtest()
    {
        $sendingTest = new SendingTest();
        $recipient = config('jetworks145.admin_email');
        try {
            Mail::to( $recipient )->send( $sendingTest );
            return "Test email sent successfully";
        } catch ( \Exception $ex ) {
            return view('errors.500', ['description' => $ex->getMessage() ]);
        }
    }

}
