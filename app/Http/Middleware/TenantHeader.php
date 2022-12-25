<?php

namespace App\Http\Middleware;

use App\Exceptions\TenantException;
use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;

class TenantHeader
{
    /**
     * @throws TenantException
     */
    public function handle(Request $request, Closure $next)
    {
        if (($request->user()->tenant()->getResults()?->id === null) ||
            !($request->user()->tenant()->getResults() instanceof Tenant))
        {
            throw new TenantException();
        }

        $tenant = $request->user()->tenant()->getResults()->id;
        $request->headers->set('X-Tenant', $tenant);

        return $next($request);
    }
}
