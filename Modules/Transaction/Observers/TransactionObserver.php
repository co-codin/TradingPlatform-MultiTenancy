<?php

declare(strict_types=1);

namespace Modules\Transaction\Observers;

use Modules\Transaction\Models\Transaction;
use Modules\Transaction\Services\CurrencyConverter;

final class TransactionObserver
{
    /**
     * @param CurrencyConverter $converter
     */
    public function __construct(protected CurrencyConverter $converter)
    {
    }

    /**
     * Handle the Customer "created" event.
     *
     * @param  Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction): void
    {
        /** Метод withdrawal и статус pending */
        if ($transaction->isWithdrawal() && $transaction->isPendingStatus()) {
            $transaction->customer->last_pending_withdrawal_date = now();
        }

        /** Метод withdrawal и статус approved */
        if ($transaction->isWithdrawal() && $transaction->isApprovedStatus()) {
            $transaction->customer->last_pending_withdrawal_date = null;

            if ($transaction->isBalanceMt5Type()) {
                $approvedDeposits = $transaction->customer->getApprovedDeposits();
                $approvedWithdraw = $transaction->customer->getApprovedWithdraws();

                $transaction->customer->balance = $approvedDeposits->sum('amount') - $approvedWithdraw->sum('amount');
                $transaction->customer->balance_usd = $approvedDeposits->sum('amount_usd') - $approvedWithdraw->sum('amount_usd');
            }
        }

        /** Метод deposit и статус pending */
        if ($transaction->isDeposit() && $transaction->isPendingStatus()) {
            $transaction->customer->last_pending_deposit_date = now();
        }

        /** Метод deposit и статус approved */
        if ($transaction->isDeposit() && $transaction->isApprovedStatus()) {
            $transaction->customer->last_approved_deposit_date = now();
            $transaction->customer->last_pending_deposit_date = null;

            if ($transaction->isFirstCustomerDeposit()) {
                $transaction->customer->first_deposit_date = now();
                $transaction->customer->is_ftd = true;
            }

            if ($transaction->isBalanceMt5Type()) {
                $approvedDeposits = $transaction->customer->getApprovedDeposits();
                $approvedWithdraw = $transaction->customer->getApprovedWithdraws();

                $transaction->customer->balance = $approvedDeposits->sum('amount') - $approvedWithdraw->sum('amount');
                $transaction->customer->balance_usd = $approvedDeposits->sum('amount_usd') - $approvedWithdraw->sum('amount_usd');
            }
        }

        if ($transaction->customer->isDirty()) {
            $transaction->customer->save();
        }
    }

    /**
     * Handle the Customer "saving" event.
     *
     * @param  Transaction  $transaction
     * @return void
     */
    public function saving(Transaction $transaction): void
    {
        if ($currency = $transaction->wallet?->iso3) {
            switch ($currency) {
                case 'USD':
                    $transaction->amount_usd = $transaction->amount;
                    $transaction->amount_eur = $this->converter->convert('USD', 'EUR', $transaction->amount);
                    break;
                case 'EUR':
                    $transaction->amount_eur = $transaction->amount;
                    $transaction->amount_usd = $this->converter->convert('EUR', 'USD', $transaction->amount);
                    break;
                default:
                    $transaction->amount_eur = $this->converter->convert($currency, 'EUR', $transaction->amount);
                    $transaction->amount_usd = $this->converter->convert($currency, 'USD', $transaction->amount);
            }
        }
    }
}
