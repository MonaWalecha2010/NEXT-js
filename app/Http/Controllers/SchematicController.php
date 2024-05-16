<?php

namespace App\Http\Controllers;

use App\Http\Resources\AirframeResource;
use App\Http\Resources\SchematicResource;
use App\Models\Airframe;
use App\Models\Schematic;
use App\Models\SchematicPanel;
use App\Models\Panel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class SchematicController extends EntityController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkAuthorization( 'viewAny', Schematic::class );

        $data = [
            'can'        => [
                'show_schematics' => Auth::user()->can( 'viewAny', Schematic::class ),
            ],
            'schematics' => SchematicResource::collection( Schematic::with(['airframe.users','panels',])->paginate() ),
        ];

        return $this->render( $data, 'Schematics/Index' );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        $this->checkAuthorization( 'create', Schematic::class );
        $airframes   = AirframeResource::collection( Airframe::with('users')->orderBy('name', 'asc')->orderBy('revision', 'asc')->paginate( 10000 ) );
        $airframe_id = $request->get( 'airframe_id' ) ?? null;
        $data        = [
            'airframes'   => $airframes,
            'airframe_id' => $airframe_id,
        ];

        return $this->render( $data, 'Schematics/Create' );
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
        $path         = null;
        $schematic    = null;
        $customErrors = [];
        try
        {

            $this->checkAuthorization( 'create', Schematic::class );

            $validator = Validator::make( $request->all(), [
                'airframe_id' => 'required',
                'name'        => 'required|max:255',
                'image'       => 'file|required|mimetypes:image/svg+xml',
            ] );
            $validator->validate();
            $path = null;
            $cleanupUploadedFile = true;
            $uploadedSvg = $request->file( 'image' );
            $filename    = $uploadedSvg->getClientOriginalName();

            $airframe = Airframe::find( $request->get( 'airframe_id' ) );
            /* @var $airframe Airframe */
            $publicStorageDestination = $airframe->getPublicFolder();

            if ( Storage::exists( $publicStorageDestination . "/" . $filename ) )
            {
                $cleanupUploadedFile = false;
                throw ValidationException::withMessages( [
                    'image' => 'A schematic with this exact filename already exists for this Airframe. Please save the schematic image with a unique name',
                ] );
            }

            $schematic              = new Schematic();
            $schematic->name        = $request->get( 'name' );
            $schematic->description = $request->get( 'description' ) ?? '';
            $schematic->user_id     = Auth::user()->id;
            $schematic->airframe_id = $request->get( 'airframe_id' );
            $schematic->image = $filename;

            $panels = [];
            if ( $request->has( 'panels' ) )
            {
                $airframe_id = $request->get( 'airframe_id' );
                $panel_names = explode( ',', $request->get( 'panels' ) );
                //Let's get the panels into the DB in a sensible order...
                sort( $panel_names );
                foreach ( $panel_names as $panel_name )
                {
                    //Does this panel already exist?
                    $panel = Panel::where( 'name', '=', $panel_name )
                                  ->where( 'airframe_id', '=', $airframe_id )
                                  ->first();
                    if ( ! $panel )
                    {
                        $panel              = new Panel();
                        $panel->airframe_id = $airframe_id;
                        $panel->name        = $panel_name;
                    }
                    $panels[] = $panel;
                }
            }

            DB::transaction( function () use ( $schematic, $request, $panels ) {
                $schematic->save();
                //Now create the corresponding Airframe Panels. These might already exist so we need to cope with potential exceptions being returned
                if ( count($panels) > 0 )
                {
                    foreach ( $panels as $panel )
                    {
                        $panel->save();
                        $schematicPanel               = new SchematicPanel();
                        $schematicPanel->schematic_id = $schematic->id;
                        $schematicPanel->panel_id     = $panel->id;
                        $schematicPanel->save();
                    }
                }
                $schematic->uploadFile( $request, 'image');
                $schematic->save();

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
            if ( str_contains( $message, 'schematics.schematics_airframe_id_name_unique' ) )
            {
                $customErrors[ 'name' ] = 'Please give this schematic for this airframe a unique name';
            } else
            {
                $customErrors[ 'name' ] = 'Please check that the schematic has some panels in it';
            }
        }

        catch ( \Exception $ex )
        {
            $customErrors[ 'general' ] = 'Unknown error occured. PLease try again';
        }

        if ( count( $customErrors ) > 0 )
        {
            if ( $path && $cleanupUploadedFile )
            {
                Storage::delete( $path );
            }
            throw ValidationException::withMessages( $customErrors );

            return redirect()->back();
        }

        return redirect()->route( 'schematics.show', $schematic );
    }

    /**
     * Display the specified resource.
     *
     * @param Schematic $schematic
     *
     * @return \Illuminate\Http\Response
     */
    public function show( Schematic $schematic )
    {
        $this->checkAuthorization( 'view', $schematic );
        $schematic->embedSvg();
        $schematic->load( ['panels' => function ($query) {
                $query->orderBy('name');
            },
           'airframe.users']);
        $data = [
            'schematic' => SchematicResource::make( $schematic ),
        ];

        return $this->render( $data, 'Schematics/Show' );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Schematic $schematic
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy( Schematic $schematic )
    {
        $customErrors = [];
        $this->checkAuthorization( 'delete', $schematic );
        if( !$schematic->canDelete() ){
            throw ValidationException::withMessages([
                'general' => "This Schematic cannot be deleted as it contains panels that are in use one or more Work Packs.",
            ]);
            return redirect()->back();
        }
        try
        {
            $schematic->panels()->detach();
            $schematic->delete();
            Storage::delete( $schematic->image );
        }
        catch ( Exception $ex )
        {
            $customErrors[ 'general' ] = 'Unknown error occured. Please try again';
        }

        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );
            return redirect()->back();
        }

        return redirect()->route( 'schematics.index' );
    }

}
