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
        if ($transaction->isWithdrawMethod() && $transaction->isPendingStatus()) {
            $transaction->customer->last_pending_withdrawal_date = now();
        }

        /** Метод withdrawal и статус approved */
        if ($transaction->isWithdrawMethod() && $transaction->isApprovedStatus()) {
            $transaction->customer->last_pending_withdrawal_date = null;

            if ($transaction->isBalanceMt5Type()) {
                $approvedDeposits = $transaction->customer->getApprovedDeposits();
                $approvedWithdraw = $transaction->customer->getApprovedWithdraws();

                $transaction->customer->balance = $approvedDeposits->sum('value') - $approvedWithdraw->sum('value');
                $transaction->customer->balance_usd = $approvedDeposits->sum('value_usd') - $approvedWithdraw->sum('value_usd');
            }
        }

        /** Метод deposit и статус pending */
        if ($transaction->isDepositMethod() && $transaction->isPendingStatus()) {
            $transaction->customer->last_pending_deposit_date = now();
        }

        /** Метод deposit и статус approved */
        if ($transaction->isDepositMethod() && $transaction->isApprovedStatus()) {
            $transaction->customer->last_approved_deposit_date = now();
            $transaction->customer->last_pending_deposit_date = null;

            if ($transaction->isFirstCustomerDeposit()) {
                $transaction->customer->first_deposit_date = now();
                $transaction->customer->is_ftd = true;
            }

            if ($transaction->isBalanceMt5Type()) {
                $approvedDeposits = $transaction->customer->getApprovedDeposits();
                $approvedWithdraw = $transaction->customer->getApprovedWithdraws();

                $transaction->customer->balance = $approvedDeposits->sum('value') - $approvedWithdraw->sum('value');
                $transaction->customer->balance_usd = $approvedDeposits->sum('value_usd') - $approvedWithdraw->sum('value_usd');
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
            && $transaction->isDepositMethod()
            && $transaction->isApprovedStatus()
            && $transaction->isBalanceMt5Type()
            && $transaction->customer->transactions()->count() === 0
        ) {
            $transaction->is_ftd = true;
        }
    }
}
