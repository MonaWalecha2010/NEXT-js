<?php

namespace App\Http\Middleware;

use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WorkpackPanelActionResource;
use App\Http\Resources\WorkpackPanelStepResource;
use App\Models\Role;
use App\Models\WorkpackPanelAction;
use App\Models\WorkpackPanelStep;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed[]
     */
    public function share(Request $request)
    {
        $user = ( $request->user() ) ? UserResource::make( $request->user() ) : null;
        return array_merge(parent::share($request), [
            'auth' => [
                'user' =>  $user,
                'code_expires' => config('jetworks145.user.code_expires')
            ],
            'helpers' => [
                'roles' =>  RoleResource::collection( Role::all() ),
                'workpack_panel_actions'  => WorkpackPanelActionResource::collection( WorkpackPanelAction::getOrdered() ),
                'workpack_panel_steps' => WorkpackPanelStepResource::collection( WorkpackPanelStep::getOrdered() ),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }
}
