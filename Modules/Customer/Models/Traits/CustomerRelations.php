<?php

declare(strict_types=1);

namespace Modules\Customer\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\Sale\Models\SaleStatus;
use Modules\User\Models\User;

trait CustomerRelations
{
    /**
     * Desk.
     *
     * @return BelongsTo
     */
    final public function desk(): BelongsTo
    {
        return $this->belongsTo(Desk::class);
    }

    /**
     * Department.
     *
     * @return BelongsTo
     */
    final public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Affiliate user.
     *
     * @return BelongsTo
     */
    final public function affiliateUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'affiliate_user_id', 'id');
    }

    /**
     * Conversion sale status.
     *
     * @return BelongsTo
     */
    final public function conversionSaleStatus(): BelongsTo
    {
        return $this->belongsTo(SaleStatus::class, 'conversion_sale_status_id', 'id');
    }

    /**
     * Retention sale status.
     *
     * @return BelongsTo
     */
    final public function retentionSaleStatus(): BelongsTo
    {
        return $this->belongsTo(SaleStatus::class, 'retention_sale_status_id', 'id');
    }

    // TODO надо допольнить всех отношений
}
