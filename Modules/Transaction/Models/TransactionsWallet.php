<?php

declare(strict_types=1);

namespace Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Transaction\Database\factories\TransactionsWalletFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class TransactionsWallet extends Model
{
    use HasFactory;
    use UsesTenantConnection;
    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return TransactionsWalletFactory::new();
    }
}
