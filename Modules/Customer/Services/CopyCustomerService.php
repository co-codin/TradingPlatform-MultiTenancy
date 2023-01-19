<?php

declare(strict_types=1);

namespace Modules\Customer\Services;

use Illuminate\Support\Arr;
use Modules\Brand\Models\Brand;
use Modules\Communication\Models\ChatMessage;
use Modules\Communication\Models\Comment;
use Modules\Customer\Models\Customer;
use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\Sale\Models\SaleStatus;
use Modules\Transaction\Models\Transaction;
use Modules\Transaction\Models\TransactionStatus;
use Modules\Transaction\Models\TransactionsMt5Type;
use Modules\Transaction\Models\TransactionsMethod;
use Modules\Transaction\Models\Wallet;

final class CopyCustomerService
{
    protected int $customerId;
    protected Brand $currentBrand;
    protected Brand $destinationBrand;

    public function run(int $customerId, Brand $currentBrand, Brand $destinationBrand)
    {
        $this->customerId = $customerId;
        $this->currentBrand = $currentBrand;
        $this->destinationBrand = $destinationBrand;

        $this->runCopyCustomer();
    }
    private function runCopyCustomer(): void
    {
        $this->currentBrand->makeCurrent();

        $customer = Customer::findOrFail($this->customerId)
            ->replicate()
            ->makeVisible(['password', 'remember_token']);

        $this->destinationBrand->makeCurrent();

        $customerNewData = array_merge($customer->toArray(), [
            'desk_id' => $this->runCopyModel($customer, Desk::class, 'desk_id'),
            'department_id' => $this->runCopyModel($customer, Department::class, 'department_id'),
            'conversion_sale_status_id' => $this->runCopyModel($customer, SaleStatus::class, 'conversion_sale_status_id'),
            'retention_sale_status_id' => $this->runCopyModel($customer, SaleStatus::class, 'retention_sale_status_id'),
            'subbrand_id' => null
        ]);

        $customerNew = Customer::firstOrCreate([
            'email' => $customerNewData['email'],
        ], Arr::except($customerNewData, ['email']));

        $this->runCopyCustomerTransactions($customerNew);
        $this->runCopyCustomerComments($customerNew);
        $this->runCopyCustomerChatMessage($customerNew);
    }

    private function runCopyModel(Customer|Transaction $parent, string $model, string $attribute): int
    {
        $this->currentBrand->makeCurrent();
        $modelData = $model::find($parent->$attribute)?->replicate();
        $this->destinationBrand->makeCurrent();

        return $modelData ? $model::firstOrCreate($modelData->toArray())->id : null;
    }

    private function runCopyCustomerTransactions(Customer $customerNew): void
    {
        $this->currentBrand->makeCurrent();

        $wallets = Wallet::whereCustomerId($this->customerId)->get();
        $transactions = Transaction::whereCustomerId($this->customerId)->get();

        $this->destinationBrand->makeCurrent();

        $walletIds = [];
        foreach ($wallets as $wallet) {
            $walletNewData = array_merge($wallet->replicate()->toArray(), [
                'customer_id' => $customerNew->id,
            ]);

            $walletIds[$wallet->id] = Wallet::firstOrCreate([
                'mt5_id' => $walletNewData['mt5_id'],
            ], Arr::except($walletNewData, ['mt5_id']))->id;
        }

        foreach ($transactions as $transaction) {
            Transaction::withoutEvents(function () use ($transaction, $customerNew, $walletIds) {
                Transaction::firstOrCreate(array_merge($transaction->replicate()->toArray(), [
                    'customer_id' => $customerNew->id,
                    'desk_id' => $this->runCopyModel($transaction, Desk::class, 'desk_id'),
                    'status_id' => $this->runCopyModel($transaction, TransactionStatus::class, 'status_id'),
                    'mt5_type_id' => $this->runCopyModel($transaction, TransactionsMt5Type::class, 'mt5_type_id'),
                    'method_id' => $this->runCopyModel($transaction, TransactionsMethod::class, 'method_id'),
                    'wallet_id' => $walletIds[$transaction->wallet_id],
                ]));
            });
        }
    }

    private function runCopyCustomerComments(Customer $customerNew): void
    {
        $this->currentBrand->makeCurrent();

        $comments = Comment::whereCustomerId($this->customerId)->get();

        $this->destinationBrand->makeCurrent();

        foreach ($comments as $comment) {
            Comment::firstOrCreate(array_merge($comment->replicate()->toArray(), [
                'customer_id' => $customerNew->id,
            ]));
        }
    }

    private function runCopyCustomerChatMessage(Customer $customerNew): void
    {
        $this->currentBrand->makeCurrent();

        $chatMessages = ChatMessage::whereCustomerId($this->customerId)->get();

        $this->destinationBrand->makeCurrent();

        foreach ($chatMessages as $chatMessage) {
            ChatMessage::firstOrCreate(array_merge($chatMessage->replicate()->toArray(), [
                'customer_id' => $customerNew->id,
                'initiator_id' => ($chatMessage->initiator_type == 'customer') ? $customerNew->id : $chatMessage->initiator_id,
            ]));
        }
    }
}
