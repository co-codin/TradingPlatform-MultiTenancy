<?php

declare(strict_types=1);

namespace App\Listeners\Tenant;

use App\Contracts\TenantEventCreated;
use App\Services\Tenant\DatabaseCreator;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

final class CreateTenantDatabase implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use InteractsWithQueue;

    /**
     * @param DatabaseCreator $databaseCreator
     */
    public function __construct(
        public DatabaseCreator $databaseCreator,
    ) {}

    /**
     * @return string
     */
    public function viaQueue(): string
    {
        return 'tenant';
    }

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
