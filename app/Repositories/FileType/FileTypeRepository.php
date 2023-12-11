<?php

namespace App\Repositories\FileType;


use App\Models\Factories\FileType\FileTypeFactory;
use App\Models\FileTypes;
use Illuminate\Database\Eloquent\Model;
use App\Modules\BaseModule;
use App\Modules\Exceptions\ValidationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

/**
 *
 */
class FileTypeRepository extends BaseModule implements FileTypeRepositoryInterface
{
    private FileTypeFactory $fileTypeFactory;

    /**
     * @param FileTypeFactory $fileTypeFactory
     */
    public function __construct(FileTypeFactory $fileTypeFactory)
    {
        $this->fileTypeFactory = $fileTypeFactory;
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public function getById(int $id): ?Model
    {
        return $this->fileTypeFactory->getById($id);
    }

    /**
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->fileTypeFactory->getAll();
    }


    /**
     * @param Model|FileTypes $fileType
     * @return mixed
     */
    public function delete(Model|FileTypes $fileType): bool
    {
        return $fileType->delete();
    }

    /**
     * @param string $title
     * @param string $directory
     * @param string $extensions
     * @param int $max_file_size
     * @return FileTypes
     */
    public function create(string $title, string $directory, string $extensions, int $max_file_size): FileTypes
    {
        $fileTypeFactory = $this->fileTypeFactory->create($title, $directory, $extensions, $max_file_size);

        if ($fileTypeFactory) {
            $dir_path = public_path() . "/files/{$directory}/";
            if (!File::exists($dir_path)) {
                File::makeDirectory($dir_path, 0777, true, true);
            }
        }

        return $fileTypeFactory;
    }

    /**
     * @param FileTypes $fileType
     * @param string $title
     * @param string $directory
     * @param string $extensions
     * @param int $max_file_size
     * @return FileTypes
     */
    public function update(FileTypes $fileType, string $title, string $directory, string $extensions, int $max_file_size): FileTypes
    {

        $dir_path = public_path() . "/files/{$directory}/";
        if (!File::exists($dir_path)) {
            File::makeDirectory($dir_path, 0777, true, true);
        }

        $old_dir_path = public_path() . "/files/{$fileType->getDirectory()}/";
        if (rename($old_dir_path, $dir_path)) {
            return $this->fileTypeFactory->update($fileType, $title, $directory, $extensions, $max_file_size);
        }

        return $fileType;
    }

}
