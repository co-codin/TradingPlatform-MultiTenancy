<?php

declare(strict_types=1);

namespace Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Currency\Models\Currency;
use Modules\Customer\Models\Customer;
use Modules\Transaction\Database\factories\TransactionFactory;
use Modules\Transaction\Enums\TransactionMt5TypeEnum;
use Modules\Transaction\Enums\TransactionStatusEnum;
use Modules\Transaction\Enums\TransactionType;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class Transaction extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    protected $casts = [
        'data' => 'array',
    ];

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

    /**
     * Transaction type is withdrawal.
     *
     * @return bool
     */
    public function isWithdrawal(): bool
    {
        return $this->type === TransactionType::WITHDRAWAL;
    }

    /**
     * Transaction type is deposit.
     *
     * @return bool
     */
    public function isDeposit(): bool
    {
        return $this->type === TransactionType::DEPOSIT;
    }

    /**
     * Transaction status is pending.
     *
     * @return bool
     */
    public function isPendingStatus(): bool
    {
        return $this->status?->name->value === TransactionStatusEnum::PENDING;
    }

    /**
     * Transaction status is approved.
     *
     * @return bool
     */
    public function isApprovedStatus(): bool
    {
        return $this->status?->name->value === TransactionStatusEnum::APPROVED;
    }

    /**
     * Transaction MT5 type is balance.
     *
     * @return bool
     */
    public function isBalanceMt5Type(): bool
    {
        return $this->mt5Type?->name->value === TransactionMt5TypeEnum::BALANCE;
    }

    /**
     * This transaction is first customer deposit.
     *
     * @return bool
     */
    public function isFirstCustomerDeposit(): bool
    {
        return $this->is_ftd || $this->customer
            ->transactions()
            ->where('id', '<>', $this->id)
            ->count() === 0;
    }

    /*
     * Scopes
     */

    /**
     * Deposit transactions.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeDeposit(Builder $query): Builder
    {
        return $query->where('type', '=', TransactionType::DEPOSIT);
    }

    /**
     * Withdrawal transactions.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeWithdrawal(Builder $query): Builder
    {
        return $query->where('type', '=', TransactionType::WITHDRAWAL);
    }

    /**
     * Approved transactions.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->whereHas('status', fn ($q) => $q->where('name', TransactionStatusEnum::APPROVED));
    }

    /**
     * Pending transactions.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->whereHas('status', fn ($q) => $q->where('name', TransactionStatusEnum::PENDING));
    }

    /**
     * Balance transactions.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeBalance(Builder $query): Builder
    {
        return $query->whereHas('mt5Type', fn ($q) => $q->where('name', TransactionMt5TypeEnum::BALANCE));
    }

    /**
     * Balance transactions.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeBonus(Builder $query): Builder
    {
        return $query->whereHas('mt5Type', fn ($q) => $q->where('name', TransactionMt5TypeEnum::BONUS));
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

    /**
     * MT5 type relation.
     *
     * @return BelongsTo
     */
    public function mt5Type(): BelongsTo
    {
        return $this->belongsTo(TransactionsMt5Type::class, 'mt5_type_id', 'id');
    }

    /**
     * Status relation.
     *
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(TransactionStatus::class, 'status_id', 'id');
    }

    /**
     * Method relation.
     *
     * @return BelongsTo
     */
    public function method(): BelongsTo
    {
        return $this->belongsTo(TransactionsMethod::class, 'method_id', 'id');
    }

    /**
     * Wallet relation.
     *
     * @return BelongsTo
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
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
}
