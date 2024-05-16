<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HasUpload {

    /**
     * @param Request $request
     * @param string  $fieldname
     * @param boolean $delete_old_file
     *
     * @return mixed
     */
    public function uploadFile( Request $request, $fieldname, $delete_old_file = true )
    {
        $originalFilePath = $this->getOriginal( $fieldname );

        if ( ! $request->hasFile( $fieldname ) )
        {
            return;
        }
        $uploadedFile             = $request->file( $fieldname );
        $filename                 = $uploadedFile->getClientOriginalName();
        $publicStorageDestination = $this->getPublicFolder();
        $reflection               = new \ReflectionClass( __CLASS__ );
        $objectName               = $reflection->getShortName();
        $fileUploadPath           = $publicStorageDestination . "/" . $filename;

        if ( Storage::exists( $fileUploadPath ) )
        {
            throw ValidationException::withMessages( [
                'image' => "A file with this exact name already exists for this {$objectName}. Please save the file with a unique name",
            ] );
        }

        $path = Storage::putFileAs(
            $publicStorageDestination,
            $uploadedFile,
            $filename
        );
        Storage::setVisibility( $path, 'public' );

        $this->$fieldname = $path;

        if( $originalFilePath ) {
            if( $this->$fieldname != $originalFilePath ){
                Storage::disk()->delete( $originalFilePath );
            }
        }

        return $this->$fieldname;
    }

    /**
     * @param Model $object
     * @param       $fieldname
     *
     * @return void
     */
    public function deleteFile( Model $object, $fieldname )
    {
        Storage::disk()->delete( $object->$fieldname );
    }


}
