<?php

declare(strict_types=1);

namespace App\Listeners\Tenant;

use App\Contracts\TenantEventCreated;
use App\Services\Tenant\DatabaseCreator;
use App\Services\Tenant\Manager;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

final class DropTenantDatabase implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use InteractsWithQueue;

    /**
     * @param DatabaseCreator $databaseCreator
     */
    public function __construct(
        protected DatabaseCreator $databaseCreator
    ) {}

    /**
     * Handle the event.
     *
     * @param TenantEventCreated $event
     * @return void
     * @throws Exception
     */
    public function handle(TenantEventCreated $event): void
    {
        if (!$this->databaseCreator->dropSchema($event->getTenantSchemaName())) {
            throw new Exception('Database failed to be created.');
        }
    }

    /**
     * @return string
     */
    public function viaQueue(): string
    {
        return Manager::TENANT_CONNECTION_NAME;
    }
}
