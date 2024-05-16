<?php

namespace App\Http\Controllers;

use App\Http\Resources\AirframeResource;
use App\Http\Resources\FileResource;
use App\Http\Resources\SchematicResource;
use App\Http\Resources\UserResource;
use App\Models\Airframe;
use App\Models\File;
use App\Models\Schematic;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AirframeController extends EntityController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkAuthorization( 'viewAny', Airframe::class );
        $data = [
            'airframes' => AirframeResource::collection( Airframe::with( [
                'aeroplanes',
                'schematics',
                'panels',
                'users',
            ] )->paginate() ),
        ];

        return $this->render( $data, 'Airframes/Index' );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->checkAuthorization( 'create', Airframe::class );

        $data = [
            'users' => User::getSeniorEngineeers()
        ];

        return $this->render( $data, 'Airframes/Create' );
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
        $this->checkAuthorization( 'create', Airframe::class );
        $airframe     = new Airframe();
        $customErrors = $this->saveRequestToModel( $request, $airframe );
        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );

            return redirect()->back();
        }

        return redirect()->route( 'airframes.edit', $airframe );
    }

    /**
     * Display the specified resource.
     *
     * @param Airframe $airframe
     *
     * @return \Inertia\Response
     */
    public function show( Airframe $airframe )
    {
        $this->checkAuthorization( 'view', $airframe );

        $data = [
            'airframe'    => AirframeResource::make( $airframe->loadMissing( [
                'aeroplanes.workpacks',
                'schematics',
                'panels',
            ] ) ),
            'attachments' => FileResource::collection( $airframe->getAttachments() ),
        ];

        return $this->render( $data, 'Airframes/Show' );
    }

    /**
     * @param Airframe $airframe
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Inertia\Response|string
     */
    public function edit( Airframe $airframe )
    {
        $this->checkAuthorization( 'update', $airframe );

        $schematics = Schematic::with('panels')->where('airframe_id', '=', $airframe->id )->get();
        $data = [
            'airframe'    => AirframeResource::make( $airframe ),
            'attachments' => FileResource::collection( $airframe->getAttachments() ),
            'schematics' => SchematicResource::collection( $schematics ),
            'users' => UserResource::collection( User::getSeniorEngineeers() )
        ];

        return $this->render( $data, 'Airframes/Edit' );
    }

    /**
     * @param Request  $request
     * @param Airframe $airframe
     *
     * @return void
     */
    public function update( Request $request, Airframe $airframe )
    {
        $this->checkAuthorization( 'update', $airframe );

        $customErrors = $this->saveRequestToModel( $request, $airframe );
        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );

            return redirect()->back();
        }

        return redirect()->route( 'airframes.show', $airframe );
    }

    /**
     * @param Airframe $airframe
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function destroy( Airframe $airframe )
    {
        $this->checkAuthorization( 'delete', $airframe );
        if ( ! $airframe->canDelete() )
        {
            throw ValidationException::withMessages( [
                'general' => "This Aeroplane Type cannot be deleted as it has a Workpack History, has qualified Users, is associated with an Aeroplane, or has Schematics. Remove any associated Aeroplanes or Schematics before attempting removal.",
            ] );

            return redirect()->back();
        }
        $airframe->delete();

        return redirect()->route( 'airframes.index' );
    }


    /**
     * Display the specified resource.
     *
     * @param Airframe $airframe
     *
     * @return \Inertia\Response
     */
    public function panels( Airframe $airframe )
    {
        $this->checkAuthorization( 'view', $airframe );

        return new AirframeResource( $airframe );
    }


    /**
     * @param Request  $request
     * @param Airframe $airframe
     *
     * @return array
     */
    protected function saveRequestToModel( Request $request, Airframe $airframe )
    {
        $errors = [];
        try
        {
            $validationRules = $airframe->getValidationRules();
            $validator       = Validator::make( $request->all(), $validationRules );
            $validator->validate();

            $airframe->name        = $request->get( 'name' );
            $airframe->active      = $request->get( 'active' );
            $airframe->revision    = $request->get( 'revision' );
            $airframe->description = $request->get( 'description' ) ?? '';

            $attachments = [];
            if ( $request->files->all( 'files' ) )
            {
                $publicStorageDestination = $airframe->getPublicFolder();

                foreach ( $request->files->all( 'files' ) as $file )
                {
                    $filename = $file->getClientOriginalName();
                    if ( $airframe->willOverwriteExistingFile( $filename ) )
                    {
                        throw ValidationException::withMessages( [
                            'image' => "An attachment with the name {$filename} is already linked to an Aeroplane Type. Please save the attachment with a unique name or remove the existing attachment",
                        ] );
                    }
                    $attachment = new File();
                    $attachment->setEntity( $airframe );
                    $attachment->saveToStorage( $publicStorageDestination, $file, $filename );
                    $attachments[] = $attachment;
                    $attachments[] = $attachment;
                }
            }

            $seniorEngineerIds = [];
            if ( $request->has( 'cengineer_ids' ) )
            {
                $seniorEngineerIds = $request->get( 'cengineer_ids' );
                ///Probably excessive, but let's make sure they actually are Seniors...
                $seniorEngineers = User::whereIn( 'id', $seniorEngineerIds )
                                       ->where('role_id', User::SENIORENGINEER_ROLE)->get();
                $seniorEngineerIds = $seniorEngineers->pluck('id');
            }

            DB::transaction( function () use ( $airframe, $attachments, $seniorEngineerIds ) {
                $airframe->save();
                foreach ( $attachments as $attachment )
                {
                    $attachment->save();
                }
                $airframe->users()->sync( $seniorEngineerIds );
                $airframe->save();
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
            if ( str_contains( $message, 'airframes.name_revision_unique' ) )
            {
                $customErrors[ 'name' ] = 'An Airframe with this exact combination of name and revision number already exists.';
            } else
            {
                $customErrors[ 'name' ] = $message;
            }
        }

        catch ( \Exception $ex )
        {
            $message = $ex->getMessage();
            $customErrors[ 'general' ] = "Untrapped error. Please email you site administrator the following: " . $message;
        }

        return $errors;
    }

}
