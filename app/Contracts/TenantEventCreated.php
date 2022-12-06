<?php

namespace App\Contracts;

interface TenantEventCreated
{
    /**
     * @param HasTenantDBConnection $tenant
     */
    public function __construct(HasTenantDBConnection $tenant);

    /**
     * @return HasTenantDBConnection
     */
    public function getTenant(): HasTenantDBConnection;

    /**
     * Get tenant db name.
     *
     * @return string
     */
    public function getTenantDBName(): string;

    /**
     * Get tenant schema name.
     *
     * @return string
     */
    public function getTenantSchemaName(): string;
}
