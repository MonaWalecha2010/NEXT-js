<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskRequestHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $unserializedData = unserialize($this->assesmant_result_data);
        return [
            'id'                       => $this->id,
            'task_req_id'              => $this->whenLoaded('taskRequest'),
            'assesmant_result_data'    => $this->assesmant_result_data,
            'task_req_result'          => $this->task_req_result,
            'task_req_comment'         => $this->task_req_comment,
            'completed_date'           => $this->completed_date,
            'modified_date'            => $this->modified_date,
            'completed_by'             => $this->whenLoaded('user'),
            'task_req_document'        => $this->task_req_document,
        ];
            
    }
}
