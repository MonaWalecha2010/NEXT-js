<?php

namespace App\Traits;


trait Noteable {

    /**
     * @return mixed
     */
    public function getNotes( $key = null ) {
        $notes = json_decode( $this->notes );
        if( $notes ){
            if( !empty( $key ) && property_exists( $notes, $key ) ){
                return $notes->{$key};
            }
            return null;
        }
        return $notes;
    }

    public function getNotesByKey( $key = null ) {
        return $this->getNotes( $key );
    }

    /**
     * @param $notes
     */
    public function setNotes( $notes ) {
        $this->notes = json_encode( $notes );
    }

    /**
     * Need to think about this...
     * @param $note
     *
     * @return void
     */
    public function setNote( $note, $key = 'comment' )
    {
        $notes = $this->getNotes() ?? new \stdClass();
        $notes->$key = $note;
        $this->notes = json_encode( $notes );
    }


}
