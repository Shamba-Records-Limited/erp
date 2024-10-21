<?php

namespace App\EloquentFilters\EmployeeLeave;

use Fouladgar\EloquentBuilder\Support\Foundation\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class MemberNoFilter extends Filter
{
    /**
     * Apply the condition to the query.
     *
     * @param Builder $builder
     * @param mixed $value
     *
     * @return Builder
     */
    public function apply(Builder $builder, $value): Builder
    {
        return $builder->whereHas('employee', function ($query) use ($value){
            $query->where('employee_no','LIKE','%'.$value.'%');
        });
    }
}