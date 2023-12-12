<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait Sort
{
    protected function sortQueryString(Builder $query): Builder
    {
        if (is_array(request()->sort)) {
            foreach (request()->sort as $key => $value) {
                if (Schema::hasColumn($this->getTable(), $key)) {
                    $query->orderBy($key, $value);
                }
            }
        }
        return $query;
    }

    protected static function booted(): void
    {
        static::addGlobalScope('default-sort', function (Builder $builder) {
            $table= $builder->getModel()->getTable();
            $builder->orderByDesc("{$table}.id");
        });
    }

    public function scopeSort($query)
    {
        return $this->sortQueryString($query);
    }
}

