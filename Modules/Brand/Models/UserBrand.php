<?php

declare(strict_types=1);

namespace Modules\Brand\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Brand\Database\factories\UserBrandFactory;

final class UserBrand extends Pivot
{
    use HasFactory;

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
