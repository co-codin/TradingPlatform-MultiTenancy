<?php

declare(strict_types=1);

namespace Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Transaction\Database\factories\TransactionsMethodFactory;
use Modules\Transaction\Enums\TransactionMethodName;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class TransactionsMethod extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'name' => TransactionMethodName::class,
    ];

    /**
     * {@inheritdoc}
     */
    protected $table = 'transaction_methods';

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): TransactionsMethodFactory
    {
        return TransactionsMethodFactory::new();
    }
}
