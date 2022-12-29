<?php

declare(strict_types=1);

namespace Modules\Transaction\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Transaction\Observers\TransactionObserver;
use Modules\Transaction\Models\Transaction;

final class EventServiceProvider extends ServiceProvider
{

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        Transaction::observe(TransactionObserver::class);
    }
}
