<?php

declare(strict_types=1);

namespace Modules\Customer\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\Geo\Models\Country;
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
     * Country relation.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
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
     * Conversion user.
     *
     * @return BelongsTo
     */
    final public function conversionUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conversion_user_id', 'id');
    }

    /**
     * Retention user.
     *
     * @return BelongsTo
     */
    final public function retentionUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'retention_user_id', 'id');
    }

    /**
     * Compliance user.
     *
     * @return BelongsTo
     */
    final public function complianceUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'compliance_user_id', 'id');
    }

    /**
     * Support user.
     *
     * @return BelongsTo
     */
    final public function supportUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'support_user_id', 'id');
    }

    /**
     * Conversion manager user.
     *
     * @return BelongsTo
     */
    final public function conversionManageUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conversion_manager_user_id', 'id');
    }

    /**
     * Retention manager user.
     *
     * @return BelongsTo
     */
    final public function retentionManageUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'retention_manager_user_id', 'id');
    }

    /**
     * First conversion user.
     *
     * @return BelongsTo
     */
    final public function firstConversionUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'first_conversion_user_id', 'id');
    }

    /**
     * First retention user.
     *
     * @return BelongsTo
     */
    final public function firstRetentionUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'first_retention_user_id', 'id');
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
