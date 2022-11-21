<?php

namespace App\Listeners\Tenant;

use App\Services\Tenant\DatabaseManager;
use App\Services\Tenant\Manager;
use Modules\Brand\Events\Tenant\BrandTenantIdentified;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class RegisterTenant
{
    /**
     * @param DatabaseManager $db
     */
    public function __construct(
        protected DatabaseManager $db
    ) {}

    /**
     * @param BrandTenantIdentified $event
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(BrandTenantIdentified $event)
    {
        app(Manager::class)->setTenant($event->tenant);

        $this->db->createConnection($event->tenant);
    }
}
