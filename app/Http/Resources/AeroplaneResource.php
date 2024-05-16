<?php

namespace App\Http\Resources;

use App\Models\Airframe;
use Illuminate\Http\Resources\Json\JsonResource;

class AeroplaneResource extends JsonResource
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
            'airframe_id' =>  $this->airframe_id,
            'owner_id' =>  $this->owner_id,
            'name' => $this->name,
            'description' => $this->description,
            'notes' => $this->notes,
            'active' =>  $this->active,
            'date' => $this->created_at->format('d/m/y'),
            'airframe' =>  $this->whenLoaded('airframe'),
            'owner' => $this->whenLoaded('owner'),
            'workpacks' => $this->whenLoaded('workpacks'),
        ];

    }
}
