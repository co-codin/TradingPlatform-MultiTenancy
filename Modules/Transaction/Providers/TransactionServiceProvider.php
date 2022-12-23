<?php

declare(strict_types=1);

namespace Modules\Transaction\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Transaction\Models\TransactionStatus;
use Modules\Transaction\Policies\TransactionStatusPolicy;

final class TransactionServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $policies = [
        TransactionStatus::class => TransactionStatusPolicy::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Transaction';
    }
}
