<?php

declare(strict_types=1);

namespace App\Listeners\Tenant;

use App\Contracts\TenantEventCreated;
use App\Services\Tenant\DatabaseCreator;
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
        if (! $this->databaseCreator->create($event->getTenantDBName())) {
            throw new Exception('Database failed to be created.');
        }
    }
}
