<?php

declare(strict_types=1);

namespace Modules\Currency\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Currency\Database\factories\CurrencyFactory;
use Modules\Geo\Models\Country;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class Currency extends Model
{
    use HasFactory;
    use UsesTenantConnection;

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

    /**
     * Countries relation.
     *
     * @return HasMany
     */
    public function countries(): HasMany
    {
        return $this->hasMany(Country::class);
    }
}
