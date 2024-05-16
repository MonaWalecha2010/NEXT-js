<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class NoticeDocumentHistoryResource extends JsonResource
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
            'version' => $this->version,
            'status' => $this->status,
            'issued_by' => $this->issued_by,
            'notice_document' => $this->notice_document,
            'notice_document_name' => $this->notice_document_name,
            'created_at' => $this->created_at->format('d/m/y'),
        ];
    }
}
