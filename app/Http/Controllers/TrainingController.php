<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\SectorResource;
use App\Http\Resources\TaskRequestResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TrainingRoleResource;
use App\Http\Resources\UserResource;
use App\Models\Notice;
use App\Models\Notification;
use App\Models\Sector;
use App\Models\Task;
use App\Models\TaskRequest;
use App\Models\TaskRequestAssesment;
use App\Models\TaskRequestAssesmentChecklist;
use App\Models\TaskRequestHistory;
use App\Models\TrainingRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon;

class TrainingController extends EntityController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $training_role_id = Auth::user()->training_role_id;
        if ($training_role_id != 4 && $training_role_id != 5) {
            $this->checkAuthorization('viewTA_TO_TM', Sector::class);
            $my_tasks = DB::table('task_role')->where('training_role_id', $training_role_id)->pluck('task_id')->toArray();

            $tasks = Task::with(['sector', 'roles', 'task_status' => function ($query) {
                $query->where('user_id', auth()->id());
            }])
                ->whereIn('id', $my_tasks)
                ->get();

            $tasksWithStatus = $tasks->filter(function ($task) {
                return $task->task_status->isNotEmpty();
            });

            $tasksWithoutStatus = $tasks->filter(function ($task) {
                return $task->task_status->isEmpty();
            });

            $tasksWithStatusSorted = $tasksWithStatus->sortBy(function ($task) {
                return $task->task_status[0]->valid_upto;
            });

            // Concatenate tasks with empty task_status list placed at the top
            $sortedTasks = $tasksWithoutStatus->merge($tasksWithStatusSorted);

            if (session()->get('tabs')) {
                $tabs = session()->get('tabs');
                session()->forget('tabs');
            } else {
                $tabs = [
                    'selectedTab' => 'My Training',
                    'selectedInnerTab' => 'Tasks'
                ];
            }

            $data = [
                'toast' => session()->get('toast'),
                'sectors' => SectorResource::collection(Sector::orderBy('order_number', 'ASC')->paginate()),
                'allsectors' => SectorResource::collection(Sector::orderBy('order_number', 'ASC')->get()),
                'training_roles' => TrainingRoleResource::collection(TrainingRole::paginate()),
                'tasks' => TaskResource::collection(Task::with('sector', 'roles')->orderBy('task_number', 'ASC')->paginate()),
                'my_tasks' => TaskResource::collection($sortedTasks),
                'signoff_tasks' => TaskRequestResource::collection(TaskRequest::where('user_id', '!=', Auth::user()->id)->where('request_status', 'Pending')->with(['user', 'task'])->with('task.sector')->orderBy('valid_upto', 'ASC')->get()),
                'count_signoff_tasks' => TaskRequestResource::collection(TaskRequest::where('user_id', '!=', Auth::user()->id)->where('request_status', 'Pending')->with(['user', 'task'])->with('task.sector')->get()),
                'notifications' => NotificationResource::collection(Notification::with('notificationSectors')->get()),
                'users' => UserResource::collection(User::with('training_role.tasks.task_status', 'training_role.tasks.sector')->paginate(25)),
                'selected_tab' => $tabs['selectedTab'],
                'selected_inner_tab' => $tabs['selectedInnerTab'],
            ];

            session()->forget('toast');

            return $this->render($data, 'Trainings/Index');
        } else {
            $this->checkAuthorization('viewTE_TMC', TaskRequest::class);

            $my_tasks = DB::table('task_role')->where('training_role_id', $training_role_id)->pluck('task_id')->toArray();

            $tasks = Task::with(['sector', 'roles', 'task_status' => function ($query) {
                $query->where('user_id', auth()->id());
            }])
                ->whereIn('id', $my_tasks)
                ->get();

            // Separate tasks with empty task_status list
            $tasksWithStatus = $tasks->filter(function ($task) {
                return $task->task_status->isNotEmpty();
            });

            $tasksWithoutStatus = $tasks->filter(function ($task) {
                return $task->task_status->isEmpty();
            });

            $tasksWithStatusSorted = $tasksWithStatus->sortBy(function ($task) {
                return $task->task_status[0]->valid_upto;
            });

            // Concatenate tasks with empty task_status list placed at the top
            $sortedTasks = $tasksWithoutStatus->merge($tasksWithStatusSorted);

            $data = [
                'sectors' => SectorResource::collection(Sector::orderBy('order_number', 'ASC')->paginate()),
                'tasks' => TaskResource::collection($sortedTasks),
                'notifications' => NotificationResource::collection(Notification::with('notificationSectors')->paginate()),
            ];

            return $this->render($data, 'Trainings/MyTrainings/Index');
        }
    }

    public function viewStaffTask(Request $request)
    {
        $taskRequest = TaskRequest::with('task', 'user', 'task.sector')->find($request->taskRequest_id);
        $task = Task::find($taskRequest->task_id);
        $data = [
            'taskRequest' => TaskRequestResource::make($taskRequest),
            'attachments' => FileResource::collection($taskRequest->getAttachments()),
            'training_material' => FileResource::collection($task->getAttachments()),
        ];
        // dd($data);

        return $this->render($data, 'Trainings/viewStaffTask');
    }

    //

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function filter(Request $request)
    {
        $roles = $request->roles;
        $sectorIds = $request->sectors;
        $status = $request->status;
        // dd($roles);
        if (count($roles) == 0) {
            $roles = TrainingRole::pluck('id')->toArray();
        }
        if (count($sectorIds) == 0) {
            $sectorIds = Sector::pluck('id')->toArray();
        }
        if (count($status) == 0) {
            $status = [
                'Active',
                'Upcoming',
                'Pending',
                'Action Required'
            ];
        }
        $data = [
            // ------------------------Corrected-------------11 Apr--------------
            'data' => UserResource::collection(User::whereIn('training_role_id', $roles)
                ->with([
                    'training_role.tasks' => function ($query) use ($sectorIds, $status) {
                        $query
                            ->whereIn('sector_id', $sectorIds)
                            ->whereHas('task_status', function ($statusQuery) use ($status) {
                                $statusQuery->whereIn('filter_status', $status);
                            })
                            ->whereHas('task_status', function ($statusQuery) use ($status) {
                                $statusQuery->whereIn('filter_status', $status);
                            });
                    },
                    'training_role.tasks.task_status',
                    'training_role.tasks.sector'
                ])
                ->get()),
        ];
        return response()
            ->json(['data' => $data, 'success' => true], 200);
    }

    public function userSearch(Request $request)
    {
        $roles = $request->roles;
        $sectorIds = $request->sectors;
        $status = $request->status;
        $user = $request->user;
        if (count($roles) == 0) {
            $roles = TrainingRole::pluck('id')->toArray();
        }
        if (count($sectorIds) == 0) {
            $sectorIds = Sector::pluck('id')->toArray();
        }
        if (count($status) == 0) {
            $status = [
                'Active',
                'Upcoming',
                'Pending',
                'Action Required'
            ];
        }
        $data = [
            // ------------------------Corrected-------------11 Apr--------------
            'data' => UserResource::collection(User::where('name', 'LIKE', '%' . $user . '%')
                ->whereIn('training_role_id', $roles)
                ->with([
                    'training_role.tasks' => function ($query) use ($sectorIds, $status) {
                        $query
                            ->whereIn('sector_id', $sectorIds)
                            ->whereHas('task_status', function ($statusQuery) use ($status) {
                                $statusQuery->whereIn('filter_status', $status);
                            })
                            ->whereHas('task_status', function ($statusQuery) use ($status) {
                                $statusQuery->whereIn('filter_status', $status);
                            });
                    },
                    'training_role.tasks.task_status',
                    'training_role.tasks.sector'
                ])
                ->get()),
        ];

        if (count($data['data']) > 0) {
            return response()
                ->json(['data' => $data, 'success' => true], 200);
        } else {
            return response()
                ->json(['data' => $data, 'success' => true], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function sortSignOffTasks(Request $request)
    {
        $descendingOrder = $request->descending;

        if ($descendingOrder == 1) {
            $data = [
                // 'data' => TaskRequestResource::collection(TaskRequest::where('user_id', '!=', Auth::user()->id)->where('request_status', 'Pending')->with(['user', 'task'])->with('task.sector')->get()),
                'data' => TaskRequestResource::collection(TaskRequest::where('user_id', '!=', Auth::user()->id)->where('request_status', 'Pending')->with(['user', 'task'])->with('task.sector')->orderBy('valid_upto', 'DESC')->get()),
            ];
        } else {
            $data = [
                // 'data' => TaskRequestResource::collection(TaskRequest::where('user_id', '!=', Auth::user()->id)->where('request_status', 'Pending')->with(['user', 'task'])->with('task.sector')->get()),
                'data' => TaskRequestResource::collection(TaskRequest::where('user_id', '!=', Auth::user()->id)->where('request_status', 'Pending')->with(['user', 'task'])->with('task.sector')->orderBy('valid_upto', 'ASC')->get()),
            ];
        }

        return response()
            ->json([
                'message' => 'All the Tasks are Listed',
                'success' => true,
                'data' => $data,
            ], 200);
    }

    public function test()
    {
        $training_role_id = Auth::user()->training_role_id;


        $my_tasks = DB::table('task_role')->where('training_role_id', $training_role_id)->pluck('task_id')->toArray();

            $tasks = Task::with(['sector', 'roles', 'task_status' => function ($query) {
                $query->where('user_id', auth()->id());
            }])
                ->whereIn('id', $my_tasks)
                ->get();

            // Separate tasks with empty task_status list
            $tasksWithStatus = $tasks->filter(function ($task) {
                return $task->task_status->isNotEmpty();
            });

            $tasksWithoutStatus = $tasks->filter(function ($task) {
                return $task->task_status->isEmpty();
            });

            $tasksWithStatusSorted = $tasksWithStatus->sortBy(function ($task) {
                return $task->task_status[0]->valid_upto;
            });

            // Concatenate tasks with empty task_status list placed at the top
            $sortedTasks = $tasksWithoutStatus->merge($tasksWithStatusSorted);


        // $roles = [1, 3];
        // $sectorIds = [21];

        $data = [
            //     'users' => UserResource::collection(User::whereIn('training_role_id', $roles)
            //         ->where('id', '!=', Auth::user()->id)
            //         ->with(['training_role.tasks' => function ($query) use ($sectorIds) {
            //             $query->whereIn('sector_id', $sectorIds);
            //         }, 'training_role.tasks.task_status'])
            //         // ->with('training_role.tasks.task_status')
            //         //         // ->with('training_role.tasks.task_status', 'training_role.tasks.sector')
            //         ->get()),
            'sectors' => SectorResource::collection(Sector::orderBy('order_number', 'ASC')->paginate()),
            // 'allsectors' => SectorResource::collection(Sector::orderBy('order_number', 'ASC')->get()),
            // 'training_roles' => TrainingRoleResource::collection(TrainingRole::paginate()),
            'tasks' => TaskResource::collection(Task::with('sector', 'roles')->orderBy('task_number', 'ASC')->paginate()),
            'my_tasks' => TaskResource::collection($sortedTasks),
            'signoff_tasks' => TaskRequestResource::collection(TaskRequest::where('user_id', '!=', Auth::user()->id)->where('request_status', 'Pending')->with(['user', 'task'])->with('task.sector')->orderBy('valid_upto', 'ASC')->get()),
            // 'count_signoff_tasks' => TaskRequestResource::collection(TaskRequest::where('user_id', '!=', Auth::user()->id)->where('request_status', 'Pending')->with(['user', 'task'])->with('task.sector')->get()),
            'notifications' => NotificationResource::collection(Notification::with('notificationSectors')->paginate()),
        ];

        // dd($data['users']);

        return $this->render($data, 'Trainings/Testing');
    }

    public function test2()
    {
        // $tasks = Task::all();
        // $tasksRequests = TaskRequest::where('status', 1)->get();
        // $tasksRequestAssesment = TaskRequestAssesment::all();
        // $taskRequestAssesmentChecklist = TaskRequestAssesmentChecklist::all();
        // $tasksRequestHistory = TaskRequestHistory::all();

        // $data = [
        //     // 'tasks' => $tasks,
        //     'tasksRequest' => $tasksRequests,
        //     // 'tasksRequestAssesment' => $tasksRequestAssesment,
        //     // 'taskRequestAssesmentChecklist' => $taskRequestAssesmentChecklist,
        //     // 'tasksRequestHistory' => $tasksRequestHistory,
        // ];

        // return $this->render($data, 'Trainings/Testing');

        //     foreach ($tasksRequests as $tasksRequest) {
        //         if ($tasksRequest->display_status == 'Active') {
        //             $validity = Carbon\Carbon::parse($tasksRequest->valid_upto);
        //             $currentDate = Carbon\Carbon::now();
        //             $today = $currentDate->format('Y-m-d');
        //             $diffInDays = $validity->diffInDays($today);
        //             if ($diffInDays < 60) {
        //                 $tasksRequest->display_status = 'Upcoming';
        //                 $tasksRequest->save();
        //                 dd('Upcoming');
        //             }
        //         }

        //         if ($tasksRequest->display_status == 'Upcoming') {
        //             $validity = Carbon\Carbon::parse($tasksRequest->valid_upto);
        //             if ($validity->isPast()) {
        //                 $tasksRequest->status = 0;
        //                 $tasksRequest->approval_status = null;
        //                 $tasksRequest->display_status = 'Expired';
        //                 $tasksRequest->save();
        //                 dd('Expired');
        //             }
        //         }
        //     }
        // }

        // public function formattedValidity($number,$period){
        //     return 'Tested '.$number.'/'.$period;
        $taskss = [];

        // $notifications = Notification::with('notificationSectors.tasks.roles.users.training_role.tasks')->get();
        $notifications = Notification::all();
        // dd($notifications);

        foreach ($notifications as $notification) {
            $sectors = $notification->notificationSectors;
            // dd($sectors);
            foreach ($sectors as $sector) {
                $mtasks = Task::where('sector_id', $sector->id)->get();
                foreach ($mtasks as $task) {
                    $task_reqs = TaskRequest::where('task_id', $task->id)->where('status', 1)->get();
                    foreach ($task_reqs as $task_req) {
                        // $taskss[] = [
                        //     'notification : ' . $notification->title,
                        //     'sector : ' . $sector->title,
                        //     'Task : ' . $task->title,
                        //     'TaskReq : ' . $task_req->display_status,
                        //     'validity : ' . $task_req->valid_upto,
                        //     'User : ' . $task_req->user_id,
                        // ];

                        $startDays = $this->calculateDays($notification->start_number, $notification->start_period);
                        $startScope = $notification->start_scope;
                        $stopDays = $this->calculateDays($notification->stop_number, $notification->stop_period);
                        $stopScope = $notification->stop_scope;
                        $repeatAfter = $this->calculateDays($notification->repeat_number, $notification->repeat_period);
                        $expiryDate = $task_req->valid_upto;
                        $this->sendOnTimeMail($expiryDate, $startDays, $startScope, $stopDays, $stopScope, $repeatAfter);
                    }
                }
            }
        }
        // $tasks = Notification::with('notificationSectors.tasks.roles.users')->get();
        // foreach ($notifications as $notification) {
        $data = [
            'tasks' => $taskss
        ];
        return $this->render($data, 'Trainings/Testing');
        // }
    }

    public function calculateDays($number, $period)
    {
        if ($period == 'Days') {
            return $number * 1;
        }

        if ($period == 'Weeks') {
            return $number * 7;
        }

        if ($period == 'Months') {
            return $number * 30;
        }

        if ($period == 'Years') {
            return $number * 365;
        }
    }

    public function sendOnTimeMail($expiryDate, $startDays, $startScope, $stopDays, $stopScope, $repeatAfter)
    {
        $today = Carbon\Carbon::today();

        if ($startScope == 'Before') {
            $startNotifyDate = Carbon\Carbon::parse($expiryDate)->subDays($startDays);
        } else {
            $startNotifyDate = Carbon\Carbon::parse($expiryDate)->addDays($startDays);
        }

        if ($stopScope == 'Before') {
            $stopNotifyDate = Carbon\Carbon::parse($expiryDate)->subDays($stopDays);
        } else {
            $stopNotifyDate = Carbon\Carbon::parse($expiryDate)->addDays($stopDays);
        }

        if ($startNotifyDate->eq($today)) {
            // dd('Send First Mail');
        }

        $interval = $today->diffInDays($startNotifyDate);

        if ($interval % $repeatAfter == 0) {
            // dd('Send Repeat Mail');
        }

        if ($stopNotifyDate->eq($today)) {
            // dd('Send Last Mail');
        }
    }
    //     use Illuminate\Support\Facades\Mail;
    // use Carbon\Carbon;

    function sendMailBeforeOrAfter70Days($date, $email, $days, $before = true)
    {
        // Convert the provided date to a Carbon instance for easier manipulation
        $date = Carbon\Carbon::parse($date);
        dd($date);

        // Calculate the target date either before or after 70 days
        $targetDate = $before ? $date->subDays($days) : $date->addDays($days);
        dd($targetDate);
        // Check if the target date is in the past or future
        $now = Carbon\Carbon::now();
        if ($before && $targetDate->isPast()) {
            return;  // Don't send email if the target date is in the past
        } elseif (!$before && $targetDate->isFuture()) {
            return;  // Don't send email if the target date is in the future
        }

        // Here, you can define the logic for sending the email
        // For example, using Laravel's built-in Mail functionality
        Mail::to($email)->send(new \App\Mail\ReminderEmail($targetDate));
    }
}
