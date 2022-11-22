<?php

declare(strict_types=1);

namespace App\Listeners\Tenant;

use App\Contracts\TenantEventCreated;
use App\Services\Tenant\DatabaseCreator;
use App\Services\Tenant\Manager;
use Exception;

final class CreateTenantDatabase
{
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
        if (! $this->databaseCreator->createSchema($event->getTenantSchemaName())) {
            throw new Exception('Database failed to be created.');
        }
    }
}
