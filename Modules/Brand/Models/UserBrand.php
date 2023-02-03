<?php

declare(strict_types=1);

namespace Modules\Brand\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Brand\Database\factories\UserBrandFactory;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

final class UserBrand extends Pivot
{
    use HasFactory;
    use UsesLandlordConnection;

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): UserBrandFactory
    {
        return UserBrandFactory::new();
    }
}
