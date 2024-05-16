<?php

namespace App\Models;

use App\Traits\Noteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkpackPanelTaskNote extends Basemodel
{
    use HasFactory;

    public function workpack_panel()
    {
        return $this->belongsTo( WorkpackPanel::class );
    }

    /**
     * @return HasOne
     */
    public function workpack_panel_task()
    {
        return $this->belongsTo( WorkpackPanelTask::class );
    }

    public function setNote( $note )
    {
        $this->note = $note;
    }

    /**
     * @param string $note
     *
     * @return bool
     */
    public function isNoteWorthSaving( $note = null )
    {
        if( $note == null ){
            $note = $this->note;
        }
        $cleanedNote = trim( strip_tags( $note ) );
        if( strlen( $cleanedNote) > 0 ){
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getTakingOverPanelNote()
    {
        $oldUserName = strtoupper( $this->workpack_panel->user->getFullName() );
        return "<p>----------TAKING OVER FROM {$oldUserName}----------</p>";
    }

    /**
     * @return void
     */
    public function setTakingOverPanelNote()
    {
        $prependNotes = $this->getTakingOverPanelNote();
        $this->setNote( $prependNotes . $this->note );
    }

    public function getReopeningPanelNote()
    {
        return "<p>----------REOPENING PANEL----------</p>";
    }

    public function setReopeningPanelNote()
    {
        $prependNotes = $this->getReopeningPanelNote();
        $this->setNote( $prependNotes . $this->note );
    }

}
