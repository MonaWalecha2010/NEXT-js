<?php

namespace App\Http\Controllers;

use App\Http\Resources\AeroplaneResource;
use App\Http\Resources\AirframeResource;
use App\Http\Resources\AirframeWorkpackResource;
use App\Http\Resources\OwnerResource;
use App\Models\Aeroplane;
use App\Models\Airframe;
use App\Models\AirframeWorkpack;
use App\Models\Owner;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AeroplaneController extends EntityController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkAuthorization( 'viewAny', Aeroplane::class );

        $data = [
            'aeroplanes' => AeroplaneResource::collection( Aeroplane::with(['owner', 'airframe', 'workpacks'])->paginate() ),
        ];
        return $this->render( $data, 'Aeroplanes/Index' );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->checkAuthorization( 'create', Aeroplane::class );

        $airframes = AirframeResource::collection( Airframe::with(['users'])->get() );
        $owners = OwnerResource::collection( Owner::get() );
        $data = [
            'airframes' => $airframes,
            'owners' => $owners,
        ];
        return $this->render( $data, 'Aeroplanes/Create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->checkAuthorization( 'create', Aeroplane::class );

        $aeroplane = new Aeroplane();
        $customErrors = $this->saveRequestToModel( $request, $aeroplane );
        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );
            return redirect()->back();
        }

        return redirect()->route( 'aeroplanes.show', $aeroplane );

    }

    /**
     * Display the specified resource.
     *
     * @param  Aeroplane  $aeroplane
     * @return \Illuminate\Http\Response
     */
    public function show( Aeroplane $aeroplane )
    {
        $this->checkAuthorization( 'view', $aeroplane );

        $data = [
            'aeroplane' => AeroplaneResource::make( $aeroplane->loadMissing(['airframe', 'owner', 'workpacks']) ),
        ];
        return $this->render( $data, 'Aeroplanes/Show' );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Aeroplane  $aeroplane
     * @return \Illuminate\Http\Response
     */
    public function edit( Aeroplane $aeroplane )
    {
        $this->checkAuthorization( 'update', $aeroplane );

        $data = [
            'aeroplane' => AeroplaneResource::make( $aeroplane ),
            'airframes' => AirframeResource::collection( Airframe::with(['users'])->get() ),
            'owners'    => OwnerResource::collection( Owner::get() )
        ];
        return $this->render( $data, 'Aeroplanes/Edit' );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Aeroplane  $airframe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Aeroplane $aeroplane )
    {
        $this->checkAuthorization( 'update', $aeroplane );

        $customErrors = $this->saveRequestToModel( $request, $aeroplane );
        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );
            return redirect()->back();
        }

        return redirect()->route( 'aeroplanes.show', $aeroplane );

    }

    /**
     * @param Aeroplane $aeroplane
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function destroy( Aeroplane $aeroplane)
    {
        $this->checkAuthorization( 'delete', $aeroplane );

        if( !$aeroplane->canDelete() ){
            throw ValidationException::withMessages([
                'general' => "This Aeroplane cannot be deleted as it has a Workpack History. Only Aeroplanes that have never had any Tasks performed on them can be deleted.",
            ]);
            return redirect()->back();
        }
        $aeroplane->delete();

        return redirect()->route( 'aeroplanes.index' );

    }

    /**
     * @param Request   $request
     * @param Aeroplane $aeroplane
     *
     * @return void
     */
    public function workpacktemplates(  Request $request, Aeroplane $aeroplane) {
        $airframe_workpacks = AirframeWorkpack::with( ['airframe', 'airframe_workpack_panels'] )->where('airframe_id', $aeroplane->airframe->id )->get();
        $data = [
            'airframe_workpacks' => AirframeWorkpackResource::collection( $airframe_workpacks )
        ];

        return $this->render( $data, null, 'json' );
    }


    /**
     * @param Request  $request
     * @param Aeroplane $aeroplane
     *
     * @return array
     */
    protected function saveRequestToModel( Request $request, Aeroplane $aeroplane )
    {
        $errors = [];
        try
        {
            $validationRules = $aeroplane->getValidationRules();
            $validator = Validator::make( $request->all(), $validationRules );
            $validator->validate();

            $aeroplane->name        = $request->get( 'name' );
            $aeroplane->airframe_id    = (int) $request->get( 'airframe_id' );
            $aeroplane->owner_id    = (int) $request->get( 'owner_id' );
            $aeroplane->description = $request->get( 'description' ) ?? '';
            $aeroplane->active        = ( $request->get( 'active' ) ) ?? 0;

            if( $aeroplane->airframe_id !== $aeroplane->getOriginal('airframe_id') ){
                if( !$aeroplane->canChangeAirframe() ){
                    throw ValidationException::withMessages([
                        'airframe_id' => "The Aeroplane Type cannot be changed as this aeroplane has maintenance history. Only Aeroplanes that have never had any Tasks performed on them can be changed.",
                    ]);
                }
            }

            $aeroplane->save();

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
            if ( str_contains( $message, 'aeroplanes.name_revision_unique' ) )
            {
                $customErrors[ 'name' ] = 'An Aeroplane with this exact name already exists.';
            } else
            {
                $customErrors[ 'name' ] = $message;
            }
        }

        catch ( \Exception $ex )
        {
            $message                   = $ex->getMessage();
            $customErrors[ 'general' ] = "Untrapped error. Please email you site administrator the following: " . $message;
        }

        return $errors;
    }
}
