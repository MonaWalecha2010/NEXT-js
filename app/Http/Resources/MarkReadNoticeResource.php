<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarkReadNoticeResource extends JsonResource
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
            'notice_id' => $this->notice_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'last_version_read' => $this->last_version_read,
            'created_at' => $this->created_at->format('d/m/y'),
        ];
    }
}
