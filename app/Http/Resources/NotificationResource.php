<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'sectors' => $this->notificationSectors,
            'content' => $this->content,
            'start_number' => $this->start_number,
            'start_period' => $this->start_period,
            'start_scope' => $this->start_scope,
            'stop_number' => $this->stop_number,
            'stop_period' => $this->stop_period,
            'stop_scope' => $this->stop_scope,
            'repeat_number' => $this->repeat_number,
            'repeat_period' => $this->repeat_period,
            'status' => $this->status,
            'for_mandatory' => $this->for_mandatory,
        ];

    }
}
