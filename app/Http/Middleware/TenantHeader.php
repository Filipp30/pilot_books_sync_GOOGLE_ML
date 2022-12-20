<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;

class TenantHeader
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->tenant()->getResults()?->id === null ) {
            return response([
                'message' => 'No tenant was created for this account or your email is not verified'
            ], 404);
        }

        if (!($request->user()->tenant()->getResults() instanceof Tenant)) {
            abort(500, 'Tenant instance invalid');
        }

        $tenant = $request->user()->tenant()->getResults()->id;
        $request->headers->set('X-Tenant', $tenant);

        return $next($request);
    }
}
