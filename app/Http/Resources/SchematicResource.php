<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SchematicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->getPublicUrl('image'),
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'airframe' => AirframeResource::make( $this->whenLoaded('airframe') ),
            'panels' => PanelResource::collection(  $this->whenLoaded('panels') )
        ];
        $resource = $this->resource;
        if( $resource->shouldEmbedSvg() == true ){
            $data['svg'] = $this->getFileData('image');
        }
        return $data;
    }
}
