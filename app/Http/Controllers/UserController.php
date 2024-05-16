<?php

namespace App\Http\Controllers;

use App\Events\UserActivated;
use App\Helpers\Helper;
use App\Http\Resources\AirframeResource;
use App\Http\Resources\UserResource;
use App\Mail\UserCreated;
use App\Models\Airframe;
use App\Models\User;
use App\Http\Resources\TrainingRoleResource;
use App\Models\TrainingRole;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserController extends EntityController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkAuthorization( 'viewAny', User::class );

        
        $data = [
            'users' => UserResource::collection( User::with( 'airframes' )->paginate() ),
        ];

        return $this->render( $data, 'Users/Index' );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->checkAuthorization( 'create', User::class );
        $user = new User();
        $data = [
            'user'      => UserResource::make( $user->load( ['airframes'] ) ),
            'airframes' => AirframeResource::collection( Airframe::with(['users'])->get() ),
            'training_roles' => TrainingRoleResource::collection(TrainingRole::paginate()),
        ];

        return $this->render( $data, 'Users/CreateAndEdit' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        
        $this->checkAuthorization( 'create', User::class );
        $user           = new User();

        $customErrors   = [];

        try
        {
            $customErrors     = $this->saveRequestToModel( $request, $user );
        }
        catch ( Exception $ex )
        {
            $message                   = $ex->getMessage();
            $customErrors[ 'general' ] = $message;
        }

        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );
            return redirect()->back();
        }

        return redirect()->route( 'users.index', $user );
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function show( User $user )
    {
        $this->checkAuthorization( 'view', $user );

        $data = [
            'user'      => UserResource::make( $user->load(['airframes']) ),
            'airframes' => AirframeResource::collection( Airframe::get() ),
        ];

        return $this->render( $data, 'Users/Show' );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit( User $user )
    {
        $this->checkAuthorization( 'update', $user );

        $data = [
            'user'      => UserResource::make( $user->load(['airframes'] ) ),
            'airframes' => AirframeResource::collection( Airframe::with(['users'])->get() ),
            'training_roles' => TrainingRoleResource::collection(TrainingRole::paginate()),

        ];

        return $this->render( $data, 'Users/CreateAndEdit' );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param User                     $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, User $user )
    {
        $this->checkAuthorization( 'update', $user );
        $customErrors = $this->saveRequestToModel( $request, $user );
        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );

            return redirect()->back();
        }

        return redirect()->route( 'users.index', $user );
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function destroy( User $user )
    {
        $this->checkAuthorization( 'delete', $user );
        if ( ! $user->canDelete() )
        {
            throw ValidationException::withMessages( [
                'general' => "This User cannot be deleted as they have either previously performed Tasks on a Workpack or are the sole remaining Admin user ",
            ] );

            return redirect()->back();
        }
        $user->delete();

        return redirect()->route( 'users.index' );
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function code( Request $request, User $user )
    {
        $this->checkAuthorization( 'checkCode', $user );
        $code = (int) $request->get( 'code' );
        if ( ! $user->checkCode( $code ) )
        {
            return response()
                ->json( [ 'message' => 'Invalid code' ], 403 );
        }

        return response()
            ->json( [ 'user_id' => $user->id, 'success' => true ], 200 );
    }


    /**
     * @param Request $request
     * @param User    $user
     *
     * @return array
     */
    protected function saveRequestToModel( Request $request, User $user )
    {
        // dd($request->all());
        $customErrors = [];
        $password = false;

        try
        {
            //We really don't want the user's code being changed...
            if ( $user->exists )
            {
                $request->request->remove( 'code' );
            }else{
                //But we do want to generate a password for new users...
                $password       = Str::random( 14 );
                $user->password = Hash::make( $password );
            }

            $validationRules = $user->getValidationRules();

            if ( ! $request->hasFile( 'signature' ) )
            {
                unset( $validationRules[ 'signature' ] );
            }

            if ( ! $request->has( 'stamp' ) )
            {
                unset( $validationRules[ 'stamp' ] );
            }



            if ( $request->has( 'role_id' ) ) {
                $role_id = $request->get( 'role_id' );
                if( in_array( $role_id, [ User::SUPERADMIN_ROLE, User::ADMIN_ROLE, User::MECHANIC_ROLE ] ) ){
                    unset( $validationRules[ 'stamp' ] );
                }
            }

            $validator = Validator::make( $request->all(), $validationRules );
            $validator->validate();
            $user->fill( $request->all() );
            // dd($user);
            // Attach any airframes this user is qualified to work on
            DB::transaction( function () use ( $user, $request, $password ) {
                $airframe_ids = [];
                if ( $user->isSeniorEngineer() )
                {
                    if ( $request->has( 'airframe_ids' ) )
                    {
                        $airframe_ids = $request->get( 'airframe_ids' );
                    }
                }
                //Has the account been activated?
                $userActivated = false;
                if( $user->isDirty('active') && $user->active){
                    $userActivated = true;
                }                    

                if ( ! $user->exists )
                {
                    $user->save();
                }
                $user->uploadFile( $request, 'signature' );
                $user->airframes()->sync( $airframe_ids );
                $user->save();

                if( $password ){
                    $userCreated = new UserCreated( $user, $password );
                    Mail::to( $user->email )->send( $userCreated );
                }
                if( $userActivated ){
                    UserActivated::dispatch( $user );
                }

            } );

        }
        catch ( ValidationException $ex )
        {
            $errors = $ex->errors();
            foreach ( $errors as $key => $error )
            {
                $customErrors[ $key ] = $error;
            }
        }
        catch ( QueryException $ex )
        {
            $message = $ex->getMessage();
            if ( str_contains( $message, 'users.users_email_unique' ) )
            {
                $customErrors[ 'name' ] = 'Please give this User a unique email address';
            } elseif ( str_contains( $message, 'users.users_codel_unique' ) )
            {
                $customErrors[ 'name' ] = 'This code is in use by another user';
            } else
            {
                $customErrors[ 'general' ] = $message;
            }
        }

        catch ( \Exception $ex )
        {
            $action                    = "Class: " . __CLASS__ . " - Method: " . __METHOD__;
            $customErrors[ 'general' ] = Helper::logError( $ex, $ex->getMessage(), $action, 'critical' );
        }

        return $customErrors;
    }


}