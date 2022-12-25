<?php

declare(strict_types=1);

namespace Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Transaction\Database\factories\TransactionStatusFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class TransactionStatus extends Model
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
    protected static function newFactory(): TransactionStatusFactory
    {
        return TransactionStatusFactory::new();
    }
}
