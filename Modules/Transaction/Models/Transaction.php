<?php

declare(strict_types=1);

namespace Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Customer\Models\Customer;
use Modules\Transaction\Database\factories\TransactionFactory;
use Modules\Transaction\Enums\TransactionMethodName;
use Modules\Transaction\Enums\TransactionMt5TypeName;
use Modules\Transaction\Enums\TransactionStatusName;

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
     * Transaction method is withdraw.
     *
     * @return bool
     */
    public function isWithdrawMethod(): bool
    {
        return $this->method?->name->value === TransactionMethodName::WITHDRAW;
    }

    /**
     * Transaction method is deposit.
     *
     * @return bool
     */
    public function isDepositMethod(): bool
    {
        return $this->method?->name->value === TransactionMethodName::DEPOSIT;
    }

    /**
     * Transaction status is pending.
     *
     * @return bool
     */
    public function isPendingStatus(): bool
    {
        return $this->status?->name->value === TransactionStatusName::PENDING;
    }

    /**
     * Transaction status is approved.
     *
     * @return bool
     */
    public function isApprovedStatus(): bool
    {
        return $this->status?->name->value === TransactionStatusName::APPROVED;
    }

    /**
     * Transaction MT5 type is balance.
     *
     * @return bool
     */
    public function isBalanceMt5Type(): bool
    {
        return $this->mt5Type?->name->value === TransactionMt5TypeName::BALANCE;
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
     * {@inheritDoc}
     */
    protected static function newFactory(): TransactionFactory
    {
        return TransactionFactory::new();
    }
}
