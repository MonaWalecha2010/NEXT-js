<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource {

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray( $request )
    {
        $data      = [
            'id'           => $this->id,
            'fname'        => $this->fname,
            'lname'        => $this->lname,
            'name'         => $this->name,
            'initials'     => $this->getInitials(),
            'color'        => $this->color,
            'active'       => $this->active,
            'role_id'      => $this->role_id,
            'training_role_id'  => $this->training_role_id,
            'license_number'  => $this->license_number,
            'training_role'  => $this->whenLoaded('training_role'),
            'user_tasks'  => $this->whenLoaded('user_tasks'),
            'readStatus'  => $this->whenLoaded('readStatus'),
            'is_worker'    => $this->isPlaneWorker(),
            'is_admin'     => $this->isAdministrator(),
            'is_mechanic'    => $this->isMechanic(),
            'is_engineer' => $this->isEngineer(),
            'is_cengineer' => $this->isSeniorEngineer(),
            'is_training_admin'    => $this->isTrainingAdmin(),
            'is_training_officer'     => $this->isTrainingOfficer(),
            'is_training_manager'    => $this->isTrainingManager(),
            'is_training_engineer' => $this->isTrainingEngineer(),
            'is_training_mechanic' => $this->isTrainingMechanic(),
            'airframe_ids' => $this->whenLoaded('airframes', function() {
                $airframe_ids = [];
                if( $this->airframes->count() > 0 ){
                    $airframe_ids = $this->airframes->pluck( 'id' )->toArray();
                }
                return $airframe_ids;
            }),
        ];
        if ( Auth::user()->isAdministrator() )
        {
            $data[ 'code' ]      = $this->code;
            $data[ 'stamp' ]      = $this->stamp;
            $data[ 'signature' ] = $this->getPublicUrl( 'signature' );
            $data[ 'email' ]     = $this->email;
        }

        return $data;
    }
}
