<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'id'                    => $this->id,
            'task_number'           => $this->task_number,
            'title'                 => $this->title,
            'short_name'            => $this->short_name,
            'external_id'           => $this->external_id,
            'revalidation_type'     => $this->revalidation_type,
            'description'           => $this->description,
            'validity_number'       => $this->validity_number,
            'validity_period'       => $this->validity_period,
            'sector_id'             => $this->whenLoaded('sector'),
            'roles'                 => $this->roles,
            'is_mandatory'          => $this->is_mandatory,
            'assesments'            => $this->whenLoaded('assesments'),
            'assesments_checklist'   => $this->whenLoaded('assesments_checklist'),
            'task_status'           => $this->whenLoaded('task_status'),
        ];
            
    }
}
