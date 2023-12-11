<?php

namespace App\Models\Factories\FileType;

use App\Models\FileTypes;
use App\Models\Factories\AbstractFactory;
use App\Modules\Exceptions\FatalRepositoryException;
use Illuminate\Support\Collection;

/**
 *
 */
class FileTypeFactory extends AbstractFactory
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct(FileTypes::class);
    }

    /**
     * @param FileTypes $fileType
     * @param string $title
     * @param string $directory
     * @param string $extensions
     * @param int $max_file_size
     * @return FileTypes
     */
    public function update(
        FileTypes $objFileType,
        string $title,
        string $directory,
        string $extensions,
        int $max_file_size,
    ): FileTypes|bool
    {
        $objFileType->setTitle($title);
        $objFileType->setDirectory($directory);
        $objFileType->setExtensions($extensions);
        $objFileType->setMaxFileSize($max_file_size);

        if(!$objFileType->save()){
            throw new FatalRepositoryException('Failed to update a file type.');
        }
        return $objFileType;
    }


    /**
     * @param string $title
     * @param string $directory
     * @param string $extensions
     * @param int $max_file_size
     * @return FileTypes
     */
    public function create(
        string $title,
        string $directory,
        string $extensions,
        int $max_file_size,
    ): FileTypes|bool
    {
        $objFileType = new FileTypes();
        $objFileType->setTitle($title);
        $objFileType->setDirectory($directory);
        $objFileType->setExtensions($extensions);
        $objFileType->setMaxFileSize($max_file_size);

        if(!$objFileType->save()){
            throw new FatalRepositoryException('Failed to create a file type.');
        }
        return $objFileType;
    }

}
