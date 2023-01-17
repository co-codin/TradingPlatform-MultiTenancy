<?php

declare(strict_types=1);

namespace Modules\Currency\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Currency\Database\factories\CurrencyFactory;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

final class Currency extends Model
{
    use HasFactory;
    use UsesLandlordConnection;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id',
    ];

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): CurrencyFactory
    {
        return CurrencyFactory::new();
    }
}
