<?php

declare(strict_types=1);

namespace Modules\Customer\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

final class UrlAuthCreator
{
    public function create(int $customerId, int $brandId, int $lifetime = 30): string
    {
        $key = Str::random();

        Cache::put(
            "url-auth:{$key}",
            compact('customerId', 'brandId'),
            now()->addMinutes($lifetime)
        );

        return url(route('customer.url-auth.login', compact('key'), false));
    }
}
