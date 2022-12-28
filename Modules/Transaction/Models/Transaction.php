<?php

declare(strict_types=1);

namespace Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Transaction\Database\factories\TransactionFactory;

final class Transaction extends Model
{
    use HasFactory;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id',
    ];

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): TransactionFactory
    {
        return TransactionFactory::new();
    }
}
