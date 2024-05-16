<?php

namespace App\Traits;

use App\Models\Basemodel;
use App\Models\File;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Storage;

trait CanAttachUploads {

    /**
     * Get the shortname of the class. Used to store files in filesystem, store related object name with a File instance
     * @return string
     */
    public function getEntityName()
    {
        $reflect = new \ReflectionClass( $this );
        return $reflect->getShortName();
    }


    /**
     * @param $attribute
     *
     * @return null|string
     */
    public function getPublicUrl( $attribute )
    {
        $modelUrl  = $this->{$attribute};
        if( $modelUrl ){
            if( $this->doesUrlContainSchema( $modelUrl ) ){
                return $modelUrl;
            }
            // It's something stored on the filesystem instead
            return Storage::url( $this->{$attribute} );
        }
        // Hasn't got a url..
        return null;
    }


    /**
     * @param $attribute
     *
     * @return string
     */
    public function getEmbeddedData( $attribute )
    {
        $path = '';
        $modelUrl  = $this->{$attribute};
        if( $modelUrl ){
            $path = $this->getPublicUrl( $attribute );
        }
        //Actual Url, just pass it through...
        if( empty($path) || $this->doesUrlContainSchema( $path ) ){
            return $path;
        }

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = $this->getFileData( $attribute );
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    /**
     * @param $attribute
     *
     * @return string|null
     */
    public function getFileData( $attribute )
    {
        return Storage::get( $this->{$attribute} );
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getStorageFolder()
    {
        if ( $this->id === null )
        {
            throw new \Exception( "Cannot call " . __METHOD__ . "on an instance that does not have an ID" );
        }

        return $this->getEntityName() . "/" . $this->id;
    }


    /**
     * @return string
     * @throws Exception
     */
    public function getPublicFolder()
    {
        return "public/" . $this->getStorageFolder();
    }

    /**
     * @param $filename
     *
     * @return bool
     * @throws Exception
     */
    public function willOverwriteExistingFile( $filename )
    {
        if ( Storage::exists( $this->getStorageFolder() . "/" . $filename ) )
        {
            return true;
        }

        return false;
    }

    public function allowedAttachments()
    {
        return [
            "text/plain",
            "text/csv",
            "application/vnd.ms-excel",
            "image/svg+xml",
            "application/pdf",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/vnd.ms-powerpoint",
            "application/vnd.openxmlformats-officedocument.presentationml.presentation",
        ];
    }

    public function doesUrlContainSchema( $url )
    {
        $parsedUrl = parse_url( $url );
        //Does the attribute contain a url scheme? There is a proper url that we can return...
        if ( ! empty( $parsedUrl[ 'scheme' ] ) )
        {
            return true;
        }
        return false;
    }


}
