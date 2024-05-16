<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AirframeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  [
            'id' => $this->id,
            'name' => $this->name,
            'revision' => $this->revision,
            'fullname' => $this->getFullname(),
            'description' => $this->description,
            'notes' => $this->notes,
            'active' =>  $this->active,
            'aeroplanes' => AeroplaneResource::collection( $this->whenLoaded('aeroplanes') ),
            'panels' => PanelResource::collection ( $this->whenLoaded('panels') ),
            'schematics' => SchematicResource::collection ( $this->whenLoaded('schematics') ),
            'cengineer_ids' => $this->users->pluck( 'id' )->toArray(),
        ];
    }
}
