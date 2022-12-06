<?php

declare(strict_types=1);

namespace App\Services\Tenant;

use App\Contracts\HasTenantDBConnection;
use Illuminate\Database\DatabaseManager as BaseDatabaseManager;
use Illuminate\Support\Facades\Config;

final class DatabaseManager
{
    /**
     * @param  BaseDatabaseManager  $db
     */
    public function __construct(
        private readonly BaseDatabaseManager $db
    ) {
    }

    /**
     * @param  HasTenantDBConnection  $tenant
     * @return void
     */
    public function createConnection(HasTenantDBConnection $tenant): void
    {
        Config::set('database.connections.tenant', $this->getTenantConnection($tenant));
    }

    /**
     * @return void
     */
    public function connectToTenant(): void
    {
        $this->db->reconnect(Manager::TENANT_CONNECTION_NAME);
    }

    /**
     * @return void
     */
    public function purge(): void
    {
        $this->db->purge(Manager::TENANT_CONNECTION_NAME);
    }

    /**
     * @param  HasTenantDBConnection  $tenant
     * @return array
     */
    private function getTenantConnection(HasTenantDBConnection $tenant): array
    {
        return array_merge(
            Config::get($this->getConfigDefaultConnectionPath()),
            $tenant->getTenantConnectionData()
        );
    }

    /**
     * @return string
     */
    private function getConfigDefaultConnectionPath(): string
    {
        return sprintf('database.connections.%s', $this->getDefaultConnectionName());
    }

    /**
     * @return string
     */
    private function getDefaultConnectionName(): string
    {
        return Config::get('database.default');
    }
}
