<?php


namespace App\Traits;


use App\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * @param $query
     * @param Filter $filter
     * @return Builder
     */
    public function scopeFilter($query, Filter $filter)
    {
        return $filter->apply($query);
    }
}
