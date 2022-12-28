<?php

declare(strict_types=1);

namespace Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Transaction\Database\factories\TransactionStatusFactory;
use Modules\Transaction\Enums\TransactionStatusName;
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
     * {@inheritdoc}
     */
    protected $casts = [
        'name' => TransactionStatusName::class,
    ];

    /**
     * {@inheritdoc}
     */
    protected $table = 'transaction_statuses';

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): TransactionStatusFactory
    {
        return TransactionStatusFactory::new();
    }
}
