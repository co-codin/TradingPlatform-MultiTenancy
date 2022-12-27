<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use Illuminate\Notifications\DatabaseNotification as BaseDatabaseNotification;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class DatabaseNotification extends BaseDatabaseNotification
{
    use UsesTenantConnection;
}
