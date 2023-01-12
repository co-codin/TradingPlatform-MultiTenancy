<?php

declare(strict_types=1);

namespace Modules\Transaction\Services;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Modules\Transaction\Dto\TransactionDto;
use Modules\Transaction\Enums\TransactionPermission;
use Modules\Transaction\Enums\TransactionStatusEnum;
use Modules\Transaction\Models\TransactionStatus;
use Modules\Transaction\Repositories\TransactionRepository;
use Modules\User\Services\Traits\HasAuthUser;

class TransactionBatchService
{
    use HasAuthUser;

    /**
     * @param  TransactionStorage  $transactionStorage
     * @param  TransactionRepository  $transactionRepository
     */
    public function __construct(
        protected TransactionStorage $transactionStorage,
        protected TransactionRepository $transactionRepository,
    ) {
    }

    /**
     * Update batch.
     *
     * @param  array  $transactionsData
     * @return Collection
     *
     * @throws Exception
     */
    final public function updateBatch(array $transactionsData): Collection
    {
        $updatedTransactions = collect();

        foreach ($transactionsData as $item) {
            $transaction = $this->transactionRepository->find($item['id']);

            if ($this->authUser?->can(TransactionPermission::EDIT_TRANSACTIONS, $transaction)) {
                if ($item['status'] == TransactionStatusEnum::PENDING) {
                    throw new Exception(__('Can not update transaction status to ' . TransactionStatusEnum::PENDING));
                }

                $updatedTransactions->push(
                    $this->transactionStorage->updateBatch(
                        $transaction,
                        array_merge(Arr::whereNotNull(TransactionDto::create($item)->toArray()), [
                            'status_id' => TransactionStatus::firstWhere('name', $item['status'])->id,
                        ])
                    )
                );
            }
        }

        return $updatedTransactions;
    }
}
