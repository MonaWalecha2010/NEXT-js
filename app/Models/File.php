<?php

namespace App\Models;

use App\Traits\Noteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class File extends Basemodel
{
    use HasFactory, Noteable;

    /**
     * @var string[]
     */
    protected static array $validationRules = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'filepath',
        'entity_id',
        'entity_name',
    ];

    protected $guarded = [
        'original_filepath',
    ];

    /**
     * @param Basemodel $entity
     *
     * @return void
     */
    public function setEntity(Basemodel $entity)
    {
        $this->entity_id = $entity->id;
        $this->entity_name = $entity->getEntityName();
    }

    /**
     * Convert the file path stored into the DB to a usable url on the frontend...
     * @return array|mixed|string|string[]
     */
    public function getFrontendUrl()
    {
        $filePath = $this->filepath;
        if (strpos($filePath, 'public/') === 0) {
            $filePath = substr_replace($filePath, '/storage/', 0, 7);
        }

        return $filePath;
    }

    /**
     * @param $path
     *
     * @return void
     */
    public function setFilePath($path)
    {
        $this->filepath = $path;
        if ($this->original_filepath) {
            unset($this->original_filepath);
        }
    }

    /**
     * @param string $publicStorageDestination
     * @param $fileObject
     * @param string $filename
     */
    public function saveToStorage($publicStorageDestination, $fileObject, $filename)
    {
        $path = Storage::putFileAs(
            $publicStorageDestination,
            $fileObject,
            $filename
        );
        Storage::setVisibility($path, 'public');
        $this->name = $filename;
        $this->filepath = $path;
    }

    /**
     * @return void
     */
    public function delete()
    {
        if (Storage::delete($this->filepath)) {
            return parent::delete();
        }
        return false;
    }

    public function deleteOnlyData()
    {
        return parent::delete();
    }
}
