<?php

namespace App\Http\Controllers;

use App\Http\Resources\AeroplaneResource;
use App\Http\Resources\OwnerResource;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OwnerController extends EntityController {

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $this->checkAuthorization( 'viewAny', Owner::class );

        $data = [
            'owners' => OwnerResource::collection( Owner::with('aeroplanes')->paginate() ),
        ];

        return $this->render( $data, 'Owners/Index' );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->checkAuthorization( 'create', Owner::class );

        return $this->render( [], 'Owners/Create' );
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
        $this->checkAuthorization( 'create', Owner::class );

        $owner        = new Owner();
        $customErrors = $this->saveRequestToModel( $request, $owner );
        // dd($customErrors);
        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );

            return redirect()->back();
        }

        return redirect()->route( 'owners.index', $owner );
    }

    /**
     * Display the specified resource.
     *
     * @param Owner $owner
     *
     * @return \Inertia\Response
     */
    public function show( Owner $owner )
    {
        $this->checkAuthorization( 'view', $owner );

        $data = [
            'owner' => OwnerResource::make( $owner->load('aeroplanes') ),
        ];

        return $this->render( $data, 'Owners/Show' );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Owner $owner
     *
     * @return \Illuminate\Http\Response
     */
    public function edit( Owner $owner )
    {
        $this->checkAuthorization( 'update', $owner );

        $data = [
            'owner' => OwnerResource::make( $owner ),
            'aeroplanes' => AeroplaneResource::collection( $owner->aeroplanes ),
        ];

        return $this->render( $data, 'Owners/Edit' );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Owner                    $owner
     *
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, Owner $owner )
    {
        $this->checkAuthorization( 'update', $owner );

        $customErrors = $this->saveRequestToModel( $request, $owner );
        if ( count( $customErrors ) > 0 )
        {
            throw ValidationException::withMessages( $customErrors );

            return redirect()->back();
        }

        return redirect()->route( 'owners.index' );
    }

    /**
     * @param Owner $owner
     *
     * @return void
     */
    public function destroy( Owner $owner )
    {
        $this->checkAuthorization( 'delete', $owner );
        if( !$owner->canDelete() ){
            throw ValidationException::withMessages([
                'general' => "This Owner cannot be deleted as they are associated with Aeroplanes. You must transfer their planes to another owner before attempting to delete again.",
            ]);
            return redirect()->back();
        }
        $owner->delete();

        return redirect()->route( 'owners.index' );
    }


    /**
     * @param Request $request
     * @param Owner   $owner
     *
     * @return array
     */
    protected function saveRequestToModel( Request $request, Owner $owner )
    {
        $errors = [];
        try
        {
            // @TODO Find a better way of adding Unique constraints to name when updating modules
            $validationRules = $owner->getValidationRules();
            $validator       = Validator::make( $request->all(), $validationRules );
            $validator->validate();

            $owner->name        = $request->get( 'name' );
            $owner->email       = $request->get( 'email' );
            $owner->phone       = $request->get( 'phone' );
            $owner->description = $request->get( 'description' ) ?? '';
            $owner->active      = $request->get( 'active' ) ?? 0;
          

          DB::transaction( function () use ( $owner ) {
                $owner->save();
            } );

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
            $message = $ex->getMessage();
            if ( str_contains( $message, 'owners.name_unique' ) )
            {
                $customErrors[ 'name' ] = 'An Owner with this exact name already exists.';
            } else
            {
                $customErrors[ 'name' ] = $message;
            }
        }

        catch ( \Exception $ex )
        {
            $message                   = $ex->getMessage();
            $customErrors[ 'general' ] = "Untrapped error. Please email your site administrator the following: " . $message;
        }

        return $errors;
    }
}
