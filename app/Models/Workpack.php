<?php

namespace App\Models;

use App\Events\WorkpackCompleted;
use App\Helpers\Helper;
use App\Traits\HasFiles;
use App\Traits\Noteable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Workpack extends Basemodel {

    use HasFactory, HasFiles, Noteable;

    /**
     * @var string[]
     */
    protected static array $validationRules = [
        'name'                 => 'required|max:255',
        'aeroplane_id'         => 'required|int',
        'airframe_workpack_id' => 'nullable|int',
    ];

    /**
     * @return HasOne
     */
    public function aeroplane() {
        return $this->belongsTo( Aeroplane::class );
    }

    /**
     * @return HasMany
     */
    public function workpack_panels() {
        return $this->hasMany( WorkpackPanel::class );
    }


    /**
     * @return HasMany
     */
    public function workpack_panel_tasks() {
        return $this->hasMany( WorkpackPanelTask::class );
    }

    /**
     * @return bool
     */
    protected function hasWorkPackPanels(): bool {
        if ( $this->workpack_panels->count() > 0 ) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function hasWorkPackPanelTasks(): bool {
        if ( $this->workpack_panel_tasks->count() > 0 ) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canDelete(): bool {
        if ( !$this->hasWorkPackPanelTasks() ) {
            return true;
        }

        return false;
    }


    /**
     * @param AirframeWorkpack $airframe_workpack
     *
     * @return void
     */
    public function copyPanels( AirframeWorkpack $airframe_workpack, Aeroplane $aeroplane ) {
        $keyName = $this->getKeyName();
        if ( empty( $this->$keyName ) ) {
            throw new InvalidArgumentException( 'This model does not have an id set. Panels cannot be copied until the Workpack has been saved.' );
        }

        $airframe_workpack_panels = $airframe_workpack->airframe_workpack_panels;
        foreach ( $airframe_workpack_panels as $airframe_workpack_panel ) {
            $workpack_panel               = new WorkpackPanel();
            $workpack_panel->workpack_id  = $this->id;
            $workpack_panel->aeroplane_id = $aeroplane->id;
            $workpack_panel->panel_id     = $airframe_workpack_panel->panel_id;
            $workpack_panel->save();
        }
    }


    /**
     * @return boolean
     */
    public function isCompleted() {
        return $this->completed;
    }


    public function setCompleted() {
        $oldComplete     = $this->completed;
        $this->completed = true;
        $this->save();
        if ( $oldComplete == false ) {
            WorkpackCompleted::dispatch( $this );
        }
    }


    /**
     * Can accept either "download", "stream" as the method parameter
     *
     * @param $method
     *
     * @return \Illuminate\Http\Response
     */
    public function getReportPdf( $method = 'download' ) {
        $start_time = microtime(true);
        $data = $this->getWorkpackReportData();
        $end_time = microtime(true);
        Log::info( '$this->getWorkpackReportData() took: ' . ( $end_time - $start_time ) );

        $start_time = microtime(true);
        $pdf  = Pdf::loadView( 'reports.workpack-pdf', $data );
        $end_time = microtime(true);
        Log::info('Pdf::loadView() took: ' . (  $end_time - $start_time) );

        $start_time = microtime(true);
        $output =  $pdf->$method( $this->getReportPdfFileName() );
        $end_time = microtime(true);
        Log::info('$pdf->$method( $this->getReportPdfFileName() ) took: ' . (  $end_time - $start_time ) );
        return $output;
    }

    /**
     * @param array $options
     *
     * @return mixed
     */
    public function getReportPdfAsString( array $options = [] ) {
        $data = $this->getWorkpackReportData();
        $pdf  = Pdf::loadView( 'reports.workpack-pdf', $data );
        return $pdf->output();
    }

    /**
     * @return string
     */
    public function getReportPdfFileName() {
        $slugifiedName = Str::slug( $this->name );
        $dateTime      = Carbon::now()->format( "Y-m-d-H-i-s" );
        return "workpack-report-{$slugifiedName}-{$dateTime}.pdf";
    }

    /**
     * @return array
     */
    public function getWorkpackReportData() {
        $panelData = [];

        $workpackPanels  = WorkpackPanel::with( [
            'workpack',
            'panel'
        ])
            ->where( 'workpack_id', $this->id )
            ->get();
        $panel_ids       = $workpackPanels->pluck( 'panel_id' )->toArray();
        $schematicPanels = SchematicPanel::whereIn( 'panel_id', $panel_ids )->get();
        $schematics      = Schematic::with( [ 'panels' ] )->whereIn( 'id', $schematicPanels->pluck( 'schematic_id' )->toArray() )->get();

        foreach ( $schematics as $schematic ) {
            $schematicKey = 'schematic_' . $schematic->id;
            if ( !array_key_exists( $schematicKey, $panelData ) ) {
                $schematicData            = new \stdClass();
                $panelData[$schematicKey] = $schematicData;
                $schematicData->schematic = $schematic;
                $schematicData->panels    = [];
            }
            $schematicPanelCollection = $schematic->panels;
            //Now populate the relevant panels...
            foreach ( $workpackPanels as $workpackPanel ) {
                if ( $schematicPanelCollection->contains( 'id', $workpackPanel->panel_id ) ) {
                    $schematicData->panels[] = $workpackPanel;
                }
            }
        }

        $data = [
            'workpack'   => $this,
            'aeroplane'  => $this->aeroplane,
            'airframe'   => $this->aeroplane->airframe,
            'panels'     => $workpackPanels,
            'schematics' => $panelData,
            'users' => User::all()->keyBy('id'),
            'user_signatures' => [],
        ];

        return $data;
    }


    /**
     * @param $paginate
     *
     * @return null
     */
    public function getSchematics( $paginate = false ) {
        $schematics = null;
        $method     = ( $paginate ) ? 'paginate' : 'get';

        $panel_ids = WorkpackPanel::where( [
            'workpack_id' => $this->id,
        ] )->pluck( 'panel_id' )->unique();

        if ( count( $panel_ids ) > 0 ) {
            $schematic_ids = SchematicPanel::whereIn( 'panel_id', $panel_ids )->pluck( 'schematic_id' )->unique();
            if ( count( $panel_ids ) > 0 ) {
                $schematics = Schematic::whereIn( 'id', $schematic_ids )->$method();
            }
        }

        return $schematics;
    }


    /**
     * @return array
     */
    public function generateWorkpackSchematicStats( Collection $schematics = null ) {
        $stats = [];
        if ( $schematics === null ) {
            $schematics = $this->getSchematics();
        }
        if ( $schematics && $schematics->count() > 0 ) {
            foreach ( $schematics as $schematic ) {
                $stats[] = $this->getSchematicStats( $schematic );
            }
        }

        return $stats;
    }


    /**
     * @param Collection $schematics
     *
     * @return array
     */
    public function getWorkpackSchematicStats( Collection $schematics = null ) {
        if ( !$this->exists ) {
            return null;
        }
//        $stats = $this->getNotes( 'schematic_stats' );
//        if ( is_null( $stats ) )
//        {
//            $stats = $this->generateWorkpackSchematicStats( $schematics );
//        }
        $stats = $this->generateWorkpackSchematicStats( $schematics );

        return $stats;
    }


    public function getSchematicStats( Schematic $schematic ) {
        $schematicStats     = new \stdClass();
        $schematicStats->id = $schematic->id;
        $schematicPanels    = $schematic->panels()->get();
        $workpackPanels     = $this->getWorkpackPanelsBySchematic( $schematicPanels );
        $this->setSchematicPanelsStats( $workpackPanels, $schematicStats );
        $this->setSchematicUserStats( $workpackPanels, $schematicStats );

        return $schematicStats;
    }


    public function getWorkpackPanelsBySchematic( Collection $schematicPanels ) {
        $panel_ids = $schematicPanels->pluck( 'id' )->toArray();

        return $this->workpack_panels()
            ->where( 'workpack_id', '=', $this->id )
            ->whereIn( 'panel_id', $panel_ids )
            ->get();
    }


    /**
     * @param Collection $workpackPanels
     * @param \stdClass  $statsObject
     *
     * @return void
     */
    public function setSchematicPanelsStats( Collection $workpackPanels, \stdClass $statsObject ) {
        $statsObject->panels_total     = $workpackPanels->count();
        $statsObject->panels_completed = $workpackPanels->where( 'completed', 1 )->count();
    }


    /**
     * @param Collection $workpackPanels
     * @param \stdClass  $statsObject
     *
     * @return void
     */
    public function setSchematicUserStats( Collection $workpackPanels, \stdClass $statsObject ) {
        $panel_ids                = $workpackPanels->pluck( 'id' )->toArray();
        $workpackUsersOnSchematic = DB::table( 'workpack_panel_tasks' )
            ->select( 'user_id' )
            ->where( 'workpack_id', '=', $this->id )
            ->whereIn( 'workpack_panel_id', $panel_ids )
            ->groupby( 'user_id' )
            ->distinct()
            ->get();
        $user_ids                 = $workpackUsersOnSchematic->pluck( 'user_id' )->toArray();
        $users                    = User::findMany( $user_ids );
        $statsObject->users       = $users->toArray();
    }


    /**
     * Get all the tasks associated with a specific Schematic
     *
     * @param Schematic $schematic
     *
     * @return mixed
     */
    public function getSchematicTasks( Schematic $schematic ) {
        return $schematic->panels->pluck( 'id' );
    }


    /**
     * @return \stdClass
     */
    public function getWorkpackStats() {
        if ( !$this->exists ) {
            return null;
        }

        $stats = $this->getNotes( 'stats' );
        if ( is_null( $stats ) ) {
            return $this->generateWorkpackStatsBasic();
        }

        return $stats;
    }

    /**
     * @param array $excluded_workpack_ids
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getAvailableWorkpacks( array $excluded_workpack_ids = [] ) {
        $workpackQuery = Workpack::doesntHave( 'workpack_panel_tasks' )
            ->where( 'completed', false )
            ->where( 'active', true )
            ->whereNotIn( 'id', $excluded_workpack_ids );
        return $workpackQuery;
    }


    /**
     * @param array $excluded_workpack_ids
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getActiveWorkpacks( array $excluded_workpack_ids = [] ) {
        $workpackQuery = Workpack::has( 'workpack_panel_tasks' )
            ->where( 'completed', false )
            ->where( 'active', true )
            ->whereNotIn( 'id', $excluded_workpack_ids );
        return $workpackQuery;
    }


    /**
     * @param User  $user
     * @param array $excluded_workpack_ids
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getUsersCurrentWorkpackAndPanels( User $user, array $excluded_workpack_ids = [] ) {
        $workpackQuery = Workpack::with(
            [
                'workpack_panels.panel',
                'workpack_panels' => function ( $query ) use ( $user ) {
                    $query->where( 'completed', false )
                        ->where( 'user_id', $user->id );
                }
            ]
        )->whereHas( 'workpack_panels', function ( $query ) use ( $user ) {
            $query->where( 'completed', false )
                ->where( 'user_id', $user->id );
        } )->where( 'completed', false )
            ->where( 'active', true );
        return $workpackQuery;
    }


    /**
     * @param User $user
     *
     * @return mixed
     */
    public function getPanelsForUserWorkingOnWorkpack( User $user ) {
        $workpackPanelsQuery = WorkpackPanel::where( 'workpack_id', $this->id )
            ->where( 'user_id', $user->id )
            ->where( 'completed', 0 );

        return $workpackPanelsQuery->get();
    }


    /**
     * @return void
     */
    public function getStaffWhoHaveWorkedOnWorkpack() {
        $users  = new Collection();
        $result = DB::table( 'workpack_panel_tasks' )
            ->selectRaw( 'DISTINCT( CONCAT_WS( ";", users.id ) ) AS id' )
            ->join( 'users', 'users.id', '=', 'workpack_panel_tasks.user_id' )
            ->join( 'workpack_panels', 'workpack_panel_tasks.workpack_panel_id', '=', 'workpack_panels.id' )
            ->where( 'workpack_panels.workpack_id', '=', $this->id )
            ->groupBy( 'workpack_panel_tasks.user_id' )
            ->get();

        if ( $result->count() ) {
            $user_ids = [];
            foreach ( $result as $resultObj ) {
                $user_ids[] = ( int ) $resultObj->id;
            }
            $users = User::findMany( $user_ids );
        }

        return $users;

//      select DISTINCT( CONCAT_WS( ";", users.id ) ) AS id
//      from `workpack_panel_tasks`
//      inner join `workpack_panels`
//      on `workpack_panel_tasks`.`workpack_panel_id` = `workpack_panels`.`id`
//      inner join `users`
//      on `users`.`id` = `workpack_panel_tasks`.`user_id`
//      where `workpack_panels`.`workpack_id` = ?
//      group by `workpack_panel_tasks`.`user_id`;

    }

    public function getStaffWhoAreWorkingOnWorkpack() {
        $users  = new Collection();
        $result = DB::table( 'workpack_panels' )
            ->selectRaw( 'DISTINCT( CONCAT_WS( ";", users.id ) ) AS id' )
            ->join( 'users', 'users.id', '=', 'workpack_panels.user_id' )
            ->where( 'workpack_panels.workpack_id', '=', $this->id )
            ->where( 'workpack_panels.user_id', '<>', '0' )
            ->groupBy( 'workpack_panels.user_id' )
            ->get();
        if ( $result->count() ) {
            $user_ids = [];
            foreach ( $result as $resultObj ) {
                $user_ids[] = ( int ) $resultObj->id;
            }
            $users = User::findMany( $user_ids );
        }

        return $users;

//        SELECT DISTINCT( CONCAT_WS( ";", users.id ) ) AS id
//        FROM `workpack_panels`
//        INNER join `users`
//        ON `users`.`id` = `workpack_panels`.`user_id`
//        WHERE `workpack_panels`.`workpack_id` = ?
//        AND `workpack_panels`.`user_id` <> 0
//        GROUP BY `workpack_panels`.`user_id`;
    }


    public function getStaffWhoHaveWorkedOnWorkpackBySchematic() {
        $schematics = $this->getSchematics();
        foreach ( $schematics as $schematic ) {

        }
//        $schematicPanels    = $this->workpack_panels()->pluck( '' );
//        $workpackPanelTasks = $this->workpack_panel_tasks;


//        SELECT DISTINCT( CONCAT_WS( ";", users.id ) ) AS id
//        FROM `workpack_panels`
//        INNER join `users`
//        ON `users`.`id` = `workpack_panels`.`user_id`
//        WHERE `workpack_panels`.`workpack_id` = ?
//        AND `workpack_panels`.`user_id` <> 0
//        GROUP BY `workpack_panels`.`user_id`;
    }

    public function generateWorkpackStatsBasic() {
        //Because we know we're only feeding an int into these query it's safe...
        $rawQueryExpression = DB::raw(
            "SELECT
               w.`id` AS `id`,
               w.`name` AS `name`,
               w.`completed`,
                ((
                SELECT count( wp.`id` )
                FROM workpack_panels AS wp
                WHERE wp.`workpack_id` = {$this->id}
                      AND wp.`completed` = 1
                )) AS panels_completed,
                 ((
                 SELECT count( wp.`id` )
                    FROM workpack_panels AS wp
                    WHERE wp.`workpack_id` = {$this->id}
                )) AS panels_total,
                ((
                SELECT GROUP_CONCAT( DISTINCT wpt.user_id )
                    FROM workpack_panel_tasks AS wpt
                    LEFT JOIN workpack_panels AS wp
                    ON wp.`id` = wpt.`workpack_panel_id`
                    WHERE wp.`workpack_id` = {$this->id}
                )) AS users,
                ((
                SELECT GROUP_CONCAT( DISTINCT wpt.user_id )
                    FROM workpack_panel_tasks AS wpt
                    LEFT JOIN workpack_panels AS wp
                    ON wp.`id` = wpt.`workpack_panel_id`
                    WHERE wp.`workpack_id` = {$this->id}
                          AND wp.`completed` = 0
                )) AS current_users
            FROM `workpacks` AS w
            WHERE w.`id` = {$this->id};"
        );
        $rawQuery = $rawQueryExpression->getValue(DB::connection()->getQueryGrammar() );
        $results               = DB::select( $rawQuery );
        $result                = $results[0];
        $result->users         = $this->getStaffWhoHaveWorkedOnWorkpack()->toArray();
        $result->current_users = $this->getStaffWhoAreWorkingOnWorkpack()->toArray();

        return $result;
    }

    /**
     * @param Schematic $schematic
     * @param User|null $user
     * @param bollean   $exclude_user
     *
     * @return Collection
     */
    public function getSchematicPanels( Schematic $schematic ) {
        $schematicPanelsQuery = $this->getSchematicPanelsBaseQuery( $schematic );
        $schematicPanels      = $schematicPanelsQuery->get();

        return $this->attachNotesToSchematicPanels( $schematicPanels );

    }

    /**
     * @param Schematic $schematic
     * @param User|null $user
     *
     * @return Collection
     */
    public function getSchematicPanelsForUser( Schematic $schematic, User $user ) {
        $schematicPanelsQuery = $this->getSchematicPanelsBaseQuery( $schematic );
        $schematicPanelsQuery->where( 'workpack_panels.user_id', '=', $user->id );
        $schematicPanels = $schematicPanelsQuery->get();

        return $this->attachNotesToSchematicPanels( $schematicPanels );
    }

    /**
     * @param Schematic $schematic
     * @param User|null $user
     *
     * @return Collection
     */
    public function getSchematicPanelsExcludingUser( Schematic $schematic, User $user ) {
        $schematicPanelsQuery = $this->getSchematicPanelsBaseQuery( $schematic );
        $schematicPanelsQuery->where( 'workpack_panels.user_id', '!=', $user->id );
        $schematicPanels = $schematicPanelsQuery->get();

        return $this->attachNotesToSchematicPanels( $schematicPanels );
    }

    /**
     * @param Schematic $schematic
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getSchematicPanelsBaseQuery( Schematic $schematic ) {
        $panel_ids = $this->getSchematicTasks( $schematic );

        // Maybe we can do something like ORDER BY FIELD ? https://dba.stackexchange.com/questions/109120/how-does-order-by-field-in-mysql-work-internally
        $schematicPanelsQuery = DB::table( 'workpack_panels' )
            ->selectRaw( "panels.airframe_id,
                panels.name AS name,
                workpack_panels.panel_id,
                workpack_panels.id,
                workpack_panels.user_id,
                CONCAT_WS(' ',users.fname,users.lname) AS user_name,
                workpack_panels.workpack_panel_step_id,
                workpack_panels.workpack_panel_action_id,
                workpack_panels.completed,
                workpack_panels.notes AS notes" )
            ->leftJoin( 'panel_schematic', 'panel_schematic.panel_id', '=', 'workpack_panels.panel_id' )
            ->leftJoin( 'workpacks', 'workpacks.id', '=', 'workpack_panels.workpack_id' )
            ->leftJoin( 'users', 'users.id', '=', 'workpack_panels.user_id' )
            ->leftJoin( 'panels', 'panels.id', '=', 'workpack_panels.panel_id' )
            ->where( 'workpack_panels.workpack_id', '=', $this->id )
            ->whereIn( 'workpack_panels.panel_id', $panel_ids )
            ->groupBy( 'workpack_panels.panel_id' )
            ->orderBy( 'workpack_panels.workpack_panel_action_id' )
            ->orderBy( 'workpack_panels.workpack_panel_step_id', 'ASC' )
            ->orderBy( 'panels.name' );
//        $blah = $schematicPanelsQuery->toSql();
//        $blah2 = implode( ',', $panel_ids->toArray() );
        return $schematicPanelsQuery;
    }

    /**
     * @param Collection $schematicPanels
     *
     * @return Collection
     */
    protected function attachNotesToSchematicPanels( Collection $schematicPanels ) {
        //Get the IDs of the Notes for each panel...
        $workpack_panel_ids = $schematicPanels->pluck( 'id' )->toArray();
        // Get the count of the notes of notes per
        $workpackPanelsNotes = DB::table( 'workpack_panel_task_notes' )
            ->selectRaw( "`workpack_panel_id`,
                             COUNT(`workpack_panel_id`) AS note_count" )
            ->whereIn( 'workpack_panel_id', $workpack_panel_ids )
            ->groupBy( 'workpack_panel_id' )
            ->get();

        foreach ( $schematicPanels as $schematicPanel ) {
            $schematicPanel->notes_count = null;
            foreach ( $workpackPanelsNotes as $workpackPanelsNote ) {
                if ( $workpackPanelsNote->workpack_panel_id == $schematicPanel->id ) {
                    $schematicPanel->note_count = $workpackPanelsNote->note_count;
                }
            }
        }

        return $schematicPanels;
    }


}
