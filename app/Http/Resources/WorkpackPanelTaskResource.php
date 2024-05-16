<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkpackPanelTaskResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray( $request )
    {
        return [
            'id'                       => $this->id,
            'user'                     => $this->whenLoaded( 'user' ),
            'user_code'                => $this->user_code,
            'workpack_panel_id'        => $this->panel_id,
            'workpack_panel_step_id'   => $this->workpack_panel_step_id,
            'workpack_panel_action_id' => $this->workpack_panel_action_id,
            'note'                     => $this->getNotesByKey( 'comment' ),
            'date'                     => $this->updated_at->format( 'd/m/y' ),
            'notes'                    => WorkpackPanelTaskNoteResource::collection( $this->whenLoaded( 'workpack_panel_task_notes' ) ),
        ];
    }
}
