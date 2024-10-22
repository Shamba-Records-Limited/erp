<?php

namespace App\EloquentFilters\Collection;

use Fouladgar\EloquentBuilder\Support\Foundation\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class FarmerPhoneFilter extends Filter
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
        return $builder->whereHas('farmer', function ($query) use ($value){
            $query->where('phone_no','LIKE','%'.$value.'%');
        });
    }
}