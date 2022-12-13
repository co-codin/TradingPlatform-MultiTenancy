<?php

declare(strict_types=1);

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

final class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use UsesLandlordConnection;
}
