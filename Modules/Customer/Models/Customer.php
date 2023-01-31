<?php

declare(strict_types=1);

namespace Modules\Customer\Models;

use App\Contracts\Models\HasAttributeColumns;
use App\Contracts\Models\HasEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Brand\Models\Brand;
use Modules\Communication\Models\DatabaseNotification;
use Modules\Communication\Models\Email;
use Modules\Customer\Database\factories\CustomerFactory;
use Modules\Customer\Events\CustomerSaving;
use Modules\Customer\Models\Traits\CustomerRelations;
use Modules\Role\Enums\ModelHasPermissionStatus;
use Modules\Role\Models\Traits\HasRoles;
use Modules\Transaction\Enums\TransactionMt5TypeEnum;
use Modules\Transaction\Enums\TransactionStatusEnum;
use Modules\Transaction\Enums\TransactionType;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Multitenancy\Models\Tenant;

/**
 * Class Customer
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property int $gender
 * @property string $email
 * @property string $phone
 * @property int|null $affiliate_user_id
 * @property int|null $conversion_user_id
 * @property int|null $retention_user_id
 * @property int|null $compliance_user_id
 * @property int|null $support_user_id
 * @property int|null $conversion_manager_user_id
 * @property int|null $retention_manager_user_id
 * @property int|null $first_conversion_user_id
 * @property int|null $first_retention_user_id
 * @property int|null $conversion_sale_status_id
 * @property int|null $retention_sale_status_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
final class Customer extends Authenticatable implements HasAttributeColumns, HasEmail
{
    use HasFactory;
    use SoftDeletes;
    use CustomerRelations;
    use HasRoles;
    use HasApiTokens;
    use Notifiable;
    use UsesTenantConnection;

    /**
     * @var string
     */
    public const DEFAULT_AUTH_GUARD = 'web-customer';

    /**
     * @var string
     */
    public const API_AUTH_GUARD = 'api-customer';

    /**
     * @var string
     */
    public const CUSTOMER_AMOUNT_PRECISION = 2;

    /**
     * @var array
     */
    public const WORKER_COLUMNS_FOR_EMAILING = [
        'affiliate_user_id',
        'conversion_user_id',
        'retention_user_id',
        'compliance_user_id',
        'support_user_id',
        'conversion_manager_user_id',
        'retention_manager_user_id',
        'first_conversion_user_id',
        'first_retention_user_id',
    ];

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritdoc}
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'last_online' => 'datetime',
        'first_autologin_time' => 'datetime',
        'first_login_time' => 'datetime',
        'first_deposit_date' => 'datetime',
        'last_approved_deposit_date' => 'datetime',
        'last_pending_deposit_date' => 'datetime',
        'last_pending_withdrawal_date' => 'datetime',
        'last_communication_date' => 'datetime',
        'balance' => 'decimal:2',
        'balance_usd' => 'decimal:2',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dispatchesEvents = [
        'saving' => CustomerSaving::class,
    ];

    /**
     * {@inheritdoc}
     */
    protected $rememberTokenName = false;

    /**
     * {@inheritDoc}
     */
    public static function getAttributeColumns(): array
    {
        return [
            'formatted_is_ftd',
            'suspend',
            'local_time',
            'last_deposit_date',
            'ftd_amount',
            'total_redeposits',
            'total_withdrawals',
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }

    /**
     * Set email attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setEmailAttribute(string $value): void
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getFirstName(): string
    {
        return $this->getOriginal('first_name', '');
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail(): string
    {
        return $this->getOriginal('email', '');
    }

    /**
     * Get formatted is ftd attribute.
     *
     * @return string
     */
    public function getFormattedIsFtdAttribute(): string
    {
        return $this->is_ftd ? __('FTD') : __('Customer');
    }

    /**
     * Get suspended status attribute.
     *
     * @return bool
     */
    public function getSuspendAttribute(): bool
    {
        return $this->permissions()
                ->where('status', ModelHasPermissionStatus::SUSPENDED)
                ->count() > 0;
    }

    /**
     * Get local time attribute.
     *
     * @return string
     */
    public function getLocalTimeAttribute(): string
    {
        return (string) Carbon::now($this->timezone);
    }

    /**
     * Get last deposit date attribute.
     *
     * @return string
     */
    public function getLastDepositDateAttribute(): string
    {
        return (string) $this->transactions()
            ->where('type', '=', TransactionType::DEPOSIT)
            ->whereHas('mt5Type', fn ($q) => $q->where('name', TransactionMt5TypeEnum::BALANCE))
            ->orderByDesc('created_at')
            ->first()
            ?->created_at;
    }

    /**
     * Get ftd amount attribute.
     *
     * @return float
     */
    public function getFtdAmountAttribute(): float
    {
        return round(
            $this->transactions()
                ->where('type', '=', TransactionType::DEPOSIT)
                ->whereHas('status', fn ($q) => $q->where('name', TransactionStatusEnum::APPROVED))
                ->whereHas('mt5Type', fn ($q) => $q->where('name', TransactionMt5TypeEnum::BALANCE))
                ->orderBy('created_at')
                ->first()
                ?->amount ?: 0,
            self::CUSTOMER_AMOUNT_PRECISION,
        );
    }

    /**
     * Get total redeposits attribute.
     *
     * @return float
     */
    public function getTotalRedepositsAttribute(): float
    {
        return round(
            $this->transactions()
                ->where('type', '=', TransactionType::DEPOSIT)
                ->whereHas('status', fn ($q) => $q->where('name', TransactionStatusEnum::APPROVED))
                ->whereHas('mt5Type', fn ($q) => $q->where('name', TransactionMt5TypeEnum::BALANCE))
                ->offset(1)
                ->sum('amount') ?: 0,
            self::CUSTOMER_AMOUNT_PRECISION,
        );
    }

    /**
     * Get total withdrawals attribute.
     *
     * @return float
     */
    public function getTotalWithdrawalsAttribute(): float
    {
        return round(
            $this->transactions()
                ->where('type', '=', TransactionType::WITHDRAWAL)
                ->whereHas('status', fn ($q) => $q->where('name', TransactionStatusEnum::APPROVED))
                ->whereHas('mt5Type', fn ($q) => $q->where('name', TransactionMt5TypeEnum::BALANCE))
                ->offset(1)
                ->sum('amount') ?: 0,
            self::CUSTOMER_AMOUNT_PRECISION,
        );
    }

    /**
     * Get brand.
     *
     * @return Brand|null
     */
    public function getBrand(): ?Brand
    {
        return app(Tenant::class)->current();
    }

    /**
     * Get approved deposits.
     *
     * @return Collection
     */
    public function getApprovedDeposits(): Collection
    {
        return $this->transactions()
            ->where('type', '=', TransactionType::DEPOSIT)
            ->whereHas('status', fn ($q) => $q->where('name', TransactionStatusEnum::APPROVED))
            ->whereHas('mt5Type', fn ($q) => $q->where('name', TransactionMt5TypeEnum::BALANCE))
            ->get();
    }

    /**
     * Get approved withdrawals.
     *
     * @return Collection
     */
    public function getApprovedWithdrawals(): Collection
    {
        return $this->transactions()
            ->where('type', '=', TransactionType::WITHDRAWAL)
            ->whereHas('status', fn ($q) => $q->where('name', TransactionStatusEnum::APPROVED))
            ->whereHas('mt5Type', fn ($q) => $q->where('name', TransactionMt5TypeEnum::BALANCE))
            ->get();
    }

    /**
     * Get the entity's notifications.
     *
     * @return MorphMany
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->latest();
    }
}
