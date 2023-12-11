<?php
namespace App\Modules\Traits;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\Schema;

trait ActiveTrait {

    /**
     * Boot the scope.
     *
     * @return void
     */
    public static function bootActiveTrait()
    {
        static::addGlobalScope(new ActiveScope);
    }

    /**
     * Get the name of the column for applying the scope.
     *
     * @return string
     */
    public function getActiveColumn()
    {
        return 'is_active';
    }

    /**
     * Get the fully qualified column name for applying the scope.
     *
     * @return string
     */
    public function getQualifiedActiveColumnName()
    {

        $column = null;
        $columns = [
            'class_id',
            'group_id',
            'group_schedule_id',
            'stage_id',
        ];

        foreach($columns as $item) {
            if (in_array($item, $this->getFillable())) {
                $column = $item;
                break;
            }
        }

        return $column;
    }

    /**
     * Get the query builder without the scope applied.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function withInactive()
    {
        return with(new static)->newQueryWithoutScope(new ActiveScope);
    }

}
