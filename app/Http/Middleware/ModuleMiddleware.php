<?php

namespace App\Http\Middleware;

use Closure;

class ModuleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $module
     * @return mixed
     */
    public function handle($request, Closure $next, string $module = null)
    {
        if ($module) {
            if (can_view_module($module)) {
                return $next($request);
            }
        }
        toastr()->warning('You do not have permissions to view this module');
        return redirect()->back();

    }
}
