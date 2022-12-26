<?php

declare(strict_types=1);

namespace Modules\Transaction\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Transaction\Models\TransactionsMt5Type;
use Modules\Transaction\Models\TransactionStatus;
use Modules\Transaction\Models\TransactionsWallet;
use Modules\Transaction\Policies\TransactionsMt5TypePolicy;
use Modules\Transaction\Policies\TransactionStatusPolicy;
use Modules\Transaction\Policies\TransactionsWalletPolicy;

final class TransactionServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $policies = [
        TransactionStatus::class => TransactionStatusPolicy::class,
        TransactionsMt5Type::class => TransactionsMt5TypePolicy::class,
        TransactionsWallet::class => TransactionsWalletPolicy::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Transaction';
    }
}
