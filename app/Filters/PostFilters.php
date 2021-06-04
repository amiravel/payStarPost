<?php


namespace App\Filters;


class PostFilters extends Filter
{

    protected $filters = [
        'title'
    ];

    /**
     * @param $value
     */
    public function title($value)
    {
        $this->builder->whereTitle('like', "%$value%");
    }

}
