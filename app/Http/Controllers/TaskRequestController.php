<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\SectorResource;
use App\Http\Resources\TaskRequestAssesmentChecklistResource;
use App\Http\Resources\TaskRequestAssesmentResource;
use App\Http\Resources\TaskRequestHistoryResource;
use App\Http\Resources\TaskRequestResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TrainingRoleResource;
use App\Models\File;
use App\Models\Notification;
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
use Carbon;

class TaskRequestController extends EntityController
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
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd('User', $request->all());
        $this->checkAuthorization('createAnyone', TaskRequest::class);

        if ($request->task_request == null) {
            $task_request = new TaskRequest;
        } else {
            $task_request = TaskRequest::find($request->task_request[0]['id']);
        }

        $customErrors = $this->saveRequestToModel($request, $task_request);

        if (count($customErrors) > 0) {
            throw ValidationException::withMessages($customErrors);

            return redirect()->back();
        } else {
            return redirect()->route('trainings.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        $this->checkAuthorization('UpdateAnyOne', TaskRequest::class);

        $task = Task::with(['sector', 'assesments', 'task_status' => function ($query) {
            $query->where('user_id', auth()->id());
        }, 'task_status.user', 'task_status.user.training_role','task_status.task_request_attachments','task_status.task_request_history' => function ($query) {
            $query->orderBy('created_at', 'DESC');
        }])->find($id);

        $taskRequest = TaskRequest::where('task_id', $task->id)->where('user_id', Auth::user()->id)->first();

        if ($taskRequest != null) {
            $taskRequest_attachments = FileResource::collection($taskRequest->getAttachments());
        } else {
            $taskRequest_attachments = [];
        }
        

        $data = [
            'scopes' => Config::get('constants.SCOPES'),
            'periods' => Config::get('constants.VALIDITY_PERIODS'),
            'sectors' => SectorResource::collection(Sector::paginate()),
            'training_roles' => TrainingRoleResource::collection(TrainingRole::paginate()),
            'task' => TaskResource::make($task),
            'my_task' => TaskRequestResource::collection(TaskRequest::where('task_id', $task->id)->where('user_id', Auth::user()->id)->with([
                'user',
                'task',
            ])->paginate()),
            'attachments' => FileResource::collection($task->getAttachments()),
            'taskRequest_attachments' => $taskRequest_attachments,
        ];

        return $this->render($data, 'Trainings/MyTrainings/TaskRequest/Edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param TaskRequest                    $taskRequest
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * @param Request $request
     * @param TaskRequest   $taskRequest
     *
     * @return array
     */
    protected function saveRequestToModel(Request $request, TaskRequest $taskRequest)
    {
   
        $errors = [];
        try {
            $validationRules = $taskRequest->getValidationRules();
            if ($request->revalidation_type != 0) {
                unset($validationRules['files']);
            }

            $validator = Validator::make($request->all(), $validationRules);
            $validator->validate();

            $current_user = Auth::user();
            $taskRequest->task_id = $request->task_id;
            $taskRequest->user_id = $current_user->id;
            $taskRequest->status = null;
            $taskRequest->filter_status = 'Pending';
            $taskRequest->training_role_id = $current_user->training_role_id;
            $taskRequest->request_status = 'Pending';

            DB::transaction(function () use ($taskRequest) {
                $taskRequest->save();
            });

            if ($request->revalidation_type != 0) {
                $task = Task::with('sector', 'assesments', 'assesments_checklist')->find($request->task_id);
                $assesments = $task->assesments;
                $assesment_checklists = $task->assesments_checklist;

                if (count($assesments) > 0) {
                    foreach ($assesments as $assesment) {
                        $taskRequestAssesment = new TaskRequestAssesment;
                        $taskRequestAssesment->task_id = $request->task_id;
                        $taskRequestAssesment->task_request_id = $taskRequest->id;
                        $taskRequestAssesment->assesment_id = $assesment->id;
                        $taskRequestAssesment->revalidation_type = $request->revalidation_type;
                        $taskRequestAssesment->save();

                        foreach ($assesment_checklists as $assesment_checklist) {
                            if ($assesment->id == $assesment_checklist->assesment_id) {
                                $taskRequestAssesmentChecklist = new TaskRequestAssesmentChecklist;
                                $taskRequestAssesmentChecklist->task_id = $request->task_id;
                                $taskRequestAssesmentChecklist->task_request_id = $taskRequest->id;
                                $taskRequestAssesmentChecklist->task_request_assesment_id = $taskRequestAssesment->id;
                                $taskRequestAssesmentChecklist->assesment_id = $assesment->id;
                                $taskRequestAssesmentChecklist->assesment_checklist_id = $assesment_checklist->id;
                                $taskRequestAssesmentChecklist->revalidation_type = $request->revalidation_type;
                                $taskRequestAssesmentChecklist->save();
                            }
                        }
                    }
                }
            }

            $oldAttacments = File::where(
                'entity_id', $taskRequest->id
            )->where(
                'entity_name', 'TaskRequest'
            )->get();

            if (count($oldAttacments) > 0) {
                foreach ($oldAttacments as $oldAttacment) {
                    $oldAttacment->deleteOnlyData();
                }
            }

            $attachments = [];
            if ($request->files->all('files')) {
                $publicStorageDestination = $taskRequest->getPublicFolder();

                foreach ($request->files->all('files') as $file) {
                    $filename = $file->getClientOriginalName();
                    if ($taskRequest->willOverwriteExistingFile($filename)) {
                        throw ValidationException::withMessages([
                            'image' => "A Document with the name {$filename} is already linked to an  this Task . Please save the attachment with a unique name",
                        ]);
                    }

                    $attachment = new File();
                    $attachment->setEntity($taskRequest);
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
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            $customErrors['general'] = 'Untrapped error. Please email your site administrator the following: ' . $message;
        }

        return $errors;
    }

    public function validatesRequest($taskRequestId)
    {
        $taskRequest = TaskRequest::with(
            'task',
            'user',
            'task.sector',
        )->find($taskRequestId);
        $task = Task::find($taskRequest->task_id);
        $data = [
            'taskRequest' => TaskRequestResource::make($taskRequest),
            'attachments' => FileResource::collection($taskRequest->getAttachments()),
            'training_material' => FileResource::collection($task->getAttachments()),
        ];

        return $this->render($data, 'Trainings/AwaitingSignOff/ValidateRequest/Edit');
    }

    public function reviewDocument($taskRequestId)
    {
        $taskRequest = TaskRequest::with('task', 'user', 'task.sector')->find($taskRequestId);
        $data = [
            'taskRequest' => TaskRequestResource::make($taskRequest),
            'attachments' => FileResource::collection($taskRequest->getAttachments()),
        ];
        return $this->render($data, 'Trainings/AwaitingSignOff/ValidateRequest/ReviewDocument');
    }

    public function validateAssesment($taskRequestId)
    {
        $taskRequest = TaskRequest::with('task', 'user', 'task.sector', 'task.assesments.assesment_checklist')->find($taskRequestId);
        $data = [
            'taskRequest' => TaskRequestResource::make($taskRequest),
            'attachments' => FileResource::collection($taskRequest->getAttachments()),
        ];
        return $this->render($data, 'Trainings/AwaitingSignOff/ValidateRequest/ValidateAssesment');
    }

    public function signOffDocument(Request $request)
    {
        $validity = $this->taskValidity($request->task_request['task']['validity_number'], $request->task_request['task']['validity_period']);
        $taskRequest = TaskRequest::find($request->task_request['id']);
        $completed_date = $taskRequest->updated_at;
        $file_resource = FileResource::collection($taskRequest->getAttachments());

        $previous_expiry = $taskRequest->valid_upto;

        if ($request->status == 1) {
            $taskRequest->status = $request->status;
            $taskRequest->display_status = 'Active';
            $taskRequest->filter_status = 'Active';
            $taskRequest->request_status = null;
            $taskRequest->approval_status = 'Accepted';
            $taskRequest->valid_upto = $validity;
            $taskRequest->save();

            $task_req_result = 'Completed';
            $comment = null;
        } else {
            $taskRequest->status = $request->status;
            $taskRequest->request_status = null;
            $taskRequest->filter_status = 'Action Required';
            $taskRequest->save();
            $task_req_result = 'Rejected';
            $comment = $request->comment;      
        }

        $filePath = str_replace('public', '/storage', $file_resource[0]->filepath);

        $taskRequestHistory = new TaskRequestHistory;
        $taskRequestHistory->task_req_id = $taskRequest->id;
        $taskRequestHistory->task_req_result = $task_req_result;
        $taskRequestHistory->task_req_comment = $comment;
        $taskRequestHistory->previous_expiry = $previous_expiry;
        $taskRequestHistory->completed_date = $completed_date;
        $taskRequestHistory->modified_date = $taskRequest->updated_at;
        $taskRequestHistory->completed_by = Auth::user()->fname . ' ' . Auth::user()->lname;
        $taskRequestHistory->task_req_document = $filePath;
        $taskRequestHistory->task_req_document_name = $file_resource[0]->name;
        $taskRequestHistory->save();

        $tabs = [
            'selectedTab' => 'Awaiting Sign Off',
            'selectedInnerTab' => 'Tasks',
        ];

        session(['tabs' => $tabs]);
        return redirect()->route('trainings.index');
        // $data = [
        //     'taskRequest' => TaskRequestResource::make($taskRequest),
        //     'attachments' => FileResource::collection($taskRequest->getAttachments()),
        // ];

        // return $this->render($data, 'Trainings/AwaitingSignOff/ValidateRequest/ReviewDocument');
    }

    public function signOffAssesment(Request $request)
    {
        $validity = $this->taskValidity($request->taskrequest['task']['validity_number'], $request->taskrequest['task']['validity_period']);

        $taskRequest = TaskRequest::find($request->taskrequest['id']);
        $taskRequest->status = $request->taskrequestresult;
        $taskRequest->request_status = null;
        $completed_date = $taskRequest->updated_at;

        if ($request->taskrequestresult == 1) {
            $taskRequest->display_status = 'Active';
            $taskRequest->filter_status = 'Active';
            $taskRequest->approval_status = 'Accepted';
            $taskRequest->valid_upto = $validity;
        }
        else{
            $taskRequest->filter_status = 'Action Required';
        }

        $taskRequest->save();

        $assesmentResults = $request->assesmentresults;
        $assesmentComments = $request->assesmentComment;
        $assesmentSectionResults = $request->sectionsResult;

        foreach ($assesmentResults as $key => $assesmentResult) {
            $taskRequestAssesmentChecklist = TaskRequestAssesmentChecklist::where('assesment_checklist_id', $key)->first();
            $taskRequestAssesmentChecklist->status = $assesmentResult;
            $taskRequestAssesmentChecklist->comment = isset($assesmentComments[$key]) ? $assesmentComments[$key] : null;
            $taskRequestAssesmentChecklist->save();
        }

        $assesmant_result_data = serialize([$assesmentResults, $assesmentComments, $assesmentSectionResults]);
        // dd($assesmant_result_data);

        $today = Carbon\Carbon::now();
        $taskRequestHistory = new TaskRequestHistory;
        $taskRequestHistory->task_req_id = $request->taskrequest['id'];
        $taskRequestHistory->assesmant_result_data = $assesmant_result_data;

        if ($request->taskrequestresult == 1) {
            $taskRequestHistory->task_req_result = 'Completed';
        } else {
            $taskRequestHistory->task_req_result = 'Rejected';
        }

        $taskRequestHistory->task_req_comment = $request->comment;
        $taskRequestHistory->completed_date = $completed_date;
        $taskRequestHistory->modified_date = $today->format('Y-m-d');
        $taskRequestHistory->completed_by = Auth::user()->fname . ' ' . Auth::user()->lname;
        // $taskRequestHistory->task_req_document      =  $this->task_req_document,
        $taskRequestHistory->save();

        // foreach ($assesmentResults as $assesmentResult) {

        // $taskRequestAssesment = TaskRequestAssesment::find($request->taskrequest['id']);
        // $taskRequestAssesment->task_id =  $request->task_id;
        // $taskRequestAssesment->task_request_id = $taskRequest->id;
        // $taskRequestAssesment->assesment_id = $assesment->id;
        // $taskRequestAssesment->revalidation_type = $request->revalidation_type;
        // $taskRequestAssesment->save();
        //  }

        // dd('check');
        $tabs = [
            'selectedTab' => 'Awaiting Sign Off',
            'selectedInnerTab' => 'Tasks',
        ];

        session(['tabs' => $tabs]);
        return redirect()->route('trainings.index');
        // $data = [
        //     'taskRequest' => TaskRequestResource::make($taskRequest),
        //     'attachments' => FileResource::collection($taskRequest->getAttachments()),
        // ];

        // return $this->render($data, 'Trainings/AwaitingSignOff/ValidateRequest/ReviewDocument');
    }

    public function viewHistory($historyId)
    {
        $taskhistory = TaskRequestHistory::with('taskRequest.user', 'taskRequest.task.assesments.assesment_checklist')->find($historyId);

        $data = [
            'task_history' => TaskRequestHistoryResource::make($taskhistory),
            'history_data' => unserialize($taskhistory->assesmant_result_data),
        ];
        // dd($data);
        return $this->render($data, 'Trainings/MyTrainings/TaskRequest/ViewTaskHistory');
    }

    public function taskValidity($number, $period)
    {
        if ($period == 'Days') {
            $days = 1;
        }

        if ($period == 'Weeks') {
            $days = 7;
        }

        if ($period == 'Months') {
            $days = 30;
        }

        if ($period == 'Years') {
            $days = 365;
        }

        $currentTime = Carbon\Carbon::now();
        $date = $currentTime->addDays($number * $days);
        $formattedDate = $date->format('Y-m-d');
        return $formattedDate;
    }
}
