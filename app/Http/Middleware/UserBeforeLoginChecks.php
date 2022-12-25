<?php

namespace App\Http\Middleware;

use App\Exceptions\EmailMustBeVerifiedException;
use App\Exceptions\TenantException;
use App\Models\Tenant;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class UserBeforeLoginChecks
{
    /**
     * @throws EmailMustBeVerifiedException
     * @throws TenantException
     */
    public function handle(Request $request, Closure $next)
    {
        $request->validate([
            'email' => ['required','email','max:35'],
        ]);

        $user = User::query()->where('email', '=', $request['email'])->firstOrFail();

        assert($user instanceof User);

        if (!$user->hasVerifiedEmail()) {
            throw new EmailMustBeVerifiedException();
        }

        if (($user->tenant()->getResults()?->id === null) || !($user->tenant()->getResults() instanceof Tenant)) {
            throw new TenantException();
        }

        return $next($request);
    }
}
