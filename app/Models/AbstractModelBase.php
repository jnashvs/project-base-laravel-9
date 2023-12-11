>Z<?php

namespace App\Models;

use App\Modules\Traits\HasRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Abstract eloquent model for the application
 * @mixin Builder
 *
 * @property ?int $id
 * @property ?string $created_at
 * @property ?string $updated_at
 */
abstract class AbstractModelBase extends Model
{
    use HasFactory;
    use HasRepository;

    public function getLogColumns(): array
    {
        return array_filter($this->fillable, function ($var) {
            return !in_array($var, ['id', 'created_at', 'updated_at', 'deleted_at']);
        });
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    public function getAuditRelationData($field)
    {
        if ($this->$field()->exists()) {
           return $this->$field()->first()->getAuditDataText();
        }
        return false;
    }


    /**
     * The attributes that should be mutated to date.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    /**
     * @param string|null $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    /**
     * @param string|null $updated_at
     */
    public function setUpdatedAt($updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}
