<?php

namespace App\Http\Controllers;

use App\Events\WorkpackPanelTaskCreated;
use App\Models\Schematic;
use App\Models\Workpack;
use App\Models\WorkpackPanel;
use App\Models\WorkpackPanelTask;
use App\Models\WorkpackPanelTaskNote;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class WorkpackPanelTaskController extends EntityController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $this->checkAuthorization( 'create', WorkpackPanelTask::class );
        $customErrors  = [];
        $user          = Auth::user();
        $workpackPanel = null;
        try
        {
            $workpackPanel = WorkpackPanel::find( $request->get( 'workpack_panel_id' ) );
            $taskRequiresCode = $workpackPanel->doesTaskRequireACode();

            $validationRules = [
                'workpack_panel_id' => 'required|int',
                'user_code'         => 'required',
            ];

            if( !$taskRequiresCode ){
                unset( $validationRules['user_code'] );
            }

            $validator = Validator::make( $request->all(), $validationRules );
            $validator->validate();

            //Check that the authorisation code entered is correct
            if (  $taskRequiresCode && ($user->code !== $request->get( 'user_code' ) ) )
            {
                throw ValidationException::withMessages( [
                    'general' => "User does not have permission to perform this task.",
                ] );
            }

            if ( count( $customErrors ) == 0 )
            {
                $workpackPanel = WorkpackPanel::find( $request->get( 'workpack_panel_id' ) );
                $this->saveRequestToModel( $workpackPanel, $request );
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

        catch ( QueryException $ex )
        {
            $message                = $ex->getMessage();
            $customErrors[ 'name' ] = $message;
        }

        catch ( \Exception $ex )
        {
            return redirect()->back();
        }

        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );

            return redirect()->route( 'workpackpanels.show', $workpackPanel->id );
        }
        $schematic_id = ( $request->has( 'schematic_id' ) ) ? $request->get( 'schematic_id' ) : null;
        if ( $schematic_id )
        {
            return redirect()->route( 'workpackpanels.schematic', [ 'workpack'      => $workpackPanel->workpack->id,
                                                                    'schematic'     => $schematic_id,
                                                                    'workpackpanel' => $workpackPanel->id,
            ] );
        } else
        {
            return redirect()->route( 'workpackpanels.show', $workpackPanel->id );
        }
    }

    /**
     * @param Workpack  $workpack
     * @param Schematic $schematic
     * @param Request   $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function storemultiple( Workpack $workpack, Schematic $schematic, Request $request )
    {
        $this->checkAuthorization( 'multicreate', WorkpackPanelTask::class );
        $customErrors = [];
        $user         = Auth::user();
        try
        {
            $validator = Validator::make( $request->all(), [
                'mass_update_panels' => 'required|array',
                'user_code'          => 'required',
            ] );
            $validator->validate();

            //Check that the authorisation code entered is correct
            if ( $user->code !== $request->get( 'user_code' ) )
            {
                throw ValidationException::withMessages( [
                    'general' => "User does not have permission to perform this task.",
                ] );
            }

            if ( count( $customErrors ) == 0 )
            {
                $mass_update_panels = $request->get( 'mass_update_panels' );
                foreach ( $mass_update_panels as $mass_update_panel )
                {
                    $workpackPanel = WorkpackPanel::find( $mass_update_panel[ 'id' ] );
                    $this->saveRequestToModel( $workpackPanel, $request );
                }
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

        catch ( QueryException $ex )
        {
            $message                = $ex->getMessage();
            $customErrors[ 'name' ] = $message;
        }

        catch ( \Exception $ex )
        {
            return redirect()->back();
        }

        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );

            return redirect()->route( 'workpackpanels.show', $workpackPanel->id );
        }

        return redirect()->route( 'workpacks.schematic', [ 'workpack'  => $workpack->id,
                                                           'schematic' => $schematic->id,
        ] );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        //
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
        //There should never be a reason to update a WorkpackPanelTask...
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
        //There should never be a reason to delete a WorkpackPanelTask...
    }

    /**
     * @param WorkpackPanel $workpackPanel
     * @param Request       $request
     *
     * @return WorkpackPanel
     * @throws ValidationException
     */
    protected function saveRequestToModel( WorkpackPanel $workpackPanel, Request $request )
    {
        $workpack = $workpackPanel->workpack;
        if( $workpack->isCompleted() ){
            throw ValidationException::withMessages( [ "general" => "Sorry, this Work Pack has been completed and no further modifications can be made." ] );
        }

        $user              = Auth::user();
        $isTakingPanelOver = false;
        $currentUser       = $workpackPanel->user;
        if ( $currentUser )
        {
            $isTakingPanelOver = $currentUser->id != $user->id;
        }
        $workpackPanel->user_id       = $user->id;
        $workpackPanelTask            = new WorkpackPanelTask();
        $workpackPanelTask->user_code = $user->code;
        $workpackPanelTask->workpack()->associate( $workpackPanel->workpack );
        $workpackPanelTask->aeroplane()->associate( $workpackPanel->workpack->aeroplane );
        $workpackPanelTask->workpack_panel()->associate( $workpackPanel );
        $workpackPanelTask->user()->associate( $user );

        $workpackPanelTaskNote = new WorkpackPanelTaskNote();
        $workpackPanelTaskNote->workpack_panel()->associate( $workpackPanel );
        if ( $request->filled( 'note' ) )
        {
            $note = $request->get( 'note' );
            if( $workpackPanelTaskNote->isNoteWorthSaving( $note ) ) {
                $workpackPanelTaskNote->setNote( $note );
            }
        }

        if ( $workpackPanel->isCompleted() )
        {
            $workpackPanel->setReopenedPanel();
            $workpackPanelTask->workpack_panel_step_id   = $workpackPanel->workpack_panel_step_id;
            $workpackPanelTask->workpack_panel_action_id = $workpackPanel->workpack_panel_action_id;
            $workpackPanelTaskNote->setReopeningPanelNote();

        }
        elseif ( $isTakingPanelOver )
        {
            if ( ! $workpackPanel->canTakeOverPanel() )
            {
                throw ValidationException::withMessages( [
                    'general' => "You can only take over in progress actions.",
                ] );
            }

            $lastWorkpackTask                            = $workpackPanel->getLastTaskPerformedOnPanel();
            $workpackPanelTask->workpack_panel_step_id   = $lastWorkpackTask->workpack_panel_step_id;
            $workpackPanelTask->workpack_panel_action_id = $lastWorkpackTask->workpack_panel_action_id;
            $workpackPanelTaskNote->setTakingOverPanelNote();

        } else
        {
            // If this is the first Step immediately move to the next step, no other actions needed...
            if ( $workpackPanel->isFirstStep() )
            {
                $workpackPanel->setStepCompleted();
                $workpackPanel->setNextAvailableAction();
            } else
            {
                $workpackPanel->setNextAvailableActionAndStep();
            }
            $workpackPanelTask->setStepAndActionFromPanel( $workpackPanel );
        }

        // Check if requested Task can be performed by current user
        if ( ! $workpackPanelTask->canPerformTask( $user ) )
        {
            throw ValidationException::withMessages( [ "general" => "Sorry, you does not have permission to perform this Task." ] );
        }

        //@TODO Check that taking over does not increment the $workpackPanel!!!!!
        DB::transaction( function () use ( $workpackPanel, $workpackPanelTask, $workpackPanelTaskNote ) {
            $workpackPanelTask->save();
            if ( $workpackPanelTaskNote->isNoteWorthSaving() )
            {
                $workpackPanelTaskNote->workpack_panel_task()->associate( $workpackPanelTask );
                $workpackPanelTaskNote->save();
            }

            if ( $workpackPanelTask->isFinalStepAndAction() )
            {
                $workpackPanel->setAllStepsCompleted();
            } elseif ( $workpackPanelTask->isStepCompleted() )
            {
                $workpackPanel->setStepCompleted();
            }
            $workpackPanel->save();
            WorkpackPanelTaskCreated::dispatch( $workpackPanelTask );
        } );

        return $workpackPanel;

    }
}
