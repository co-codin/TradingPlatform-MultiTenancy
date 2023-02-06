<?php

declare(strict_types=1);

namespace Modules\Transaction\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Currency\Models\Currency;
use Modules\Customer\Models\Customer;
use Modules\Transaction\Database\factories\WalletFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class Wallet extends Model
{
    use HasFactory;
    use UsesTenantConnection;
    use ActivityLog;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return WalletFactory::new();
    }

    /**
     * Currency relation.
     *
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Customer relation.
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
