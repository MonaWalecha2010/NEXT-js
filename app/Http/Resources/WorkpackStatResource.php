<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkpackStatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'completed' => $this->completed,
            'panels_completed' => $this->panels_completed,
            'panels_total' => $this->panels_total,
            'users' => UserResource::collection( $this->users ),
            'current_users' => UserResource::collection( $this->current_users ),
        ];
    }
}
