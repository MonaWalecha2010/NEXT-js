<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskRequestAssesmentResource extends JsonResource
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
            'id'                     => $this->id,
            'task'                   => $this->whenLoaded('task'),
            'task_request'           => $this->task_request,
            'assesments'             => $this->whenLoaded('assesments'),
            'revalidation_type'      => $this->revalidation_type,
            'status'                 => $this->status,
            'comment'                => $this->comment,
        ];
            
    }
}
