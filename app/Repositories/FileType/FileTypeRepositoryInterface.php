<?php

namespace App\Repositories\FileType;

use App\Models\FileTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


/**
 *
 */
interface FileTypeRepositoryInterface
{
    /**
     * @param int $id
     * @return Model|null
     */
    public function getById(int $id): ?Model;

    /**
     * @return Collection
     */
    public function get(): Collection;

    public function create(string $title, string $directory, string $extensions, int $max_file_size): FileTypes;

    public function update(FileTypes $fileType, string $title, string $directory, string $extensions, int $max_file_size): FileTypes;

    public function delete(FileTypes $fileType): bool;
}
