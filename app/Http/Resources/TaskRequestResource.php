<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class TaskRequestResource extends JsonResource
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
            'task' => $this->whenLoaded('task'),
            'user' => $this->whenLoaded('user'),
            'training_role_id' => $this->training_role_id,
            'status' => $this->status,
            'display_status' => $this->display_status,
            'request_status' => $this->request_status,
            'approval_status' => $this->approval_status,
            'valid_upto' => $this->valid_upto,
            'document' => $this->document,
            'history'
        ];
    }
}
