<?php

declare(strict_types=1);

namespace App\Listeners\Tenant;

use App\Services\Tenant\DatabaseManager;
use App\Services\Tenant\Manager;
use Modules\Brand\Events\Tenant\BrandTenantIdentified;

final class RegisterTenant
{
    /**
     * @param  DatabaseManager  $db
     */
    public function __construct(
        private readonly DatabaseManager $db
    ) {
    }

    /**
     * @param  BrandTenantIdentified  $event
     * @return void
     */
    public function handle(BrandTenantIdentified $event)
    {
        app(Manager::class)->setTenant($event->tenant);

        $this->db->createConnection($event->tenant);
    }
}
