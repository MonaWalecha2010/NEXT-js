<?php

namespace App\Http\Resources;

use App\Models\AirframeWorkpackPanel;
use App\Models\Panel;
use Illuminate\Http\Resources\Json\JsonResource;

class AirframeWorkpackResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray( $request )
    {
        $workpackpanels = $this->whenLoaded('airframe_workpack_panels');
        $panel_ids = $workpackpanels->pluck('id');

        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'description'     => $this->description,
            'notes'           => $this->notes,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
            'airframe'        => AirframeResource::make( $this->whenLoaded('airframe') ),
            'airframe_workpack_panels' => AirframeWorkpackPanelResource::collection( $workpackpanels ),
            'panels' => PanelResource::collection( Panel::find( $panel_ids) ),
        ];
    }
}
