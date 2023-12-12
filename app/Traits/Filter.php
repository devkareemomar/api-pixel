<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait Filter
{
    protected function filterQueryString(Builder $query): Builder
    {
        if (is_array(request()->filter)) {
            foreach (request()->filter as $key => $value) {
                $table = $query->getModel()->getTable();
                $isColumnExist = Schema::hasColumn($table, $key);
                $isValueEmpty = !isset($value) || $value === '';
                $keyIsTitle = str()->contains($key, ['name','title', 'reference', 'sku', 'description','date']);
                if (!$isValueEmpty && $isColumnExist) {
                    $query = $query
                        ->when(
                            $keyIsTitle,
                            fn($query) => $query->where($table . '.' . $key, "like", "%$value%"),
                            fn($query) => $query->where($table . '.' . $key, $value)
                        );
                }
            }
        }
        return $query;
    }

    public function scopeFilter($query)
    {
        return $this->filterQueryString($query);
    }
}
