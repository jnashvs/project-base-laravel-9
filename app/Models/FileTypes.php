<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use function GuzzleHttp\json_decode;

class FileTypes extends Model
{
    protected $fillable = ['id', 'directory', 'title', 'extensions', 'max_file_size'];

    /**
     * @property integer $id
     * @property string $directory
     * @property string $title
     * @property string $extensions
     * @property integer $max_file_size
     * */

    protected $casts = [
        'created_at' => 'datetime:d/m/Y',
        'updated_at' => 'datetime:d/m/Y',
    ];

    private $_EXTENSIONS;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    public function files(): HasMany
    {
        return $this->hasMany(Files::class);
    }

    public function getExtensions()
    {

        foreach ($this->getExtensionsDecoded() as $key => $value) {
            $plus = count($this->getExtensionsDecoded()) > ++$key ? ', ' : '';
            $this->_EXTENSIONS .= $value->name . $plus;
        }

        return $this->_EXTENSIONS;
    }

    public function getExtensionsDecoded()
    {
        return json_decode($this->extensions);
    }

    public function getMaxFileSize()
    {
        return $this->max_file_size . ' Mb' ?? '';
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the value of id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * Set the value of directory
     */
    public function setDirectory($directory): void
    {
        $this->directory = $directory;
    }

    /**
     * Set the value of title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * Set the value of extensions
     */
    public function setExtensions($extensions): void
    {
        $this->extensions = $extensions;
    }

    /**
     * Set the value of max_file_size
     */
    public function setMaxFileSize($max_file_size): void
    {
        $this->max_file_size = $max_file_size;
    }
}