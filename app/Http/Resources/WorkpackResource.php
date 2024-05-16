<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkpackResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'notes' => $this->notes,
            'active' => $this->active,
            'completed' => $this->completed,
            'aeroplane' => $this->whenLoaded('aeroplane'),
            'date' => ($this->created_at ) ? $this->created_at->format('d/m/y') : null,
            'workpack_panels' => $this->whenLoaded('workpack_panels'),
            'workpack_stats' => $this->resource->getWorkpackStats(),
            'workpack_schematic_stats' => $this->resource->getWorkpackSchematicStats()
        ];
    }
}
