<?php

namespace App\Http\Controllers;

use App\Http\Resources\PanelResource;
use App\Models\Panel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PanelController extends EntityController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkAuthorization( 'viewAny', Panel::class );

        $data = [
            'panels' => PanelResource::collection( Panel::with('airframe')->paginate() ),
        ];
        return $this->render( $data, 'Panels/Index' );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  Panel $panel
     * @return \Illuminate\Http\Response
     */
    public function show( Panel $panel )
    {
        $this->checkAuthorization( 'viewAny', $panel );

        $data = [
            'panel' => PanelResource::make( $panel ),
        ];
        return $this->render( $data, 'Panels/Show' );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
