<?php

namespace App\Http\Middleware;

use Closure;

class SubModuleGateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, string $submodule = null, string $operation = null)
    {
        if ($submodule && $operation) {
            if (has_right_permission($submodule, $operation)) {
                return $next($request);
            }
        }

        toastr()->warning('You do not have permission for that operation on '.$submodule);
        return redirect()->back();
    }
}
