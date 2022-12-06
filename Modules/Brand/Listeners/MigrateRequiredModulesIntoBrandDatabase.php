<?php

declare(strict_types=1);

namespace Modules\Brand\Listeners;

use App\Contracts\TenantEventCreated;
use App\Services\Tenant\DatabaseCreator;
use App\Services\Tenant\Manager;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Brand\Services\BrandDBService;

final class MigrateRequiredModulesIntoBrandDatabase implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use InteractsWithQueue;

    /**
     * @param DatabaseCreator $databaseCreator
     * @param BrandDBService $brandDBService
     */
    public function __construct(
        public DatabaseCreator $databaseCreator,
        public BrandDBService $brandDBService,
    ) {
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
        $this->brandDBService
            ->setBrand($event->getTenant())
            ->setModules(BrandDBService::REQUIRED_MODULES)
            ->migrateDB()
            ->seedData();

    }

    /**
     * @return string
     */
    public function viaQueue(): string
    {
        return Manager::TENANT_CONNECTION_NAME;
    }
}
