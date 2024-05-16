<?php

namespace App\Http\Controllers;

use App\Http\Resources\AeroplaneResource;
use App\Http\Resources\AirframeResource;
use App\Http\Resources\SchematicResource;
use App\Http\Resources\WorkpackPanelResource;
use App\Http\Resources\WorkpackPanelTaskResource;
use App\Http\Resources\WorkpackResource;
use App\Models\Panel;
use App\Models\Schematic;
use App\Models\Workpack;
use App\Models\WorkpackPanel;
use App\Models\WorkpackPanelTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkpackPanelController extends EntityController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkAuthorization( 'viewAny', WorkpackPanel::class );
        $panels       = WorkpackPanelResource::collection( WorkpackPanel::paginate() );
        $panel        = WorkpackPanel::find( 1 );
        $panel_action = $panel->workpack_panel_action;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function storebyName( Request $request, Workpack $workpack, Schematic $schematic = null )
    {
        $this->checkAuthorization( 'create', WorkpackPanel::class );
        $this->checkAuthorization( 'updatePanels', $workpack );

        if ( $request->has( 'panel_name' ) )
        {
            $panel_name = $request->get( 'panel_name' );
            $panel      = Panel::where( 'name', $panel_name )
                               ->where( 'airframe_id', $workpack->aeroplane->airframe->id )
                               ->first();
            if ( $panel )
            {
                $workpackPanel = new WorkpackPanel();
                $workpackPanel->panel()->associate( $panel );
                $workpackPanel->workpack()->associate( $workpack );
                $workpackPanel->aeroplane()->associate( $workpack->aeroplane );
                if( $workpackPanel->canPanelBeAdddedToWorkpack() ){
                    $workpackPanel->save();
                }

                $routeData = [
                    'workpack'  => $workpack,
                    'schematic' => $schematic,
                ];

                return redirect()->route( 'workpacks.schematic', $routeData );
            }

        }
    }

    /**
     * @param WorkpackPanel $workpackPanel
     *
     * @return void
     */
    public function show( WorkpackPanel $workpackpanel )
    {
        $this->checkAuthorization( 'view', $workpackpanel );
        $workpack  = $workpackpanel->workpack;
        $aeroplane = $workpack->aeroplane;
        $airframe  = $aeroplane->airframe;
        $tasks     = WorkpackPanelTask::where( 'workpack_panel_id', $workpackpanel->id )->get();

        $data = [
            'workpack_panel' => WorkpackPanelResource::make( $workpackpanel->loadMissing( [ 'panel', 'user' ] ) ),
            'workpack'       => WorkpackResource::make( $workpack ),
            'aeropane'       => AeroplaneResource::make( $aeroplane ),
            'airframe'       => AirframeResource::make( $airframe ),
            'tasks'          => WorkpackPanelTaskResource::collection( $tasks->loadMissing( 'user', 'workpack_panel_task_notes' ) ),
        ];

        return $this->render( $data, 'WorkpackPanels/Show' );
    }


    /**
     * @param Workpack      $workpack
     * @param Schematic     $schematic
     * @param WorkpackPanel $workpackpanel
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Inertia\Response|string
     */
    public function schematic( Workpack $workpack, Schematic $schematic, WorkpackPanel $workpackpanel )
    {
        $this->checkAuthorization( 'view', $workpackpanel );
        $aeroplane = $workpack->aeroplane;
        $airframe  = $aeroplane->airframe;
        $tasks     = WorkpackPanelTask::where( 'workpack_panel_id', $workpackpanel->id )->get();

        $data = [
            'workpack_panel' => WorkpackPanelResource::make( $workpackpanel->loadMissing( [ 'panel', 'user' ] ) ),
            'schematic'      => SchematicResource::make( $schematic ),
            'workpack'       => WorkpackResource::make( $workpack ),
            'aeropane'       => AeroplaneResource::make( $aeroplane ),
            'airframe'       => AirframeResource::make( $airframe ),
            'tasks'          => WorkpackPanelTaskResource::collection( $tasks->loadMissing( 'user', 'workpack_panel_task_notes' ) ),
        ];

        return $this->render( $data, 'Workpacks/Panel' );
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
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {
        //
    }
}
