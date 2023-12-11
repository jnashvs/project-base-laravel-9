<?php

namespace App\Models\Factories;

use App\Modules\Exceptions\FatalModuleException;
use App\Modules\Exceptions\FatalRepositoryException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Abstract repository to handle Model queries
 */
abstract class AbstractFactory implements FactoryInterface
{

    public const REPO_NAMESPACE = "App\\Modules\\Repositories\\";

    /**
     * @var Model $model
     */
    public $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, array $arrRelations = [], string $dto = null, bool $isPublic = false): ?Model
    {
        if ($isPublic) {
            $objBuilder = $this->model::withAllData();
        } else {
            $objBuilder = $this->model::query();
        }
        $objBuilder->where('id', '=', $id);

        foreach ($arrRelations as $relation) {
            $objBuilder->with($relation);
        }

        return $objBuilder->first();
    }

    /**
     * @inheritDoc
     */
    public function getByIds(array $ids, array $arrRelations = [], string $dto = null, bool $isPublic = false): Collection
    {
        $objBuilder = $this->model::query();

        $objBuilder->whereIn('id', $ids);

        foreach ($arrRelations as $relation) {
            $objBuilder->with($relation);
        }

        return $objBuilder->get();
    }

    /**
     * @inheritDoc
     */
    public function getAll(array $arrRelations = [], bool $isPublic = false): Collection
    {
        if ($isPublic) {
            $objBuilder = $this->model::withAllData();
        } else {
            $objBuilder = $this->model::query();
        }

        foreach ($arrRelations as $relation) {
            $objBuilder->with($relation);
        }

        return $objBuilder->get();
    }

    /**
     * @inheritDoc
     */
    public function getAllSelectedField($field=[]): Collection
    {
        $objBuilder = $this->model::query();
        if (!empty($field)) {
            $objBuilder->select($field);
        }
        return $objBuilder->get();
    }

    /**
     * @inheritDoc
     */
    public function getAllObjects(array $arrRelations = [], bool $isPublic = false): array
    {
        if ($isPublic) {
            $objBuilder = $this->model::withAllData();
        } else {
            $objBuilder = $this->model::query();
        }
        foreach ($arrRelations as $relation) {
            $objBuilder->with($relation);
        }

        return $objBuilder->get()->all();
    }

    /**
     * @inheritDoc
     */
    public function updateByModel($model, array $arrData): Model
    {
        try {
            $result = $model->update($arrData);
            if (empty($result)) {
                throw new FatalModuleException("Unable to save a model", 500, null, [
                    "data" => $arrData
                ]);
            }

            return $model;
        } catch (\Exception $e) {
            throw new FatalModuleException("Unable to save a model", 500, $e, [
                "data" => $arrData
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(Model $objModel): bool
    {
        try {
            if ($objModel->delete() === false) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            throw new FatalModuleException("Unable to delete a model", 500, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $id): bool
    {
        $model = $this->getById($id);
        if (empty($model)) {
            throw new FatalModuleException("Unable to find the model by its ID", 500, null, [
                "ID" => $id
            ]);
        }

        return $this->delete($model);
    }

    /**
     * @param Model $objModel
     * @return Model
     * @throws FatalRepositoryException
     */
    public function activate(Model $objModel)
    {
        $objModel->setIsActive(true);

        if (!$objModel->save()) {
            throw new FatalRepositoryException('Failed to activate the Model');
        }

        return $objModel;
    }

    /**
     * @param Model $objModel
     * @return Model
     * @throws FatalRepositoryException
     */
    public function deactivate(Model $objModel)
    {
        $objModel->setIsActive(false);

        if (!$objModel->save()) {
            throw new FatalRepositoryException('Failed to activate the Model');
        }

        return $objModel;
    }
}
