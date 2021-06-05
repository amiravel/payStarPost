<?php


namespace App\Filters;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filter
{

    protected $filters = [];

    public $request;

    public $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)){
                $this->{$filter}($value);
            }
        }

        return $this->builder;
    }


    public function getFilters()
    {
        return $this->request->only($this->filters);
    }


}
