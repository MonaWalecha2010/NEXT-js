<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Http\Resources\NoticeResource;
use App\Http\Resources\NoticeTypeResource;
use App\Http\Resources\TrainingRoleResource;
use App\Mail\NoticeReminderMail;
use App\Models\File;
use App\Models\MarkReadNotice;
use App\Models\Notice;
use App\Models\NoticeDocumentHistory;
use App\Models\NoticeType;
use App\Models\TrainingRole;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Auth;
use Mail;

class NoticeController extends EntityController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $training_role_id = Auth::user()->training_role_id;
        if ($training_role_id != 4 && $training_role_id != 5) {

            // $notice = Notice::with('notice_type')->get();
            // dd($notice);
            $data = [
                'toast' => session()->get('toast'),

                'notice_types' => NoticeTypeResource::collection(NoticeType::get()),
                'notices' => NoticeResource::collection(Notice::with('notice_type')->orderby('number', 'ASC')->paginate(20)),
                'my_notices' => NoticeResource::collection(Notice::with(['roles' => function ($query) {
                    $query->where('training_role_id', Auth::user()->training_role_id);
                },'notice_type',
                    'markReadStatus' => function ($query) {
                        $query->where('user_id', Auth::user()->id);
                    }])->where('status',1)->orderby('number', 'ASC')->paginate()),
            ];
            session()->forget('toast');
            return $this->render($data, 'Notices/Index');
        } else {
            $data = [
                'notice_types' => NoticeTypeResource::collection(NoticeType::paginate()),
                'my_notices' => NoticeResource::collection(Notice::with(['roles' => function ($query) {
                    $query->where('training_role_id', Auth::user()->training_role_id);
                },'notice_type',
                    'markReadStatus' => function ($query) {
                        $query->where('user_id', Auth::user()->id);
                    }])->where('status',1)->orderby('number', 'ASC')->get()),
            ];
            return $this->render($data, 'Notices/MyNotices');
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization('createTA', Notice::class);
        $data = [
            'training_roles' => TrainingRoleResource::collection(TrainingRole::paginate()),
            'notice_types' => NoticeTypeResource::collection(NoticeType::paginate()),
        ];

        return $this->render($data, 'Notices/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->checkAuthorization('saveTA', Notice::class);

        $notice = new Notice();

        $customErrors = $this->saveRequestToModel($request, $notice);

        if (count($customErrors) > 0) {
            throw ValidationException::withMessages($customErrors);

            return redirect()->back();
        }

        $toast = [
            'message' => 'New Notice Created Successfully',
            'status' => 200,
        ];

        session()->put('toast', $toast);
        return redirect()->route('notices.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notice $notice)
    {
        $this->checkAuthorization('viewAnyOne', $notice);

        $notice = Notice::with(['notice_type','notice_documents_history' => function ($query) {
            $query->orderBy('created_at', 'DESC');
        },'markReadStatus' => function ($query) {
            $query->where('user_id', Auth::user()->id);
        }])->find($notice->id);

        $data = [
            'notice_types' => NoticeTypeResource::collection(NoticeType::paginate()),
            'notice' => NoticeResource::make($notice),
            'attachments' => FileResource::collection($notice->getAttachments()),
        ];
        return $this->render($data, 'Notices/Show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notice $notice)
    {
        $this->checkAuthorization('editTA', $notice);
        $notice = Notice::with(['notice_type','roles', 'notice_documents_history' => function ($query) {
            $query->orderBy('created_at', 'DESC');
        }])->find($notice->id);

        $data = [
            'training_roles' => TrainingRoleResource::collection(TrainingRole::paginate()),
            'notice_types' => NoticeTypeResource::collection(NoticeType::paginate()),
            'notice' => NoticeResource::make($notice),
            'attachments' => FileResource::collection($notice->getAttachments()),
        ];
        // dd($data);
        return $this->render($data, 'Notices/Edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notice $notice)
    {
        $this->checkAuthorization('updateTA', $notice);
        $customErrors = $this->saveRequestToModel($request, $notice);
        if (count($customErrors) > 0) {
            throw ValidationException::withMessages($customErrors);

            return redirect()->back();
        }

        $toast = [
            'message' => 'Notice Updated Successfully',
            'status' => 200,
        ];

        session()->put('toast', $toast);
        return redirect()->route('notices.index');
    }

    public function updateFile(Request $request)
    {
        $notice = Notice::find($request->id);
        $this->checkAuthorization('updateTA', $notice);
        $customErrors = $this->saveRequestToModel($request, $notice);
        if (count($customErrors) > 0) {
            throw ValidationException::withMessages($customErrors);

            return redirect()->back();
        }

        $toast = [
            'message' => 'Notice Updated Successfully',
            'status' => 200,
        ];

        session()->put('toast', $toast);
        return redirect()->route('notices.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * @param Request $request
     * @param Notice   $notice
     *
     * @return array
     */
    protected function saveRequestToModel(Request $request, Notice $notice)
    {
        $errors = [];
        try {
            $validationRules = $notice->getValidationRules();
            if ($notice->id != null) {
                unset($validationRules['files']);
            }
            $validator = Validator::make($request->all(), $validationRules);
            $validator->validate();
            $notice->title = $request->get('title');
            $notice->number = $request->get('number');
            $notice->notice_type_id = $request->get('notice_type') ?? '';
            $notice->comment = $request->get('comment');

            if ($request->get('created_by_fname')) {
                $notice->created_by_fname = $request->get('created_by_fname');
            }

            if ($request->get('created_by_lname')) {
                $notice->created_by_lname = $request->get('created_by_lname');
            }

            if ($request->get('modified_by_fname')) {
                $notice->modified_by_fname = $request->get('modified_by_fname');
            }

            if ($request->get('modified_by_lname')) {
                $notice->modified_by_lname = $request->get('modified_by_lname');
            }

            // if ($request->get('status')) {
                $notice->status = 1;
            // }

            DB::transaction(function () use ($notice) {
                $notice->save();
            });

            $notice_roles = $request->get('training_role_ids');
            $db_notice_roles = DB::table('notice_role')
                ->where('notice_id', $notice->id)
                ->get();

            $noticeRoleIds = $db_notice_roles->pluck('training_role_id')->toArray();

            if (count($noticeRoleIds) == 0) {
                $notice->roles()->attach($notice_roles);
            } else {
                foreach ($notice_roles as $notice_role) {
                    if (in_array($notice_role, $noticeRoleIds)) {
                        continue;  // Skip this iteration if sector ID is already in $notice_roles
                    } else {
                        $notice->roles()->attach($notice_role);
                    }
                }
            }

            $rolesToRemove = array_diff($noticeRoleIds, $notice_roles);
            if (!empty($rolesToRemove)) {
                $notice->roles()->detach($rolesToRemove);
            }

            $oldAttacments = File::where(
                'entity_id', $notice->id
            )->where(
                'entity_name', 'Notice'
            )->get();

            if (count($oldAttacments) > 0) {
                foreach ($oldAttacments as $oldAttacment) {
                    $oldAttacment->deleteOnlyData();
                }
            }

            $attachments = [];
            if ($request->files->all('files')) {
                $publicStorageDestination = $notice->getPublicFolder();

                foreach ($request->files->all('files') as $file) {
                    $filename = $file->getClientOriginalName();
                    if ($notice->willOverwriteExistingFile($filename)) {
                        throw ValidationException::withMessages([
                            'image' => "An attachment with the name {$filename} is already linked to a notice. Please save the attachment with a unique name or remove the existing attachment",
                        ]);
                    }
                    $attachment = new File();
                    $attachment->setEntity($notice);
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

            $file_resource = FileResource::collection($notice->getAttachments());

            $documents_in_history = NoticeDocumentHistory::where(
                'notice_id', $notice->id
            )->get();

            $version = 'V' . count($documents_in_history) + 1;

            if (count($documents_in_history) > 0) {
                foreach ($documents_in_history as $document) {
                    $document->status = 'Withdrawn';
                    $document->save();
                }
            }

        $filePath = str_replace('public', '/storage', $file_resource[0]->filepath);

            $noticeDocumentHistory = new NoticeDocumentHistory;
            $noticeDocumentHistory->notice_id = $notice->id;
            $noticeDocumentHistory->version = $version;
            $noticeDocumentHistory->status = 'Current';
            $noticeDocumentHistory->issued_by = Auth::user()->fname . ' ' . Auth::user()->lname;
            $noticeDocumentHistory->notice_document = $filePath;
            $noticeDocumentHistory->notice_document_name = $file_resource[0]->name;
            $noticeDocumentHistory->save();
        } catch (ValidationException $ex) {
            $errors = $ex->errors();

            foreach ($errors as $key => $error) {
                $customErrors[$key] = $error;
            }
        } catch (QueryException $ex) {
            $message = $ex->getMessage();
            if (str_contains($message, 'notices.number_unique')) {
                $customErrors['number'] = 'A notice with this exact number already exists.';
            } else {
                $customErrors['number'] = $message;
            }
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            $customErrors['general'] = 'Untrapped error. Please email your site administrator the following: ' . $message;
        }
        return $errors;
    }

    public function markRead(Request $request)
    {
        $markReadNotice = new MarkReadNotice;
        $markReadNotice->notice_id = $request->notice_id;
        $markReadNotice->user_id = Auth::user()->id;
        $markReadNotice->save();

        return redirect()->route('notices.index');
    }

    public function trackReaders($notice_id)
    {        
        $this->checkAuthorization('view_Admin_TA', Notice::class);

        $notice = Notice::with(['roles.users.readStatus' => function ($query) use ($notice_id) {
            $query->where('notice_id', $notice_id);
        }, 'markReadStatus' => function ($query) {
            $query->where('user_id', Auth::user()->id);
        }])->find($notice_id);
        // dd($notice);
        $data = [
            // 'notice_types' => NoticeTypeResource::collection(NoticeType::paginate()),
            'notice' => NoticeResource::make($notice),
            // 'attachments' => FileResource::collection($notice->getAttachments()),
        ];

        return $this->render($data, 'Notices/TrackReader');
    }

    public function remind(Request $request)
    {
        $notice = Notice::find($request->notice_id);
        $user = User::find($request->user_id);
        $noticeReminderMail = new NoticeReminderMail($user, $notice);
        $sentMail = Mail::to($user->email)->send($noticeReminderMail);

        if ($sentMail) {
            return response()
                ->json(['success' => true, 'message' => 'Reminder Send Successfully'], 200);
        } else {
            return response()
                ->json(['success' => false, 'message' => 'Reminder Send Successfully'], 424);
        }
    }

    public function withdrawNotice(Request $request)
    {

        $notice = Notice::find($request->id);
        $notice->status = 0;
        $notice->save();

        $toast = [
            'message' => 'Notice Witdrawn Successfully',
            'status' => 200,
        ];

        session()->put('toast', $toast);
        return redirect()->route('notices.index');

        return redirect()->route('notices.index');

    }

    public function createNoticeType(Request $request)
    {
        $notice_type = new NoticeType;

        // dd($notice_type);

        $errors = [];
        try {
            $validationRules = $notice_type->getValidationRules();
            $validator = Validator::make($request->all(), $validationRules);
            $validator->validate();

            $notice_type->name = $request->name;
            $notice_type->slug = Str::slug($request->name);
            $notice_type->created_by = Auth::user()->id;
            $notice_type->save();
        } catch (ValidationException $ex) {
            $errors = $ex->errors();

            foreach ($errors as $key => $error) {
                $customErrors[$key] = $error;
            }
        } catch (QueryException $ex) {
            $message = $ex->getMessage();
            if (str_contains($message, 'notice_types.name.unique')) {
                $customErrors['name'] = 'A Notice Type with this exact name already exists.';
            } else {
                $customErrors['name'] = $message;
            }
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            $customErrors['general'] = 'Untrapped error. Please email your site administrator the following: ' . $message;
        }

        $data = [
            'notice_types' => NoticeTypeResource::collection(NoticeType::paginate()),
        ];

        if (count($errors) == 0) {
            return response()
                ->json(['success' => true, 'data' => $data, 'message' => 'Notice Type Created Successfully'], 200);
        } else {
            return response()
                ->json(['success' => false, 'errors' => $errors], 200);
        }
    }

    public function search(Request $request)
    {
        // if (!empty($request->noticeValue)) {
            $data = [
                'data' => NoticeResource::collection(Notice::with('notice_type')->where('title', 'LIKE', '%' . $request->noticeValue . '%')->orderBy('number', 'ASC')->get()),
            ];
        // } else {
        //     $data = [
        //         'data' => NoticeResource::collection(Notice::with('notice_type')->orderBy('number', 'ASC')->get()),
        //     ];
        // }
        
       

        if (count($data) != 0) {
            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Notices Record Found'
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'No Notice Record Found'
            ];
            return response()->json($response, 404);
        }
    }

    public function getNoticeTypes(){
        $data = [
            'notice_types' => NoticeTypeResource::collection(NoticeType::paginate(20)),
        ];

        if (count($data) > 0) {
            return response()
                ->json(['success' => true, 'data' => $data, 'message' => 'Notice Type Created Successfully'], 200);
        } else {
            return response()
                ->json(['success' => false, 'errors' => $errors], 200);
        }


    }
}
