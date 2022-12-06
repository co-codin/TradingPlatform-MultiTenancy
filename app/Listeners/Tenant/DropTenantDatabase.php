<?php

declare(strict_types=1);

namespace App\Listeners\Tenant;

use App\Contracts\TenantEventCreated;
use App\Services\Tenant\DatabaseManipulator;
use App\Services\Tenant\Manager;
use Exception;
use Modules\Brand\Events\BrandDeleted;

final class DropTenantDatabase
{
    /**
     * @param  DatabaseManipulator  $databaseManipulator
     */
    public function __construct(
        private readonly DatabaseManipulator $databaseManipulator
    ) {
    }

    /**
     * Handle the event.
     *
     * @param  BrandDeleted  $event
     * @return void
     *
     * @throws Exception
     */
    public function handle(TenantEventCreated $event): void
    {
        if (! $this->databaseManipulator->dropSchema($event->getTenantSchemaName())) {
            throw new \RuntimeException('Database failed to be created.');
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
