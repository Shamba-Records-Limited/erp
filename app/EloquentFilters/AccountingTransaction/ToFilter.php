<?php

namespace App\EloquentFilters\AccountingTransaction;

use Fouladgar\EloquentBuilder\Support\Foundation\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
class ToFilter extends Filter
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
        return $builder->whereDate('date', '<=',Carbon::create($value));
    }
}