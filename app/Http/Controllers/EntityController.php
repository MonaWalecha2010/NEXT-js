<?php

namespace App\Http\Controllers;

use App\Http\Resources\AirframeWorkpackResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EntityController extends AbstractController {

    /**
     * @var Request|null
     */
    protected $request = null;

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct( Request $request )
    {
        $this->request = $request;
    }


    /**
     * @param array $data
     * @param       $template
     * @param       $render_method
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Inertia\Response|string
     */
    public function render( array $data, $template = null, $render_method = "inertia" )
    {
        $queryStringKey = 'format';
        if ( $this->request->has( $queryStringKey ) )
        {
            $render_method = $this->request->get( $queryStringKey );
        }

        switch ( $render_method )
        {
            case "json" :
                $output = response()->json( $data );
                break;
            case "html" :
                $output = view( strtolower( $template ), $data );
                break;
            case "inertia" :
                $output = Inertia::render( $template, $data );
                break;
            default:
                $output = "";
        }

        return $output;
    }

}
