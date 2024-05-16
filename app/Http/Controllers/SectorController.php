<?php

namespace App\Http\Controllers;

use App\Http\Resources\SectorResource;
use App\Models\Sector;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Auth;

class SectorController extends EntityController
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization('createTA', Sector::class);

        return $this->render([], 'Trainings/Sectors/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAuthorization('createTA', Sector::class);

        $sector = new Sector();

        $customErrors = $this->saveRequestToModel($request, $sector);

        if (count($customErrors) > 0) {
            throw ValidationException::withMessages($customErrors);

            return redirect()->back();
        }

        $toast = [
            'message' => 'New Sector Created Successfully',
            'status' => 200,
        ];

         $tabs = [
            'selectedTab' => 'Admin Settings',
            'selectedInnerTab' => 'Sectors',
        ];

        session(['toast' => $toast, 'tabs' => $tabs]);
        return redirect()->route(
            'trainings.index',
        );
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
     * @param Sector $sector
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Sector $sector)
    {
        $this->checkAuthorization('editTA', $sector);

        $data = [
            'sector' => SectorResource::make($sector),
        ];

        return $this->render($data, 'Trainings/Sectors/Edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Sector                    $sector
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sector $sector)
    {
        $this->checkAuthorization('updateTA', $sector);

        $customErrors = $this->saveRequestToModel($request, $sector);
        if (count($customErrors) > 0) {
            throw ValidationException::withMessages($customErrors);

            return redirect()->back();
        }

        $toast = [
            'message' => 'Sector Updated Successfully',
            'status' => 200,
        ];

         $tabs = [
            'selectedTab' => 'Admin Settings',
            'selectedInnerTab' => 'Sectors',
        ];

        session(['toast' => $toast, 'tabs' => $tabs]);
        return redirect()->route(
            'trainings.index',
        );
    }

    /**
     * @param Sector $sector
     *
     * @return void
     */
    public function destroy(Sector $sector)
    {
        $this->checkAuthorization('deleteTA', $sector);
        if (!$sector->canDelete()) {
            throw ValidationException::withMessages([
                'general' => 'This sector cannot be deleted as they are associated with Tasks. You must transfer their Tasks to another sector before attempting to delete again.',
            ]);
            return redirect()->back();
        }
        $sector->delete();

        $toast = [
            'message' => 'Sector Deleted Successfully',
            'status' => 200,
        ];

         $tabs = [
            'selectedTab' => 'Admin Settings',
            'selectedInnerTab' => 'Sectors',
        ];

        session(['toast' => $toast, 'tabs' => $tabs]);
        return redirect()->route('trainings.index');
    }

    /**
     * @param Request $request
     * @param Sector   $sector
     *
     * @return array
     */
    protected function saveRequestToModel(Request $request, Sector $sector)
    {
        $errors = [];
        try {
            // @TODO Find a better way of adding Unique constraints to name when updating modules
            $validationRules = $sector->getValidationRules();
            $validator = Validator::make($request->all(), $validationRules);
            $validator->validate();
            $sector->title = $request->get('title');
            $sector->order_number = $request->get('order_number');

            if ($request->get('fname_created_by')) {
                $sector->fname_created_by = $request->get('fname_created_by');
            }

            if ($request->get('lname_created_by')) {
                $sector->lname_created_by = $request->get('lname_created_by');
            }

            if ($request->get('user_id_created_by')) {
                $sector->user_id_created_by = $request->get('user_id_created_by');
            }

            if ($request->get('fname_modified_by')) {
                $sector->fname_modified_by = $request->get('fname_modified_by');
            }

            if ($request->get('lname_modified_by')) {
                $sector->lname_modified_by = $request->get('lname_modified_by');
            }

            if ($request->get('user_id_modified_by')) {
                $sector->user_id_modified_by = $request->get('user_id_modified_by');
            }

            $sector->description = $request->get('description') ?? '';

            $sector->status = ($request->get('status')) ?? 1;

            DB::transaction(function () use ($sector) {
                $sector->save();
            });
        } catch (ValidationException $ex) {
            $errors = $ex->errors();

            foreach ($errors as $key => $error) {
                $customErrors[$key] = $error;
            }
        } catch (QueryException $ex) {
            $message = $ex->getMessage();
            if (str_contains($message, 'sectors.title_unique')) {
                $customErrors['title'] = 'A sector with this exact title already exists.';
            } else {
                $customErrors['title'] = $message;
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
            'data' => SectorResource::collection(Sector::orderBy('order_number', 'ASC')->get()),
        ];

        return response()
            ->json([
                'message' => 'All the Sectors are Listed',
                'success' => true,
                'data' => $data,
            ], 200);
    }

    public function search(Request $request)
    {
        $data = [
            'data' => SectorResource::collection(Sector::where('title', 'LIKE', '%' . $request->sectorValue . '%')->orderBy('order_number', 'ASC')->paginate()),
        ];
        if (count($data) != 0) {
            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Sectors Record Found'
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'No Sectors Record Found'
            ];
            return response()->json($response, 404);
        }
    }
}
