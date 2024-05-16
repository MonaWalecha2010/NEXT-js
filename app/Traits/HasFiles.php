<?php

namespace App\Traits;

use App\Models\Basemodel;
use App\Models\File;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Storage;

trait HasFiles {

    /**
     * Get any file attachments related to this model...
     * @return mixed
     */
    public function getAttachments()
    {
        return File::where( [
            'entity_id'   => $this->id,
            'entity_name' => $this->getEntityName(),
        ] )->get();
    }


    /**
     * @param Basemodel $source
     * @param Basemodel $target
     *
     * @return void
     * @throws \Exception
     */
    public function copyFiles( Basemodel $source, Basemodel $target )
    {
        $keyName = $target->getKeyName();
        if( empty($target->$keyName ) ){
            throw new InvalidArgumentException( '$target model does not have an id set. Files cannot be copied until the model has been saved.');
        }

        $source_attachments = $source->getAttachments();
        foreach (  $source_attachments  as $source_attachment )
        {
            $target_attachment = new File();
            $target_attachment->setEntity( $target );
            $target_attachment->name = $source_attachment->name;
            $target_attachment->original_filepath = $source_attachment->filepath;
            $attachmentPath = $target->getPublicFolder() . "/" . $source_attachment->name;
            if( Storage::copy( $source_attachment->filepath, $attachmentPath ) ) {
                $target_attachment->setFilePath($attachmentPath);
                $target_attachment->save();
            }else{
                //Should we be using throw new League\Flysystem\UnableToCopyFile();
                throw new \Exception( 'Failed copying files from $source to $target');
            }
        }
    }

}
