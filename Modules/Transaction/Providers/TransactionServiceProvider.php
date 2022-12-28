<?php

declare(strict_types=1);

namespace Modules\Transaction\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Transaction\Models\Transaction;
use Modules\Transaction\Models\TransactionsMethod;
use Modules\Transaction\Models\TransactionsMt5Type;
use Modules\Transaction\Models\TransactionStatus;
use Modules\Transaction\Models\TransactionsWallet;
use Modules\Transaction\Policies\TransactionsMethodPolicy;
use Modules\Transaction\Policies\TransactionsMt5TypePolicy;
use Modules\Transaction\Policies\TransactionStatusPolicy;
use Modules\Transaction\Policies\TransactionsWalletPolicy;
use Modules\Transaction\Policies\TransactionPolicy;

final class TransactionServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $policies = [
        Transaction::class => TransactionPolicy::class,
        TransactionStatus::class => TransactionStatusPolicy::class,
        TransactionsMt5Type::class => TransactionsMt5TypePolicy::class,
        TransactionsWallet::class => TransactionsWalletPolicy::class,
        TransactionsMethod::class => TransactionsMethodPolicy::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Transaction';
    }
}
