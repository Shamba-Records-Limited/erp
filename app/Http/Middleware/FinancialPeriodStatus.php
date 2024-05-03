<?php

namespace App\Http\Middleware;

use App\Http\Traits\Accounting;
use Closure;

class FinancialPeriodStatus
{
    use Accounting;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!check_active_financial_period()) {
            if (!$this->updateFinancialPeriods()) {
                return redirect()->route('cooperative.accounting.reports');
            }
        }
        return $next($request);
    }
}
