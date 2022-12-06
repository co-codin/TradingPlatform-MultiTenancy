<?php

namespace App\Services\Tenant;

use App\Contracts\HasTenantDBConnection;
use Illuminate\Database\DatabaseManager as BaseDatabaseManager;
use Illuminate\Support\Facades\Config;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DatabaseManager
{
    /**
     * @param BaseDatabaseManager $db
     */
    public function __construct(
        protected BaseDatabaseManager $db
    ) {}

    /**
     * @param HasTenantDBConnection $tenant
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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
    public function connectToDefault(): void
    {
        $this->db->reconnect($this->getDefaultConnectionName());
    }

    /**
     * @return void
     */
    public function purge(): void
    {
        $this->db->purge(Manager::TENANT_CONNECTION_NAME);
    }

    /**
     * @param HasTenantDBConnection $tenant
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getTenantConnection(HasTenantDBConnection $tenant): array
    {
        return array_merge(
            Config::get($this->getConfigConnectionPath()),
            $tenant->getTenantConnectionData()
        );
    }

    /**
     * @return string
     */
    protected function getConfigConnectionPath(): string
    {
        return sprintf('database.connections.%s', $this->getDefaultConnectionName());
    }

    /**
     * @return string
     */
    protected function getDefaultConnectionName(): string
    {
        return Config::get('database.default');
    }
}
