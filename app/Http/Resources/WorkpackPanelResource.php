<?php

namespace App\Http\Resources;

use App\Models\WorkpackPanel;
use App\Models\WorkpackPanelAction;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkpackPanelResource extends JsonResource
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
            'user_id' => $this->user_id,
            'workpack_id' => $this->workpack_id,
            'panel_id' => $this->panel_id,
            'workpack_panel_step_id' => $this->workpack_panel_step_id,
            'workpack_panel_action_id' => $this->workpack_panel_action_id,
            'order' => $this->order,
            'description' => $this->description,
            'notes' => $this->notes,
            'completed' => $this->completed,
            'removable' => $this->canPanelBeDeletedFromWorkpack(),
            'panel' => PanelResource::make( $this->whenLoaded('panel') ),
            'user' => UserResource::make( $this->whenLoaded('user') ),
            'status' => WorkpackPanelStepResource::make( $this->whenLoaded('workpack_panel_step') ),
            'action' => WorkpackPanelActionResource::make( $this->whenLoaded('workpack_panel_action') ),
        ];
    }
}
