<?php

declare(strict_types=1);

namespace Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Transaction\Database\factories\TransactionsMt5TypeFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class TransactionsMt5Type extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return TransactionsMt5TypeFactory::new();
    }
}
