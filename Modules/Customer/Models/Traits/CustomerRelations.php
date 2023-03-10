<?php

declare(strict_types=1);

namespace Modules\Customer\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Campaign\Models\Campaign;
use Modules\Communication\Models\Call;
use Modules\Communication\Models\Email;
use Modules\Currency\Models\Currency;
use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\Geo\Models\Country;
use Modules\Language\Models\Language;
use Modules\Sale\Models\SaleStatus;
use Modules\Transaction\Models\Transaction;
use Modules\Transaction\Models\Wallet;
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
     * Campaign.
     *
     * @return BelongsTo
     */
    final public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    /**
     * Country relation.
     *
     * @return BelongsTo
     */
    final public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Language.
     *
     * @return BelongsTo
     */
    final public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }

    /**
     * Language.
     *
     * @return BelongsTo
     */
    final public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    /**
     * Supposed language.
     *
     * @return BelongsTo
     */
    final public function supposedLanguage(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'supposed_language_id', 'id');
    }

    /**
     * Platform language.
     *
     * @return BelongsTo
     */
    final public function platformLanguage(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'platformLanguage', 'id');
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

    /**
     * Transactions relation.
     *
     * @return HasMany
     */
    final public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'customer_id', 'id');
    }

    /**
     * Wallets relation.
     *
     * @return HasMany
     */
    final public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class, 'customer_id', 'id');
    }

    /**
     * Customer emails.
     *
     * @return MorphMany
     */
    public function emails(): MorphMany
    {
        return $this->morphMany(Email::class, 'emailable')->latest();
    }

    /**
     * Customer send emails.
     *
     * @return MorphMany
     */
    public function sendEmails(): MorphMany
    {
        return $this->morphMany(Email::class, 'sendemailable')->latest();
    }

    /**
     * Customer calls.
     *
     * @return MorphMany
     */
    public function calls(): MorphMany
    {
        return $this->morphMany(Call::class, 'callable')->latest();
    }

    /**
     * Customer send calls.
     *
     * @return MorphMany
     */
    public function sendCalls(): MorphMany
    {
        return $this->morphMany(Call::class, 'sendcallable')->latest();
    }
}
