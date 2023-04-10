<?php

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * @codeCoverageIgnore
 */
class SongTermAdvancedFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        return $query
            ->where('name', 'like', '%'.$value.'%')
            ->orWhere(function ($query) use ($value) {
                $query->whereJsonContains('data->artists', ['name' => $value]);
            });
    }
}
