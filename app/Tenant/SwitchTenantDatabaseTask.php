<?php

declare(strict_types=1);

namespace App\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Exceptions\InvalidConfiguration;
use Spatie\Multitenancy\Tasks\SwitchTenantDatabaseTask as BaseSwitchTenantDatabaseTask;

final class SwitchTenantDatabaseTask extends BaseSwitchTenantDatabaseTask
{
    /**
     * @param string|null $databaseName
     * @return void
     * @throws InvalidConfiguration
     */
    protected function setTenantConnectionDatabaseName(?string $databaseName): void
    {
        $tenantConnectionName = $this->tenantDatabaseConnectionName();

        if ($tenantConnectionName === $this->landlordDatabaseConnectionName()) {
            throw InvalidConfiguration::tenantConnectionIsEmptyOrEqualsToLandlordConnection();
        }

        if (is_null(config("database.connections.{$tenantConnectionName}"))) {
            throw InvalidConfiguration::tenantConnectionDoesNotExist($tenantConnectionName);
        }

        config([
            "database.connections.{$tenantConnectionName}.search_path" => $databaseName,
        ]);

        app('db')->extend($tenantConnectionName, function ($config, $name) use ($databaseName) {
            $config['search_path'] = $databaseName;

            return app('db.factory')->make($config, $name);
        });

        DB::purge($tenantConnectionName);

        // Octane will have an old `db` instance in the Model::$resolver.
        Model::setConnectionResolver(app('db'));
    }
}
