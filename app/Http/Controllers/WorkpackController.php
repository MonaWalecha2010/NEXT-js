<?php

namespace App\Http\Controllers;

use App\Events\WorkpackChanged;
use App\Helpers\Helper;
use App\Http\Resources\AeroplaneResource;
use App\Http\Resources\AirframeResource;
use App\Http\Resources\AirframeWorkpackResource;
use App\Http\Resources\FileResource;
use App\Http\Resources\PanelCollection;
use App\Http\Resources\SchematicResource;
use App\Http\Resources\WorkpackPanelResource;
use App\Http\Resources\WorkpackResource;
use App\Models\Aeroplane;
use App\Models\Airframe;
use App\Models\AirframeWorkpack;
use App\Models\Panel;
use App\Models\Schematic;
use App\Models\Workpack;
use App\Models\WorkpackPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class WorkpackController extends EntityController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkAuthorization( 'viewAny', Workpack::class );

        //Grrh. Why don't redirects work!!!
        //        return redirect()->action([ WorkpackController::class, 'workerindex' ]);
        //        return redirect()->action( $this, 'adminindex');

        $data = [];

        $user = Auth::user();

        if ( $user->isAdministrator() )
        {
            $workpacks_inactive            = WorkpackResource::collection( Workpack::with( [ 'aeroplane' ] )
                                                                                   ->where( 'active', false )
                                                                                   ->paginate( 100 )
            );
            $workpacks_completed           = WorkpackResource::collection( Workpack::with( [ 'aeroplane' ] )
                                                                                   ->where( 'completed', true )
                                                                                   ->paginate( 100 ) );
            $workpacks_active              = WorkpackResource::collection( Workpack::with( [ 'aeroplane' ] )
                                                                                   ->where( 'completed', false )
                                                                                   ->where( 'active', true )
                                                                                   ->paginate()
            );
            $data[ 'workpacks_active' ]    = $workpacks_active;
            $data[ 'workpacks_inactive' ]  = $workpacks_inactive;
            $data[ 'workpacks_completed' ] = $workpacks_completed;

            return $this->render( $data, 'Workpacks/Index' );

        }

        if ( $user->isPlaneWorker() )
        {
            // @TODO Can we find some way of cleaning this up? It smells rather...
            $userWorkpacks       = Workpack::getUsersCurrentWorkpackAndPanels( $user )->paginate( 100 );
            $userWorkpackIds     = $userWorkpacks->pluck( 'id' )->toArray();
            $excludedWorkpackIds = $userWorkpackIds;
            $activeWorkpacks     = Workpack::getActiveWorkpacks( $excludedWorkpackIds )->paginate( 100 );
            $activeWorkpackIds   = $activeWorkpacks->pluck( 'id' )->toArray();
            $excludedWorkpackIds = array_merge( $userWorkpackIds, $activeWorkpackIds );
            $availableWorkpacks  = Workpack::getAvailableWorkpacks()->paginate( 100 );

            $data[ 'workpacks_mine' ]      = WorkpackResource::collection( $userWorkpacks );
            $data[ 'workpacks_active' ]    = WorkpackResource::collection( $activeWorkpacks );
            $data[ 'workpacks_available' ] = WorkpackResource::collection( $availableWorkpacks );

            return $this->render( $data, 'Workpacks/IndexWorker' );

        }

    }


    /**
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function workerindex()
    {
        $user = Auth::user();
    }

    /**
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function adminindex()
    {
        $user                = Auth::user();
        $workpacks_active    = WorkpackResource::collection( Workpack::with( [ 'aeroplane' ] )
                                                                     ->where( 'completed', false )
                                                                     ->where( 'active', true )
                                                                     ->paginate()
        );
        $workpacks_completed = WorkpackResource::collection( Workpack::with( [ 'aeroplane' ] )
                                                                     ->where( 'completed', true )
                                                                     ->paginate() );

        $data = [
            'workpacks_active'    => $workpacks_active,
            'workpacks_completed' => $workpacks_completed,
        ];

        if ( $user->isAdministrator() )
        {
            $workpacks_inactive           = WorkpackResource::collection( Workpack::with( [ 'aeroplane' ] )
                                                                                  ->where( 'active', false )
                                                                                  ->paginate()
            );
            $data[ 'workpacks_inactive' ] = $workpacks_inactive;
        }

        return $this->render( $data, 'Workpacks/Index' );
    }


    /**
     * @param Request $request
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Inertia\Response|string|void
     */
    public function create( Request $request )
    {
        $this->checkAuthorization( 'create', Workpack::class );
        $data = [
            'aeroplanes'         => AeroplaneResource::collection( Aeroplane::paginate( 1000 ) ),
            'airframe_workpacks' => AirframeWorkpackResource::collection( AirframeWorkpack::with( [
                'airframe.users',
                'airframe_workpack_panels',
            ] )->paginate( 1000 ) ),
        ];
        if ( $request->has( 'aeroplane_id' ) )
        {
            $data[ 'aeroplane_id' ] = (int) $request->get( 'aeroplane_id' );
        }

        return $this->render( $data, 'Workpacks/Create' );
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
        $this->checkAuthorization( 'create', Workpack::class );
        $customErrors = [];
        try
        {
            $workpack        = new Workpack();
            $validationRules = $workpack->getValidationRules();
            $validator       = Validator::make( $request->all(), $validationRules );
            $validator->validate();
            $workpack->name         = $request->get( 'name' );
            $workpack->description  = $request->get( 'description' ) ?? '';
            $workpack->active       = $request->get( 'active' );
            $workpack->user_id      = Auth::user()->id;
            $aeroplane = Aeroplane::find( $request->get( 'aeroplane_id' ) );
            $workpack->aeroplane_id = $aeroplane->id;
            $workpack->airframe_id  = $aeroplane->airframe->id;

            $airframe_workpack = null;
            if ( $request->filled( 'airframe_workpack_id' ) )
            {
                $airframe_workpack = AirframeWorkpack::find( $request->get( 'airframe_workpack_id' ) );
            }

            DB::transaction( function () use ( $workpack, $airframe_workpack ) {
                $workpack->save();
                if ( $airframe_workpack )
                {
                    $workpack->copyPanels( $airframe_workpack, $workpack->aeroplane );
//                    $workpack->copyFiles( $airframe_workpack, $workpack );
                }
                WorkpackChanged::dispatch( $workpack );

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
            if ( str_contains( $message, 'workpacks.workpacks_aeroplane_id_name_unique' ) )
            {
                $customErrors[ 'name' ] = 'Please give a unique name for this Aeroplane Workpack';
            } else
            {
                $customErrors[ 'general' ] = $message;
            }
        }

        catch ( \Exception $ex )
        {
            $message                   = $ex->getMessage();
            $customErrors[ 'general' ] = $message;
        }

        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );

            return redirect()->back();
        }

        return redirect()->route( 'workpacks.edit', [ 'workpack' => $workpack ] );
    }

    /**
     * Display the specified resource.
     *
     * @param Workpack $workpack
     *
     * @return \Illuminate\Http\Response
     */
    public function show( Workpack $workpack )
    {
        $this->checkAuthorization( 'view', $workpack );
        $airframe = Airframe::find( $workpack->aeroplane->airframe_id );

        $data = [
            'workpack'        => WorkpackResource::make( $workpack->loadMissing( [ 'aeroplane' ] ) ),
            'workpack_panels' => WorkpackPanelResource::collection(
                WorkpackPanel::with( [
                    'panel' => function ( $query ) {
                        $query->orderBy( 'name' );
                    },
                    'workpack_panel_action',
                    'workpack_panel_step',
                ] )->where( [ 'workpack_id' => $workpack->id ] )
                             ->paginate( 1000 )
            ),
            'airframe'        => AirframeResource::make( $airframe ),
            'attachments'     => FileResource::collection( $airframe->getAttachments() ),
        ];

        if ( $workpack_schematics = $workpack->getSchematics() )
        {
            $data[ 'workpack_schematics' ] = SchematicResource::collection( $workpack_schematics );
            $schematic_ids                 = $workpack_schematics->pluck( 'id' )->toArray();
            // Punch out any Schematics that have Tasks associated with them, to avoid them displaying twice...
            $airframe_schematics           = $airframe->schematics->except( $schematic_ids );
            $data[ 'airframe_schematics' ] = SchematicResource::collection( $airframe_schematics );
        } else
        {
            $data[ 'airframe_schematics' ] = SchematicResource::collection( $airframe->schematics );
        }

        return $this->render( $data, 'Workpacks/Show' );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Workpack $workpack
     *
     * @return \Illuminate\Http\Response
     */
    public function edit( Workpack $workpack )
    {
        $this->checkAuthorization( 'update', $workpack );
        $this->checkAuthorization( 'isActive', $workpack );

        $airframe = $workpack->aeroplane->airframe;
        $panels   = Panel::with( 'schematics' )
                         ->where( 'airframe_id', '=', $airframe->id )
                         ->orderBy( 'name' )
                         ->get();

        $data = [
            'workpack'       => WorkpackResource::make( $workpack ),
            'workpackpanels' => WorkpackPanelResource::collection( $workpack->workpack_panels ),
            'panels'         => PanelCollection::make( $panels ),
        ];

        return $this->render( $data, 'Workpacks/Edit' );
    }

    /**
     * @param Request  $request
     * @param Workpack $workpack
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Inertia\Response|string
     */
    public function editpanels( Request $request, Workpack $workpack, Schematic $schematic = null )
    {
        $this->checkAuthorization( 'updatePanels', $workpack );
        $this->checkAuthorization( 'isActive', $workpack );

        $airframe = $workpack->aeroplane->airframe;
        $panels   = Panel::with( 'schematics' )->where( 'airframe_id', '=', $airframe->id )->get();
        $data     = [
            'workpack'       => WorkpackResource::make( $workpack ),
            'workpackpanels' => WorkpackPanelResource::collection( $workpack->workpack_panels ),
            'panels'         => PanelCollection::make( $panels ),
            'airframe'       => AirframeResource::make( $airframe->loadMissing( [ 'users' ] ) ),
            'schematic'      => null,
        ];

        if ( ! is_null( $schematic ) )
        {
            $data[ 'schematic' ] = SchematicResource::make( $schematic );
        }

        return $this->render( $data, 'Workpacks/EditPanels' );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Workpack                 $workpack
     *
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, Workpack $workpack )
    {
        $this->checkAuthorization( 'update', $workpack );
        $this->checkAuthorization( 'isActive', $workpack );

        $customErrors = [];
        try
        {
            $validationRules = $workpack->getValidationRules();
            $validator       = Validator::make( $request->all(), $validationRules );

            $validator->validate();

            $workpack->user_id     = Auth::user()->id;
            $workpack->name        = $request->get( 'name' );
            $workpack->active      = $request->get( 'active' );
            $workpack->description = $request->get( 'description' ) ?? '';

            $this->saveRequestToModel( $request, $workpack );

        }
        catch ( Exception $ex )
        {
            $message = $ex->getMessage();
        }


        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );

            return redirect()->back();
        }


        return redirect()->route( 'workpacks.show', $workpack );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Workpack                 $workpack
     *
     * @return \Illuminate\Http\Response
     */
    public function updatepanels( Request $request, Workpack $workpack, Schematic $schematic = null )
    {
        $this->checkAuthorization( 'updatePanels', $workpack );
        $this->checkAuthorization( 'isActive', $workpack );

        $customErrors = [];
        try
        {
            $validationRules = $workpack->getValidationRules();
            $validator       = Validator::make( $request->all(), $validationRules );

            $validator->validate();

            $workpack->user_id = Auth::user()->id;

            $this->saveRequestToModel( $request, $workpack );

        }
        catch ( Exception $ex )
        {
            $message = $ex->getMessage();
        }


        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );

            return redirect()->back();
        }

        if( !is_null( $schematic ) ){
            return redirect()->route( 'workpacks.schematic', [ 'workpack' => $workpack, 'schematic' => $schematic ] );
        }
        return redirect()->route( 'workpacks.show', [ 'workpack' => $workpack] );
    }

    /**
     * @param Workpack $workpack
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function destroy( Workpack $workpack )
    {
        $customErrors = [];
        $this->checkAuthorization( 'delete', $workpack );
        try
        {
            if ( ! $workpack->canDelete() )
            {
                throw ValidationException::withMessages( [
                    'general' => "This Workpack cannot be deleted because it has Tasks performed on it",
                ] );

                return redirect()->back();
            }

            DB::transaction( function () use ( $workpack ) {
                $workpack->workpack_panels()->delete();
                $workpack->delete();
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

        return redirect()->route( 'workpacks.index' );
    }


    /**
     * @param Workpack  $workpack
     * @param Schematic $schematic
     *
     * @return void
     */
    public function schematic( Workpack $workpack, Schematic $schematic )
    {
        $this->checkAuthorization( 'workpackSchematic', [ $workpack, $schematic ] );
        $schematic->embedSvg();

        $data = [
            'workpack'              => WorkpackResource::make( $workpack ),
            'aeroplane'             => $workpack->aeroplane,
            'airframe'              => $workpack->aeroplane->airframe,
            'schematic'             => SchematicResource::make( $schematic ),
            'schematic_panels'      => $workpack->getSchematicPanelsExcludingUser( $schematic, Auth::user() )->toArray(),
            'schematic_panels_user' => $workpack->getSchematicPanelsForUser( $schematic, Auth::user() )->toArray(),
        ];

        return $this->render( $data, 'Workpacks/Schematic' );
    }

    protected function saveRequestToModel( Request $request, Workpack $workpack )
    {
        //Now update the panels...
        $workPackPanels          = [];
        $currentWorkpackPanels   = $workpack->workpack_panels()->with( 'panel' )->get();
        $currentWorkpackPanelIds = $currentWorkpackPanels->pluck( 'panel_id' );

        if ( $request->has( 'panels' ) )
        {
            $panel_ids = array_unique( $request->get( 'panels' ) );

            // Is the panel_id not already in the Workpack? If so add
            foreach ( $panel_ids as $panel_id )
            {
                if ( ! in_array( $panel_id, $currentWorkpackPanelIds->toArray() ) )
                {
                    $workpackPanel               = new WorkpackPanel();
                    $workpackPanel->workpack_id  = $workpack->id;
                    $workpackPanel->aeroplane_id = $workpack->aeroplane_id;
                    $workpackPanel->panel_id     = $panel_id;
                    $workpackPanel->workpack()->associate( $workpack );
                    $workPackPanels[] = $workpackPanel;
                }
            }

            // Is the panel id  from the current Workpack missing? If so delete if allowable?
            foreach ( $currentWorkpackPanels as $currentWorkpackPanel )
            {
                if ( ! in_array( $currentWorkpackPanel->panel_id, $panel_ids ) )
                {
                    if ( $currentWorkpackPanel->canPanelBeDeletedFromWorkpack() )
                    {
                        $currentWorkpackPanel->delete();
                    } else
                    {
                        throw ValidationException::withMessages( [
                            'general' => $currentWorkpackPanel->panel->name . ' could not be removed from this Work Pack as work has already been started on it.',
                        ] );
                    }
                }
            }
        }

        DB::transaction( function () use ( $workpack, $workPackPanels ) {
            $workpack->save();
            foreach ( $workPackPanels as $workpackPanel )
            {
                if( $workpackPanel->canPanelBeAdddedToWorkpack() ){
                    $workpackPanel->save();
                }
            }
            WorkpackChanged::dispatch( $workpack );
        } );
    }


    /**
     * @param Request  $request
     * @param Workpack $workpack
     *
     * @return void
     */
    public function complete( Request $request, Workpack $workpack )
    {
        $this->checkAuthorization( 'complete', [ $workpack ] );
        $user         = Auth::user();
        $customErrors = [];

        $validationRules = [
            'user_code' => 'required',
        ];
        $validator       = Validator::make( $request->all(), $validationRules );
        $validator->validate();

        try
        {
            //Check that the authorisation code entered is correct
            if ( $user->code !== $request->get( 'user_code' ) )
            {
                throw ValidationException::withMessages( [
                    'general' => "User does not have permission to perform this task.",
                ] );
            }
            if ( count( $customErrors ) == 0 )
            {
                DB::transaction( function () use ( $workpack ) {
                    $workpack->setCompleted();
                } );

            }
        }
        catch ( ValidationException $ex )
        {
            $errors = $ex->errors();
            foreach ( $errors as $key => $error )
            {
                $customErrors[ $key ] = $error;
            }
        }

        catch ( \Exception $ex )
        {
            $customErrors[ 'general' ] = 'Unknown error occured. Please try again. Exception Message' . $ex->getMessage() ;
            Log::error( $ex->getMessage() );
        }

        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );
            return redirect()->back();
        }

        return redirect()->route( 'workpacks.index' );

    }

    /**
     * @param Request  $request
     * @param Workpack $workpack
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function report( Request $request, Workpack $workpack )
    {
        $this->checkAuthorization( 'report', [ $workpack ] );
        $data = $workpack->getWorkpackReportData();
        return view( 'reports.workpack', $data );

    }

    /**
     * @param Request  $request
     * @param Workpack $workpack
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function reportpdf( Request $request, Workpack $workpack )
    {
        $this->checkAuthorization( 'report', [ $workpack ] );

        return $workpack->getReportPdf( 'download' );
    }

}
