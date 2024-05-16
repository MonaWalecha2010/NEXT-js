<?php

namespace App\Http\Controllers;

use App\Http\Resources\SchematicResource;
use App\Models\Schematic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

abstract class AbstractController extends Controller
{

    /**
     * @param       $permissionName
     * @param       $entityClass
     * @param array $data
     *
     * @return true
     */
    public function checkAuthorization( $permissionName, $authorizationData )
    {
        if( !Auth::check() ){
            abort(403);
        }
        if( !Auth::user()->can( $permissionName , $authorizationData ) ){
            abort(403);
        }
        return true;
    }

}
