<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoticeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'number' => $this->number,
            'status' => $this->status,
            'notice_type' => $this->whenLoaded('notice_type'),
            'comment' => $this->comment,
            'created_by_fname' => $this->created_by_fname,
            'created_by_lname' => $this->created_by_lname,
            'created_at' => $this->created_at->format('d/m/y'),
            'updated_at' => $this->updated_at->format('d/m/y'),
            'roles' => $this->whenLoaded('roles'),
            'notice_documents_history' => $this->whenLoaded('notice_documents_history'),
            'markReadStatus' => $this->whenLoaded('markReadStatus'),
        ];
    }
}
