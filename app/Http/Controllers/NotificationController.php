<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Http\Resources\SectorResource;
use App\Models\Notification;
use App\Models\Sector;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NotificationController extends EntityController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization('createTA_TO_TM', Notification::class);
        $data = [
            'scopes' => Config::get('constants.SCOPES'),
            'periods' => Config::get('constants.VALIDITY_PERIODS'),
            'sectors' => SectorResource::collection(Sector::paginate()),
        ];
        return $this->render($data, 'Trainings/Notifications/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAuthorization('saveTA_TO_TM', Notification::class);

        $notification = new Notification();
        $customErrors = $this->saveRequestToModel($request, $notification);

        if (count($customErrors) > 0) {
            throw ValidationException::withMessages($customErrors);

            return redirect()->back();
        }
        $toast = [
            'message' => 'Notification Created Successfully',
            'status' => 200,
        ];

        $tabs = [
            'selectedTab' => 'Admin Settings',
            'selectedInnerTab' => 'Notifications',
        ];

        session(['toast' => $toast, 'tabs' => $tabs]);
        return redirect()->route('trainings.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function updateStatus(Request $request)
    {
        $notification = Notification::find($request->notificationId);
        $notification->status = $request->status;
        $notification->save();
        $response = [
            'success' => true,
            'message' => 'Notifications Staus Updated'
        ];
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Notification $notification
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        $this->checkAuthorization('editTA_TO_TM', $notification);
        $data = [
            'scopes' => Config::get('constants.SCOPES'),
            'periods' => Config::get('constants.VALIDITY_PERIODS'),
            'sectors' => SectorResource::collection(Sector::paginate()),
            'notification' => NotificationResource::make($notification),
        ];

        return $this->render($data, 'Trainings/Notifications/Edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Notification                    $notification
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        $this->checkAuthorization('updateTA_TO_TM', $notification);

        $customErrors = $this->saveRequestToModel($request, $notification);
        if (count($customErrors) > 0) {
            throw ValidationException::withMessages($customErrors);

            return redirect()->back();
        }

        $toast = [
            'message' => 'Notification Updated Successfully',
            'status' => 200,
        ];

        $tabs = [
            'selectedTab' => 'Admin Settings',
            'selectedInnerTab' => 'Notifications',
        ];

        session(['toast' => $toast, 'tabs' => $tabs]);

        return redirect()->route('trainings.index', $notification);
    }

    /**
     * @param Notification $notification
     *
     * @return void
     */
    public function destroy(Notification $notification)
    {
        $this->checkAuthorization('deleteTA_TO_TM', $notification);
        $notification->delete();

        $toast = [
            'message' => 'Notification Deleted Successfully',
            'status' => 200,
        ];

        $tabs = [
            'selectedTab' => 'Admin Settings',
            'selectedInnerTab' => 'Notifications',
        ];

        session(['toast' => $toast, 'tabs' => $tabs]);
        return redirect()->route('trainings.index');
    }

    /**
     * @param Request $request
     * @param Notification   $notification
     *
     * @return array
     */
    protected function saveRequestToModel(Request $request, Notification $notification)
    {
        $errors = [];
        try {
            // @TODO Find a better way of adding Unique constraints to name when updating modules
            $validationRules = $notification->getValidationRules();
            $validator = Validator::make($request->all(), $validationRules);
            $validator->validate();

            $notification->title = $request->get('title');
            $notification->status = ($request->get('active'));
            $notification->content = $request->get('content');
            $notification->start_number = $request->get('start_number');
            $notification->start_period = $request->get('start_period');
            $notification->start_scope = $request->get('start_scope');
            $notification->stop_number = $request->get('stop_number');
            $notification->stop_period = $request->get('stop_period');
            $notification->stop_scope = $request->get('stop_scope');
            $notification->repeat_number = $request->get('repeat_number');
            $notification->repeat_period = $request->get('repeat_period');
            $notification->repeat_period = $request->get('repeat_period');
            $notification->for_mandatory = ($request->get('for_mandatory'));

            DB::transaction(function () use ($notification) {
                $notification->save();
            });

            $notifications_sectors = $request->get('notification_sectors');
            $db_notification_sectors = DB::table('notification_sector')
                ->where('notification_id', $notification->id)
                ->get();

            $notificationSectorIds = $db_notification_sectors->pluck('sector_id')->toArray();

            if (count($notificationSectorIds) == 0) {
                $notification->notificationSectors()->attach($notifications_sectors);
            } else {
                foreach ($notifications_sectors as $notifications_sector) {
                    if (in_array($notifications_sector, $notificationSectorIds)) {
                        continue;  // Skip this iteration if sector ID is already in $notifications_sectors
                    } else {
                        // $sectorsToAdd = array_diff($notificationSectorIds, $notifications_sectors);
                        $notification->notificationSectors()->attach($notifications_sector);
                    }
                }
            }

            $sectorsToRemove = array_diff($notificationSectorIds, $notifications_sectors);
            if (!empty($sectorsToRemove)) {
                $notification->notificationSectors()->detach($sectorsToRemove);
            }
        } catch (ValidationException $ex) {
            $errors = $ex->errors();

            foreach ($errors as $key => $error) {
                $customErrors[$key] = $error;
            }
        } catch (QueryException $ex) {
            $message = $ex->getMessage();
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            $customErrors['general'] = 'Untrapped error. Please email your site administrator the following: ' . $message;
        }
        return $errors;
    }

    public function viewAllData(Request $request)
    {
        $data = [
            'data' => NotificationResource::collection(Notification::with('notificationSectors')->get()),
        ];

        return response()
            ->json([
                'message' => 'All the Notifications are Listed',
                'success' => true,
                'data' => $data,
            ], 200);
    }

    public function search(Request $request)
    {
        $data = [
            'data' => NotificationResource::collection(Notification::where('title', 'LIKE', '%' . $request->notificationValue . '%')->with('notificationSectors')->get()),
        ];
        if (count($data) != 0) {
            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Notifications Record Found'
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'No Notifications Record Found'
            ];
            return response()->json($response, 404);
        }
    }
}
