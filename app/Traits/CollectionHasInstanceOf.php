<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait CollectionHasInstanceOf
{
    protected static function hasInstance(Collection $collection, $instance): bool
    {
        try {
            $collection->map(function($class) use ($instance): void {
                assert($class instanceof $instance);
            });
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
