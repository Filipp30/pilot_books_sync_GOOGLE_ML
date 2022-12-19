<?php

namespace App\Listeners;

use App\Events\UserIsVerified;
use App\Models\Tenant;
use App\Models\User;
use PHPUnit\Framework\Exception;

class CreateTenant
{
    public function handle(UserIsVerified $event): void
    {
        $user = User::with('tenant')->findOrFail($event->userId);

        if ($user->tenant()->getResults() !== null) {
            return;
        }

        $tenant = new Tenant();
        $tenant->user_id = $user->id;
        $tenant->save();

        if ($tenant->user()->getResults()->id !== $user->id) {
            throw new Exception('User and Tenant not matches.');
        }
    }
}
