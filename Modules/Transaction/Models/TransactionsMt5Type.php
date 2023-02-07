<?php

declare(strict_types=1);

namespace Modules\Transaction\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Transaction\Database\factories\TransactionsMt5TypeFactory;
use Modules\Transaction\Enums\TransactionMt5TypeEnum;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class TransactionsMt5Type extends Model
{
    use HasFactory;
    use UsesTenantConnection;
    use ActivityLog;

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'name' => TransactionMt5TypeEnum::class,
    ];

    /**
     * {@inheritdoc}
     */
    protected $table = 'transaction_mt5_types';

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): TransactionsMt5TypeFactory
    {
        return TransactionsMt5TypeFactory::new();
    }
}
