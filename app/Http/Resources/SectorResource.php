<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectorResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'order_number' => $this->order_number,
            'fname_created_by' => $this->fname_created_by,
            'lname_created_by' => $this->lname_created_by,
            'user_id_created_by' => $this->user_id_created_by,
            'fname_modified_by' => $this->fname_modified_by,
            'lname_modified_by' => $this->lname_modified_by,
            'user_id_modified_by' => $this->user_id_modified_by,
            'status' => $this->status,
            'created_at' => $this->created_at->format('d/m/y'),
            'updated_at' => $this->updated_at->format('d/m/y'),
        ];
            
    }
}
