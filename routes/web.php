<?php

use App\Http\Controllers\AeroplaneController;
use App\Http\Controllers\AirframeController;
use App\Http\Controllers\AirframeWorkpackController;
use App\Http\Controllers\AirframeWorkpackPanelController;
use App\Http\Controllers\DeploymentController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SchematicController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkpackController;
use App\Http\Controllers\WorkpackPanelActionController;
use App\Http\Controllers\WorkpackPanelController;
use App\Http\Controllers\WorkpackPanelStepController;
use App\Http\Controllers\WorkpackPanelTaskController;
use App\Http\Controllers\TaskRequestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get( '/', function () {
    return redirect()->route( 'dashboard' );
} );

Route::get( '/dashboard', function () {
    return redirect()->route( 'workpacks.index' );
    return Inertia::render( 'Dashboard' );
} )->middleware( [ 'auth', 'verified' ] )->name( 'dashboard' );

Route::get( '/deployment/clearcache', [
    DeploymentController::class,
    'clearcache',
] )->name( 'deployment.clearcache' );

Route::get( '/deployment/clearroutes', [
    DeploymentController::class,
    'clearroutes',
] )->name( 'deployment.clearroutes' );

Route::get( '/deployment/clearconfig', [
    DeploymentController::class,
    'clearconfig',
] )->name( 'deployment.clearconfig' );

Route::get( '/deployment/clearview', [
    DeploymentController::class,
    'clearview',
] )->name( 'deployment.clearview' );

Route::get( '/deployment/clearbackend', [
    DeploymentController::class,
    'clearbackend',
] )->name( 'deployment.clearbackend' );

Route::get( '/deployment/clearfrontend', [
    DeploymentController::class,
    'clearfrontend',
] )->name( 'deployment.clearfrontend' );

Route::get( '/deployment/emailtest', [
    DeploymentController::class,
    'emailtest',
] )->name( 'deployment.emailtest' );


Route::middleware( 'auth' )->group( function () {
    Route::get( '/profile', [ ProfileController::class, 'edit' ] )->name( 'profile.edit' );
    Route::patch( '/profile', [ ProfileController::class, 'update' ] )->name( 'profile.update' );
    Route::delete( '/profile', [ ProfileController::class, 'destroy' ] )->name( 'profile.destroy' );
} );


// Additional app specific routes. They should be placed before the Route Resources so they can be assessed first...
Route::middleware( 'auth' )->group( function () {

    Route::get( '/aeroplane/workpacktemplates/{aeroplane}', [
        AeroplaneController::class,
        'workpacktemplates',
    ] )->name( 'aeroplane.workpacktemplates' );

    Route::get( '/workpackpanels/workpack/{workpack}/schematic/{schematic?}', [
        WorkpackPanelController::class,
        'storebyName',
    ] )->name( 'workpackpanels.storebyName' );

    Route::get( '/workpacks/{workpack}/schematic/{schematic}/workpackpanel/{workpackpanel}', [
        WorkpackPanelController::class,
        'schematic',
    ] )->name( 'workpackpanels.schematic' );

    Route::get( '/workpacks/{workpack}/schematic/{schematic}', [
        WorkpackController::class,
        'schematic',
    ] )->name( 'workpacks.schematic' );

    Route::get( '/workpacks/{workpack}/panels/schematic/{schematic?}', [
        WorkpackController::class,
        'editpanels',
    ] )->name( 'workpacks.edit.panels' );

    Route::put( '/workpacks/update/{workpack}/schematic/{schematic?}', [
        WorkpackController::class,
        'updatepanels',
    ] )->name( 'workpacks.update.panels' );

    Route::post( '/workpacks/{workpack}/schematic/{schematic}/multi', [
        WorkpackPanelTaskController::class,
        'storemultiple',
    ] )->name( 'workpackpaneltasks.storemultiple' );

    Route::post( '/workpacks/complete/{workpack}', [
        WorkpackController::class,
        'complete',
    ] )->name( 'workpacks.complete' );

    Route::get( '/workpacks/report/{workpack}', [
        WorkpackController::class,
        'report',
    ] )->name( 'workpacks.report' );

    Route::get( '/workpacks/report/pdf/{workpack}', [
        WorkpackController::class,
        'reportpdf',
    ] )->name( 'workpacks.report.pdf' );

    Route::post( '/user/code/{user}', [
        UserController::class,
        'code',
    ] )->name( 'users.code' );

    Route::post( '/trainings/sortSignOffTasks', [
       TrainingController::class,
        'sortSignOffTasks',
    ] )->name( 'trainings.sortSignOffTasks' );


    Route::get( '/sectors/viewAllData', [
        SectorController::class,
        'viewAllData',
    ] )->name( 'sectors.viewAllData' );

    Route::post( '/sectors/search', [
        SectorController::class,
        'search',
    ] )->name( 'sectors.search' );


    Route::post( '/tasks/updateFile', [
        TaskController::class,
        'updateFile',
    ] )->name( 'tasks.updateFile' );

    Route::get( '/tasks/viewAllData', [
        TaskController::class,
        'viewAllData',
    ] )->name( 'tasks.viewAllData' );

    Route::post( '/tasks/search', [
        TaskController::class,
        'search',
    ] )->name( 'tasks.search' );

    Route::post( '/tasks/sortMyTasks', [
        TaskController::class,
        'sortMyTasks',
    ] )->name( 'tasks.sortMyTasks' );


    Route::get( '/taskrequests/validates/{taskRequest}', [
        TaskRequestController::class,
        'validatesRequest',
    ] )->name( 'taskrequests.validates' );

    Route::get( '/taskrequests/{taskRequestId}/reviewDocument', [
        TaskRequestController::class,
        'reviewDocument',
    ] )->name( 'taskrequests.reviewDocument' );

    Route::get( '/taskrequests/{taskRequestId}/assesment', [
        TaskRequestController::class,
        'validateAssesment',
    ] )->name( 'taskrequests.assesment' );

    Route::get( '/taskrequests/{historyId}/assesmentHistory', [
        TaskRequestController::class,
        'viewHistory',
    ] )->name( 'taskrequests.assesmentHistory' );

    Route::post( '/taskrequests/signOffDocument', [
        TaskRequestController::class,
        'signOffDocument',
    ] )->name( 'taskrequests.signOffDocument' );

    Route::post( '/taskrequests/signOffAssesment', [
        TaskRequestController::class,
        'signOffAssesment',
    ] )->name( 'taskrequests.signOffAssesment' );

    Route::post( '/trainings/filter', [
        TrainingController::class,
        'filter',
    ] )->name( 'trainings.filter' );

    Route::post( '/trainings/userSearch', [
        TrainingController::class,
        'userSearch',
    ] )->name( 'trainings.userSearch' );

    Route::get( '/trainings/viewStaffTask', [
        TrainingController::class,
        'viewStaffTask',
    ] )->name( 'trainings.viewStaffTask' );

    Route::get( '/trainings/test', [
        TrainingController::class,
        'test',
    ] )->name( 'trainings.test' );

    Route::post( '/notifications/updateStatus', [
        NotificationController::class,
        'updateStatus',
    ] )->name( 'notifications.updateStatus' );

    Route::post( '/notifications/search', [
        NotificationController::class,
        'search',
    ] )->name( 'notifications.search' );

    Route::get( '/notifications/viewAllData', [
        NotificationController::class,
        'viewAllData',
    ] )->name( 'notifications.viewAllData' );

    Route::post( '/notices/updateFile', [
        NoticeController::class,
        'updateFile',
    ] )->name( 'notices.updateFile' );

    Route::post( '/notices/markRead', [
        NoticeController::class,
        'markRead',
    ] )->name( 'notices.markRead' );

    Route::get( '/notices/{notice_id}/trackReaders', [
        NoticeController::class,
        'trackReaders',
    ] )->name( 'notices.trackReaders' );

    Route::post( '/notices/search', [
        NoticeController::class,
        'search',
    ] )->name( 'notices.search' );

    Route::post( '/notices/remind', [
        NoticeController::class,
        'remind',
    ] )->name( 'notices.remind' );

    Route::post( '/notices/withdrawNotice', [
        NoticeController::class,
        'withdrawNotice',
    ] )->name( 'notices.withdrawNotice' );

    Route::post( '/notices/createNoticeType', [
        NoticeController::class,
        'createNoticeType',
    ] )->name( 'notices.createNoticeType' );

    Route::get( '/notices/getNoticeTypes', [
        NoticeController::class,
        'getNoticeTypes',
    ] )->name( 'notices.getNoticeTypes' );

    

   

    
    
} );

// Default Resource route names
// <models>.index
// <models>.create
// <models>.store
// <models>.show
// <models>.edit
// <models>.update
// <models>.destroy
Route::resources( [
    'aeroplanes'             => AeroplaneController::class,
    'airframes'              => AirframeController::class,
    'airframeworkpacks'      => AirframeWorkpackController::class,
    'airframeworkpackpanels' => AirframeWorkpackPanelController::class,
    'schematics'             => SchematicController::class,
    'entities'               => EntityController::class,
    'files'                  => FileController::class,
    'owners'                 => OwnerController::class,
    'panels'                 => PanelController::class,
    'roles'                  => RoleController::class,
    'workpacks'              => WorkpackController::class,
    'workpackpanels'         => WorkpackPanelController::class,
    'workpackpanelactions'   => WorkpackPanelActionController::class,
    'workpackpanelsteps'     => WorkpackPanelStepController::class,
    'workpackpaneltask'      => WorkpackPanelTaskController::class,
    'permissions'            => PermissionController::class,
    'users'                  => UserController::class,
    'trainings'              => TrainingController::class,
    'sectors'                => SectorController::class,
    'tasks'                  => TaskController::class,
    'taskrequests'           => TaskRequestController::class,
    'notifications'          => NotificationController::class,
    'notices'                => NoticeController::class,
] );

require __DIR__ . '/auth.php';
