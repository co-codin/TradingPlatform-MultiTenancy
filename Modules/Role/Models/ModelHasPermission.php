<?php

declare(strict_types=1);

namespace Modules\Role\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Modules\Role\Dto\ModelHasPermissionData;
use Modules\Role\Enums\ModelHasPermissionStatus;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class ModelHasPermission extends MorphPivot
{
    use UsesTenantConnection;

    /**
     * @var string
     */
    public const DEFAULT_STATUS = ModelHasPermissionStatus::ACTIVE;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'status' => ModelHasPermissionStatus::class,
        'data' => ModelHasPermissionData::class,
    ];
}
