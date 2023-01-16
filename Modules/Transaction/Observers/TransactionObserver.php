<?php

declare(strict_types=1);

namespace Modules\Transaction\Observers;

use Modules\Transaction\Jobs\ChangeCustomerDepartmentAfterDeposit;
use Modules\Transaction\Models\Transaction;
use Modules\Transaction\Services\CurrencyConverter;

final class TransactionObserver
{
    /**
     * @param  CurrencyConverter  $converter
     */
    public function __construct(private readonly CurrencyConverter $converter)
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
        if ($transaction->isWithdrawal() && $transaction->isPendingStatus()) {
            $transaction->customer->last_pending_withdrawal_date = now();
        }

        if ($transaction->isWithdrawal() && $transaction->isApprovedStatus()) {
            $transaction->customer->last_pending_withdrawal_date = null;

            if ($transaction->isBalanceMt5Type()) {
                $approvedDeposits = $transaction->customer->getApprovedDeposits();
                $approvedWithdrawals = $transaction->customer->getApprovedWithdrawals();

                $transaction->customer->balance = $approvedDeposits->sum('amount') - $approvedWithdrawals->sum('amount');
                $transaction->customer->balance_usd = $approvedDeposits->sum('amount_usd') - $approvedWithdrawals->sum('amount_usd');
            }
        }

        if ($transaction->isDeposit() && $transaction->isPendingStatus()) {
            $transaction->customer->last_pending_deposit_date = now();
        }

        if ($transaction->isDeposit() && $transaction->isApprovedStatus()) {
            $transaction->customer->last_approved_deposit_date = now();
            $transaction->customer->last_pending_deposit_date = null;

            ChangeCustomerDepartmentAfterDeposit::dispatchSync($transaction->customer);

            if ($transaction->isFirstCustomerDeposit()) {
                $transaction->customer->first_deposit_date = now();
                $transaction->customer->is_ftd = true;
            }

            if ($transaction->isBalanceMt5Type()) {
                $approvedDeposits = $transaction->customer->getApprovedDeposits();
                $approvedWithdrawals = $transaction->customer->getApprovedWithdrawals();

                $transaction->customer->balance = $approvedDeposits->sum('amount') - $approvedWithdrawals->sum('amount');
                $transaction->customer->balance_usd = $approvedDeposits->sum('amount_usd') - $approvedWithdrawals->sum('amount_usd');
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
        if ($currency = $transaction->currency?->iso3) {
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

    /**
     * Handle the Customer "updating" event.
     *
     * @param  Transaction  $transaction
     * @return void
     */
    public function updating(Transaction $transaction): void
    {
        $converter = new CurrencyConverter;
        if (
            $transaction->isDeposit()
            && $transaction->isPendingStatus()
            && $transaction->isBalanceMt5Type()
        ) {
            switch ($currency = $transaction->wallet->iso3) {
                case 'USD':
                    $transaction->amount_usd = $transaction->amount;
                    $transaction->amount_eur = $converter->convert('USD', 'EUR', $transaction->amount);
                    break;
                case 'EUR':
                    $transaction->amount_eur = $transaction->amount;
                    $transaction->amount_usd = $converter->convert('EUR', 'USD', $transaction->amount);
                    break;
                default:
                    $transaction->amount_eur = $converter->convert($currency, 'EUR', $transaction->amount);
                    $transaction->amount_usd = $converter->convert($currency, 'USD', $transaction->amount);
            }
        }
    }
}
