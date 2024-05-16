<?php

namespace App\Http\Controllers;

use App\Http\Resources\AssesmentChecklistResource;
use App\Http\Resources\AssesmentResource;
use App\Http\Resources\FileResource;
use App\Http\Resources\SectorResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TrainingRoleResource;
use App\Models\Assesment;
use App\Models\AssesmentChecklist;
use App\Models\File;
use App\Models\Sector;
use App\Models\Task;
use App\Models\TaskRequest;
use App\Models\TaskRequestAssesment;
use App\Models\TaskRequestAssesmentChecklist;
use App\Models\TaskRequestHistory;
use App\Models\TrainingRole;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Auth;

class TaskController extends EntityController
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
        $this->checkAuthorization('createTA', Task::class);
        $data = [
            'revalidation_types' => Config::get('constants.REVALIDATION_TYPES'),
            'validity_periods' => Config::get('constants.VALIDITY_PERIODS'),
            'sectors' => SectorResource::collection(Sector::paginate()),
            'training_roles' => TrainingRoleResource::collection(TrainingRole::paginate()),
        ];
        return $this->render($data, 'Trainings/Tasks/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAuthorization('saveTA', Task::class);

        $task = new Task();

        $customErrors = $this->saveRequestToModel($request, $task);

        if (count($customErrors) > 0) {
            throw ValidationException::withMessages($customErrors);

            return redirect()->back();
        }
        $toast = [
            'message' => 'New Task Created Successfully',
            'status' => 200,
        ];
        $tabs = [
            'selectedTab' => 'Admin Settings',
            'selectedInnerTab' => 'Tasks',
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $this->checkAuthorization('editTA', $task);
        $task = Task::with('sector','task_status', 'assesments.assesment_checklist')->find($task->id);

        $data = [
            'revalidation_types' => Config::get('constants.REVALIDATION_TYPES'),
            'validity_periods' => Config::get('constants.VALIDITY_PERIODS'),
            'sectors' => SectorResource::collection(Sector::paginate()),
            'training_roles' => TrainingRoleResource::collection(TrainingRole::paginate()),
            'task' => TaskResource::make($task),
            'attachments' => FileResource::collection($task->getAttachments()),
        ];

        return $this->render($data, 'Trainings/Tasks/Edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Task                    $task
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        dd('Oops');
        $this->checkAuthorization('updateTA', $task);
        $customErrors = $this->saveRequestToModel($request, $task);
        if (count($customErrors) > 0) {
            throw ValidationException::withMessages($customErrors);

            return redirect()->back();
        }

        $toast = [
            'message' => 'Task Updated Successfully',
            'status' => 200,
        ];
        $tabs = [
            'selectedTab' => 'Admin Settings',
            'selectedInnerTab' => 'Tasks',
        ];
        session(['toast' => $toast, 'tabs' => $tabs]);
        return redirect()->route('trainings.index');
    }



    public function updateFile(Request $request, Task $task)
    {
        $task = Task::find($request->id);
        $this->checkAuthorization('updateTA', $task);
        $customErrors = $this->saveRequestToModel($request, $task);
        if (count($customErrors) > 0) {
            throw ValidationException::withMessages($customErrors);

            return redirect()->back();
        }

        $toast = [
            'message' => 'Task Updated Successfully',
            'status' => 200,
        ];
        $tabs = [
            'selectedTab' => 'Admin Settings',
            'selectedInnerTab' => 'Tasks',
        ];
        session(['toast' => $toast, 'tabs' => $tabs]);
        return redirect()->route('trainings.index');
    }

    /**
     * @param Task $task
     *
     * @return void
     */
    public function destroy(Task $task)
    {
        $this->checkAuthorization('deleteTA', $task);
        try {
            $taskRequests = TaskRequest::where('task_id', $task->id)->get();
            if (count($taskRequests) > 0) {
                foreach ($taskRequests as $taskRequest) {
                    $taskRequestId = $taskRequest->id;
                    $taskRequestAssesments = TaskRequestAssesment::where('task_request_id', $taskRequestId)->get();
                    $taskRequestAssesmentChecklists = TaskRequestAssesmentChecklist::where('task_request_id', $taskRequestId)->get();
                    $taskRequestHistories = TaskRequestHistory::where('task_req_id', $taskRequestId)->get();

                    if (count($taskRequestHistories) > 0) {
                        foreach ($taskRequestHistories as $taskRequestHistory) {
                            $taskRequestHistory->delete();
                        }
                    }

                    if (count($taskRequestAssesmentChecklists) > 0) {
                        foreach ($taskRequestAssesmentChecklists as $taskRequestAssesmentChecklist) {
                            $taskRequestAssesmentChecklist->delete();
                        }
                    }

                    if (count($taskRequestAssesments) > 0) {
                        foreach ($taskRequestAssesments as $taskRequestAssesment) {
                            $taskRequestAssesment->delete();
                        }
                    }

                    $taskRequest->delete();
                }
            }
            $task->delete();
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            return response()
                ->json(['message' => $message], 500);
        }

        $toast = [
            'message' => 'Task Deleted Successfully',
            'status' => 200,
        ];

        $tabs = [
            'selectedTab' => 'Admin Settings',
            'selectedInnerTab' => 'Tasks',
        ];
        session(['toast' => $toast, 'tabs' => $tabs]);
        return redirect()->route('trainings.index');
    }

    /**
     * @param Request $request
     * @param Task   $task
     *
     * @return array
     */
    protected function saveRequestToModel(Request $request, Task $task)
    {
        $errors = [];
        try {
            // @TODO Find a better way of adding Unique constraints to name when updating modules
            $validationRules = $task->getValidationRules();
            if ($request->revalidation_type == 0) {
                unset($validationRules['sections']);
            }
            $validator = Validator::make($request->all(), $validationRules);
            $validator->validate();

            $task->task_number = $request->get('task_number');
            $task->title = $request->get('title');
            $task->short_name = $request->get('short_name');
            $task->external_id = $request->get('external_id') ?? '';
            $task->revalidation_type = $request->get('revalidation_type');
            $task->description = $request->get('description') ?? '';
            $task->validity_number = $request->get('validity_number');
            $task->validity_period = $request->get('validity_period');
            $task->sector_id = $request->get('sector_id');
            $task->is_mandatory = $request->get('is_mandatory');
            DB::transaction(function () use ($task) {
                $task->save();
            });

            if ($request->get('revalidation_type') != 0) {
                $sections = $request->get('sectionItems');

                foreach ($sections as $section) {
                    if (isset($section['section_id'])) {
                        $assesment = Assesment::find($section['section_id']);
                    } else {
                        $assesment = new Assesment;
                    }

                    $assesment->task_id = $task->id;
                    $assesment->title = $section['value'];
                    $assesment->revalidation_type = $task->revalidation_type;
                    $assesment->status = 1;
                    $assesment->save();

                    $sectionItems = $section['sectionItems'];

                    foreach ($sectionItems as $sectionItem) {
                        if (isset($sectionItem['section_item_id'])) {
                            $assesmentChecklist = AssesmentChecklist::find($sectionItem['section_item_id']);
                        } else {
                            $assesmentChecklist = new AssesmentChecklist;
                        }

                        $assesmentChecklist->task_id = $task->id;
                        $assesmentChecklist->assesment_id = $assesment->id;
                        $assesmentChecklist->asigned_check_num = $sectionItem['number'];
                        $assesmentChecklist->check_value = $sectionItem['value'];

                        if ($request->get('revalidation_type') == 1) {
                            $assesmentChecklist->check_description = $sectionItem['description'];
                        }
                        $assesmentChecklist->status = 1;
                        $assesmentChecklist->save();
                    }
                }
            }

            $task_roles = $request->get('training_role_ids');
            $db_task_roles = DB::table('task_role')
                ->where('task_id', $task->id)
                ->get();

            $taskRoleIds = $db_task_roles->pluck('training_role_id')->toArray();

            if (count($taskRoleIds) == 0) {
                $task->roles()->attach($task_roles);
            } else {
                foreach ($task_roles as $task_role) {
                    if (in_array($task_role, $taskRoleIds)) {
                        continue;  // Skip this iteration if sector ID is already in $task_roles
                    } else {
                        $task->roles()->attach($task_role);
                    }
                }
            }

            $rolesToRemove = array_diff($taskRoleIds, $task_roles);
            if (!empty($rolesToRemove)) {
                $task->roles()->detach($rolesToRemove);
            }

            $attachments = [];
            if ($request->files->all('files')) {
                $publicStorageDestination = $task->getPublicFolder();

                foreach ($request->files->all('files') as $file) {
                    $filename = $file->getClientOriginalName();
                    if ($task->willOverwriteExistingFile($filename)) {
                        throw ValidationException::withMessages([
                            'image' => "An attachment with the name {$filename} is already linked to a Task. Please save the attachment with a unique name or remove the existing attachment",
                        ]);
                    }
                    $attachment = new File();
                    $attachment->setEntity($task);
                    $attachment->saveToStorage($publicStorageDestination, $file, $filename);
                    $attachments[] = $attachment;
                    $attachments[] = $attachment;
                }
            }

            DB::transaction(function () use ($attachments) {
                foreach ($attachments as $attachment) {
                    $attachment->save();
                }
            });
        } catch (ValidationException $ex) {
            $errors = $ex->errors();

            foreach ($errors as $key => $error) {
                $customErrors[$key] = $error;
            }
        } catch (QueryException $ex) {
            $message = $ex->getMessage();
            if (str_contains($message, 'tasks.task_number_unique')) {
                $customErrors['task_number'] = 'A task with this exact task_number already exists.';
            } else {
                $customErrors['task_number'] = $message;
            }
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            $customErrors['general'] = 'Untrapped error. Please email your site administrator the following: ' . $message;
        }
        return $errors;
    }

    public function viewAllData(Request $request)
    {
        
        $data = [
            'data' => TaskResource::collection(Task::with('sector', 'roles')->orderBy('task_number', 'ASC')->get()),
        ];

        return response()
            ->json([
                'message' => 'All the Tasks are Listed',
                'success' => true,
                'data' => $data,
            ], 200);
    }

    public function search(Request $request)
    {
        $data = [
            'data' => TaskResource::collection(Task::where('title', 'LIKE', '%' . $request->taskValue . '%')->with('sector', 'roles')->orderBy('task_number', 'ASC')->get()),
        ];
        if (count($data) != 0) {
            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Tasks Record Found'
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'No Task Record Found'
            ];
            return response()->json($response, 404);
        }
    }

    public function sortMyTasks(Request $request)
    {
        $training_role_id = Auth::user()->training_role_id;
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
           

            $descendingOrder = $request->descending;

            if ($descendingOrder == 1) {
                $tasksWithStatusSorted = $tasksWithStatus->sortByDesc(function ($task) {
                    return $task->task_status[0]->valid_upto;
                });
            } else {
                $tasksWithStatusSorted = $tasksWithStatus->sortBy(function ($task) {
                    return $task->task_status[0]->valid_upto;
                });
            }

            $sortedTasks = $tasksWithoutStatus->merge($tasksWithStatusSorted);

        $data = [
            'tasks' => TaskResource::collection($sortedTasks),
        ];

        return response()
            ->json([
                'message' => 'All the Tasks are Listed',
                'success' => true,
                'data' => $data,
            ], 200);
    }
}
