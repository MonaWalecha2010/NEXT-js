<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Resources\AirframeResource;
use App\Http\Resources\AirframeWorkpackResource;
use App\Http\Resources\FileResource;
use App\Http\Resources\PanelCollection;
use App\Http\Resources\SchematicResource;
use App\Models\Airframe;
use App\Models\AirframeWorkpack;
use App\Models\AirframeWorkpackPanel;
use App\Models\File;
use App\Models\Panel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AirframeWorkpackController extends EntityController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkAuthorization( 'viewAny', AirframeWorkpack::class );
        $airframe_workpacks = AirframeWorkpack::with(['airframe.users','user','airframe_workpack_panels'] )->paginate();

        $data = [
            'airframeworkpacks' => AirframeWorkpackResource::collection( $airframe_workpacks ),
        ];
        return $this->render( $data,'AirframeWorkpacks/Index' );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->checkAuthorization( 'create', AirframeWorkpack::class );

        $data = [
            'airframes' => AirframeResource::collection( Airframe::with(['aeroplanes', 'users', 'schematics', 'panels'])->paginate() ),
        ];
        return $this->render( $data,'AirframeWorkpacks/Create' );
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
        $this->checkAuthorization( 'create', AirframeWorkpack::class );
        $customErrors = [];
        try
        {

            $validator = Validator::make( $request->all(), [
                'airframe_id' => 'required|int',
                'name'        => 'required|max:255',
            ] );

            $validator->validate();

            $airframeworkpack = new AirframeWorkpack();

            DB::transaction( function () use ( $airframeworkpack, $request ) {
                $airframeworkpack->airframe_id = $request->get( 'airframe_id' );
                $airframeworkpack->user_id     = Auth::user()->id;
                $airframeworkpack->name        = $request->get( 'name' );
                $airframeworkpack->description = $request->get( 'description' ) ?? '';
                $airframeworkpack->save();
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
            if ( str_contains( $message, 'aeroplanes.aeroplanes_name_unique' ) )
            {
                $customErrors[ 'name' ] = 'Please give this aeroplane a unique name';
            } else
            {
                $customErrors[ 'name' ] = $message;
            }
        }

        catch ( \Exception $ex )
        {
            return redirect()->back();
        }

        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );

            return redirect()->back();
        }

        return redirect()->route( 'airframeworkpacks.edit', [ 'airframeworkpack' => $airframeworkpack ] );

    }

    /**
     * @param AirframeWorkpack $airframeworkpack
     *
     * @return AirframeWorkpackResource
     */
    public function show( AirframeWorkpack $airframeworkpack )
    {
        $this->checkAuthorization( 'view', $airframeworkpack );
        $airframeworkpack->loadMissing(['airframe','airframe_workpack_panels']);
        $data = [
            'airframeworkpack' => AirframeWorkpackResource::make( $airframeworkpack ),
            'attachments' => FileResource::collection( $airframeworkpack->airframe->getAttachments() ),
        ];

        if ( $workpack_schematics = $airframeworkpack->airframe->schematics )
    {
        $data[ 'workpack_schematics' ] = SchematicResource::collection( $workpack_schematics );
        $schematic_ids =  $workpack_schematics->pluck('id')->toArray();
        // Punch out any Schematics that have Tasks associated with them, to avoid them displaying twice...
        $airframe_schematics = $airframeworkpack->airframe->schematics->except( $schematic_ids );
        $data['airframe_schematics'] = SchematicResource::collection( $airframe_schematics );
    }else{
        $data['airframe_schematics'] = SchematicResource::collection( $airframeworkpack->airframe->schematics );
    }

        return $this->render( $data,'AirframeWorkpacks/Show' );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AirframeWorkpack $airframeworkpack
     *
     * @return \Illuminate\Http\Response
     */
    public function edit( AirframeWorkpack $airframeworkpack )
    {
        $this->checkAuthorization( 'update', $airframeworkpack );
        $airframeworkpack->loadMissing(['airframe','airframe_workpack_panels']);

        $airframe = $airframeworkpack->airframe;
        $panels   = Panel::with( 'schematics' )
            ->where( 'airframe_id', '=', $airframe->id )
            ->orderBy( 'name' )
            ->get();

        $data = [
            'airframeworkpack' => AirframeWorkpackResource::make( $airframeworkpack ),
            'panels'         => PanelCollection::make( $panels ),
        ];
        return $this->render( $data,'AirframeWorkpacks/Edit' );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request     $request
     * @param \App\Models\AirframeWorkpack $airframeworkpack
     *
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, AirframeWorkpack $airframeworkpack )
    {
        $this->checkAuthorization( 'update', $airframeworkpack );

        $customErrors = [];
        try
        {
            $validationRules = $airframeworkpack->getValidationRules();
            $validator = Validator::make( $request->all(), $validationRules );
            $validator->validate();

            $airframeWorkpackPanels = [];
            if( $request->filled( 'panels' ) ){
                $panel_ids = array_unique( $request->get( 'panels' ) );
                foreach ( $panel_ids as $panel_id )
                {
                    $airframeWorkpackPanel = new AirframeWorkpackPanel();
                    $airframeWorkpackPanel->airframe_workpack_id = $airframeworkpack->id;
                    $airframeWorkpackPanel->panel_id = $panel_id;
                    $airframeWorkpackPanels[] = $airframeWorkpackPanel;
                }
            }

            //Attachments....
            $attachments = [];
            if( $request->filled('files') ) {
                $storageDestination = $airframeworkpack->getStorageFolder();
                $publicStorageDestination = $airframeworkpack->getPublicFolder();

                foreach ( $request->files->all('files') as $file  ) {
                    $fileObject = $file['file'];
                    $filename    = $fileObject->getClientOriginalName();
                    if( $airframeworkpack->willOverwriteExistingFile( $filename ) ) {
                        throw ValidationException::withMessages( [
                            'image' => "An attachment with the name {$filename} is already linked to the Workpack Template. Please save the attachment with a unique name or remove the existing attachment",
                        ] );
                    }

                    $attachment = new File();
                    $attachment->setEntity( $airframeworkpack );
                    $attachment->saveToStorage( $publicStorageDestination, $fileObject, $filename );
                    $attachments[] = $attachment;
                }

            }

            //Update the model from the request...
            $airframeworkpack->airframe_id = $request->get( 'airframe_id' );
            $airframeworkpack->user_id     = Auth::user()->id;
            $airframeworkpack->name        = $request->get( 'name' );
            $airframeworkpack->description = $request->get( 'description' ) ?? '';

            DB::transaction( function () use ( $airframeworkpack, $airframeWorkpackPanels, $attachments ) {
                foreach ( $attachments as $attachment){
                    $attachment->save();
                }
                $airframeworkpack->airframe_workpack_panels->each->delete();
                $airframeworkpack->airframe_workpack_panels()->saveMany( $airframeWorkpackPanels );
                $airframeworkpack->save();
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
            if ( str_contains( $message, 'aeroplanes.aeroplanes_name_unique' ) )
            {
                $customErrors[ 'name' ] = 'Please give this aeroplane a unique name';
            } else
            {
                $customErrors[ 'name' ] = $message;
            }
        }

        catch ( \Exception $ex )
        {
            return redirect()->back();
        }

        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );

            return redirect()->back();
        }

        return redirect()->route( 'airframeworkpacks.index' );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AirframeWorkpack $airframeworkpack
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy( AirframeWorkpack $airframeworkpack )
    {
        $customErrors = [];
        $this->checkAuthorization( 'delete', $airframeworkpack );
        try
        {


            DB::transaction( function () use ( $airframeworkpack ) {
                $airframeworkpack->airframe_workpack_panels()->delete();
                $airframeworkpack->delete();
            } );

        }
        catch ( \Exception $ex )
        {
            $action                    = "Class: " . __CLASS__ . " - Method: " . __METHOD__;
            $customErrors[ 'general' ] = Helper::logError( $ex, $ex->getMessage(), $action, 'critical' );
        }

        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );

            return redirect()->back();
        }

        return redirect()->route( 'airframeworkpacks.index' );
    }
}
