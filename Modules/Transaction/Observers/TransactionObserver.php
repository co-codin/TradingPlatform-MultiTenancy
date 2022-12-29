<?php

declare(strict_types=1);

namespace Modules\Transaction\Observers;

use Modules\Customer\Models\Customer;
use Modules\Transaction\Models\Transaction;

final class TransactionObserver
{
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
     * Handle the Customer "creating" event.
     *
     * @param  Transaction  $transaction
     * @return void
     */
    public function creating(Transaction $transaction): void
    {
        if (
            ! $transaction->is_ftd
            && $transaction->isDeposit()
            && $transaction->isApprovedStatus()
            && $transaction->isBalanceMt5Type()
            && $transaction->customer->transactions()->count() === 0
        ) {
            $transaction->is_ftd = true;
        }
    }
}
